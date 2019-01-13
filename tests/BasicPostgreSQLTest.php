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
class BasicPostgreSQLTest extends AbstractTestCase {
	/**
	 * @backupGlobals enabled
	 */
	protected function setUp() {
		parent::setUp();
		$this->pdodb = new PDODb([
			'driver'	=> 'pgsql',
			'host'		=> $GLOBALS['DB_HOST'],
			'port'		=> (int) $GLOBALS['PGSQL_PORT'],
			'user'		=> $GLOBALS['PGSQL_USER'],
			'dbname'	=> $GLOBALS['DB_NAME'],
			'prefix'	=> $GLOBALS['DB_TPREFIX']
		]);
	}
}
