<?php

class CharAssembler {

	static function getOneRandomChar(){
		$characters = '0123456789abcdefghijklmnopqrxtuvwxyzABCDEFGHIJKLMNOPQRXTUVWXYZ';
		return $characters[mt_rand(0, strlen($characters) - 1)];
		# return substr($characters,rand(0, strlen($characters) - 1),1);
	}

	static function getRandomCharacters($length) {
		$result = '';
		for($i = 0; $i < $length; $i++){
			$result .= self::getOneRandomChar(); 
		}
		return $result;
	}
	
	static function getASCIIValue($indexes, $string)
	{
		$value = 0;
		foreach ($indexes as $i) {
			$value += ord(substr($string, $i, 1));
		}
		return $value;
	}	

}


?>
