<?php /*~*/
use NitroXy\PHPForms\Form;
?>
<?php
class MyForm extends Form {
	static protected function defaultOptions(){
		return [
			'layout' => 'bootstrap',
		];
	}
};

MyForm::create("example_default_options", function($f){
	$f->textField('name', 'My field name');
});
