<?php /*~*/
use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormOptions;
global $layout;
?>
<?php

Form::create('smoketest', function($f){
	$f->hiddenField('name', 'Hidden (this wont show at all)', ['value' => '8']);
	$f->textField('text_field', 'Text', ['hint' => 'Use the "type" option to use custom type such as number.']);
	$f->passwordField('password_field', 'Password', ['hint' => 'Passwords are not persistent if autocomplete is off', 'autocomplete' => 'off']);
	$f->customField('custom_field', 'email', 'Email', ['hint' => 'Another way to add custom input fields (same as using textField with type).', 'placeholder' => 'email@example.net']);
	$f->textarea('textarea', 'Textarea');
	$f->select('select', 'Select', FormOptions::fromArray(['A', 'B', 'C']));
	$f->staticValue('static', 'Static text');
	$f->link('link', 'https://example.net', 'Static link');
	$f->hint('static', 'Lorem ipsum dot sit amet.');
	$f->uploadField('upload', 'File upload', ['remove' => true]);
	$f->checkbox('checkbox', 'Checkbox');
	$f->manual('manual', 'Manual', '<em>Custom html</em>', false);
	$f->submit('Submit button');
	$f->button('Generic button', ['class' => 'btn-success']);

	/* groups allows you to put multiple fields inline (label is optional) */
	$f->group('Inline group', function($f){
		$f->button('Button 1', ['class' => 'btn-default']);
		$f->button('Button 2', ['class' => 'btn-danger']);
		$f->button('Button 3');
	});

	/* fieldsets */
	$f->fieldset('Fieldset', function($f){
		$f->textField('text1', 'Input 1');
		$f->textField('text2', 'Input 2');
		$f->textField('text3', 'Input 3');
	});
}, ['layout' => $layout]);
