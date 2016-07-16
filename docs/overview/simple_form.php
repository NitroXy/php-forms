<?php /*~*/ use NitroXy\PHPForms\Form; ?>
<?php
Form::create('example1', function($f){
	$f->textField('name', 'My field name');
});
