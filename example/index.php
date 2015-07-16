<?php
/**
 * Simple PDO Class - Example
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

require_once __DIR__ . '/Customer.php';

$customer = new Customer;

if (isset($_GET['a'])) {
	switch ($_GET['a']) {
		# delete
		case 'd':
			if (isset($_GET['ci'])) {
				$customer->delete_customer($_GET['ci']);
			}
			break;
		# restore
		case 'r':
			if (isset($_GET['ci'])) {
				$customer->restore_customer($_GET['ci']);
			}
			break;
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>PDODb Demo</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<style>#raw_data{max-height: 350px; overflow: auto;}</style>
	</head>
	<body>
		
		<div class="container-fluid">
			<div class="row">
				
				<div class="col-md-12">
					<h3>Customers</h3>
					<div class="table-responsive">
						<table class="table table-striped table-hover table-condensed">
							<thead>
								<tr>
									<th>customer_id</th>
									<th>contact_name</th>
									<th>postal_address</th>
									<th>city</th>
									<th>country</th>
									<th>postal_code</th>
									<th>actions</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($customer->get_customers() as $c): ?>
								<tr>
									<td><?php echo $c->customer_id ?></td>
									<td><?php echo $c->contact_name ?></td>
									<td><?php echo $c->postal_address ?></td>
									<td><?php echo $c->city ?></td>
									<td><?php echo $c->country ?></td>
									<td><?php echo $c->postal_code ?></td>
									<td>
										<div class="btn-group btn-group-xs">
											<a class="btn btn-danger" href="?a=d&ci=<?php echo $c->customer_id ?>">
												<span class="glyphicon glyphicon-remove"></span>
												delete
											</a>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
								<?php if (count($customer->get_customers()) == 0): ?>
								<tr>
									<td class="warning" colspan="7" style="text-align:center">n/a</td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="col-md-12">
					<h3>Soft-deleted customers</h3>
					<div class="table-responsive">
						<table class="table table-striped table-hover table-condensed">
							<thead>
								<tr>
									<th>customer_id</th>
									<th>contact_name</th>
									<th>postal_address</th>
									<th>city</th>
									<th>country</th>
									<th>postal_code</th>
									<th>actions</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($customer->get_deleted_customers() as $c): ?>
								<tr class="text-muted">
									<td><?php echo $c->customer_id ?></td>
									<td><?php echo $c->contact_name ?></td>
									<td><?php echo $c->postal_address ?></td>
									<td><?php echo $c->city ?></td>
									<td><?php echo $c->country ?></td>
									<td><?php echo $c->postal_code ?></td>
									<td>
										<div class="btn-group btn-group-xs">
											<a class="btn btn-success" href="?a=r&ci=<?php echo $c->customer_id ?>">
												<span class="glyphicon glyphicon-ok"></span>
												restore
											</a>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
								<?php if (count($customer->get_deleted_customers()) == 0): ?>
								<tr>
									<td class="warning" colspan="7" style="text-align:center">n/a</td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
				
			</div>
		</div>
		
		<div class="col-md-12">
			<h3>Raw data</h3>
			<pre id="raw_data"><code><?php var_dump($customer->get_raw_data()); ?></code></pre>
		</div>
		

	</body>
</html>