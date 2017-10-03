<?php

/**
 * A simple PDO wrapper
 *
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright 2013 Alfredo Ramos
 * @license GPL-3.0+
 * @link https://github.com/AlfredoRamos/pdodb
 */

namespace AlfredoRamos\PDODb;

use PDO;

class PDODb implements PDODbInterface {

	/** @var \PDO $dbh */
	private $dbh;

	/** @var \PDOStatement $stmt */
	private $stmt;

	/** @var array $fetchModes */
	private $fetchModes;

	/** @var string $prefix */
	public $prefix;

	/**
	 * Constructor.
	 *
	 * @param array $config
	 *
	 * @throws \PDOException
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
		$config['dsn'] = vsprintf(
			'%1$s:host=%2$s;port=%3$u;charset=%4$s;',
			[
				$config['driver'],
				$config['host'],
				$config['port'],
				$config['charset']
			]
		);

		// Add database to DSN if database name is not empty
		if (!empty($config['database'])) {
			$config['dsn'] .= sprintf('dbname=%s;', $config['database']);
		}

		// Table prefix
		$this->prefix = $config['prefix'];

		// Create a new PDO instanace
		$this->dbh = new PDO(
			$config['dsn'],
			$config['user'],
			$config['password'],
			$config['options']
		);

		// Remove configuration
		unset($config);

		// Allowed fetch modes
		$this->fetchModes = [
			PDO::FETCH_ASSOC,
			PDO::FETCH_NUM,
			PDO::FETCH_OBJ,
			PDO::FETCH_NAMED
		];
	}

	/**
	 * Make a query with a prepared statement.
	 *
	 * @param string $sql
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
	 * Bind the data.
	 *
	 * @param string					$param
	 * @param string					$value
	 * @param string|integer|bool|null	$type
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
	 * Bind the data from an array.
	 *
	 * @see \AlfredoRamos\PDODb::bind()
	 *
	 * @param array $param
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
	 * Executhe the query.
	 *
	 * @return bool
	 */
	public function execute() {
		return $this->stmt->execute();
	}

	/**
	 * Get single record.
	 *
	 * @param integer $mode
	 *
	 * @throws \PDOException
	 *
	 * @return object|array
	 */
	public function fetch($mode = null) {
		$this->execute();

		// Change fetch mode
		if (in_array($mode, $this->fetchModes, true)) {
			$this->stmt->setFetchMode($mode);
		}

		return $this->stmt->fetch();
	}

	/**
	 * Get multiple records.
	 *
	 * @param integer $mode
	 *
	 * @throws \PDOException
	 *
	 * @return array
	 */
	public function fetchAll($mode = null) {
		$this->execute();

		// Change fetch mode
		if (in_array($mode, $this->fetchModes, true)) {
			$this->stmt->setFetchMode($mode);
		}

		return $this->stmt->fetchAll();
	}

	/**
	 * Get single field (column) by index.
	 *
	 * @param integer $column
	 *
	 * @return string|integer|float|null|bool
	 */
	public function fetchColumn($column = 0) {
		$this->execute();

		return $this->stmt->fetchColumn($column);
	}

	/**
	 * Get single field (column) by name.
	 *
	 * @param string $name
	 *
	 * @throws \PDOException
	 *
	 * @return string|integer|float|null
	 */
	public function fetchField($name = '', $mode = null) {
		$this->execute();

		// Change fetch mode
		if (in_array($mode, $this->fetchModes, true)) {
			$this->stmt->setFetchMode($mode);
		}

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
	 * Get number of affected rows.
	 *
	 * @return integer
	 */
	public function rowCount() {
		return $this->stmt->rowCount();
	}

	/**
	 * Get number of columns in the result set.
	 *
	 * @return integer
	 */
	public function columnCount() {
		return $this->stmt->columnCount();
	}

	/**
	 * Get last inserted ID.
	 *
	 * @return integer
	 */
	public function lastInsertId() {
		return $this->dbh->lastInsertId();
	}

	/**
	 * Run transaction.
	 *
	 * @throws \PDOException
	 *
	 * @return bool
	 */
	public function beginTransaction() {
		return $this->dbh->beginTransaction();
	}

	/**
	 * Stop transaction.
	 *
	 * @throws \PDOException
	 *
	 * @return bool
	 */
	public function endTransaction() {
		return $this->dbh->commit();
	}

	/**
	 * Cancel transaction.
	 *
	 * @throws \PDOException
	 *
	 * @return bool
	 */
	public function cancelTransaction() {
		return $this->dbh->rollBack();
	}

	/**
	 * Dumps info contained in prepared statement.
	 *
	 * @return void
	 */
	public function debugDumpParams() {
		return $this->stmt->debugDumpParams();
	}

}
