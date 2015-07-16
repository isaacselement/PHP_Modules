<?php

class CharAssembler {

	public static function getOneRandomChar(){
		$characters = array_merge(range(48, 57), range(65, 90), range(97, 123));
		return chr(mt_rand(0, count($characters) - 1));

		# $characters = '0123456789abcdefghijklmnopqrxtuvwxyzABCDEFGHIJKLMNOPQRXTUVWXYZ';
		# return $characters[mt_rand(0, strlen($characters) - 1)];
		# return substr($characters,rand(0, strlen($characters) - 1),1);
	}

	public static function getRandomCharacters($length) {
		$result = '';
		for($i = 0; $i < $length; $i++){
			$result .= self::getOneRandomChar(); 
		}
		return $result;
	}
	
	public static function getASCIIValue($indexes, $string)
	{
		$value = 0;
		foreach ($indexes as $i) {
			$value += ord(substr($string, $i, 1));
		}
		return $value;
	}	

}


?>
