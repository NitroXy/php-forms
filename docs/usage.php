<?php
require '../vendor/autoload.php';
require 'utils.php';

class Form extends NitroXy\PHPForms\Form {
	static protected function default_options(){
		return [
			'layout' => 'bootstrap',
		];
	}
};

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
		</div>

		<?php include('badge.php'); ?>
		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<script src="autolink.js"></script>
	</body>
</html>
