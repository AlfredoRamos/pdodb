<?php namespace AlfredoRamos\Tests;

/**
 * PDODb - A simple PDO wrapper
 * @link https://github.com/AlfredoRamos/pdodb
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright (c) 2013 Alfredo Ramos
 * @license GNU GPL 3.0+ <https://www.gnu.org/licenses/gpl-3.0.txt>
 */

use \AlfredoRamos\PDODb\PDODb;

class BasicTest extends \PHPUnit_Framework_TestCase {
	public function testInstance() {
		$db = PDODb::instance();

		$this->assertInstanceOf(\AlfredoRamos\PDODb\PDODb::class, $db);
	}
}
