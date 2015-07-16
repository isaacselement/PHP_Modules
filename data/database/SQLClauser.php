<?php

class SQLClauser
{

	public static function comma($str) {
		return "'".addslashes($str)."'";			
	}

	public static function commaElements($arr) {
		$result = [];
		foreach($arr as $ele) {
			$result[] = self::comma($ele);
		}
		return $result;
	}

	public static function cockles($str) {
		return '`'.addslashes($str).'`';
	}

	public static function cocklesElements($arr) {
		$result = [];
		foreach($arr as $ele) {
			$result[] = self::cockles($ele);
		}
		return $result;
	}

	public static function insertClause($table, $keysvalues) {
		$insert_clause = 'insert into ' . self::cockles($table);

		$columns = self::cocklesElements(array_keys($keysvalues));
		$columns_values = self::commaElements(array_values($keysvalues));

		$insert_clause .= '(' . implode(',' , $columns) . ')';	
		$insert_clause .= ' values ';
		$insert_clause .= '(' . implode(',' , $columns_values) . ')';
		
		return $insert_clause;
	}
}


?>
