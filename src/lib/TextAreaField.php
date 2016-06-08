<?php

namespace NitroXy\PHPForms;

class TextAreaField extends FormInput {
	private $value = '';

	public function __construct($key, $id, $name, $value, $label, $attr) {
		parent::__construct($key, $id, $name, null, null, $label, $attr);
		$this->value = $value;
	}

	public function get_content(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		$value = htmlspecialchars($this->value);
		return "<textarea " . $this->serialize_attr($attr) . " >{$value}</textarea>";
	}
}
