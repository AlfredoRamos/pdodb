<?php namespace AlfredoRamos;
/**
 * Simple PDO Class - PDODb Interface
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

interface PDODbInterface {
	public function query($query = '');
	public function bind($param = '', $value = '', $type = null);
	public function bindArray($param = []);
	public function execute();
	public function fetchAll($mode = null);
	public function fetch($mode = null);
	public function rowCount();
	public function lastInsertId();
	public function beginTransaction();
	public function endTransaction();
	public function cancelTransaction();
	public function debugDumpParams();
}