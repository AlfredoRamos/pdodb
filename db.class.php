<?php
class DataBase {
	private $stmt;

	private $host	= DB_HOST;
	private $user	= DB_USER;
	private $pass	= DB_PASS;
	private $dbname	= DB_NAME;

	private $dbh;
	private $error;

	public function __construct(){
		// Set DSN
		$dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;

		// Set options
		$options = array(
			PDO::ATTR_PERSISTENT		=> true,
			PDO::ATTR_ERRMODE		=> PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_INIT_COMMAND	=> 'SET NAMES utf8',
			PDO::ATTR_EMULATE_PREPARES	=> false
		);

		try {
			// Create a new PDO instanace
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		} catch(PDOException $e) {
			// Catch any errors
			$this->error = $e->getMessage();
		}
	}

	// Make a query
	public function query($query) {
		$this->stmt = $this->dbh->prepare($query);
	}

	// Bind the data
	public function bind($param, $value, $type = null){
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}

	// Executhe the query
	public function execute(){
		return $this->stmt->execute();
	}

	// Get multiple records
	public function resultSet(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	// Get single record
	public function single(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Get number of affected rows
	public function rowCount(){
		return $this->stmt->rowCount();
	}

	// Get last inserted id (string)
	public function lastInsertId(){
		return $this->dbh->lastInsertId();
	}

	// Run batch queries
	public function beginTransaction(){
		return $this->dbh->beginTransaction();
	}

	// Stop batch queries
	public function endTransaction(){
		return $this->dbh->commit();
	}

	// Cancel batch queries
	public function cancelTransaction(){
		return $this->dbh->rollBack();
	}

	// Dumps info contained in prepared statement
	public function debugDumpParams(){
		return $this->stmt->debugDumpParams();
	}
}
?>
