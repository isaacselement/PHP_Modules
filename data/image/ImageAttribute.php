<?php

class ImageAttribute {

	public static function getWidth($image_path) {
		$img_info = getimagesize($image_path);
		return $img_info[0];
	}

	public static function getHeight($image_path) {
		$img_info = getimagesize($image_path);
		return $img_info[1];
	}

	public static function getType($image_path) {
		$img_info = getimagesize($image_path);
		switch($img_info[2]){
		case 1:
			$imgtype="gif";
			break;
		case 2:
			$imgtype="jpg";
			break;
		case 3:
			$imgtype="png";
			break;
		} 	

		if($imgtype == null) {
			$imgtype = $img_info['mime'];
			if(strpos($imgtype, '/') !== false){
				$imgtype = explode('/', $imgtype)[1]; 
			}
		}
		return $imgtype; 
	}

}


?>
