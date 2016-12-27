<?php

/**
 * PDODb - A simple PDO wrapper
 * @link https://github.com/AlfredoRamos/pdodb
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright (c) 2013 Alfredo Ramos
 * @license GNU GPL 3.0+ <https://www.gnu.org/licenses/gpl-3.0.txt>
 */

namespace AlfredoRamos\PDODb;

use PDO;
use PDOException;
use AlfredoRamos\PDODb\Interfaces\PDODbInterface;

class PDODb implements PDODbInterface {

	private $dbh;
	private $stmt;
	public $prefix;

	/**
	 * Constructor
	 * @param	array	$config
	 * @return	void
	 */
	public function __construct($config = []) {
		// Default options
		$config = array_replace(
			[
				'driver'	=> 'mysql',
				'host'		=> 'localhost',
				'port'		=> 3306,
				'database'	=> '',
				'charset'	=> 'utf8',
				'user'		=> '',
				'password'	=> '',
				'prefix'	=> '',
				'options'	=> [
					PDO::ATTR_EMULATE_PREPARES		=> false,
					PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_OBJ,
					PDO::ATTR_PERSISTENT			=> true
				]
			],
			$config
		);

		// Default PDO options
		$config['dsn']	= vsprintf(
			'%1$s:host=%2$s;port=%3$u;dbname=%4$s;charset=%5$s', [
				$config['driver'],
				$config['host'],
				$config['port'],
				$config['database'],
				$config['charset']
			]
		);

		// Table prefix
		$this->prefix = $config['prefix'];

		try {
			// Create a new PDO instanace
			$this->dbh = new PDO(
				$config['dsn'],
				$config['user'],
				$config['password'],
				$config['options']
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
		array_map(
			[$this, 'bind'],
			array_keys($param),
			array_values($param)
		);
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
