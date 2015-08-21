<?php 
class MysqlDatabase
{
	private static $instance = null;
	public static function setInstance($obj) {
		self::$instance = $obj;
	}	
	public static function getInstance() {
		if(is_null(self::$instance)){
			$self_clazz = get_class();		// use get_class(), other than self.
			$called_clazz = get_called_class();	// get the called class
			if ($self_clazz == $called_clazz || is_subclass_of($called_clazz, $self_clazz)) {
				self::setInstance(new $called_clazz);
			}
		}
		return self::$instance;
	}

	// for multi mysql connect instance, i.e AMysqlDatabase/BMysqlDatabase .
	private static $sharedInstances = null;
	public static function sharedInstance() {
		if (is_null(self::$sharedInstances)) {
			self::$sharedInstances = [];
		}
		
		$called_clazz = get_called_class();
		if (!isset(self::$sharedInstances[$called_clazz])) { 
			if (isset(self::$instance) && get_class(self::$instance) == $called_clazz) {
				self::$sharedInstances[$called_clazz] = self::$instance;
			} else {
				$self_clazz = get_class();
				if ($self_clazz == $called_clazz || is_subclass_of($called_clazz, $self_clazz)) {
					self::$sharedInstances[$called_clazz] = new $called_clazz;
				}
			}
		}

		return self::$sharedInstances[$called_clazz];
	}	

	/*
	// in subclass, define a non-parameters construct . then u can just use subclass::getInstance();	
	public function __construct() {
		$mysql_config = Config::get('mysql_projectName');
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
		$connection = mysql_connect($db_server, $db_user, $db_password) or die ('sorry, cannot open mysql connection');
		$this->connection = $connection;
		mysql_select_db($db_name, $connection);
		mysql_query("set names utf8", $connection);
	}

	public function __destruct() {
		$this->closeConnection();
	}

	public function getConnection() {
		return $this->connection;
	}

	public function closeConnection() {
		if (is_resource($this->connection)) {
			mysql_close($this->connection);
		}
		$this->connection = null;
	}

	public function query($sql_clause, $iter_func, $result_type = MYSQL_BOTH) {
		$result = mysql_query($sql_clause, $this->connection);
		// echo '<br />' . $sql_clause . '<br />';
		if (!$result) {
			die('Invalid query: ' . mysql_error());
		}
		if (is_bool($result)) {
			return;
		}
		while($row = mysql_fetch_array($result, $result_type)) {
			if(!is_null($iter_func)){
				$iter_func($row);
			}
		}
		mysql_free_result($result);
	}
}


?>
