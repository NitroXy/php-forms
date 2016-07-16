<?php /*~*/
use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormSelect;
?>
<?php

class MyClassA {};
class MyClassB {};

$a = new MyClassA; $a->name = 'Name A';
$b = new MyClassB; $b->name = 'Name B';

Form::create('example6', function($f) use ($a, $b) {
	$f->fieldsFor('A', $a, function($f){
		$f->textField('name', 'Name');
	});
	$f->fieldsFor('B', $b, function($f){
		$f->textField('name', 'Name');
	});
}, ['layout' => 'bootstrap']);
