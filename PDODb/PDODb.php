<?php namespace AlfredoRamos;
/**
 * Simple PDO Class
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @link https://github.com/AlfredoRamos/simple-pdo-class
 * @copyright Copyright (c) 2013 Alfredo Ramos
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
		// Set the configuration file
		$this->setConfigFile(__DIR__ . '/config.inc.php');
		
		// Set PDODb::$table_prefix
		$this->table_prefix = $this->config['table_prefix'];
		
		$dsn = vsprintf('%1$s:host=%2$s;port=%3$u;dbname=%4$s', [
			$this->config['driver'],
			$this->config['host'],
			$this->config['port'],
			$this->config['database']
		]);
		
		try {
			/**
			 * Create a new PDO instanace
			 * @param	string	$dsn
			 * @param	string	PDODb::$config['user']
			 * @param	string	PDODb::$config['password']
			 * @param	array	PDODb::$config['options']
			 */
			$this->dbh = new PDO(
				$dsn,
				$this->config['user'],
				$this->config['password'],
				$this->config['options']
			);
		} catch (PDOException $ex) {
			trigger_error($ex->getMessage(), E_USER_ERROR);
		}
		
	}
	
	/**
	 * Set the configuration file
	 * @param	string	$file
	 */
	public function setConfigFile($file = '') {
		$this->config = $this->getConfig($file);
	}
	
	/**
	 * Read the configuration file
	 * @param	string	$config
	 * @return	array|null
	 */
	protected function getConfig($file = '') {
		// Default options
		$defaults = __DIR__ . '/config.inc.php.example';
		
		if (file_exists($defaults)) {
			$defaults = require $defaults;
			$defaults = is_array($defaults) ? $defaults : [];
		} else {
			// The're replaced latter, so do not change them
			$defaults = [
				// PDO driver
				// <https://php.net/manual/en/pdo.getavailabledrivers.php>
				'driver'		=> 'mysql',
				
				//Database host.
				// Default: localhost
				'host'			=> 'localhost',
		
				// Port to connect to your database server
				// Default: 3306 (MySQL/MariaDB)
				'port'			=> 3306,
				
				// Database name
				'database'		=> '',
		
				// Database user
				'user'			=> '',
		
				// Database user's password
				'password'		=> '',
		
				// Table prefix
				'table_prefix'	=> '',
		
				// PDO driver options
				// <https://php.net/manual/en/pdo.setattribute.php>
				'options'		=> [
					PDO::ATTR_EMULATE_PREPARES		=> false,
					PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_OBJ,
					PDO::ATTR_PERSISTENT			=> true,
					PDO::MYSQL_ATTR_INIT_COMMAND	=> 'SET NAMES utf8'
				]
			];
		}
		
		// Configuration array
		$config = [];
		
		if (!empty($file) && file_exists($file)) {
			// Load the array from file
			$config = require $file;
			
			// Check if it's an array
			$config = is_array($config) ? $config : [];
		}
		
		// Return the config array with the values
		// replaced with the ones from the file
		return array_replace_recursive($defaults, $config);
	}

	/**
	 * Make a query
	 * @param	string	$query
	 * @return	PDOStatement|PDOException
	 */
	public function query($query = '') {
		try {
			$this->stmt = $this->dbh->prepare($query);
		} catch (PDOException $ex) {
			trigger_error($ex->getMessage(), E_USER_ERROR);
		}
		
		return $this->stmt;
	}

	/**
	 * Bind the data
	 * @param	string	$param
	 * @param	string	$value
	 * @param	integer|bool|null|string	$type
	 * @return	bool
	 */
	public function bind($param = '', $value = '', $type = null) {
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
		
		try {
			return $this->stmt->bindValue($param, $value, $type);
		} catch (PDOException $ex) {
			trigger_error($ex->getMessage(), E_USER_ERROR);
		}
	}
	
	/**
	 * Bind the data from an array
	 * @see		PDODb::bind()
	 * @param	array	$param
	 * @return	bool
	 */
	public function bindArray($param = []) {
		array_map([$this, 'bind'], array_keys($param), array_values($param));
	}

	/**
	 * Executhe the query
	 * @return	bool
	 */
	public function execute() {
		return $this->stmt->execute();
	}

	/**
	 * Get multiple records
	 * @param	integer	$mode	<https://secure.php.net/manual/en/pdostatement.fetch.php>
	 * @return	array
	 */
	public function fetchAll($mode = null) {
		$this->execute();
		
		try {
			if (is_int($mode)) {
				$this->stmt->setFetchMode($mode);
			}
		} catch (PDOException $ex) {
			trigger_error($ex->getMessage(), E_USER_ERROR);
		}
		
		return $this->stmt->fetchAll();
	}

	/**
	 * Get single record
	 * @param	integer	$mode	<https://secure.php.net/manual/en/pdostatement.fetch.php>
	 * @return	object
	 */
	public function fetch($mode = null) {
		$this->execute();
		
		try {
			if (is_int($mode)) {
				$this->stmt->setFetchMode($mode);
			}
		} catch (PDOException $ex) {
			trigger_error($ex->getMessage(), E_USER_ERROR);
		}
		
		return $this->stmt->fetch();
	}
	
	/**
	 * Get meta-data for a single field
	 * @param	string	$name
	 */
	public function fetchField($name = '') {
		$this->execute();
		
		// Fetch the row
		$row = $this->fetch();
		
		switch (gettype($row)) {
			case 'array': // PDO::FETCH_BOTH/PDO::FETCH_ASSOC
				$field = isset($row[$name]) ? $row[$name] : false;
				break;
			case 'object': // PDO::FETCH_OBJ
				$field = isset($row->{$name}) ? $row->{$name} : false;
				break;
			default: // Default value
				$field = false;
				break;
		}
		
		return $field;
	}

	/**
	 * Get number of affected rows
	 * @return	integer
	 */
	public function rowCount() {
		return $this->stmt->rowCount();
	}

	/**
	 * Get last inserted id
	 * @return	integer
	 */
	public function lastInsertId() {
		return $this->dbh->lastInsertId();
	}

	/**
	 * Run batch queries
	 * @return	bool
	 */
	public function beginTransaction() {
		return $this->dbh->beginTransaction();
	}

	/**
	 * Stop batch queries
	 * @return	bool
	 */
	public function endTransaction() {
		return $this->dbh->commit();
	}

	/**
	 * Cancel batch queries
	 * @return	bool
	 */
	public function cancelTransaction() {
		return $this->dbh->rollBack();
	}
	
	/**
	 * Dumps info contained in prepared statement
	 * @return	void
	 */
	public function debugDumpParams() {
		return $this->stmt->debugDumpParams();
	}

}