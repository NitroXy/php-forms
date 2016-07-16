<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => '1', 'd' => '2', 'g' => '3'];
?>
<?php
Form::fromArray('example5', $data, function($f){
	$f->group('Row 1', function($f){
		$f->textField('a', 'Text 1');
		$f->textField('b', 'Text 2');
		$f->textField('c', 'Text 3');
	});
	$f->group('Row 2', function($f){
		$f->textField('d', 'Text 1', ['class' => 'col-xs-8']);
		$f->textField('e', 'Text 2', ['class' => 'col-xs-4']);
	});
	$f->group('Row 3', function($f){
		$f->textField('f', 'Text 1', ['class' => 'col-xs-4']);
		$f->textField('g', 'Text 2', ['class' => 'col-xs-8']);
	});
	$f->group(false, function($f){
		$f->button('Action 1');
		$f->button('Action 2');
	});
}, ['layout' => $layout]);
