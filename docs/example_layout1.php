<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => 'Value 1', 'b' => 'Value 2', 'c' => 'Value 3'];
?>
<?php
Form::fromArray('example5', $data, function($f){
	$f->textField('a', 'Text 1');
	$f->textField('b', 'Text 2');
	$f->textField('c', 'Text 3', ['required' => true, 'hint' => 'Required field']);
}, ['layout' => $layout]);
