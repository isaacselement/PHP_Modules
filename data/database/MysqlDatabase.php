<?php

class MysqlDatabase
{

	private static $instance = null;
	private $connection = null;

	public static function setInstance($obj) {
		self::$instance = $obj;
	}	
	public static function getInstance() {
		return self::$instance;
	}

	public function __construct($db_server, $db_name, $db_user, $db_password) {
		// connect the databases
		$this->connection = mysql_connect($db_server, $db_user, $db_password) or die ('sorry, cannot open mysql connection');
		mysql_select_db($db_name, $this->connection);
	}

	public function getConnection() {
		return $this->connection;
	}

	public function query($sql_clause, $iter_func) {
		$result = mysql_query($sql_clause, $this->connection);
		//echo '<br />' . $sql_clause . '<br />';
		if (!$result) {
			die('Invalid query: ' . mysql_error());
		}
		while($row = mysql_fetch_array($result)) {
			if(!is_null($iter_func)){
				$iter_func($row);
			}
		}
		mysql_free_result($result);
	}
}


?>
