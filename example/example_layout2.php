<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => 'Value 1', 'b' => 'Value 2', 'c' => 'Value 3', 'd' => 'Value 4', 'e' => 'Value 5'];
?>
<?php
Form::from_array('example5', $data, function($f){
	$f->text_field('a', 'Text 1');
	$f->fieldset('Fieldset A', function($f){
		$f->text_field('b', 'Text 2');
		$f->text_field('c', 'Text 3');
	});
	$f->fieldset('Fieldset B', function($f){
		$f->text_field('d', 'Text 4');
		$f->text_field('e', 'Text 5');
	});
}, ['layout' => $layout]);
