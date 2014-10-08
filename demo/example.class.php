<?php
/**
 * Simple PDO Class - Demo Class
 * @package simple-pdo-class
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @link https://github.com/AlfredoRamos/simple-pdo-class
 * @copyright Copyright (c) 2014, Alfredo Ramos
 * @licence http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3
 *
 * This file is part of Simple PDO Class.
 *
 * Simple PDO Class is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Simple PDO Class is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Simple PDO Class.  If not, see <http://www.gnu.org/licenses/>.
 */

	require_once '../config.inc.php';
	require_once '../Database.class.php';

class Demo {
	
	private $db;
	
	public function __construct() {
		
		$this->db = new Database;
		
		if (!$this->table_exists()) {
			$this->create_table();
			if (!$this->initial_data_exist()) {
				$this->set_initial_data();
			}
		}
		
	}
	
	public function table_exists() {
		
		$sql = 'SHOW TABLES LIKE "pdo_customers"';
		$this->db->query($sql);
		$this->db->fetch();
		$row = $this->db->rowCount();
		
		$exists = ($row > 0) ? true : false;
		
		return $exists;
		
	}
	
	public function create_table() {
		
		$sql = 'CREATE TABLE IF NOT EXISTS pdo_customers (
					customer_id int(11) NOT NULL AUTO_INCREMENT,
					contact_name varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					postal_address text COLLATE utf8_unicode_ci NOT NULL,
					city varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					country varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					postal_code varchar(15) COLLATE utf8_unicode_ci NOT NULL,
					PRIMARY KEY (customer_id)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
		$this->db->query($sql);
		$this->db->execute();
		
	}
	
	public function initial_data_exist() {
		
		// Just for testing
		$sql = 'SELECT COUNT(customer_id) AS total_rows
				FROM pdo_customers';
		$this->db->query($sql);
		$row = $this->db->fetch();
		$exist = ($row->total_rows >= 5) ? true : false;
		
		return $exist;
		
	}
	
	public function set_initial_data() {
		
		// Start transaction
		$this->db->beginTransaction();
		
		$sql = 'INSERT INTO pdo_customers (
					contact_name,
					postal_address,
					city,
					country,
					postal_code
				) VALUES (
					:contact_name,
					:postal_address,
					:city,
					:country,
					:postal_code
				)';
		$this->db->query($sql);
		
		$this->db->bindArray(array(
			':contact_name'		=> 'Thomas Hardy',
			':postal_address'	=> '120 Hanover Sq.',
			':city'				=> 'London',
			':country'			=> 'United Kingdom',
			':postal_code'		=> 'WA1 1DP'
		));
		$this->db->execute();
		
		$this->db->bindArray(array(
			':contact_name'		=> 'Christina Berglund',
			':postal_address'	=> 'Berguvsvägen 8',
			':city'				=> 'Luleå',
			':country'			=> 'Sweden',
			':postal_code'		=> 'S-958 22'
		));
		$this->db->execute();
		
		$this->db->bindArray(array(
			':contact_name'		=> 'Ana Ramos',
			':postal_address'	=> 'Avda. de la Constitución 2222',
			':city'				=> 'México D.F.',
			':country'			=> 'Mexico',
			':postal_code'		=> '05021'
		));	
		$this->db->execute();
		
		$this->db->bindArray(array(
			':contact_name'		=> 'Howard Snyder',
			':postal_address'	=> '2732 Baker Blvd.',
			':city'				=> 'Eugene',
			':country'			=> 'United States of America',
			':postal_code'		=> '97403'
		));
		$this->db->execute();
		
		$this->db->bindArray(array(
			':contact_name'		=> 'Renate Messner',
			':postal_address'	=> 'Magazinweg 7',
			':city'				=> 'Frankfurt a.M.',
			':country'			=> 'Germany',
			':postal_code'		=> '60528'
		));
		$this->db->execute();
		
		$last_insert_id = (int) $this->db->lastInsertId();
		
		// End transaction
		$this->db->endTransaction();

	}
	
	public function get_raw_data() {
		$sql = 'SELECT customer_id, contact_name, postal_address, city, country, postal_code
				FROM pdo_customers';
		$this->db->query($sql);
		return $this->db->fetchAll();
	}
}