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
	'connection'	=> 'mysql',
	
	// Connection settings
	// Drivers:			https://php.net/manual/en/pdo.getavailabledrivers.php
	// Driver options:	https://php.net/manual/en/pdo.setattribute.php
	'connections'	=> [
		'mysql'	=> [
			'driver'	=> 'mysql',
			'host'		=> 'localhost',
			'port'		=> 3306,
			'database'	=> '',
			'user'		=> '',
			'password'	=> '',
			'prefix'	=> '',
			'options'	=> [
				PDO::ATTR_EMULATE_PREPARES		=> false,
				PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_OBJ,
				PDO::ATTR_PERSISTENT			=> true,
				PDO::MYSQL_ATTR_INIT_COMMAND	=> 'SET NAMES utf8'
			]
		],
		'sqlite'	=> [
			'driver'	=> 'sqlite',
			'database'	=> realpath('/path/to/database.sqlite'),
			'prefix'	=> '',
			'options'	=> [
				PDO::ATTR_EMULATE_PREPARES		=> false,
				PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_OBJ,
				PDO::ATTR_PERSISTENT			=> true,
				PDO::MYSQL_ATTR_INIT_COMMAND	=> 'SET NAMES utf8'
			]
		]
	]
];