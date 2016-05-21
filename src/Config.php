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

	/**
	 * Set the configuration file
	 * @param	string	$file
	 * @return	bool|null
	 */
	public function setConfigFile($file = '') {
		// Exit if $file is empty
		if (empty($file)) {
			return false;
		}

		// Check if file exists
		if (!file_exists($file)) {
			$this->config = [];
			trigger_error(sprintf('The config file <code>%s</code> doesn\'t exist.', $file), E_USER_ERROR);
		}

		$this->config = require $file;
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
