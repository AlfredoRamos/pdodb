<?php

/**
 * A simple PDO wrapper.
 *
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright 2013 Alfredo Ramos
 * @license GPL-3.0+
 * @link https://github.com/AlfredoRamos/pdodb
 */

namespace AlfredoRamos\PDODb;

interface PDODbInterface {

	/**
	 * Make a query with a prepared statement.
	 *
	 * @param string $sql
	 *
	 * @throws \PDOException
	 *
	 * @return \PDOStatement
	 */
	public function query($sql = '');

	/**
	 * Bind the data.
	 *
	 * @param string					$param
	 * @param string					$value
	 * @param string|integer|bool|null	$type
	 *
	 * @return bool
	 */
	public function bind($param = '', $value = '', $type = null);

	/**
	 * Bind the data from an array.
	 *
	 * @see \AlfredoRamos\PDODb::bind()
	 *
	 * @param array $param
	 *
	 * @return void
	 */
	public function bindArray($param = []);

	/**
	 * Executhe the query.
	 *
	 * @return bool
	 */
	public function execute();

	/**
	 * Get single record.
	 *
	 * @param integer $mode
	 *
	 * @throws \PDOException
	 *
	 * @return object|array
	 */
	public function fetch($mode = null);

	/**
	 * Get multiple records.
	 *
	 * @param integer $mode
	 *
	 * @throws \PDOException
	 *
	 * @return array
	 */
	public function fetchAll($mode = null);

	/**
	 * Get single field (column) by index.
	 *
	 * @param integer $column
	 *
	 * @return string|integer|float|null|bool
	 */
	public function fetchColumn($column = 0);

	/**
	 * Get single field (column) by name.
	 *
	 * @param string	$name
	 * @param integer	$mode
	 *
	 * @throws \PDOException
	 *
	 * @return string|integer|float|null
	 */
	public function fetchField($name = '', $mode = null);

	/**
	 * Get number of affected rows.
	 *
	 * @return integer
	 */
	public function rowCount();

	/**
	 * Get number of columns in the result set.
	 *
	 * @return integer
	 */
	public function columnCount();

	/**
	 * Get last inserted ID.
	 *
	 * @return integer
	 */
	public function lastInsertId();

	/**
	 * Run transaction.
	 *
	 * @throws \PDOException
	 *
	 * @return bool
	 */
	public function beginTransaction();

	/**
	 * Stop transaction.
	 *
	 * @throws \PDOException
	 *
	 * @return bool
	 */
	public function endTransaction();

	/**
	 * Cancel transaction.
	 *
	 * @throws \PDOException
	 *
	 * @return bool
	 */
	public function cancelTransaction();

	/**
	 * Dumps info contained in prepared statement.
	 *
	 * @return void
	 */
	public function debugDumpParams();

	/**
	 * Close connection.
	 *
	 * @return void
	 */
	public function close();
}
