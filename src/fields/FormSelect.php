<?php

namespace NitroXy\PHPForms;

class FormSelect extends FormInput {
	private $selected;
	private $options = null;

	/**
	 * Create a selection input field.
	 *
	 * @option 'postback' if true it will submit the form when changed.
	 * @option 'selected' forces key to be selected.
	 */
	public function __construct($key, $id, $name, $value, $label=null, array $attr=[]){
		/* if postback is enabled, add onchange which submits the forrm */
		if ( $this->pop_attr('postback', $attr, $postback) && $postback ){
			$attr['onchange'] = 'this.form.submit();';
		}

		$this->selected = $value;
		$this->pop_attr('selected', $attr, $this->selected);
		$this->pop_attr('options', $attr, $this->options);

		parent::__construct($key, $id, $name, $value, null, $label, $attr);
	}

	public function get_options(){
		return $this->options->get_options();
	}

	public function get_value(){
		return $this->selected;
	}

	public function get_content(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		return '<select ' . $this->serialize_attr($attr) . ">\n" . $this->options->serialize_options($this->selected) . "\n</select>\n";
	}
}
