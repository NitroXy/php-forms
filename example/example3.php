<?php /*~*/
use NitroXy\PHPForms\Form;

function get_current_csrf_token(){
	return 'my-current-csrf-token';
}

?>
<?php
class MyForm extends Form {
	protected static function csrf_token(){
		return get_current_csrf_token();
	}
};

MyForm::create("example3", function($f){
	$f->text_field('name', 'My field name');
}, ['layout' => 'bootstrap']);
