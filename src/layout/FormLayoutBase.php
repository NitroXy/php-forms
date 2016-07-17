<?php

namespace NitroXy\PHPForms;

abstract class FormLayoutBase implements FormLayoutInterface {
	public function preamble($form){
		$attr = FormUtils::serializeAttr($form->attr);
		echo "<form $attr>\n";
	}

	public function postamble($form){
		echo "</form>\n";
	}

	public function renderHidden($field){
		echo "\t{$field->getContent()}\n";
	}

	public function renderFieldset($fieldset, $children_cb){
		$this->end();
		echo "	<fieldset>\n";

		$label = $fieldset->getLabel();
		if ( $label ){
			echo "		<legend>{$label}</legend>\n";
		}

		$children_cb();
		$this->end();

		echo "	</fieldset>\n";
	}
}
