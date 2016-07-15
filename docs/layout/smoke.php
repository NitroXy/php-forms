<?php /*~*/
use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormOptions;
global $layout;
?>
<?php

Form::create('smoketest', function($f){
	$f->hidden_field('name', 'Hidden (this wont show at all)', ['value' => '8']);
	$f->text_field('text_field', 'Text', ['hint' => 'Use the "type" option to use custom type such as number.']);
	$f->password_field('password_field', 'Password', ['hint' => 'Passwords are not persistent if autocomplete is off', 'autocomplete' => 'off']);
	$f->custom_field('custom_field', 'email', 'Email', ['hint' => 'Another way to add custom input fields (same as using text_field with type).', 'placeholder' => 'email@example.net']);
	$f->textarea('textarea', 'Textarea');
	$f->select('select', 'Select', FormOptions::from_array(['A', 'B', 'C']));
	$f->static_value('static', 'Static text');
	$f->link('link', 'https://example.net', 'Static link');
	$f->hint('static', 'Lorem ipsum dot sit amet.');
	$f->upload_field('upload', 'File upload', ['remove' => true]);
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
		$f->text_field('text1', 'Input 1');
		$f->text_field('text2', 'Input 2');
		$f->text_field('text3', 'Input 3');
	});
}, ['layout' => $layout]);
