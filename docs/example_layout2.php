<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = ['a' => 'Value 1', 'b' => 'Value 2', 'c' => 'Value 3', 'd' => 'Value 4', 'e' => 'Value 5'];
?>
<?php
Form::fromArray('example5', $data, function($f){
	$f->textField('a', 'Text 1');
	$f->fieldset('Fieldset A', function($f){
		$f->textField('b', 'Text 2');
		$f->textField('c', 'Text 3');
	});
	$f->fieldset('Fieldset B', function($f){
		$f->textField('d', 'Text 4');
		$f->textField('e', 'Text 5');
	});
}, ['layout' => $layout]);
