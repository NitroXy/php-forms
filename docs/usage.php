<?php
require '../vendor/autoload.php';
require 'utils.php';
require 'docparser.php';

class Form extends NitroXy\PHPForms\Form {
	static protected function default_options(){
		return [
			'layout' => 'bootstrap',
		];
	}
};

$reflection_form = new ReflectionClass('NitroXy\PHPForms\FormContainer');

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

			<h2 id="generic">Generic usage</h2>
			<p>The general prototype is:</p>
			<?=code('xyz_field($key, $label=null, array $attr=[])', 'php')?>

			<ul class="param">
				<li><code>$key</code> is the name of the data inside the resource object, i.e. array key or member name. If the key isn't found in the resource it is assumed to be <code>''</code>.</li>
				<li><code>$label</code> is the textual label describing the field. Setting to <code>false</code> disables the label. Both <code>null</code> and <code>''</code> are legal values which retains the space for the label but blank.</li>
				<li><code>$attr</code> is an array with optional attributes. Most of these are serialized and passed as-is to the input-field (e.g. passing <code>['data-foo' => 'bar']</code> results in <code>&lt;input&nbsp;data-foo="bar"&gt;</code>. Some fields have special options (see table below) which are consumed and not serialized.</li>
			</ul>

			<h2 id="fields">Fields</h2>
			<table class="table table-striped doc-table">
				<thead>
					<tr>
						<th>Field</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $reflection_form->getMethods(ReflectionMethod::IS_PUBLIC) as $method ): ?>
						<?php if ( $method->name === '__construct' ) continue; ?>
						<tr>
							<td><?=code(prototype($method), 'php')?></td>
<td>
<?=phpdoc($method)?>
</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<h2 id="array">Array binding</h2>
			<div class="row">
				<div class="col-sm-6"><?php display('usage/array.php'); ?></div>
				<div class="col-sm-6"><?php include('usage/array.php'); ?></div>
			</div>

			<h2 id="object">Object binding</h2>
			<div class="row">
				<div class="col-sm-6"><?php display('usage/object.php'); ?></div>
				<div class="col-sm-6"><?php include('usage/array.php'); ?></div>
			</div>

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
