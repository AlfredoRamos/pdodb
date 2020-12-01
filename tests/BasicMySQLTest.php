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

/**
 * @group basic
 */
class BasicMySQLTest extends AbstractTestCase {
	/**
	 * @backupGlobals enabled
	 */
	protected function setUp(): void {
		parent::setUp();
		$this->pdodb = new PDODb([
			'driver'	=> 'mysql',
			'host'		=> $GLOBALS['DB_HOST'],
			'port'		=> (int) $GLOBALS['MYSQL_PORT'],
			'user'		=> $GLOBALS['MYSQL_USER'],
			'dbname'	=> $GLOBALS['DB_NAME'],
			'charset'	=> 'utf8',
			'prefix'	=> $GLOBALS['DB_TPREFIX']
		]);
	}

	public function testTableExists() {
		$sql = 'SHOW TABLES LIKE "' . $this->pdodb->prefix . $this->table . '"';
		$this->pdodb->query($sql);
		$this->assertFalse(empty($this->pdodb->fetch()));
	}
}
