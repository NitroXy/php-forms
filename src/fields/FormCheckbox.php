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

		$this->popAttr('hint', $attr, $this->hint);
		$this->popAttr('text', $attr, $this->text);

		if (  $type !== null  ) $attr['type'] = $type;
		if (    $id !== null  ) $attr['id'] = $id;
		if (  $name !== null  ) $attr['name'] = $name;

		$attr['value'] = '1';
		if ( $value ){
			$attr['checked'] = 'checked';
		}

		$this->attr = $attr;
	}

	public function getText(){
		return $this->text;
	}

	public function getContent(array $extra_attr = array(), array $label = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		if ( $this->getContainer() instanceof FormGroup ){
			return "<label class=\"form-checkbox\" " . FormUtils::serializeAttr($label) . "><input " . $this->serializeAttr($attr) . " /> {$this->text}</label>";
		} else {
			return "<input " . $this->serializeAttr($attr) . " />";
		}
	}
}
