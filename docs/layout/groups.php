<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => '1', 'e' => '2', 'i' => '3'];
?>
<?php
Form::from_array('example5', $data, function($f){
	$f->group('Row 1', function($f){
		$f->text_field('a', 'Text 1', ['style' => 'width: 50px;']);
		$f->text_field('b', 'Text 2', ['style' => 'width: 50px;']);
		$f->text_field('c', 'Text 3', ['style' => 'width: 50px;']);
	});
	$f->group('Row 2', function($f){
		$f->text_field('d', 'Text 1', ['style' => 'width: 50px;']);
		$f->text_field('e', 'Text 2', ['style' => 'width: 50px;']);
		$f->text_field('f', 'Text 3', ['style' => 'width: 50px;']);
	});
	$f->group('Row 3', function($f){
		$f->text_field('g', 'Text 1', ['style' => 'width: 50px;']);
		$f->text_field('h', 'Text 2', ['style' => 'width: 50px;']);
		$f->text_field('i', 'Text 3', ['style' => 'width: 50px;']);
	});
	$f->group(false, function($f){
		$f->button('Action 1');
		$f->button('Action 2');
	});
}, ['layout' => $layout]);
