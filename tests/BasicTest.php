<?php

/**
 * A simple PDO wrapper
 * @link https://github.com/AlfredoRamos/pdodb
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright 2013 Alfredo Ramos
 * @license GNU GPL 3.0+
 */

namespace AlfredoRamos\Tests;

use AlfredoRamos\PDODb\PDODb;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase {

	protected $db;

	protected function setUp() {
		parent::setUp();

		$this->db = new PDODb([
			'user'		=> $GLOBALS['DB_USER'],
			'database'	=> $GLOBALS['DB_DBNAME'],
			'prefix'	=> $GLOBALS['DB_TPREFIX']
		]);
	}

	public function testInstance() {
		$this->assertInstanceOf(PDODb::class, $this->db);
	}

	public function testTableExists() {
		$sql = 'SHOW TABLES LIKE "' . $this->db->prefix . $GLOBALS['DB_TABLE'] . '"';
		$this->db->query($sql);
		$this->assertFalse(empty($this->db->fetch()));
	}

	public function testTotalUsers() {
		$sql = 'SELECT COUNT(id) as total_users
			FROM ' . $this->db->prefix . $GLOBALS['DB_TABLE'];
		$this->db->query($sql);
		$this->assertEquals(5, $this->db->fetchField('total_users'));
	}

	public function testInsertToTable() {
		$sql = 'INSERT INTO ' . $this->db->prefix . $GLOBALS['DB_TABLE'] . '
			(first_name, last_name, city)
			VALUES (:first_name, :last_name, :city)';
		$this->db->query($sql);
		$this->db->bindArray([
			':first_name'	=> 'Yang',
			':last_name'	=> 'Wang',
			':city'			=> 'Switzerland'
		]);
		$this->db->execute();
		$this->assertGreaterThan(5, $this->db->lastInsertId());
	}
}
