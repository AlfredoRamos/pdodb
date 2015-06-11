<?php
/**
 * Simple PDO Class - Example
 * @package simple-pdo-class
 * @author Alfredo Ramos <alfredo.ramos@yandex.com>
 * @link https://github.com/AlfredoRamos/simple-pdo-class
 * @copyright Copyright (c) 2014 Alfredo Ramos
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

require_once __DIR__ . '/Customer.php';

$customer = new Customer;
$customers = $customer->get_raw_data();

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Simple PDO Class Demo</title>
	</head>
	<body>
		<h3>Customers</h3>
		<table>
			<thead>
				<tr>
					<th>customer_id</th>
					<th>contact_name</th>
					<th>postal_address</th>
					<th>city</th>
					<th>country</th>
					<th>postal_code</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($customers as $c): ?>
				<tr>
					<td><?php echo $c->customer_id ?></td>
					<td><?php echo $c->contact_name ?></td>
					<td><?php echo $c->postal_address ?></td>
					<td><?php echo $c->city ?></td>
					<td><?php echo $c->country ?></td>
					<td><?php echo $c->postal_code ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<hr />
		<h3>Customers RAW</h3>
		<pre><code><?php var_dump($customers); ?></code></pre>
	</body>
</html>