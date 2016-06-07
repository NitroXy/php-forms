<?php
require '../vendor/autoload.php';
require 'utils.php';
use NitroXy\PHPForms\Form;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>PHP-Forms - Options</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="container">
			<h1>PHP Forms <small>Options</small></h1>
			<?php include('menu.php') ?>

			<h2>Form options</h2>
			<dl>
				<dt>method</dt>
				<dd>Form method (default: post)</dd>
				<dt>method_field_name</dt>
				<dd>Name of the special method field when using methods other than <tt>GET</tt> and <tt>POST</tt></dd>
				<dt>action</dt>
				<dd>Form action (default: "")</dd>
				<dt>enctype</dt>
				<dd>Form enctype</dd>
				<dt>layout</dt>
				<dd>Form layout ("plain", "table", "bootstrap" or an instance of <code>FormLayout</code>)</dd>
				<dt>prefix</dt>
				<dd>Use custom prefix when generating names and ID</dd>
				<dt>attr</dt>
				<dd>Array with arbitrary attributes to pass directly to form.</dd>
				<dt>Other</dt>
				<dd>Form also accepts <code>style</code>, <code>class</code> and <code>data</code> which is just passed directly to the form.</dd>
			</dl>

			<h3>Default options</h3>
			<p>Default options for forms can be set by extending <code>default_options</code>:</p>
			<?php display('overview/options.php'); ?>

			<h2>Field options</h2>
			<dl>
				<dt>hint <span class="label label-default">string</span></dt>
				<dd>Description of the field. (default: none)</dd>
				<dt>Other</dt>
				<dd>All other attributes is passed directly to field, allowing custom attributes such as <code>placeholder</code>, <code>title</code>, etc.</dd>
			</dl>

			<h3 id="upload-field">Upload field</h3>
			<dl>
				<dt>remove <span class="label label-default">boolean</span></dt>
				<dd>Adds a removal checkbox (e.g. a user avatar which is set if file is uploaded but retained if nothing is sent and removed if checkbox is checked) (default: false)</dd>
				<dt>current <span class="label label-default">html</span></dt>
				<dd>HTML to preview the current uploaded data, e.g. <code>&lt;img src="/user/123/avatar.png"/&gt;</code>. (default: none)</dd>
			</dl>

			<h3 id="button">Button</h3>
			<dl>
				<dt>confirm <span class="label label-default">string</span></dt>
				<dd>Adds a javascript confirmation prompt before submit/click. (default: none)</dd>
			</dl>

			<h3 id="select">Select</h3>
			<dl>
				<dt>postback <span class="label label-default">boolean</span></dt>
				<dd>Automatically submit form when value changes. (default: false)</dd>
			</dl>

			<h2>Serialization</h2>
			<p>Most attributes is serialized using these rules:</p>
			<ul>
				<li><code>['foo' => 'bar']</code> becomes <code>foo="bar"</code></li>
				<li><code>['foo' => ['a', 'b', 'c']]</code> becomes <code>foo="a b c"</code> (order preserved)</li>
				<li><code>['foo' => ['spam => 'ham']]</code> becomes <code>foo-spam="ham"</code> (deeper nesting is ok)</li>
			</ul>
		</div>

		<?php include('badge.php'); ?>
		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<script src="autolink.js"></script>
	</body>
</html>
