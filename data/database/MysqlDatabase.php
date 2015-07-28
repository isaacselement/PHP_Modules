<?php

class MysqlDatabase
{
	private static $instance = null;
	public static function setInstance($obj) {
		self::$instance = $obj;
	}	
	public static function getInstance() {
		if(is_null(self::$instance)){
			$clazz = get_called_class();
			$self_clazz = get_class();		// should use get_class(), other than self.
			if ($self_clazz == $clazz || is_subclass_of($clazz, $self_clazz)) {
				self::setInstance(new $clazz);
			}
		}
		return self::$instance;
	}

	/*
	// in subclass, define a non-parameters construct . then u can just use subclass::getInstance();	
	public function __construct() {
		$mysql_config = Config::get('mysql');
		$db_server_ip = $mysql_config['server_ip'];
		$db_server_port = $mysql_config['server_port'];
		$db_name = $mysql_config['database'];
		$db_user = $mysql_config['user'];
		$db_password = $mysql_config['password'];	
		parent::__construct($db_server_ip, $db_server_port, $db_name, $db_user, $db_password);
	}
	 */
	private $connection = null;
	public function __construct($server_ip, $server_port, $db_name, $db_user, $db_password) {
		// connect the databases
		$db_server = $server_ip . ':' . $server_port;
		$this->connection = mysql_connect($db_server, $db_user, $db_password) or die ('sorry, cannot open mysql connection');
		mysql_select_db($db_name, $this->connection);
	}

	public function __destruct() {
		$this->closeConnection();
	}

	public function getConnection() {
		return $this->connection;
	}

	public function closeConnection() {
		mysql_close($this->connection);
		$this->connection = null;
	}

	public function query($sql_clause, $iter_func) {
		$result = mysql_query($sql_clause, $this->connection);
		//echo '<br />' . $sql_clause . '<br />';
		if (!$result) {
			die('Invalid query: ' . mysql_error());
		}
		if (is_bool($result)) {
			return;
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
