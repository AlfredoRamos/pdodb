<?php
/**
 * Simple PDO Class
 * @package simple-pdo-class
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @link https://github.com/AlfredoRamos/simple-pdo-class
 * @copyright Copyright (c) 2014, Alfredo Ramos
 * @licence http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3
 *
 * This file is part of Simple PDO Class.
 *
 * Simple PDO Class is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Simple PDO Class is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Simple PDO Class.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @example examples.php
 */
class DataBase {

	private $host	= DB_HOST;
	private $user	= DB_USER;
	private $pass	= DB_PASS;
	private $dbname	= DB_NAME;

	private $stmt;
	private $dbh;
	private $error;

	private $dsn;
	private $driver_options;

	public function __construct(){
		/**
		 * Set DSN
		 */
		$this->dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;

		/**
		 * Set options
		 */
		$this->driver_options = array(
			PDO::ATTR_EMULATE_PREPARES	=> false,
			PDO::ATTR_ERRMODE		=> PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_OBJ,
			PDO::ATTR_PERSISTENT		=> true,
			PDO::MYSQL_ATTR_INIT_COMMAND	=> 'SET NAMES utf8'
		);

		try {
			/**
			 * Create a new PDO instanace
			 * @param string $dns
			 * @param string $user
			 * @param string $pass
			 * @param array $driver_options
			 */
			$this->dbh = new PDO($this->dsn, $this->user, $this->pass, $this->driver_options);
		} catch (PDOException $ex) {
			/**
			 * Catch any errors
			 */
			$this->error = $ex->getMessage();
		}
	}

	/**
	 * Make a query
	 * @param string $query
	 * @return PDOStatement|PDOException
	 */
	public function query($query) {
		$this->stmt = $this->dbh->prepare($query);
	}

	/**
	 * Bind the data
	 * @param string $param
	 * @param string $value
	 * @param int|bool|null|string $type
	 * @return bool
	 */
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
					break;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}
	
	/**
	 * Bind the data from an array
	 * @param array $param_array
	 * @return bool
	 * @see DataBase::bind()
	 */
	public function bindArray($param_array) {
		array_map(array($this, 'bind'), array_keys($param_array), array_values($param_array));
	}

	/**
	 * Executhe the query
	 * @return bool
	 */
	public function execute(){
		return $this->stmt->execute();
	}

	/**
	 * Get multiple records
	 * @return object
	 * @see DataBase::$driver_options
	 */
	public function resultSet(){
		$this->execute();
		return $this->stmt->fetchAll();
	}

	/**
	 * Get single record
	 * @return object
	 * @see DataBase::$driver_options
	 */
	public function single(){
		$this->execute();
		return $this->stmt->fetch();
	}

	/**
	 * Get number of affected rows
	 * @return int
	 */
	public function rowCount(){
		return $this->stmt->rowCount();
	}

	/**
	 * Get last inserted id
	 * @return int
	 */
	public function lastInsertId(){
		return $this->dbh->lastInsertId();
	}

	/**
	 * Run batch queries
	 * @return bool
	 */
	public function beginTransaction(){
		return $this->dbh->beginTransaction();
	}

	/**
	 * Stop batch queries
	 * @return bool
	 */
	public function endTransaction(){
		return $this->dbh->commit();
	}

	/**
	 * Cancel batch queries
	 * @return bool
	 */
	public function cancelTransaction(){
		return $this->dbh->rollBack();
	}

	/**
	 * Dumps info contained in prepared statement
	 * @return void
	 */
	public function debugDumpParams(){
		return $this->stmt->debugDumpParams();
	}
}
?>