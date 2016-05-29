<?php

namespace NitroXy\PHPForms;

abstract class FormLayoutBase implements FormLayout {
	public function preamble($form){
		$attr = FormUtils::serialize_attr($form->attr);
		echo "<form id=\"{$form->id}\" $attr>\n";
	}

	public function postamble($form){
		echo "</form>\n";
	}

	public function render_hidden($field){
		echo "\t{$field->get_content()}\n";
	}
}
