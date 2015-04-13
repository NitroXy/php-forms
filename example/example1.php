<?php /*~*/ use NitroXy\PHPForms\Form; ?>
<?php
Form::create('example1', function($f){
	$f->text_field('name', 'My field name');
});
