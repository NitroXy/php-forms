<?php /*~*/
use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormSelect;
?>
<?php

class MyCustomClass extends stdClass {

};

$myObject = new MyCustomClass;
$myObject->name = 'John Doe';
$myObject->age = 46;
$myObject->role = 2; /* frobnicator */
$myObject->description = 'Lorem ipsum dot sit amet.';

Form::from_object($myObject, function($f){
	$f->text_field('name', 'Name');
	$f->text_field('age', 'Age', ['type' => 'number', 'min' => 1]);
	$f->select(FormSelect::from_array($f, 'role', array('', 'Manager', 'Frobnicator', 'Developer'), 'Role'));
	$f->textarea('description', 'Description');
}, ['layout' => 'bootstrap']);
