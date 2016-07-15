<?php

namespace NitroXy\PHPForms;

abstract class FormLayoutBase implements FormLayoutInterface {
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

	public function render_fieldset($fieldset, $children_cb){
		$this->end();
		echo "	<fieldset>\n";

		$label = $fieldset->get_label();
		if ( $label ){
			echo "		<legend>{$label}</legend>\n";
		}

		$children_cb();
		$this->end();

		echo "	</fieldset>\n";
	}
}
