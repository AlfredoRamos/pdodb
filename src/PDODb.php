<?php namespace AlfredoRamos\PDODb;
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
use \AlfredoRamos\PDODb\Config;
use \AlfredoRamos\PDODb\Interfaces\PDODbInterface;

/**
 * @example example/Customer.php
 */
class PDODb implements PDODbInterface {
	
	use \AlfredoRamos\PDODb\Traits\SingletonTrait;
	
	private $dbh;
	private $stmt;
	
	protected $config;
	
	public $prefix;
	
	/**
	 * Constructor
	 * @see AlfredoRamos\SingletonTrait::__construct()
	 */
	protected function init() {
		// Config helper
		$this->config = Config::instance();
		
		// Connection name
		$connection =  sprintf('connections.%s', $this->config->get('connection'));
		
		// Default PDO options
		$data = [
			'dsn'		=> vsprintf('%1$s:host=%2$s;port=%3$u;dbname=%4$s', [
				$this->config->get($connection . '.driver'),
				$this->config->get($connection . '.host'),
				$this->config->get($connection . '.port'),
				$this->config->get($connection . '.database')
			]),
			'user'		=> $this->config->get($connection . '.user'),
			'password'	=> $this->config->get($connection . '.password'),
			'options'	=> $this->config->get($connection . '.options')
		];
		
		// Table prefix
		$this->prefix = $this->config->get($connection . '.prefix');
		
		switch($this->config->get($connection . '.driver')) {
			case 'sqlite':
				$data = array_merge($data, [
					'dsn'	=> vsprintf('%1$s:%2$s', [
						$this->config->get($connection . '.driver'),
						$this->config->get($connection . '.database')
					])
				]);
		}
		
		try {
			// Create a new PDO instanace
			$this->dbh = new PDO(
				$data['dsn'],
				$data['user'],
				$data['password'],
				$data['options']
			);
		} catch (PDOException $ex) {
			trigger_error($ex->getMessage(), E_USER_ERROR);
		}
		
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
	 * @see		AlfredoRamos\PDODb::bind()
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
	 * @return	array|object|null
	 */
	public function fetchField($name = '') {
		$this->execute();
		
		// Fetch the row
		$row = $this->fetch();
		
		switch (gettype($row)) {
			case 'array': // PDO::FETCH_BOTH/PDO::FETCH_ASSOC
				$field = isset($row[$name]) ? $row[$name] : null;
				break;
			case 'object': // PDO::FETCH_OBJ
				$field = isset($row->{$name}) ? $row->{$name} : null;
				break;
			default: // Default value
				$field = null;
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