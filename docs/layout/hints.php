<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => '1', 'e' => '2', 'i' => '3'];
?>
<?php
Form::from_array('example5', $data, function($f){
	$f->hint('Row-level hints offers a hint that spans an entire row (similar to a paragraph).', false);
	$f->hint('Field-level hints has a similar look as a static field.', 'Field');
	$f->text_field('a', 'Text 1', ['hint' => 'Field hints describe a specific field']);
}, ['layout' => $layout]);
