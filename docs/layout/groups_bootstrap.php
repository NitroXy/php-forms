<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => '1', 'd' => '2', 'g' => '3'];
?>
<?php
Form::from_array('example5', $data, function($f){
	$f->group('Row 1', function($f){
		$f->text_field('a', 'Text 1');
		$f->text_field('b', 'Text 2');
		$f->text_field('c', 'Text 3');
	});
	$f->group('Row 2', function($f){
		$f->text_field('d', 'Text 1', ['class' => 'col-xs-8']);
		$f->text_field('e', 'Text 2', ['class' => 'col-xs-4']);
	});
	$f->group('Row 3', function($f){
		$f->text_field('f', 'Text 1', ['class' => 'col-xs-4']);
		$f->text_field('g', 'Text 2', ['class' => 'col-xs-8']);
	});
	$f->group(false, function($f){
		$f->button('Action 1');
		$f->button('Action 2');
	});
}, ['layout' => $layout]);
