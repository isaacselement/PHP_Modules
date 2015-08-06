<?php

class FileManager {

	public static function autoInclude($className, $auto_load_paths) {
		foreach ($auto_load_paths as $path) {
			$files = self::listFilesRecursivelyIn($path);
			foreach($files as $file) {
				$base_name = basename($file, '.php');
				if ($base_name == $className && is_readable($file) ) {
					include($file);
					return true;
				}
			}
		}
		return false;
	}
	
	public function autoIncludeNamespaceClass($className, $auto_load_paths){
		foreach ($autoInclude as $path) {
			$classFile = $path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, ltrim($className, '\\')) . '.php';
			if(is_readable($classFile)) {
				include($classFile);
				return true;
			}
		}
		return false;
	}

	public function isNamaspaceClass($className){
		return strpos($className, '\\') !== false;
	}


	public static function requireFilesIn($directory) {
		$php_ex = substr($directory, -1) == '/' ? '*.php' : '/*.php';
		foreach (glob($directory . $php_ex) as $filename) {
			require_once($filename);
			#include_once $filename;
		}
		exit();
	}

	public static function listFilesIn($directory)
	{
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		return $scanned_directory;
	}

	public static function listFilesRecursivelyIn($directory)
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

	public static function writeFilesToEcho($file_paths, $boundary)
	{
		$length = count($file_paths);
		if ($length == 1) {
			return self::writeFileToEcho($file_paths[0]);
		}
		for($i = 0; $i < $length; $i++) {
			self::writeFileToEcho($file_paths[$i]);
			if ($i != $length - 1) {
				echo $boundary;
			}
		}
	}

	public static function writeFileToEcho($file_path)
	{
		$fp = fopen($file_path,'r');
		if ($fp == false) {
			error_log('Read file ' . $file_path . ' failed');
			return false;
		}
		$bufferSize = 1024;
		while(!feof($fp)) {
			$file_data = fread($fp, $bufferSize);
			echo $file_data;
		}
		fclose($fp);
	}

	public static function writeToFile($file_path, $content)
	{
		$fp = fopen($file_path, 'a+');
		if ($fp == false) {
			error_log('Read file ' . $file_path . ' failed');
			return false;
		}
		fwrite($fp, $content);
		fclose($fp);
	}
	
	public static function replaceExtension($filename, $new_extension) {
		$info = pathinfo($filename);
		return $info['dirname'] . '/' . $info['filename'] . '.' . $new_extension;
	}
}
?>
