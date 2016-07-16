<?php

$data = [
	'name' => 'Fred Flintstone',
	'age' => 31,
];

Form::fromArray("User", $data, function($f){
	$f->textField('name', 'Name', ['required' => true]);
	$f->textField('age', 'Age', ['type' => 'number', 'min' => 1, 'required' => true]);
});
