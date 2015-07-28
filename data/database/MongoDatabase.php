<?php

class MongoDatabase 
{
	// copy from MysqlDatabase
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

	private $mongoClient = null;
	private $mongoDB = null;
	private $mongoCollection = null;
	public function __construct($server_ip, $server_port, $db_name, $db_user = null, $db_password = null) {
		// mongodb://[username:password@]host1[:port1][,host2[:port2:],...]/db
		$db_server = 'mongodb://';
	        if (isset($db_user) && isset($db_password)) {
			$db_server .= $db_user . ':' . $db_password . '@' ;
		}
		$db_server .= $server_ip . ':' . $server_port;	
		$mongoClient = new Mongo($db_server);
		$this->mongoClient = $mongoClient;
		$this->setCurrentDB($db_name);
	}

	public function __destruct() {
		$this->mongoClient->close();
		$this->mongoClient = null;
		$this->mongoDB = null;
	}

	public function getMongoClient() {
		return $this->mongoClient;
	}

	public function setCurrentDB($db_name) {
		$this->mongoDB = $this->mongoClient->selectDB($db_name);
		return $this->getCurrentDB();
	}
	public function getCurrentDB() {
		return $this->mongoDB;
	}

	public function setCurrentCollection($collection_name) {
		$this->mongoCollection = $this->getCurrentDB()->selectCollection($collection_name);
		return $this->getCurrentCollection();
	}

	public function getCurrentCollection() {
		return $this->mongoCollection;
	}

}
?>
