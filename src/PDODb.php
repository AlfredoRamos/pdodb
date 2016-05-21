<?php namespace AlfredoRamos\PDODb;

/**
 * PDODb - A simple PDO wrapper
 * @link https://github.com/AlfredoRamos/pdodb
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright (c) 2013 Alfredo Ramos
 * @license GNU GPL 3.0+ <https://www.gnu.org/licenses/gpl-3.0.txt>
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
	private $config;

	public $prefix;

	/**
	 * Constructor
	 * @see AlfredoRamos\SingletonTrait::__construct()
	 */
	protected function init() {
		// Config helper
		$this->config = Config::instance();
		$this->config->setConfigFile(__DIR__ . '/../config/config.inc.php');

		// Connection name
		$connection =  sprintf('connections.%s', $this->config->get('connection'));

		// Default PDO options
		$data = [
			'dsn'	=> vsprintf('%1$s:host=%2$s;port=%3$u;dbname=%4$s;charset=%5$s', [
				$this->config->get(sprintf('%s.driver', $connection)),
				$this->config->get(sprintf('%s.host', $connection)),
				$this->config->get(sprintf('%s.port', $connection)),
				$this->config->get(sprintf('%s.database', $connection)),
				$this->config->get(sprintf('%s.charset', $connection))
			]),
			'user'		=> $this->config->get(sprintf('%s.user', $connection)),
			'password'	=> $this->config->get(sprintf('%s.password', $connection)),
			'options'	=> $this->config->get(sprintf('%s.options', $connection)),
		];

		// Table prefix
		$this->prefix = $this->config->get(sprintf('%s.prefix', $connection));

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
