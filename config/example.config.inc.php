<?php

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

return [
	// Default connection
	// Must exist in the connections array
	'connection'	=> 'mysql1',

	// Connection settings
	// Drivers:			https://php.net/manual/en/pdo.getavailabledrivers.php
	// Driver options:	https://php.net/manual/en/pdo.setattribute.php
	'connections'	=> [
		'mysql1'	=> [
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
				PDO::ATTR_PERSISTENT			=> true,
			]
		]
	]
];
