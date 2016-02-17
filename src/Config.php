<?php namespace AlfredoRamos\PDODb;

/**
 * PDODb - A simple PDO wrapper
 * @link https://github.com/AlfredoRamos/pdodb
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright (c) 2013 Alfredo Ramos
 * @license GNU GPL 3.0+ <https://www.gnu.org/licenses/gpl-3.0.txt>
 */

/**
 * @ignore
 */
if (!defined('IN_PDODB')) {
	exit;
}

use \AlfredoRamos\PDODb\Interfaces\ConfigInterface;

class Config implements ConfigInterface {
	
	use \AlfredoRamos\PDODb\Traits\SingletonTrait;
	
	private $config;
	
	protected function init() {
		$this->setConfigFile(__DIR__ . '/../config/config.inc.php');
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
		
		$this->config = $this->setupConfigFile($file);
	}
	
	/**
	 * Read the configuration file
	 * @param	string	$config
	 * @return	bool|array|null
	 */
	protected function setupConfigFile($file = '') {
		// Exit if $file is empty
		if (empty($file)) {
			return false;
		}
		
		// Default options
		$defaults = __DIR__ . '/../config/example.config.inc.php';
		
		// Configuration array
		$config = [];
		
		if (file_exists($defaults)) {
			$defaults = require $defaults;
		} else {
			trigger_error('The file <code>' . $defaults . '</code> must exist to load default values.', E_USER_ERROR);
		}
		
		if (file_exists($file)) {
			// Load the array from file
			$config = require $file;
			
			// Check if it's an array
			$config = is_array($config) ? $config : [];
		} else {
			trigger_error('The file <code>' . $file . '</code> doesn\'t exist.', E_USER_ERROR);
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