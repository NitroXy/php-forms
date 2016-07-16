<?php /*~*/ use NitroXy\PHPForms\Form; ?>
<?php
Form::create("example2-$layout", function($f){
	$f->textField('name', 'My field name');
}, ['layout' => $layout]);
