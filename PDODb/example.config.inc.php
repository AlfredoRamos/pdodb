<?php
/**
 * Simple PDO Class - Configuration file example
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @link https://github.com/AlfredoRamos/simple-pdo-class
 * @copyright Copyright (c) 2013 Alfredo Ramos
 * @licence GNU GPL-3.0+
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
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
			'driver'		=> 'mysql',
			'host'			=> 'localhost',
			'port'			=> 3306,
			'database'		=> '',
			'user'			=> '',
			'password'		=> '',
			'table_prefix'	=> '',
			'options'		=> [
				PDO::ATTR_EMULATE_PREPARES		=> false,
				PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_OBJ,
				PDO::ATTR_PERSISTENT			=> true,
				PDO::MYSQL_ATTR_INIT_COMMAND	=> 'SET NAMES utf8'
			]
		]
	]
];