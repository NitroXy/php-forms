<?php
require '../vendor/autoload.php';
use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormSelect;

$layout = isset($_GET['layout']) ? $_GET['layout'] : 'plain';
$available = array(
	'plain' => 'Plain',
	'table' => 'Table',
	'bootstrap' => 'Bootstrap',
);

class Object {
	public function has_errors(){
		return false;
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>PHP-Forms Smoketest</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
	</head>
	<body>
		<div class="container">
			<h1>Form smoketest</h1>
			<select onchange="window.location=this.value;">
				<?php foreach ( $available as $key => $value ): ?>
					<option value="?layout=<?=$key?>"<?php if ( $layout == $key ): ?> selected="selected"<?php endif; ?>><?=$value?></option>
				<?php endforeach; ?>
			</select>

			<?php
			Form::from_object(new Object(), function($f){
				$f->fieldset('Fieldset 1', function($f){
					$f->hidden_field('hidden', 12);
					$f->text_field('name', 'Namn');
					$f->password_field('password', 'Password');
					$f->hint('Hint without label 1');
					$f->hint('Hint without label 2', false);
					$f->hint('Hint with label', 'Label');
					$f->upload_field('upload', 'File upload 1');
					$f->upload_field('upload', 'File upload 2', ['remove' => true]);
					$f->textarea('text', 'Text 1', ['hint' => 'Textarea without any hints']);
					$f->textarea('text', 'Text 2', ['hint' => 'Textarea with "tworow" hint', 'tworow' => true]);
					$f->textarea('text', 'Text 3', ['hint' => 'Textarea with "tworow" and "fill" hint', 'tworow' => true, 'fill' => true]);
					$f->checkbox('check', 'Single checkbox');
					$f->group('Checkbox group', function($g){
						$g->checkbox('bool1', 'A');
						$g->checkbox('bool2', 'B');
						$g->checkbox('bool3', 'C');
					});
					$data = array('1' => 'Model A', '2' => 'Model B', '3' => 'Model C');
					$f->select(FormSelect::from_array($f, 'sel2', $data, 'Select'));
					$f->select(FormSelect::from_array($f, 'sel2', $data, 'Select', array('hint' => 'With hint')));

					$f->group('Button', function($g){
						$g->button('Confirm', null, array('confirm' => 'Confirmation text'));
					});

					$f->submit('Submit', null);
					$f->group(false, function($g){
						$g->submit('Button 1', null);
						$g->submit('Button 2', null);
						$g->submit('Button 3', null);
					});
					$f->group('Button group label', function($g){
						$g->submit('Button 1', null);
						$g->submit('Button 2', null);
						$g->submit('Button 3', null);
					});
				});
				$f->fieldset('Fieldset 2', function($g){
					$g->hint('Hint in fieldset');
					$g->text_field('name', 'Namn');
					$g->textarea('fieldset_text', 'Textarea', ['hint' => 'Hint', 'tworow' => true, 'fill' => true]);
				});
			}, array('layout' => $layout));
			?>
		</div>
		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	</body>
</html>
