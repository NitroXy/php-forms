<?php
require '../vendor/autoload.php';
require 'utils.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>PHP-Forms - Usage</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="container">
			<h1>PHP Forms <small>Usage</small></h1>
			<?php include('menu.php') ?>

			<h2 id="styling">Styling</h2>
			<table class="table table-striped">
				<thead>
					<th>Selector</th>
					<th>Description</th>
					<th>Sample style</th>
				</thead>
				<tbody>
					<tr>
						<td><code>.form-checkbox</code></td>
						<td>Class added to all checkbox labels (wrapping the input field).</td>
						<td><?php code(<<<EOT
/* add some margin to checkboxes (useful in groups) */
.form-checkbox {
	margin-right: 15px;
}
EOT
, 'css'); ?></td>
					</tr>
					<tr>
						<td><code>.form-group</code></td>
						<td>Class added to row wrapper for for groups.</td>
						<td></td>
					</tr>
					<tr>
						<td><code>.form-addon</code></td>
						<td>Class wrapping addons and field when addons are used.</td>
						<td><?php code(<<<EOT
/* suggested structural style */
.form-addon {
	display: inline-table;
}
EOT
, 'css'); ?></td>
					</tr>
					<tr>
						<td><code>.form-prefix, .form-suffix</code></td>
						<td>Class added to addons.</td>
						<td><?php code(<<<EOT
/* suggested structural style */
.form-prefix, .form-suffix {
	display: table-cell;
	width: auto;
}
EOT
, 'css'); ?></td>
					</tr>
					<tr>
						<td><code>.form-group</code></td>
						<td>Class added to row wrapper for for groups.</td>
						<td></td>
					</tr>
					<tr>
						<td><code>.required</code></td>
						<td>Class added to row wrapper for all required fields.</td>
						<td><?php code(<<<EOT
/* adds a red asterisk after the label */
.required label:after {
	content: '*';
	color: #a00;
	margin-left: 5px;
}
EOT
, 'css'); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<?php include('badge.php'); ?>
		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<script src="autolink.js"></script>
	</body>
</html>
