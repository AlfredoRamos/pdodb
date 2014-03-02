<?php

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