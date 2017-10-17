<?php


namespace Arisharyanto\Laracrop;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use URL;
use File;

class Laracrop extends Controller {


	function __construct(){
	}

	static function cropImage($imageOpt) {

			$imageOpt = (object) $imageOpt;

			// dd($imageOpt);

			$imgUrl = base_path() . '/' . config('laracrop.path_upload') . '/tmp/' . $imageOpt->imgUrl;
			// original sizes
			$imgInitW = $imageOpt->oriw;
			$imgInitH = $imageOpt->orih;
			// offsets
			$imgY1 = $imageOpt->y;
			$imgX1 = $imageOpt->x;
			// crop box
			$cropW = $imageOpt->w;
			$cropH = $imageOpt->h;
			// resized sizes
			$imgW = $imageOpt->oriw;
			$imgH = $imageOpt->orih;
			
			// rotation angle
			$angle = 0;

			$jpeg_quality = 100;
            $png_quality = 100;

			$output_filename = base_path() . '/' . config('laracrop.path_upload') . '/' . md5(rand().'_'.rand().'_'.rand());

			//uncomment line below to save the cropped image in the same location as the original image.
			//$output_filename = dirname($imgUrl). "/croppedImg_".rand();

			$what = getimagesize($imgUrl);

			switch(strtolower($what['mime']))
			{
			    case 'image/png':
			        $img_r = imagecreatefrompng($imgUrl);
					$source_image = imagecreatefrompng($imgUrl);
					$type = '.png';
			        break;
			    case 'image/jpeg':
			        $img_r = imagecreatefromjpeg($imgUrl);
					$source_image = imagecreatefromjpeg($imgUrl);
					$type = '.jpg';
			        break;
			    case 'image/jpg':
			        $img_r = imagecreatefromjpeg($imgUrl);
					$source_image = imagecreatefromjpeg($imgUrl);
					$type = '.jpg';
			        break;
			    default: die('image type not supported');
			}


			//Check write Access to Directory

			if(!is_writable(dirname($output_filename))){
				$response = Array(
				    "status" => 'error',
				    "message" => 'Can`t write cropped File'
			    );	
			}else{

				// $dst_r = ImageCreateTrueColor( $imgW, $imgH );

				// imagecopyresampled($dst_r,$img_r,0,0,$imgX1,$imgY1,
				// $imgW,$imgH,$cropW,$cropH);
				// imagejpeg($dst_r, $output_filename.$type, $jpeg_quality);
				
			    //resize the original image to size of editor
			    $resizedImage = imagecreatetruecolor($imgW, $imgH);

                //TRANSPARANT
                imagealphablending($resizedImage, true); 
                $transparent = imagecolorallocatealpha( $resizedImage, 0, 0, 0, 0 ); 
                imagefill( $resizedImage, 0, 0, $transparent ); 
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);

				imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
			    // rotate the rezized image
			    $rotated_image = imagerotate($resizedImage, -$angle, 0);
			    // find new width & height of rotated image
			    $rotated_width = imagesx($rotated_image);
			    $rotated_height = imagesy($rotated_image);
			    // diff between rotated & original sizes
			    $dx = $rotated_width - $imgW;
			    $dy = $rotated_height - $imgH;
			    // crop rotated image to fit into original rezized rectangle
				$cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);

                //TRANSPARANT
                imagealphablending($cropped_rotated_image, true); 
                $transparent = imagecolorallocatealpha( $cropped_rotated_image, 0, 0, 0, 0 ); 
                imagefill( $cropped_rotated_image, 0, 0, $transparent ); 
                imagealphablending($cropped_rotated_image, false);
                imagesavealpha($cropped_rotated_image, true);

				imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
				imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
				// crop image into selected area
				$final_image = imagecreatetruecolor($cropW, $cropH);

                //TRANSPARANT
                imagealphablending($final_image, true); 
                $transparent = imagecolorallocatealpha( $final_image, 0, 0, 0, 0 ); 
                imagefill( $final_image, 0, 0, $transparent ); 
                imagealphablending($final_image, false);
                imagesavealpha($final_image, true);

				imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
				imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
                
				//finally output png image
                if($type == '.png'){
				    imagepng($final_image, $output_filename.$type);
				}else{
                    imagejpeg($final_image, $output_filename.$type, $jpeg_quality);
                }
			}
			// unlink($imgUrl);
			$explodeName = explode('/', $output_filename.$type);
			return end($explodeName);
	}

	function uploadAjax(Request $request){
		$baseUrl = URL::to('/');
        $uploadUrl = $baseUrl.'/'.config('laracrop.image_url').'/tmp/';

		$imageTempName = $request->file('image')->getPathname();
        $imageName = md5(rand().'_'.rand().'_'.rand()).$request->file('image')->getClientOriginalName();
        $path = base_path().'/'.config('laracrop.path_upload').'/tmp';
        $request->file('image')->move($path , $imageName);
        list($width, $height) = getimagesize( $path .'/'. $imageName );
        $response = array(
				"url" => $uploadUrl.$imageName, //url('/filetmp/'.$imageName),
				"filename" => $imageName,
				"width" => $width,
				"height" => $height
			  );
        print json_encode($response);
	}

	static function cleanCropTemp(){
		File::deleteDirectory(base_path().'/'.config('laracrop.path_upload').'/tmp', true);
	}

}

?>