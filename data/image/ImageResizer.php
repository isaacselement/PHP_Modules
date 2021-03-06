<?php

class ImageResizer {

	public static function convert_image($file, $output, $output_type) {
		$image = self::get_image_data($file);
		return self::save_image_data($image, $output, $output_type);
	}

	public static function smart_resize_image($file, $final_width, $final_height, $output, $output_type) {
		$info = getimagesize($file);
		list($width_old, $height_old) = $info;
		$image = self::get_image_data($file);

		$image_resized = imagecreatetruecolor($final_width, $final_height );
		if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
			$trnprt_indx = imagecolortransparent($image);
			// If we have a specific transparent color
			if ($trnprt_indx >= 0) {
				$image_resized = imagecreate($final_width, $final_height);
				// Get the original image's transparent color's RGB values
				$trnprt_color    = imagecolorsforindex($image, $trnprt_indx);
				// Allocate the same color in the new image resource
				$trnprt_indx    = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
				// Completely fill the background of the new image with allocated color.
				imagefill($image_resized, 0, 0, $trnprt_indx);
				// Set the background color for new image to transparent
				imagecolortransparent($image_resized, $trnprt_indx);
				
			// Always make a transparent background color for PNGs that don't have one allocated already
			} elseif ($info[2] == IMAGETYPE_PNG) {
				// Turn off transparency blending (temporarily)
				imagealphablending($image_resized, false);
				// Create a new transparent color for image
				$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
				// Completely fill the background of the new image with allocated color.
				imagefill($image_resized, 0, 0, $color);
				// Restore transparency blending
				imagesavealpha($image_resized, true);
			}
		}
		imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
		
		if ($output_type == null) {
			$output_type = $info[2];
		}
		return self::save_image_data($image_resized, $output, $output_type);
	}

	static function get_image_data($file) {
		$info = getimagesize($file);
		list($width_old, $height_old) = $info;

		switch ($info[2] ) {
		case IMAGETYPE_GIF:
			$image = imagecreatefromgif($file);
			break;
		case IMAGETYPE_JPEG:
			$image = imagecreatefromjpeg($file);
			break;
		case IMAGETYPE_PNG:
			$image = imagecreatefrompng($file);
			break;
		default:
			return false;
		}
		return $image;
	}

	static function save_image_data($image, $output, $output_type) {
		switch ($output_type ) {
		case IMAGETYPE_GIF:
			imagegif($image, $output);
			break;
		case IMAGETYPE_JPEG:
			imagejpeg($image, $output);
			break;
		case IMAGETYPE_PNG:
			imagepng($image, $output);
			break;
		default:
			return false;
		}

		return true;
	}

	static function getImageTypeIdenfier($typestr) {
		if ($typestr == 'png') {
			return IMAGETYPE_PNG;
		} elseif ($typestr == 'jpg') {
			return IMAGETYPE_JPEG; 
		} elseif ($typestr == 'jpeg') {
			return IMAGETYPE_JPEG;
		} elseif ($typestr == 'gif') {
			return IMAGETYPE_GIF;
		}
		return null;
	}

	static function imageTypeToExtension($image_path) {
		$img_info = getimagesize($image_path);
		return self::imageExtension($img_info[2]);
	}

	static function imageExtension($img_info_index_tow) {
		$extensions = array(
			IMAGETYPE_GIF => "gif",
			IMAGETYPE_JPEG => "jpg",
			IMAGETYPE_PNG => "png",
			IMAGETYPE_SWF => "swf",
			IMAGETYPE_PSD => "psd",
			IMAGETYPE_BMP => "bmp",
			IMAGETYPE_TIFF_II => "tiff",
			IMAGETYPE_TIFF_MM => "tiff",
			IMAGETYPE_JPC => "jpc",
			IMAGETYPE_JP2 => "jp2",
			IMAGETYPE_JPX => "jpx",
			IMAGETYPE_JB2 => "jb2",
			IMAGETYPE_SWC => "swc",
			IMAGETYPE_IFF => "iff",
			IMAGETYPE_WBMP => "wbmp",
			IMAGETYPE_XBM => "xbm",
			IMAGETYPE_ICO => "ico"
		);
		return $extensions[$img_info_index_tow];
	}
}

?>
