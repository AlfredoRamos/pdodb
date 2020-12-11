### About

A simple PDO wrapper

[![Build Status](https://img.shields.io/github/workflow/status/AlfredoRamos/pdodb/GitHub%20Actions%20CI?style=flat-square)](https://github.com/AlfredoRamos/pdodb/actions)
[![Latest Stable Version](https://img.shields.io/github/tag/AlfredoRamos/pdodb.svg?style=flat-square&label=stable)](https://github.com/AlfredoRamos/pdodb/releases)
[![Code Quality](https://img.shields.io/codacy/grade/587b5f5a3b534cb8aec7c3a7cffe6707.svg?style=flat-square)](https://app.codacy.com/manual/AlfredoRamos/pdodb/dashboard)
[![License](https://img.shields.io/github/license/AlfredoRamos/pdodb.svg?style=flat-square)](https://raw.githubusercontent.com/AlfredoRamos/pdodb/master/LICENSE)

### PDO Drivers

Currently it has only been used and tested with:

- MySQL/MariaDB
- PostgreSQL

### Installation

Open your `composer.json` file and add this repository in your `repositories` array ([repositories#vcs](https://getcomposer.org/doc/05-repositories.md#vcs)):

```json
{
	"repositories": [
		{
			"type": "git",
			"url": "https://github.com/AlfredoRamos/pdodb.git"
		}
	]
}
```

Add the following line in the `require` object:

**Stable version**

```json
"alfredo-ramos/pdodb": "^2.2.0"
```

**Development version**

```json
"alfredo-ramos/pdodb": "dev-master"
```

Then run `composer install` or `composer update` on your terminal.

### Usage

The constructor takes an array with all the values needed to connect generate the DSN and connect to the database.

```php
require __DIR__ . '/vendor/autoload.php';

$db = new \AlfredoRamos\PDODb\PDODb([
	'driver'	=> 'mysql',
	'host'		=> 'localhost',
	'port'		=> 3306,
	'dbname'	=> 'database_name',
	'charset'	=> 'utf-8',
	'user'		=> 'database_user',
	'password'	=> 'database_user_password',
	'prefix'	=> '',
	'options'	=> [
		PDO::ATTR_EMULATE_PREPARES	=> false,
		PDO::ATTR_ERRMODE		=> PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_OBJ,
		PDO::ATTR_PERSISTENT		=> true
	]
]);
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
