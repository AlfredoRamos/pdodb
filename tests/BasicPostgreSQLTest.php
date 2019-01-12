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

/**
 * @group basic
 */
class BasicPostgreSQLTest extends AbstractTestCase {
	/**
	 * @backupGlobals enabled
	 */
	protected function setUp() {
		parent::setUp();
		$this->pdodb = new PDODb([
			'driver'	=> 'pgsql',
			'host'		=> 'localhost',
			'port'		=> 5432,
			'user'		=> 'postgres',
			'dbname'	=> $GLOBALS['DB_NAME'],
			'prefix'	=> $GLOBALS['DB_TPREFIX']
		]);
	}
}
