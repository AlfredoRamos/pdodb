<?php

/**
 * A simple PDO wrapper
 * @link https://github.com/AlfredoRamos/pdodb
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright 2013 Alfredo Ramos
 * @license GNU GPL 3.0+
 */

namespace AlfredoRamos\PDODb\Interfaces;

interface PDODbInterface {
	public function query($query = '');
	public function bind($param = '', $value = '', $type = null);
	public function bindArray($param = []);
	public function execute();
	public function fetchAll($mode = null);
	public function fetch($mode = null);
	public function fetchField($name = '');
	public function rowCount();
	public function lastInsertId();
	public function beginTransaction();
	public function endTransaction();
	public function cancelTransaction();
	public function debugDumpParams();
}
