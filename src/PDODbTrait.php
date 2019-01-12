<?php

/**
 * A simple PDO wrapper.
 *
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright 2013 Alfredo Ramos
 * @license GPL-3.0-or-later
 * @link https://github.com/AlfredoRamos/pdodb
 */

namespace AlfredoRamos\PDODb;

use PDO;
use PDOException;
use RuntimeException;

trait PDODbTrait {
	/** @var \PDO */
	private $dbh;

	/** @var \PDOStatement */
	private $stmt;

	/** @var array */
	private $fetchModes;

	/** @var string */
	public $prefix;

	/**
	 * Constructor.
	 *
	 * @param array $config
	 *
	 * @throws \PDOException
	 *
	 * @return void
	 */
	public function __construct($config = []) {
		// Default options
		$config = array_replace(
			[
				'driver'	=> '',
				'user'		=> '',
				'password'	=> '',
				'prefix'	=> '',
				'options'	=> [
					PDO::ATTR_EMULATE_PREPARES		=> false,
					PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_OBJ,
					PDO::ATTR_PERSISTENT			=> true
				]
			],
			$config
		);

		// DSN helper
		$config['dsn'] = [];

		// Configuration keys not needed in DSN
		$unneeded = [
			// Directly pased to the PDO constructor
			'user', 'password',
			// Will be used later
			'driver', 'options',
			// Table helper
			'prefix',
			// Will be generated later
			'dsn'
		];

		// Generate DNS
		foreach ($config as $key => $value) {
			if (in_array($key, $unneeded, true) || empty($value)) {
				continue;
			}

			$config['dsn'][$key] = $value;
		}

		// Sanitize DNS
		$this->sanitizeDsn($config['dsn']);

		// DSN string
		$config['dsn'] = vsprintf('%1$s:%2$s', [
			$config['driver'],
			http_build_query($config['dsn'], '', ';')
		]);

		// Table prefix
		$this->prefix = $config['prefix'];

		// Create a new PDO instanace
		try {
			$this->dbh = new PDO(
				$config['dsn'],
				$config['user'],
				$config['password'],
				$config['options']
			);
		} catch (PDOException $ex) {
			// Hide stack trace that contains the password
			throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}

		// Remove configuration
		unset($config);

		// Allowed fetch modes
		$this->fetchModes = [
			PDO::FETCH_ASSOC,
			PDO::FETCH_NUM,
			PDO::FETCH_OBJ,
			PDO::FETCH_NAMED
		];
	}

	/**
	 * Remove unknown DSN properties.
	 *
	 * @param array $dsn
	 *
	 * @return void
	 */
	private function sanitizeDsn(&$dsn = []) {
		if (empty($dsn)) {
			return;
		}

		$valid = [
			'host', 'port', 'dbname',
			'unix_socket', 'charset'
		];

		// Remove invalid DSN keys
		foreach ($dsn as $key => $value) {
			if (in_array($key, $valid, true)) {
				continue;
			}

			unset($dsn[$key]);
		}
	}
}
