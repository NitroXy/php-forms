<?php

Form::create('login', function($f){
	$f->textField('username', 'Username');
	$f->passwordField('password', 'Password');
});
