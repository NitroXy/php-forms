<?php /*~*/
use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormBuilder;
?>
<?php

class MyBuilder extends FormBuilder {
	public function myField($label){
		$this->manual(false, $label, 'custom');
	}
}

class MyForm extends Form {
	public static $defaultBuilderClass = MyBuilder::class;
}

MyForm::create("id", function($f){
	$f->myField('Label');
});
