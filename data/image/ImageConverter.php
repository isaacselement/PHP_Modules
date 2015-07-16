<?php

class ImageConverter {

	public static function covertImage($src_img_path, $to_img_path, $to_img_type) {

		$src_img_type = ImageAttribute::getType($src_img_path);
		if ($src_img_type == $to_img_type) {

		}

		if($src_img_type=="jpg") {
			$src_img_data = imagecreatefromjpeg($src_img_path);
		} else if($src_img_type=="gif"){
			$src_img_data = imagecreatefromgif($src_img_path);
		} else if($src_img_type=="png"){
			$src_img_data = imagecreatefrompng($src_img_path);
		}

		$width = imagesx($src_img_data);
		$height = imagesy($src_img_data);

		$newimg = imagecreatetruecolor($width, $height);
		imagecopyresampled($newimg, $src_img_data, 0, 0, 0, 0, $width, $width, $width, $height);
		
		if($to_img_type=="jpg") {
			ImageJpeg ($newimg,$to_img_path);
		} else if($to_img_type=="gif"){
			Imagegif ($newimg,$to_img_path);
		} else if($to_img_type=="png"){
			Imagepng ($newimg,$to_img_path);
		}

		ImageDestroy($newimg);
	}

}


?>
