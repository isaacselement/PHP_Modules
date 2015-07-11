<?php

class FileManager {

	static function replaceExtension($filename, $new_extension) {
		$info = pathinfo($filename);
		return $info['dirname'] . '/' . $info['filename'] . '.' . $new_extension;
	}

	static function requireFilesIn($directory) {
		$php_ex = substr($directory, -1) == '/' ? '*.php' : '/*.php';
		foreach (glob($directory . $php_ex) as $filename) {
			require_once($filename);
			#include_once $filename;
		}
		exit();
	}

	static function listFilesIn($directory)
	{
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		return $scanned_directory;
	}

	static function listFilesRecursivelyIn($directory)
	{
		$result = array();
		$handler = opendir($directory);
		while($file = readdir($handler)) {
			if($file == "." || $file == "..") {
				continue;
			}
			
			$file_path = $directory . DIRECTORY_SEPARATOR . $file;	
			if (is_dir($file_path)) {
				$subResult = self::listFilesRecursivelyIn($file_path);
				$result = array_merge($result, $subResult);
			} else {
				$result[] = $file_path;
			}
		}
		closedir($handler);
		return $result;
	}

	static function writeFileToEcho($file_path)
	{
		$fp = fopen($file_path,'r');
		if ($fp == false) {
			error_log('Read file ' . $file_path . ' failed');
			return false;
		}
		$buffer = 1024;
		while(!feof($fp)) {
			$file_data = fread($fp, $buffer);
			echo $file_data;
		}
		fclose($fp);
	}

	static function writeToFile($file_path, $content)
	{
		$fp = fopen($file_path, 'a+');
		if ($fp == false) {
			error_log('Read file ' . $file_path . ' failed');
			return false;
		}
		fwrite($fp, $content);
		fclose($fp);
	}
}
?>
