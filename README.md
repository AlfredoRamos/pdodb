### Simple PDO Class

Simple PDO Class with prepared statements.

### Installation via composer

Open your ```composer.json``` file and add the following line in the ```require``` object:

**Stable version**

```json
"alfredo-ramos/pdodb": "~1.5"
```

**Development version**

```json
"alfredo-ramos/pdodb": "~1.6@dev"
```

Then run ```composer install``` or ```composer update``` on your terminal.

### Configuration

Copy the file ```src/config/example.config.inc.php``` to ```src/config/config.inc.php``` and edit the values of this last one as needed.

### Usage

**Autoloading**

```php
require __DIR__ . '/vendor/autoload.php';

$db = \AlfredoRamos\PDODb\PDODb::instance();
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