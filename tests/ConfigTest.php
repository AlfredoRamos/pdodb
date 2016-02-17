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

use \AlfredoRamos\PDODb\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase {
	protected $config;
	
	protected function setUp() {
		parent::setUp();
		
		$this->config = Config::instance();
	}
	
	public function testInstance() {
		$this->assertInstanceOf(\AlfredoRamos\PDODb\Config::class, $this->config);
	}
	
	public function testConfigValue() {
		$this->assertSame('mysql', $this->config->get('connections.mysql.driver'));
	}
	
	public function testInvalidConfigValue() {
		$this->assertNull($this->config->get('xyz.123'));
	}
	
	public function testInvalidConfigWithDefaultValue() {
		$this->assertSame(0.1, $this->config->get('xyz.123', 0.1));
	}
}