<?php

namespace NitroXy\PHPForms;

class FormCheckbox extends FormInput {
	public function __construct($key, $id, $name, $value, $type, $label, $attr) {
		$this->key = $key;
		$this->id = $id;
		$this->name = $name;
		$this->label = $label;
		$this->hint = null;
		$this->text = '';

		$this->pop_attr('hint', $attr, $this->hint);
		$this->pop_attr('text', $attr, $this->text);

		if (  $type != null  ) $attr['type'] = $type;
		if (    $id != null  ) $attr['id'] = $id;
		if (  $name != null  ) $attr['name'] = $name;

		$attr['value'] = '1';
		if ( $value ){
			$attr['checked'] = 'checked';
		}

		$this->attr = $attr;
	}

	public function get_text(){
		return $this->text;
	}

	public function get_content(array $extra_attr = array(), array $label = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		if ( $this->get_container() instanceof FormGroup ){
			return "<label class=\"form-checkbox\" " . FormUtils::serialize_attr($label) . "><input " . $this->serialize_attr($attr) . " /> {$this->text}</label>";
		} else {
			return "<input " . $this->serialize_attr($attr) . " />";
		}
	}
}