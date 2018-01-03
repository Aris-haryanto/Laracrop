/*####################################*/
/*###### Custom JS Functions #########*/
/*###### Author : Aris Haryanto ######*/
/*####################################*/

$(document).ready(function() {

    //CROP IMAGE
    function initJcrop(thisElem, aspectratio, minsize, maxsize, bgcolor, bgopacity, boxwidth, boxheight){

        var d = document, ge = 'getElementById';

          thisElem.find('.interface').on('cropmove cropend',function(e,s,c){
            // alert(c.x+' # '+c.y+' # '+c.w+' # '+c.h);
            thisElem.find('.crop-x').val(c.x);
            thisElem.find('.crop-y').val(c.y);
            thisElem.find('.crop-w').val(c.w);
            thisElem.find('.crop-h').val(c.h);
          });

          // Most basic attachment example
          thisElem.find('.target').Jcrop({
            edge: {n:2,s:-2,e:-2,w:2},
            setSelect: [ 175, 100, 400, 300 ],
            aspectRatio: aspectratio, //get from data input file
            minSize: minsize,
            maxSize: maxsize,
            bgColor: bgcolor,
            bgOpacity: bgopacity,
            boxWidth: boxwidth,
            boxHeight: boxheight
          });
    };

    $('.cropimage').change(function(){
      var _URL = window.URL || window.webkitURL;

      var thisImg = $(this);
      var aspectratio = $(this).data('aspectratio');
      var minsize = $(this).data('minsize');
      var maxsize = $(this).data('maxsize');
      var bgcolor = $(this).data('bgcolor');
      var bgopacity = $(this).data('bgopacity');
      var boxwidth = $(this).data('boxwidth');
      var boxheight = $(this).data('boxheight');

      var uploadurl = $(this).data('uploadurl');
      var inputName = $(this).attr('name');
      var thisElem = $(this).closest('.showimage');

      var file = thisImg[0].files[0];

      img = new Image();
      img.src = _URL.createObjectURL(file);

      img.onload = function() {

        if(aspectratio == "1"){
          var getImgWidth = this.width;
          var getImgHeight = this.height;
          aspectratio = parseFloat(getImgWidth/getImgHeight);
        }else if(aspectratio != "0"){
          var ratio = aspectratio.split('/');
          var getImgWidth = this.width;
          var getImgHeight = this.height;
          aspectratio = parseFloat(ratio[0]/ratio[1]);
        }

        var data = new FormData();
        data.append('image', thisImg[0].files[0]);

        $.ajax({
            url: uploadurl,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data){
                var data = JSON.parse(data);
                thisElem.find('.block-crop').remove();
                thisElem.prepend('<div class="block-crop">\
                                          <div class="interface" style="max-width: 100%; overflow-x: scroll; overflow-y: hidden;"><img class="target" src="'+data.url+'"></div>\
                                          <div class="form-group form-inline text-inputs"  style="margin-top: 20px;">\
                                            <input type="hidden" name="'+inputName+'[x]" class="crop-x" />\
                                            <input type="hidden" name="'+inputName+'[y]" class="crop-y" />\
                                            <input type="hidden" name="'+inputName+'[w]" class="crop-size crop-w" />\
                                            <input type="hidden" name="'+inputName+'[h]" class="crop-size crop-h" />\
                                          </div>\
                                          <input type="hidden" class="imgUrl" name="'+inputName+'[imgUrl]" value="'+data.filename+'" />\
                                          <input type="hidden" class="oriw" name="'+inputName+'[oriw]" value="'+data.width+'" />\
                                          <input type="hidden" class="orih" name="'+inputName+'[orih]" value="'+data.height+'" />\
                                          </div>');

                initJcrop(thisElem.find('.block-crop'), aspectratio, minsize, maxsize, bgcolor, bgopacity, boxwidth, boxheight);
            }
        });
      };
    });
});