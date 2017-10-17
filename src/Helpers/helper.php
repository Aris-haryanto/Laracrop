<?php
/*
ex:

$param = array(
              'path_upload' => "http://domaincoeg.com",
              'aspectratio' => "1",
              'minsize' => "[200, 200]",
              'maxsize' => "[400, 400]",
              'bgcolor' => "black",
              'bgopacity' => "0.6"
          );

*/

function laracropCss(){
  return '<link rel="stylesheet" href="./public/vendor/laracrop/jCrop/css/Jcrop.css">
          <link rel="stylesheet" href="./public/vendor/laracrop/jCrop/css/demos.css">';
}

function laracropJs(){
  return '<script src="./public/vendor/laracrop/jCrop/js/Jcrop.js"></script>
          <script src="./public/vendor/laracrop/laracrop.js"></script>';
}
function laracrop(){
  return '<div class="form-group showimage">
                      <label for="exampleInputEmail1">Image</label>
                      <input type="file" class="cropimage" name="image" 
                        data-uploadurl="http://domaincoeg.com"
                        data-aspectratio="1"
                        data-minsize="[200, 200]"
                        data-maxsize="[400, 400]"
                        data-bgcolor="black"
                        data-bgopacity="0.6" 
                        class="form-control">
                    </div>';
}

?>