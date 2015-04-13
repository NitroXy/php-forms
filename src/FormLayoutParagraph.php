<?php

class FormLayoutParagraph implements FormLayout {
	public function add_row($label, $field, $error, $hint){
		echo "	<p>\n";
		if($label !== false) echo "	<span class=\"form-label\">$label</span>\n";
		echo "	<span class=\"form-field\">{$field->get_content()}</span>\n";
		if($error !== false) echo "	<span class=\"form-error\">$error</span>\n";
		if($hint !== false) echo "	<span class=\"form-hint\">$hint</span>\n";
		echo "	</p>\n";
	}
}
