<?php

class User {
	public $name;
	public $age;
};

$user = new User;
$user->name = 'Fred Flintstone',
$user->age => 31;

Form::fromObject($user, function($f){
	$f->textField('name', 'Name', ['required' => true]);
	$f->textField('age', 'Age', ['type' => 'number', 'min' => 1, 'required' => true]);
});
