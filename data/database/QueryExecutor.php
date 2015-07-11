<?php

class QueryExecutor
{

	public static function slashed($str) {
		if(is_string($str)){
			return "'".addslashes($str)."'";			
		}
		return $str;
	}

}


?>
