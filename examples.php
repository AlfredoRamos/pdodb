<?php
/**
 * Simple PDO Class
 * @package simple-pdo-class
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @link https://github.com/AlfredoRamos/simple-pdo-class
 * @copyright Copyright (c) 2014, Alfredo Ramos
 * @licence http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3
 *
 * This file is part of Simple PDO Class.
 *
 * Simple PDO Class is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Simple PDO Class is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Simple PDO Class.  If not, see <http://www.gnu.org/licenses/>.
 */

	require_once 'config.inc.php';
	require_once 'database.class.php';

	$db = new DataBase();

	// SELECT statement

	$db->query('SELECT id, column_1, column_2 FROM table_name WHERE column_3 = :value');
	$db->bind(':value', 'Something');
	$db->resultSet();


	// INSERT statement

	$db->query('INSERT INTO table_name (column_1, column_2) VALUES (:value_1, :value_2, :value3)');

	// Binding values manually
	$db->bind(':value_1', 'Value 1');
	$db->bind(':value_2', 'Value 2');
	$db->bind(':value_3', 'Value 3');

	// Binding calues with an array
	$param_array = array(
		':value_1'	=> 'Value 1',
		':value_2'	=> 'Value 2',
		':value_3'	=> 'Value 3',
	);
	$db->bindArray($param_array);

	$db->execute();

?>