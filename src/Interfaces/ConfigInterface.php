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

interface ConfigInterface {
	public function get($path = '', $default = null);
	public function set($path = '', $value = '');
	public function has($path = '');
}