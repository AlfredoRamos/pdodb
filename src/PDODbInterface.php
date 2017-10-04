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

interface PDODbInterface {
	public function query($sql = '');
	public function bind($param = '', $value = '', $type = null);
	public function bindArray($param = []);
	public function execute();
	public function fetch($mode = null);
	public function fetchAll($mode = null);
	public function fetchColumn($column = 0);
	public function fetchField($name = '', $mode = null);
	public function rowCount();
	public function columnCount();
	public function lastInsertId();
	public function beginTransaction();
	public function endTransaction();
	public function cancelTransaction();
	public function debugDumpParams();
	public function close();
}
