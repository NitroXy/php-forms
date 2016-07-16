<?php

namespace NitroXy\PHPForms;

class FormButton extends FormInput {
	public function getContent(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		$this->popAttr('value', $attr, $text);
		$this->popAttr('icon', $attr, $icon); /* layout reads icon data, puts html back into attr */
		return "<button "  . $this->serializeAttr($attr) . ">$icon$text</button>";
	}
}
