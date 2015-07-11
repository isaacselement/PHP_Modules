<?php

class DLog {

	public static $enable = true;

	public static $file_path = null;

	public static function f($msg, $log_file_path = null ) {
		if (!self::$enable)
			return;
		
		if (self::$file_path == null) 
			self::$file_path = sys_get_temp_dir() . '/php-dlog.log';

		if($log_file_path == null )
			$log_file_path = self::$file_path; 

		FileManager::writeToFile($log_file_path, "\n".$msg);
	}

}

?>
