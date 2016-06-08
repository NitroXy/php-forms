<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => 'Value 1', 'b' => 'Value 2', 'c' => 'Value 3', 'd' => 'Value 4'];
?>
<?php
Form::from_array('example_prefix_suffix', $data, function($f){
	$f->text_field('a', 'Text 1');
	$f->text_field('b', 'Text 2', ['prefix' => 'Prefix']);
	$f->text_field('c', 'Text 3', ['suffix' => 'Suffix']);
	$f->text_field('d', 'Text 4', ['prefix' => 'Prefix', 'suffix' => 'Suffix']);
}, ['layout' => $layout]);
