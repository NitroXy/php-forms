<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => '1', 'e' => '2', 'i' => '3'];
?>
<?php
Form::fromArray('example5', $data, function($f){
	$f->group('Row 1', function($f){
		$f->textField('a', 'Text 1', ['style' => 'width: 50px;']);
		$f->textField('b', 'Text 2', ['style' => 'width: 50px;']);
		$f->textField('c', 'Text 3', ['style' => 'width: 50px;']);
	});
	$f->group('Row 2', function($f){
		$f->textField('d', 'Text 1', ['style' => 'width: 50px;']);
		$f->textField('e', 'Text 2', ['style' => 'width: 50px;']);
		$f->textField('f', 'Text 3', ['style' => 'width: 50px;']);
	});
	$f->group('Row 3', function($f){
		$f->textField('g', 'Text 1', ['style' => 'width: 50px;']);
		$f->textField('h', 'Text 2', ['style' => 'width: 50px;']);
		$f->textField('i', 'Text 3', ['style' => 'width: 50px;']);
	});
	$f->group(false, function($f){
		$f->button('Action 1');
		$f->button('Action 2');
	});
}, ['layout' => $layout]);
