<?php

/**
 * A simple PDO wrapper
 * https://github.com/AlfredoRamos/pdodb
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright 2013 Alfredo Ramos
 * @license GNU GPL 3.0+
 */

namespace AlfredoRamos\PDODb\Interfaces;

interface PDODbInterface {
	public function query($sql = '');
	public function bind($param = '', $value = '', $type = null);
	public function bindArray($param = []);
	public function execute();
	public function fetch($mode = null);
	public function fetchAll($mode = null);
	public function fetchColumn($column = 0);
	public function fetchField($name = '');
	public function rowCount();
	public function columnCount();
	public function lastInsertId();
	public function beginTransaction();
	public function endTransaction();
	public function cancelTransaction();
	public function debugDumpParams();
}
