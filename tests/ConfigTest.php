<?php namespace AlfredoRamos\Tests;

/**
 * PDODb - A simple PDO wrapper
 * @link https://github.com/AlfredoRamos/pdodb
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright (c) 2013 Alfredo Ramos
 * @license GNU GPL 3.0+ <https://www.gnu.org/licenses/gpl-3.0.txt>
 */

use \AlfredoRamos\PDODb\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase {
	protected $config;

	protected function setUp() {
		parent::setUp();

		$this->config = Config::instance();
		$this->config->setConfigFile(__DIR__ . '/../config/example.config.inc.php');
	}

	public function testInstance() {
		$this->assertInstanceOf(\AlfredoRamos\PDODb\Config::class, $this->config);
	}

	public function testConfigValue() {
		$this->assertSame('mysql', $this->config->get('connections.mysql1.driver'));
	}

	public function testInvalidConfigValue() {
		$this->assertNull($this->config->get('xyz.123'));
	}

	public function testInvalidConfigWithDefaultValue() {
		$this->assertSame(0.1, $this->config->get('xyz.123', 0.1));
	}
}
