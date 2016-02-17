<?php namespace AlfredoRamos\PDODb\Interfaces;

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
if (!defined('IN_PDODB')) {
	exit;
}

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