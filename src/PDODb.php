<?php

/**
 * A simple PDO wrapper.
 *
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright 2013 Alfredo Ramos
 * @license GPL-3.0-or-later
 * @link https://github.com/AlfredoRamos/pdodb
 */

namespace AlfredoRamos\PDODb;

use PDO;

class PDODb implements PDODbInterface {
	use PDODbTrait;

	/**
	 * {@inheritDoc}
	 */
	public function query($sql = '') {
		$this->stmt = $this->dbh->prepare($sql);

		return $this->stmt;
	}

	/**
	 * {@inheritDoc}
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
	 * {@inheritDoc}
	 */
	public function bindArray($param = []) {
		array_map(
			[$this, 'bind'],
			array_keys($param),
			array_values($param)
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute() {
		return $this->stmt->execute();
	}

	/**
	 * {@inheritDoc}
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
	 * {@inheritDoc}
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
	 * {@inheritDoc}
	 */
	public function fetchColumn($column = 0) {
		$this->execute();

		return $this->stmt->fetchColumn($column);
	}

	/**
	 * {@inheritDoc}
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
	 * {@inheritDoc}
	 */
	public function rowCount() {
		return $this->stmt->rowCount();
	}

	/**
	 * {@inheritDoc}
	 */
	public function columnCount() {
		return $this->stmt->columnCount();
	}

	/**
	 * {@inheritDoc}
	 */
	public function lastInsertId() {
		return $this->dbh->lastInsertId();
	}

	/**
	 * {@inheritDoc}
	 */
	public function beginTransaction() {
		return $this->dbh->beginTransaction();
	}

	/**
	 * {@inheritDoc}
	 */
	public function endTransaction() {
		return $this->dbh->commit();
	}

	/**
	 * {@inheritDoc}
	 */
	public function cancelTransaction() {
		return $this->dbh->rollBack();
	}

	/**
	 * {@inheritDoc}
	 */
	public function debugDumpParams() {
		return $this->stmt->debugDumpParams();
	}

	/**
	 * {@inheritDoc}
	 */
	public function close() {
		$this->stmt = null;
		$this->dbh = null;
	}
}
