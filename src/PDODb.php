<?php

/**
 * A simple PDO wrapper
 * https://github.com/AlfredoRamos/pdodb
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright 2013 Alfredo Ramos
 * @license GNU GPL 3.0+
 */

namespace AlfredoRamos\PDODb;

use PDO;

class PDODb implements PDODbInterface {

	/** @var \PDO $dbh */
	private $dbh;

	/** @var \PDOStatement $stmt */
	private $stmt;

	/** @var string $prefix */
	public $prefix;

	/**
	 * Constructor
	 *
	 * @param array	$config
	 *
	 * @return void
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

		// Create a new PDO instanace
		$this->dbh = new PDO(
			$config['dsn'],
			$config['user'],
			$config['password'],
			$config['options']
		);
	}

	/**
	 * Make a query
	 *
	 * @param string	$sql
	 *
	 * @throws \PDOException
	 *
	 * @return \PDOStatement
	 */
	public function query($sql = '') {
		$this->stmt = $this->dbh->prepare($sql);

		return $this->stmt;
	}

	/**
	 * Bind the data
	 *
	 * @param string					$param
	 * @param string					$value
	 * @param integer|bool|null|string	$type
	 *
	 * @return bool
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

		return $this->stmt->bindValue($param, $value, $type);
	}

	/**
	 * Bind the data from an array
	 *
	 * @see \AlfredoRamos\PDODb::bind()
	 *
	 * @param array	$param
	 *
	 * @return void
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
	 *
	 * @return bool
	 */
	public function execute() {
		return $this->stmt->execute();
	}

	/**
	 * Get single record
	 *
	 * @param integer	$mode
	 *
	 * @return object|array
	 */
	public function fetch($mode = null) {
		$this->execute();

		$fetch_modes = [
			PDO::FETCH_ASSOC,
			PDO::FETCH_NUM,
			PDO::FETCH_OBJ,
			PDO::FETCH_NAMED
		];

		if (in_array($mode, $fetch_modes, true)) {
			$this->stmt->setFetchMode($mode);
		}

		return $this->stmt->fetch();
	}

	/**
	 * Get multiple records
	 *
	 * @param integer	$mode
	 *
	 * @return array
	 */
	public function fetchAll($mode = null) {
		$this->execute();

		$fetch_modes = [
			PDO::FETCH_ASSOC,
			PDO::FETCH_NUM,
			PDO::FETCH_OBJ,
			PDO::FETCH_NAMED
		];

		if (in_array($mode, $fetch_modes, true)) {
			$this->stmt->setFetchMode($mode);
		}

		return $this->stmt->fetchAll();
	}

	/**
	 * Get single field (column) by index
	 *
	 * @param integer	$column
	 *
	 * @return string|integer|float|null|bool
	 */
	public function fetchColumn($column = 0) {
		$this->execute();

		return $this->stmt->fetchColumn($column);
	}

	/**
	 * Get single field (column) by name
	 *
	 * @param string	$name
	 *
	 * @return string|integer|float|null
	 */
	public function fetchField($name = '') {
		$this->execute();

		// Fetch the row
		$row = $this->fetch();
		$field = null;

		if (is_array($row)) {
			// PDO::FETCH_BOTH/PDO::FETCH_ASSOC
			$field = isset($row[$name]) ? $row[$name] : $field;
		} elseif (is_object($row)) {
			// PDO::FETCH_OBJ
			$field = isset($row->{$name}) ? $row->{$name} : $field;
		}

		return $field;
	}

	/**
	 * Get number of affected rows
	 *
	 * @return integer
	 */
	public function rowCount() {
		return $this->stmt->rowCount();
	}

	/**
	 * Get number of columns in the result set
	 *
	 * @return integer
	 */
	public function columnCount() {
		return $this->stmt->columnCount();
	}

	/**
	 * Get last inserted id
	 *
	 * @return integer
	 */
	public function lastInsertId() {
		return $this->dbh->lastInsertId();
	}

	/**
	 * Run transaction
	 *
	 * @return bool
	 */
	public function beginTransaction() {
		return $this->dbh->beginTransaction();
	}

	/**
	 * Stop transaction
	 *
	 * @return bool
	 */
	public function endTransaction() {
		return $this->dbh->commit();
	}

	/**
	 * Cancel transaction
	 *
	 * @return bool
	 */
	public function cancelTransaction() {
		return $this->dbh->rollBack();
	}

	/**
	 * Dumps info contained in prepared statement
	 *
	 * @return void
	 */
	public function debugDumpParams() {
		return $this->stmt->debugDumpParams();
	}

}
