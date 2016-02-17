### Simple PDO Class

Simple PDO Class with prepared statements.

### Configuration

Copy the file ```PDODb/example.config.inc.php``` to ```PDODb/config.inc.php``` and edit the values of this last one as needed.

### Usage

**Setup**
```php
require_once 'PDODb/autoload.php';

$db = AlfredoRamos\PDODb::instance();
```

**SELECT**

```php
// Multiple records
$sql = 'SELECT id, column_1, column_2
		FROM ' . $db->prefix . 'table_name
		WHERE column_3 = :value'
$db->query($sql);
$db->bind(':value', 'Something');
$result = $db->fetchAll();

// Single record
$sql = 'SELECT id, column_1, column_2
		FROM ' . $db->prefix . 'table_name
		WHERE id = :id';
$db->query($sql);
$db->bind(':id', 1);
$result = $db->fetch();

// Single field
$sql = 'SELECT column_3
		FROM ' . $db->prefix . 'table_name
		WHERE id = :id';
$db->query($sql);
$db->bind(':id', 3);
$result = $db->fetchField('column_3');
```

**INSERT**

```php
$sql = 'INSERT INTO ' . $db->prefix . 'table_name
		(column_1, column_2)
		VALUES (:value_1, :value_2)';
$db->query($sql);

// Binding values
$db->bind(':value_1', 'Value 1');
$db->bind(':value_2', 'Value 2');
$db->execute();

// Binding values with an array
$db->bindArray([
	':value_1'	=> 'Value 1',
	':value_2'	=> 'Value 2'
]);
$db->execute();
```

For a working MySQL example see the ```example``` directory.