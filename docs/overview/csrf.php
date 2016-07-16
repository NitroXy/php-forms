<?php /*~*/
use NitroXy\PHPForms\Form;

function get_current_csrf_token(){
	return 'my-current-csrf-token';
}

?>
<?php
class MyForm extends Form {
	protected static function csrfToken(){
		return get_current_csrf_token();
	}
};

MyForm::create("example_csrf", function($f){
	/* ... */
});
