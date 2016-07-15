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

$reflection_form = new ReflectionClass('NitroXy\PHPForms\FormBuilder');

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

			<h2 id="unbound">Simple form (unbound)</h2>
			<?=code('Form::create(string $id, callable $callback, [array $options])', 'php')?>
			<dl class="doc-table">
				<dt><code>$id</code>  <span class="label type-string">string</span></dt>
				<dd>Form id (prefix).</dd>
				<dt><code>$callback</code>  <span class="label type-callable">callable</span></dt>
				<dd>Build callback.</dd>
				<dt><code>$options</code>  <span class="label type-array">array</span></dt>
				<dd>Optional options and form attributes, see <a href="options.php#form-options">Form Options</a></dd>
			</dl>
			<h3>Example</h3>
			<div class="row">
				<div class="col-sm-6"><?php display('usage/unbound.php'); ?></div>
				<div class="col-sm-6"><?php include('usage/unbound.php'); ?></div>
			</div>

			<h2 id="array">Array binding</h2>
			<?=code('Form::from_array(string $id, array $array, callable $callback, [array $options])', 'php')?>
			<dl class="doc-table">
				<dt><code>$id</code>  <span class="label type-string">string</span></dt>
				<dd>Form id (prefix).</dd>
				<dt><code>$array</code>  <span class="label type-array">array</span></dt>
				<dd>Array to bind data from.</dd>
				<dt><code>$callback</code>  <span class="label type-callable">callable</span></dt>
				<dd>Build callback.</dd>
				<dt><code>$options</code>  <span class="label type-array">array</span></dt>
				<dd>Optional options and form attributes, see <a href="options.php#form-options">Form Options</a></dd>
			</dl>
			<h3>Example</h3>
			<div class="row">
				<div class="col-sm-6"><?php display('usage/array.php'); ?></div>
				<div class="col-sm-6"><?php include('usage/array.php'); ?></div>
			</div>

			<h2 id="object">Object binding</h2>
			<?=code('Form::from_object(object $obj, callable $callback, [array $options])', 'php')?>
			<dl class="doc-table">
				<dt><code>$object</code>  <span class="label type-object">object</span></dt>
				<dd>Object to bind data from.</dd>
				<dt><code>$callback</code>  <span class="label type-callable">callable</span></dt>
				<dd>Build callback.</dd>
				<dt><code>$options</code>  <span class="label type-array">array</span></dt>
				<dd>Optional options and form attributes, see <a href="options.php#form-options">Form Options</a></dd>
			</dl>
			<h3>Example</h3>
			<div class="row">
				<div class="col-sm-6"><?php display('usage/object.php'); ?></div>
				<div class="col-sm-6"><?php include('usage/array.php'); ?></div>
			</div>

			<h2 id="generic">Fields</h2>
			<p>The general prototype is:</p>
			<?=code('xyz_field($key, $label=null, array $attr=[])', 'php')?>

			<ul class="param">
				<li><code>$key</code> is the name of the data inside the resource object, i.e. array key or member name. If the key isn't found in the resource it is assumed to be <code>''</code>.</li>
				<li><code>$label</code> is the textual label describing the field. Setting to <code>false</code> disables the label. Both <code>null</code> and <code>''</code> are legal values which retains the space for the label but blank.</li>
				<li><code>$attr</code> is an array with optional attributes. Most of these are serialized and passed as-is to the input-field (e.g. passing <code>['data-foo' => 'bar']</code> results in <code>&lt;input&nbsp;data-foo="bar"&gt;</code>. Some fields have special options (see table below) which are consumed and not serialized.</li>
			</ul>

			<h2 id="fields">Available fields</h2>
			<table class="table table-striped doc-table">
				<thead>
					<tr>
						<th>Field</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $reflection_form->getMethods(ReflectionMethod::IS_PUBLIC) as $method ): ?>
						<?php if ( methodIgnored($method) ) continue; ?>
						<tr>
							<td><?=code(prototype($method), 'php')?></td>
<td>
<?=phpdoc($method)?>
</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<h2>Extending fields</h2>
			<p>Custom fields or customization of existing fields is possible
				by extending <code>FormBuilder</code>.</p>
				<div class="col-sm-6"><?php display('usage/extend.php'); ?></div>
				<div class="col-sm-6"><?php include('usage/extend.php'); ?></div>
		</div>

		<?php include('badge.php'); ?>
		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<script src="autolink.js"></script>
	</body>
</html>
