<?php

class User {
	public $name;
	public $age;
};

$user = new User;
$user->name = 'Fred Flintstone',
$user->age => 31;

Form::from_object($user, function($f){
	$f->text_field('name', 'Name', ['required' => true]);
	$f->text_field('age', 'Age', ['type' => 'number', 'min' => 1, 'required' => true]);
});
