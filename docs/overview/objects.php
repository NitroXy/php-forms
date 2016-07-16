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

Form::fromObject($myObject, function($f){
	$f->textField('name', 'Name');
	$f->textField('age', 'Age', ['type' => 'number', 'min' => 1]);
	$f->select('role', 'Role', FormOptions::fromArray(['', 'Manager', 'Frobnicator', 'Developer']));
	$f->textarea('description', 'Description');
}, ['layout' => 'bootstrap']);
