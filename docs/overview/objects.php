<?php /*~*/
use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormOptions;
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
	$f->select('role', 'Role', FormOptions::from_array(['', 'Manager', 'Frobnicator', 'Developer']));
	$f->textarea('description', 'Description');
}, ['layout' => 'bootstrap']);
