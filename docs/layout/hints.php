<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => '1', 'e' => '2', 'i' => '3'];
?>
<?php
Form::from_array('example5', $data, function($f){
	$f->text_field('a', 'Text 1', ['hint' => 'Lorem ipsum']);
}, ['layout' => $layout]);
