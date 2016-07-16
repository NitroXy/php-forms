<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => 'Value 1', 'b' => 'Value 2', 'c' => 'Value 3', 'd' => 'Value 4'];
?>
<?php
Form::fromArray('example_prefix_suffix', $data, function($f){
	$f->textField('a', 'Text 1');
	$f->textField('b', 'Text 2', ['prefix' => 'Prefix']);
	$f->textField('c', 'Text 3', ['suffix' => 'Suffix']);
	$f->textField('d', 'Text 4', ['prefix' => 'Prefix', 'suffix' => 'Suffix']);
}, ['layout' => $layout]);
