<?php
require '../vendor/autoload.php';
use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormSelect;

function display($filename){
	/* get source code but remove boilerplate */
	$code = file_get_contents($filename);
	$code = preg_replace('#<\?php( /\*~\*/.*\?>)?#ims', '', $code);
	$geshi = new GeSHi(trim($code), 'php');
	echo $geshi->parse_code();
}

function htmlify($filename){
	ob_start();
	include($filename);
	$html = ob_get_contents();
	ob_end_clean();
	$geshi = new GeSHi(trim($html), 'html5');
	$geshi->set_header_type(GESHI_HEADER_NONE);
	$geshi->enable_keyword_links(false);
	echo $geshi->parse_code();
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>PHP-Forms Examples</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
		<style>
			.form.table { margin-bottom: 0; /* override bootstrap */ }
			.form.table textarea { width: 100%; }
			.example { border: 1px dashed #ccc; padding: 15px; border-radius: 4px; margin-bottom: 10px; }
		</style>
	</head>
	<body>
		<div class="container">
			<h1>PHP Forms</h1>

			<h2>Table of contents</h2>
			<ol>
				<li><a href="#usage">Usage</a></li>
				<li><a href="#layout">Layout</a></li>
				<li><a href="#csrf">CSRF</a></li>
				<li><a href="#methods">Methods</a></li>
				<li><a href="#persistent">Persistent values</a></li>
				<li><a href="#controls">Supported controls</a></li>
				<li><a href="#options">Options</a></li>
				<li><a href="#nested">Nested data/"forms"</a></li>
			</ol>

			<h2 id="usage">Usage</h2>
			<p><code>composer require nitroxy/php-forms</code> or install manually.</p>

			<h3>Simple form</h3>
			<p>Forms can be created using three different constructors: <code>create</code>, <code>from_array</code> and <code>from_object</code>. Among the three <code>create</code> is the simplest and most manual, only taking an ID and a closure.</p>
			<?php display('example1.php'); ?>
			<div class="example">
				<?php include('example1.php'); ?>
			</div>
			<div class="example">
				<?php htmlify('example1.php'); ?>
			</div>

			<h2 id="layout">Layout</h2>
			<p>PHP-Forms supports a few default layout engines or you can create your own custom. The layout engines is used to render each field.</p>
			<?php display('example2.php'); ?>
			<h3>Tables (default)</h3>
			<p>The default layout is table-based and will put the labels and fields in columns. Quick-and-dirty way to get a form.</p>
			<div class="example">
				<?php $layout = 'table'; include('example2.php'); ?>
			</div>
			<h3>Plain</h3>
			<p>Plain layout is similar to tables but will instead use spans and divs.</p>
			<div class="example">
				<?php $layout = 'plain'; include('example2.php'); ?>
			</div>
			<h3>Bootstrap</h3>
			<p>Outputs fields with bootstrap classes.</p>
			<div class="example">
				<?php $layout = 'bootstrap'; include('example2.php'); ?>
			</div>
			<h3>Unbuffered</h3>
			<p>A special layout called <code>unbuffered</code> also exists which allow full freedoom when creating the form. All fields is outputted directly as is, no labels, hints etc. Useful when you want to create a very custom form but still wants features such as value persistance, CRSF-protection, etc.</p>

			<h2 id="csrf">CSRF</h2>
			<p>To enable CSRF protection you need to extend <code>Form</code>. This will ensure the token is present on all forms (using a special hidden called <code>csrf_token</code>). The developer must validate the token when POSTing the form.</p>
			<?php display('example3.php'); ?>
			<div class="example">
				<?php htmlify('example3.php'); ?>
			</div>

			<h2 id="methods">Methods</h2>
			<p>To support other methods than <tt>GET</tt> and <tt>POST</tt> <code>Form</code> inserts a hidden field <code>_method</code> and uses POST to submit.</p>
			<?php display('example_methods.php'); ?>
			<div class="example">
				<?php htmlify('example_methods.php'); ?>
			</div>
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
			<?php display('example4.php'); ?>
			<div class="example">
				<?php include('example4.php'); ?>
			</div>

			<h2 id="controls">Supported controls</h2>
			<p>The basic form of all fields is <code>$f->field($key, $label, $options);</code></p>
			<?php display('example5.php'); ?>
			<div class="example">
				<?php include('example5.php'); ?>
			</div>

			<h2 id="options">Options</h2>
			<h3>Form options</h3>
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
				<dt>Other</dt>
				<dd>Form also accepts <code>style</code>, <code>class</code> and <code>data</code> which is just passed directly to the form.</dd>
			</dl>
			<p>Default options for forms can be set by extending <code>default_options</code>:</p>
			<?php display('example_default_options.php'); ?>
			<h3>Fields</h3>
			<dl>
				<dt>hint</dt>
				<dd>Description of the field</dd>
				<dt>confirm</dt>
				<dd>Buttons: adds a javascript confirmation prompt before submit/click</dd>
				<dt>remove</dt>
				<dd>Upload: adds a removal checkbox (e.g. a user avatar which is set if file is uploaded but retained if nothing is sent and removed if checkbox is checked)</dd>
				<dt>Other</dt>
				<dd>All other attributes is passed directly to field, allowing custom attributes such as <code>placeholder</code>, <code>title</code>, etc.</dd>
			</dl>

			<h3>Serialization</h3>
			<p>Most attributes is serialized using these rules:</p>
			<ul>
				<li><code>['foo' => 'bar']</code> becomes <tt>foo="bar"</tt></li>
				<li><code>['foo' => ['a', 'b', 'c']]</code> becomes <tt>foo="a b c"</tt> (order preserved)</li>
				<li><code>['foo' => ['spam => 'ham']]</code> becomes <tt>foo-spam="ham"</tt> (deeper nesting is ok)</li>
			</ul>

			<h2 id="nested" >Nested data/"forms"</h2>
			<p>Forms can be nested by using <code>fields_for</code> to allow either sending to unrelated objects or multiple instances of the same class.</p>
			<?php display('example6.php'); ?>
			<div class="example">
				<?php include('example6.php'); ?>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	</body>
</html>
