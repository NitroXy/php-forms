<?php
preg_match('/layout_([a-z]+).php/', basename($_SERVER['SCRIPT_FILENAME']), $match);
$layout = $match[1];
$examples = [
	'example_layout1.php' => 'Simple form',
	'example_layout2.php' => 'Fieldsets',
];

require '../vendor/autoload.php';
require 'utils.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>PHP-Forms - <?=ucfirst($layout)?> layout</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
		<style>
			.form.table { margin-bottom: 0; /* override bootstrap */ }
			.form.table textarea { width: 100%; }
			.example { border: 1px dashed #ccc; padding: 15px; border-radius: 4px; margin-bottom: 10px; }
			.panel-title a { display: block; }
		</style>
	</head>
	<body>
		<div class="container">
			<h1>PHP Forms &gt; <?=ucfirst($layout)?> layout</h1>

			<?php $n = 0; ?>
			<?php foreach ( $examples as $filename => $title ): $n++; ?>
				<h2><?=$title ?></h2>
				<div class="row">
					<div class="col-sm-6">
						<div class="example"><?php include($filename); ?></div>
					</div>
					<div class="col-sm-6">
						<div class="panel-group" role="tablist">
							<div class="panel panel-default">
								<div class="panel-heading" role="tab"><h4 class="panel-title"><a role="button" data-toggle="collapse" href="#example<?=$n?>-source">View form source</a></h4></div>
								<div id="example<?=$n?>-source" class="panel-collapse collapse" role="tabpanel"><div class="panel-body"><?php display($filename); ?></div></div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading" role="tab"><h4 class="panel-title"><a role="button" data-toggle="collapse" href="#example<?=$n?>-html">View output html</a></h4></div>
								<div id="example<?=$n?>-html" class="panel-collapse collapse" role="tabpanel"><div class="panel-body"><?php htmlify($filename); ?></div></div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	</body>
</html>
