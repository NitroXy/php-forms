<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => 'Value 1', 'b' => 'Value 2', 'c' => 'Value 3'];
?>
<?php
Form::from_array('example5', $data, function($f){
	$f->text_field('a', 'Text 1');
	$f->text_field('b', 'Text 2');
	$f->text_field('c', 'Text 3', ['required' => true, 'hint' => 'Required field']);
}, ['layout' => $layout]);
