## Simple PDO Class
Simple PDO Class with prepared statements.

## Usage Example
```php
<?php
	require_once 'PDODb.class.php';

	$db = new AlfredoRamos\PDODb;

	// SELECT statement

	$db->query('SELECT id, column_1, column_2 FROM ' . $db->table_prefix . 'table_name WHERE column_3 = :value');
	$db->bind(':value', 'Something');
	$db->fetchAll();


	// INSERT statement

	$db->query('INSERT INTO ' . $db->table_prefix . 'table_name (column_1, column_2) VALUES (:value_1, :value_2, :value3)');

	// Binding values manually
	$db->bind(':value_1', 'Value 1');
	$db->bind(':value_2', 'Value 2');
	$db->bind(':value_3', 'Value 3');

	// Binding values with an array
	$param_array = [
		':value_1'	=> 'Value 1',
		':value_2'	=> 'Value 2',
		':value_3'	=> 'Value 3',
	];
	$db->bindArray($param_array);

	$db->execute();

?>
```

## Demo
See "example" directory for a working example.