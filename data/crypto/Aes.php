<?php
abstract class Aes {

	// Has other two length 192 and 256
	const CIPHER = MCRYPT_RIJNDAEL_128;
	const MODE = MCRYPT_MODE_ECB;

	static public function encode($key, $str){
		$str = self::pkcs5_pad($str, 16);
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(self::CIPHER,self::MODE),MCRYPT_RAND);
		return mcrypt_encrypt(self::CIPHER, $key, $str, self::MODE, $iv);
	}

	static public function decode( $key, $str){
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(self::CIPHER,self::MODE),MCRYPT_RAND);
		$res = mcrypt_decrypt(self::CIPHER, $key, $str, self::MODE, $iv);
		$res = self::pkcs5_unpad($res);
		return $res;
	}

	static function pkcs5_unpad($text)
	{
		$pad = ord($text{strlen($text) - 1});
		if ($pad > strlen($text)) return false;
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
		return substr($text, 0, -1 * $pad);
	}

	static function pkcs5_pad($text, $blocksize)
	{
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	// create the aes keys pool
	static function getAesKeys($count, $aes_length = 32)
	{
		$result = [];
		for($i = 0; $i < $count; $i++)	{
			$result[] = CharAssembler::getRandomCharacters($aes_length);
		}
		return $result;
	}
}
?>
