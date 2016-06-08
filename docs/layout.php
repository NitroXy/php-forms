<?php
preg_match('/layout_([a-z]+).php/', basename($_SERVER['SCRIPT_FILENAME']), $match);
$layout = $match[1];
$examples = [
	'Simple form' => 'example_layout1.php',
	'Hints' => 'layout/hints.php',
	'Fieldsets' => 'example_layout2.php',
	'Groups' => [
		'bootstrap' => 'layout/groups_bootstrap.php',
		'*'         => 'layout/groups.php',
	],
	'Checkboxes' => 'layout/checkboxes.php',
	'Addons' => 'layout/addons.php',
	'Layout hints' => [
		'table'     => 'layout/layout_hints.php',
		'*'         => false,
	],
	'Supported controls' => 'layout/smoke.php',
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
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="container">
			<h1>PHP Forms  <small><?=ucfirst($layout)?> layout</small></h1>
			<?php include('menu.php') ?>

			<?php $n = 0; ?>
			<?php foreach ( $examples as $title => $filename ): $n++; ?>
				<?php
				if ( is_array($filename) ){
					if ( array_key_exists($layout, $filename) ){
						$filename = $filename[$layout];
					} else {
						$filename = $filename['*'];
					}
				}
				if ( $filename === false ) continue;
				?>
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

		<?php include('badge.php'); ?>
		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<script src="autolink.js"></script>
	</body>
</html>
