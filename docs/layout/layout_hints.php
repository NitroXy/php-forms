<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => '1', 'e' => '2', 'i' => '3'];
?>
<?php
Form::from_array('example5', $data, function($f){
	$f->textarea('foo', 'Regular textarea', ['hint' => 'Lorem ipsum']);
	$f->textarea('bar', '"tworow"', ['tworow' => true, 'hint' => 'Lorem ipsum']);
	$f->textarea('baz', '"tworow" + "fill"', ['tworow' => true, 'fill' => true, 'hint' => 'Lorem ipsum']);
}, ['layout' => $layout]);
