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
use PDOException;

/**
 * @group basic
 */
class BasicTest extends TestCase {

	/** @var \AlfredoRamos\PDODb\PDODb $pdodb */
	protected static $pdodb;

	/** @var string $table */
	protected static $table;

	/**
	 * @backupGlobals enabled
	 */
	public static function setUpBeforeClass() {
		// Database instance
		self::$pdodb = new PDODb([
			'user'		=> $GLOBALS['DB_USER'],
			'database'	=> $GLOBALS['DB_DBNAME'],
			'prefix'	=> $GLOBALS['DB_TPREFIX']
		]);

		// Table name
		self::$table = $GLOBALS['DB_TABLE'];

		// Drop test table
		$sql = 'DROP TABLE IF EXISTS ' . self::$pdodb->prefix . self::$table;
		self::$pdodb->query($sql);
		self::$pdodb->execute();

		// Create test table
		$sql = 'CREATE TABLE ' . self::$pdodb->prefix . self::$table . ' (
			id int(11) NOT NULL AUTO_INCREMENT,
			first_name varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			last_name varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			city varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
		self::$pdodb->query($sql);
		self::$pdodb->execute();

		// Insert initial data
		$users = [
			['Maria', 'Anders', 'Germany'],
			['Ana', 'Trujillo', 'Mexico'],
			['Thomas', 'Hardy', 'United Kingdom'],
			['Patricia', 'McKenna', 'Ireland'],
			['Frédérique', 'Citeaux', 'France']
		];
		$sql = 'INSERT INTO ' . self::$pdodb->prefix . self::$table . '
				(first_name, last_name, city)
				VALUES (:first_name, :last_name, :city)';
		self::$pdodb->query($sql);

		self::$pdodb->beginTransaction();
		foreach ($users as $user) {
			self::$pdodb->bindArray([
				':first_name'	=> $user[0],
				':last_name'	=> $user[1],
				':city'			=> $user[2]
			]);
			self::$pdodb->execute();
		}
		self::$pdodb->endTransaction();
	}

	public function testInstance() {
		$this->assertInstanceOf(PDODb::class, self::$pdodb);
	}

	public function testInvalidInstance() {
		$this->expectException(PDOException::class);
		$db = new PDODb;
	}

	public function testTableExists() {
		$sql = 'SHOW TABLES LIKE "' . self::$pdodb->prefix . self::$table . '"';
		self::$pdodb->query($sql);
		$this->assertFalse(empty(self::$pdodb->fetch()));
	}

	public function testTotalUsers() {
		$sql = 'SELECT COUNT(id) as total_users
			FROM ' . self::$pdodb->prefix . self::$table;
		self::$pdodb->query($sql);
		$this->assertEquals(5, self::$pdodb->fetchField('total_users'));
	}

	public function testInsertToTable() {
		$sql = 'INSERT INTO ' . self::$pdodb->prefix . self::$table . '
			(first_name, last_name, city)
			VALUES (:first_name, :last_name, :city)';
		self::$pdodb->query($sql);
		self::$pdodb->bindArray([
			':first_name'	=> 'Yang',
			':last_name'	=> 'Wang',
			':city'			=> 'Switzerland'
		]);
		self::$pdodb->execute();
		$this->assertEquals(6, self::$pdodb->lastInsertId());
	}

	public function testFetchColumn() {
		$sql = 'SELECT id, city
			FROM ' . self::$pdodb->prefix . self::$table . '
			WHERE last_name = :last_name';
		self::$pdodb->query($sql);
		self::$pdodb->bind(':last_name', 'Trujillo');
		$this->assertEquals(2, self::$pdodb->fetchColumn());
		$this->assertEquals('Mexico', self::$pdodb->fetchColumn(1));
	}

	public function testRowCount() {
		$sql = 'SELECT id FROM ' . self::$pdodb->prefix . self::$table;
		self::$pdodb->query($sql);
		self::$pdodb->execute();
		$this->assertEquals(6, self::$pdodb->rowCount());
	}

	public function testColumnCount() {
		$sql = 'SELECT * FROM ' . self::$pdodb->prefix . self::$table;
		self::$pdodb->query($sql);
		self::$pdodb->execute();
		$this->assertEquals(4, self::$pdodb->columnCount());
	}

}
