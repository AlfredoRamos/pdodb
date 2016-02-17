<?php namespace AlfredoRamos;
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

/**
 * @ignore
 */
if (!defined('IN_PDODB')) {
	define('IN_PDODB', true);
}

use \PDO;
use \PDOException;

class Config implements ConfigInterface {
	
	use SingletonTrait;
	
	private $config;
	
	protected function init() {
		$this->setConfigFile(__DIR__ . '/config.inc.php');
	}
	
	/**
	 * Set the configuration file
	 * @param	string	$file
	 * @return	bool|null
	 */
	protected function setConfigFile($file = '') {
		// Exit if $file is empty
		if (empty($file)) {
			return false;
		}
		
		$this->config = $this->getConfig($file);
	}
	
	/**
	 * Read the configuration file
	 * @param	string	$config
	 * @return	bool|array|null
	 */
	protected function getConfig($file = '') {
		// Exit if $file is empty
		if (empty($file)) {
			return false;
		}
		
		// Default options
		$defaults = __DIR__ . '/example.config.inc.php';
		
		// Configuration array
		$config = [];
		
		if (file_exists($defaults)) {
			$defaults = require $defaults;
		} else {
			trigger_error('The file "' . $defaults . '" must exist to load default values.', E_USER_ERROR);
		}
		
		if (file_exists($file)) {
			// Load the array from file
			$config = require $file;
			
			// Check if it's an array
			$config = is_array($config) ? $config : [];
		}
		
		// Return the config array with the values
		// replaced with the ones from the file
		return array_replace_recursive($defaults, $config);
	}
	
	/**
	 * Get configuration value
	 * @param	string	$path
	 * @param	mixed	$default	Default value
	 * @return	mixed
	 */
	public function get($path = '', $default = null) {
		// Exit if $path is empty
		if (empty($path)) {
			return false;
		}
		
		$config = $this->config;
		$keys = explode('.', $path);
		
		foreach ($keys as $key) {
			$config = isset($config[$key]) ? $config[$key] : $default;
		}
		
		return $config;
	}
	
	/**
	 * Set configuration value
	 * @param	string	$path
	 * @param	mixed	$value
	 * @return	bool|null
	 */
	public function set($path = '', $value = null) {
		// Exit if $path is empty
		if (empty($path) || empty($value)) {
			return false;
		}
		
		$config = &$this->config;
		$keys = explode('.', $path);
		
		if (!is_array($config)) {
			return false;
		}
		
		while (count($keys) > 0) {
			if (count($keys) === 1) {
				if (is_array($config)) {
					$key = array_shift($keys);
					$config[$key] = $value;
				}
			} else {
				$key = array_shift($keys);
				
				if (!isset($config[$key])) {
					$config[$key] = [];
				}
				
				$config = &$config[$key];
			}
		}
	}
	
	/**
	 * Check if configuration exists
	 * @param	string	$path
	 * @return	bool
	 */
	public function has($path = '') {
		// Exit if $path is empty
		if (empty($path)) {
			return false;
		}
		
		$config = $this->config;
		$keys = explode('.', $path);
		
		foreach ($keys as $key) {
			$config = isset($config[$key]) ? $config[$key] : null;
		}
		
		return isset($config);
	}
	
}