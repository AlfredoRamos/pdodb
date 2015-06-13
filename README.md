## Simple PDO Class
Simple PDO Class with prepared statements.

## Configuration
Rename the ```config.inc.example.php``` file to ```config.inc.php``` and edit the values as needed.

## Usage
```php
<?php
	require_once 'PDODb.class.php';

	$db = AlfredoRamos\PDODb::instance();

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
```

For a working example see "example" directory.