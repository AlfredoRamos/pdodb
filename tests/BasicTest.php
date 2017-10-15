<?php

/**
 * A simple PDO wrapper
 *
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright 2013 Alfredo Ramos
 * @license GPL-3.0+
 * @link https://github.com/AlfredoRamos/pdodb
 */

namespace AlfredoRamos\Tests;

use AlfredoRamos\PDODb\PDODb;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use PDOException;
use Error;

/**
 * @group basic
 */
class BasicTest extends TestCase {

	/** @var \AlfredoRamos\PDODb\PDODb $pdodb */
	protected $pdodb;

	/** @var string $table */
	protected $table;

	/**
	 * @backupGlobals enabled
	 */
	public static function setUpBeforeClass() {
		// Database instance
		$pdodb = new PDODb([
			'user'		=> $GLOBALS['DB_USER'],
			'prefix'	=> $GLOBALS['DB_TPREFIX']
		]);

		// Drop database
		$sql = 'DROP DATABASE IF EXISTS ' . $GLOBALS['DB_DBNAME'];
		$pdodb->query($sql);
		$pdodb->execute();

		// Create database
		$sql = 'CREATE DATABASE IF NOT EXISTS ' . $GLOBALS['DB_DBNAME'];
		$pdodb->query($sql);
		$pdodb->execute();

		// Drop test table
		$sql = 'DROP TABLE IF EXISTS ' . vsprintf('%1$s.%2$s%3$s', [
			$GLOBALS['DB_DBNAME'],
			$pdodb->prefix,
			$GLOBALS['DB_TABLE']
		]);
		$pdodb->query($sql);
		$pdodb->execute();

		// Create test table
		$sql = 'CREATE TABLE IF NOT EXISTS ' . vsprintf('%1$s.%2$s%3$s', [
			$GLOBALS['DB_DBNAME'],
			$pdodb->prefix,
			$GLOBALS['DB_TABLE']
		]) . ' (
			id int(11) NOT NULL AUTO_INCREMENT,
			first_name varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			last_name varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			city varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
		$pdodb->query($sql);
		$pdodb->execute();

		// Insert initial data
		$users = [
			['Maria', 'Anders', 'Germany'],
			['Ana', 'Trujillo', 'Mexico'],
			['Thomas', 'Hardy', 'United Kingdom'],
			['Patricia', 'McKenna', 'Ireland'],
			['Frédérique', 'Citeaux', 'France']
		];
		$sql = 'INSERT INTO ' . vsprintf('%1$s.%2$s%3$s', [
			$GLOBALS['DB_DBNAME'],
			$pdodb->prefix,
			$GLOBALS['DB_TABLE']
		]) . ' (first_name, last_name, city)
			VALUES (:first_name, :last_name, :city)';
		$pdodb->query($sql);

		$pdodb->beginTransaction();
		foreach ($users as $user) {
			$pdodb->bindArray([
				':first_name'	=> $user[0],
				':last_name'	=> $user[1],
				':city'			=> $user[2]
			]);
			$pdodb->execute();
		}
		$pdodb->endTransaction();

		$pdodb->close();
	}

	/**
	 * @backupGlobals enabled
	 */
	protected function setUp() {
		parent::setUp();

		$this->pdodb = new PDODb([
			'user'		=> $GLOBALS['DB_USER'],
			'database'	=> $GLOBALS['DB_DBNAME'],
			'prefix'	=> $GLOBALS['DB_TPREFIX']
		]);

		$this->table = $GLOBALS['DB_TABLE'];
	}

	protected function tearDown() {
		parent::tearDown();

		$this->pdodb->close();
	}

	public function testInstance() {
		$this->assertInstanceOf(PDODb::class, $this->pdodb);
	}

	public function testFailedConnection() {
		$this->expectException(RuntimeException::class);
		$pdodb = new PDODb;
		$pdodb->close();
	}

	public function testClosedConnection() {
		$this->expectException(Error::class);
		$this->expectExceptionMessage('Call to a member function prepare() on null');
		$pdodb = $this->pdodb;
		$pdodb->close();
		$sql = 'SELECT * FROM ' . $pdodb->prefix . $this->table;
		$pdodb->query($sql);
		$pdodb->execute();
		$this->assertEquals(0, $pdodb->columnCount());
	}

	public function testTableExists() {
		$sql = 'SHOW TABLES LIKE "' . $this->pdodb->prefix . $this->table . '"';
		$this->pdodb->query($sql);
		$this->assertFalse(empty($this->pdodb->fetch()));
	}

	public function testWrongTablePrefix() {
		$this->expectException(PDOException::class);
		$sql = 'SELECT * FROM ' . 'inv_' . $this->table;
		$this->pdodb->query($sql);
		$this->pdodb->execute();
		$this->assertEquals(0, $this->pdodb->columnCount());
	}

	public function testTotalUsers() {
		$sql = 'SELECT COUNT(id) as total_users
			FROM ' . $this->pdodb->prefix . $this->table;
		$this->pdodb->query($sql);
		$this->assertEquals(5, $this->pdodb->fetchField('total_users'));
	}

	public function testInsertToTable() {
		$sql = 'INSERT INTO ' . $this->pdodb->prefix . $this->table . '
			(first_name, last_name, city)
			VALUES (:first_name, :last_name, :city)';
		$this->pdodb->query($sql);
		$this->pdodb->bindArray([
			':first_name'	=> 'Yang',
			':last_name'	=> 'Wang',
			':city'			=> 'Switzerland'
		]);
		$this->pdodb->execute();
		$this->assertEquals(6, $this->pdodb->lastInsertId());
	}

	public function testFetchColumn() {
		$sql = 'SELECT id, city
			FROM ' . $this->pdodb->prefix . $this->table . '
			WHERE last_name = :last_name';
		$this->pdodb->query($sql);
		$this->pdodb->bind(':last_name', 'Trujillo');
		$this->assertEquals(2, $this->pdodb->fetchColumn());
		$this->assertEquals('Mexico', $this->pdodb->fetchColumn(1));
	}

	public function testRowCount() {
		$sql = 'SELECT id FROM ' . $this->pdodb->prefix . $this->table;
		$this->pdodb->query($sql);
		$this->pdodb->execute();
		$this->assertEquals(6, $this->pdodb->rowCount());
	}

	public function testColumnCount() {
		$sql = 'SELECT * FROM ' . $this->pdodb->prefix . $this->table;
		$this->pdodb->query($sql);
		$this->pdodb->execute();
		$this->assertEquals(4, $this->pdodb->columnCount());
	}

}
