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
	$f->fields_for('A', $a, function($f){
		$f->text_field('name', 'Name');
	});
	$f->fields_for('B', $b, function($f){
		$f->text_field('name', 'Name');
	});
}, ['layout' => 'bootstrap']);
