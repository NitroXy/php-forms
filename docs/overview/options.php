<?php /*~*/
use NitroXy\PHPForms\Form;
?>
<?php
class MyForm extends Form {
	static protected function default_options(){
		return [
			'layout' => 'bootstrap',
		];
	}
};

MyForm::create("example_default_options", function($f){
	$f->text_field('name', 'My field name');
});
