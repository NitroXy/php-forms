<?php

namespace NitroXy\PHPForms;

class FormButton extends FormInput {
	public function get_content(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		$this->pop_attr('value', $attr, $text);
		$this->pop_attr('icon', $attr, $icon); /* layout reads icon data, puts html back into attr */
		return "<button "  . $this->serialize_attr($attr) . ">$icon$text</button>";
	}
}
