<?php
require '../vendor/autoload.php';
require 'utils.php';
use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormSelect;

?>
<!DOCTYPE html>
<html>
	<head>
		<title>PHP-Forms Examples</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="container">
			<h1>PHP Forms</h1>
			<?php include('menu.php') ?>

			<h2>Table of contents</h2>
			<ol>
				<li><a href="#usage">Usage</a></li>
				<li><a href="#layout">Layout</a></li>
				<li><a href="#csrf">CSRF</a></li>
				<li><a href="#methods">HTTP Methods (REST verbs)</a></li>
				<li><a href="#persistent">Persistent values</a></li>
				<li><a href="#controls">Supported controls</a></li>
				<li><a href="#nested">Nested data/"forms"</a></li>
			</ol>

			<h2 id="usage">Usage</h2>
			<p><code>composer require nitroxy/php-forms</code> or install manually.</p>

			<h3>Simple form</h3>
			<p>Forms can be created using three different constructors: <code>create</code>, <code>from_array</code> and <code>from_object</code>. Among the three <code>create</code> is the simplest and most manual, only taking an ID and a closure.</p>
			<?php display('overview/simple_form.php'); ?>
			<div class="example">
				<?php include('overview/simple_form.php'); ?>
			</div>
			<?php htmlify('overview/simple_form.php'); ?>

			<h2 id="layout">Layout</h2>
			<p>PHP-Forms supports a few default layout engines or you can create your own custom. The layout engines is used to render each field.</p>
			<?php display('overview/layouts.php'); ?>
			<h3>Tables (default)</h3>
			<p>The default layout is table-based and will put the labels and fields in columns. Quick-and-dirty way to get a form.</p>
			<div class="example">
				<?php $layout = 'table'; include('overview/layouts.php'); ?>
			</div>
			<h3>Plain</h3>
			<p>Plain layout is similar to tables but will instead use spans and divs.</p>
			<div class="example">
				<?php $layout = 'plain'; include('overview/layouts.php'); ?>
			</div>
			<h3>Bootstrap</h3>
			<p>Outputs fields with bootstrap classes.</p>
			<div class="example">
				<?php $layout = 'bootstrap'; include('overview/layouts.php'); ?>
			</div>
			<h3>Unbuffered</h3>
			<p>A special layout called <code>unbuffered</code> also exists which allow full freedoom when creating the form. All fields is outputted directly as is, no labels, hints etc. Useful when you want to create a very custom form but still wants features such as value persistance, CRSF-protection, etc.</p>

			<h2 id="csrf">CSRF</h2>
			<p>To enable CSRF protection you need to extend <code>Form</code>. This will ensure the token is present on all forms (using a special hidden called <code>csrf_token</code>). The developer must validate the token when POSTing the form.</p>
			<?php display('overview/csrf.php'); ?>
			<?php htmlify('overview/csrf.php'); ?>

			<h2 id="methods">HTTP Methods (REST verbs)</h2>
			<p>To support other methods than <tt>GET</tt> and <tt>POST</tt> <code>Form</code> inserts a hidden field <code>_method</code> and uses POST to submit.</p>
			<?php display('overview/methods.php'); ?>
			<?php htmlify('overview/methods.php'); ?>
			<table class="table table-striped table-condensed" style="width: auto;">
				<thead>
					<tr><th>Method</th><th>Submitted as</th><th>_method</th></tr>
				</thead>
				<tbody>
					<tr><td>GET</td><td>GET</td><td>unset</td></tr>
					<tr><td>POST</td><td>POST</td><td>unset</td></tr>
					<tr><td>PATCH</td><td>POST</td><td>PATCH</td></tr>
					<tr><td>DELETE</td><td>POST</td><td>DELETE</td></tr>
				</tbody>
				<tfoot>
					<tr><td colspan="4">... and so on.</td></tr>
				</tfoot>
			</table>

			<h2 id="persistent">Persistent values</h2>
			<p>By utilizing either <code>from_array</code> or <code>from_object</code> values can be stored and automatically filled into the form.</p>
			<div class="alert alert-info" role="alert"><strong>Note:</strong> in addition to reading the values it will change the way ID and name is set.</div>
			<p>Objects will use the classname by default (e.g. MyCustomClass in the example) and <code>from_array</code> has an argument for ID. In addition there is also an form option <code>prefix</code> which can be used to customize it. Field names are set as <code>prefix[fieldname]</code>. The major reason is to read the entire object at once by accessing <code>$_POST['prefix']</code>.</p>
			<p>If you are using <strong>BasicObject</strong> you can use <code>$model = MyModel::update_attributes($_POST['MyCustomClass'], ['permit' => ['name', 'age', role', description']);</code> to update the model.</p>
			<?php display('overview/objects.php'); ?>
			<div class="example">
				<?php include('overview/objects.php'); ?>
			</div>

			<h2 id="controls">Supported controls</h2>
			<p>The basic form of all fields is <code>$f->field($key, $label, $options);</code></p>
			<?php display('overview/controls.php'); ?>
			<div class="example">
				<?php include('overview/controls.php'); ?>
			</div>

			<h2 id="nested" >Nested data/"forms"</h2>
			<p>Forms can be nested by using <code>fields_for</code> to allow either sending to unrelated objects or multiple instances of the same class.</p>
			<?php display('overview/nested.php'); ?>
			<div class="example">
				<?php include('overview/nested.php'); ?>
			</div>
		</div>

		<?php include('badge.php'); ?>
		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	</body>
</html>
