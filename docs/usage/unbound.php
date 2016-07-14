<?php

Form::create('login', function($f){
	$f->text_field('username', 'Username');
	$f->password_field('password', 'Password');
});
