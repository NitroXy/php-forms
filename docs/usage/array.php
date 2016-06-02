<?php

$data = [
	'name' => 'Fred Flintstone',
	'age' => 31,
];

Form::from_array("User", $data, function($f){
	$f->text_field('name', 'Name', ['required' => true]);
	$f->text_field('age', 'Age', ['type' => 'number', 'min' => 1, 'required' => true]);
});
