<?php /*~*/
use NitroXy\PHPForms\Form;
global $layout;
$data = [];
?>
<?php
Form::from_array('checkboxes', $data, function($f){
	$f->checkbox('a', 'Text 1', null, ['hint' => 'Checkbox using regular label']);
	$f->checkbox('b', 'Text 2', false, ['hint' => 'Checkbox using inline label']);
	$f->group('Label', function($f){
		$f->checkbox('c', 'Text 3');
		$f->checkbox('d', 'Text 4');
	}, ['hint' => 'Checkbox using group']);
}, ['layout' => $layout]);
