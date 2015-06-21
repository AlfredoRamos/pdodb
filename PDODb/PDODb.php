<?php
/**
 * Simple PDO Class
 * @package simple-pdo-class
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @link https://github.com/AlfredoRamos/simple-pdo-class
 * @copyright Copyright (c) 2014 Alfredo Ramos
 * @licence GNU GPL-3.0+
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace AlfredoRamos;

/**
 * @ignore
 */
define('IN_PDODB', true);

use \PDO;
use \PDOException;
use \Exception;

/**
 * @example example/Customer.php
 */
class PDODb implements PDODbInterface {
	use SingletonTrait;
	
	private $dbh;
	private $stmt;
	
	private $config;
	
	public $table_prefix;
	
	protected function init() {
		$this->config = require __DIR__ . '/config.inc.php';
		$this->config = is_array($this->config) ? $this->config : [];
		
		/**
		 * Set PDODb::$table_prefix
		 */
		$this->table_prefix = $this->config['table_prefix'];
		
		$dsn = sprintf(
			'%s:host=%s;port=%u;dbname=%s',
			$this->config['driver'],
			$this->config['host'],
			$this->config['port'],
			$this->config['database']
		);
		
		try {
			/**
			 * Create a new PDO instanace
			 * @param string $dsn
			 * @param string PDODb::$config['user']
			 * @param string PDODb::$config['password']
			 * @param array PDODb::$config['driver_options']
			 */
			$this->dbh = new PDO(
				$dsn,
				$this->config['user'],
				$this->config['password'],
				$this->config['driver_options']
			);
		} catch (PDOException $ex) {
			/**
			 * Catch any errors
			 */
			throw new Exception($ex->getMessage(), $ex->getCode());
		}
		
	}

	/**
	 * Make a query
	 * @param string $query
	 * @return PDOStatement|PDOException
	 */
	public function query($query = '') {
		$this->stmt = $this->dbh->prepare($query);
		return $this->stmt;
	}

	/**
	 * Bind the data
	 * @param string $param
	 * @param string $value
	 * @param int|bool|null|string $type
	 * @return bool
	 */
	public function bind($param = '', $value = '', $type = null){
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
	 * @see PDODb::bind()
	 * @param array $param
	 * @return bool
	 */
	public function bindArray($param = []) {
		array_map(array($this, 'bind'), array_keys($param), array_values($param));
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
	 * @return array
	 */
	public function fetchAll(){
		$this->execute();
		return $this->stmt->fetchAll();
	}

	/**
	 * Get single record
	 * @return object
	 */
	public function fetch(){
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