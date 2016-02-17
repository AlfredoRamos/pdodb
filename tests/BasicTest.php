<?php namespace AlfredoRamos\Tests;
/**
 * Simple PDO Class
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @link https://github.com/AlfredoRamos/simple-pdo-class
 * @copyright Copyright (c) 2013 Alfredo Ramos
 * @licence GNU GPL-3.0+
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use \AlfredoRamos\PDODb\PDODb;

class BasicTest extends \PHPUnit_Framework_TestCase {
	public function testInstance() {
		$db = PDODb::instance();
		
		$this->assertInstanceOf(\AlfredoRamos\PDODb\PDODb::class, $db);
	}
}