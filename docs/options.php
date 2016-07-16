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

			<h2 id="form-options">Form options</h2>
			<dl class="doc-table">
				<dt><code>method</code> <span class="label type-string">string</span></dt>
				<dd>Form method (default: post)</dd>
				<dt><code>method_field_name</code> <span class="label type-string">string</span></dt>
				<dd>Name of the special method field when using methods other than <tt>GET</tt> and <tt>POST</tt></dd>
				<dt><code>action</code> <span class="label type-string">string</span></dt>
				<dd>Form action (default: "")</dd>
				<dt><code>layout</code> <span class="label type-string">string</span></dt>
				<dd>Form layout ("plain", "table", "bootstrap" or an instance of <code>FormLayout</code>)</dd>
				<dt><code>prefix</code> <span class="label label-default">mixed</span></dt>
				<dd>Use custom prefix when generating names and ID</dd>
				<dt>Other</dt>
				<dd>All other options are passed directly to the form as attributes. Use it to specify attributes such as <code>name</code>, <code>class</code>, etc.</dd>
			</dl>

			<h3>Default options</h3>
			<p>Default options for forms can be set by extending <code>defaultOptions</code>:</p>
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
