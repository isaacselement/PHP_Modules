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

	public static function selectClause($table, $andKeysValues) {
		$select_clause = 'select * from ' . self::cockles($table); 

		$and_clause_arr = [];
		foreach($andKeysValues as $key => $value) {
			$column = self::cockles($key);
			$column_value = self::comma($value);
			$and_clause_arr[] = $column . ' = ' . $column_value;
		}
		$and_clause = implode(' and ', $and_clause_arr);
		$where_clause = ' where ' . $and_clause;

		$result_clause = $select_clause . $where_clause . ';';
		return $result_clause;
	}
}


?>
