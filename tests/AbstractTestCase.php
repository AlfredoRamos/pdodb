<?php

/**
 * A simple PDO wrapper.
 *
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright 2013 Alfredo Ramos
 * @license GPL-3.0-or-later
 * @link https://github.com/AlfredoRamos/pdodb
 */

namespace AlfredoRamos\Tests;

use AlfredoRamos\PDODb\PDODb;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use PDOException;
use Error;

abstract class AbstractTestCase extends TestCase {
	/** @var \AlfredoRamos\PDODb\PDODb */
	protected $pdodb;

	/** @var string */
	protected $table;

	protected function setUp() {
		parent::setUp();
		$this->table = $GLOBALS['DB_TABLE'];
	}

	public function testInstance() {
		$this->assertInstanceOf(PDODb::class, $this->pdodb);
	}

	public function testInsertInitialData() {
		// Insert initial data
		$users = [
			['Maria', 'Anders', 'Germany'],
			['Ana', 'Trujillo', 'Mexico'],
			['Thomas', 'Hardy', 'United Kingdom'],
			['Patricia', 'McKenna', 'Ireland'],
			['Frédérique', 'Citeaux', 'France']
		];
		$sql = 'INSERT INTO ' . $this->pdodb->prefix . $this->table . '
			(first_name, last_name, city)
			VALUES (:first_name, :last_name, :city)';
		$this->pdodb->query($sql);

		$this->pdodb->beginTransaction();
		foreach ($users as $user) {
			$this->pdodb->bindArray([
				':first_name'	=> $user[0],
				':last_name'	=> $user[1],
				':city'			=> $user[2]
			]);
			$this->pdodb->execute();
		}
		$this->assertTrue($this->pdodb->endTransaction());
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
		$this->assertSame(0, $pdodb->columnCount());
	}

	public function testWrongTablePrefix() {
		$this->expectException(PDOException::class);
		$sql = 'SELECT * FROM ' . 'inv_' . $this->table;
		$this->pdodb->query($sql);
		$this->pdodb->execute();
		$this->assertSame(0, $this->pdodb->columnCount());
	}

	public function testTotalUsers() {
		$sql = 'SELECT COUNT(id) as total_users
			FROM ' . $this->pdodb->prefix . $this->table;
		$this->pdodb->query($sql);
		$this->assertSame(5, $this->pdodb->fetchField('total_users'));
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
		$this->assertSame('6', $this->pdodb->lastInsertId());
	}

	public function testFetchColumn() {
		$sql = 'SELECT id, city
			FROM ' . $this->pdodb->prefix . $this->table . '
			WHERE last_name = :last_name';
		$this->pdodb->query($sql);
		$this->pdodb->bind(':last_name', 'Trujillo');
		$this->assertSame(2, $this->pdodb->fetchColumn());
		$this->assertSame('Mexico', $this->pdodb->fetchColumn(1));
	}

	public function testRowCount() {
		$sql = 'SELECT id FROM ' . $this->pdodb->prefix . $this->table;
		$this->pdodb->query($sql);
		$this->pdodb->execute();
		$this->assertSame(6, $this->pdodb->rowCount());
	}

	public function testColumnCount() {
		$sql = 'SELECT * FROM ' . $this->pdodb->prefix . $this->table;
		$this->pdodb->query($sql);
		$this->pdodb->execute();
		$this->assertSame(4, $this->pdodb->columnCount());
	}
}
