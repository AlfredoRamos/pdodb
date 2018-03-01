<?php

/**
 * A simple PDO wrapper.
 *
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @copyright 2013 Alfredo Ramos
 * @license GPL-3.0+
 * @link https://github.com/AlfredoRamos/pdodb
 */

namespace AlfredoRamos\PDODb;

use PDO;
use PDOException;
use RuntimeException;

trait PDODbTrait {

	/** @var \PDO $dbh */
	private $dbh;

	/** @var \PDOStatement $stmt */
	private $stmt;

	/** @var array $fetchModes */
	private $fetchModes;

	/** @var string $prefix */
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
				'driver'	=> 'mysql',
				'host'		=> 'localhost',
				'port'		=> 3306,
				'database'	=> '',
				'charset'	=> 'utf8',
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

		// Default PDO options
		$config['dsn'] = vsprintf(
			'%1$s:host=%2$s;port=%3$u;charset=%4$s',
			[
				$config['driver'],
				$config['host'],
				$config['port'],
				$config['charset']
			]
		);

		// Add database to DSN if database name is not empty
		if (!empty($config['database'])) {
			$config['dsn'] = vsprintf(
				'%1$s;dbname=%2$s',
				[
					$config['dsn'],
					$config['database']
				]
			);
		}

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

}
