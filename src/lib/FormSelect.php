<?php

namespace NitroXy\PHPForms;

class FormSelect extends FormInput {
	private $selected;
	private $options = array();

	/**
	 * Create a selection input field.
	 *
	 * @option 'postback' if true it will submit the form when changed.
	 * @option 'selected' forces key to be selected.
	 */
	public function __construct($form, $key, $label=null, $attr=array()){
		if ( $label && !is_string($label) ){
			trigger_error("Label must be string");
		}

		list($id, $name, $value) = $form->generate_data($key, $attr);

		/* if postback is enabled, add onchange which submits the forrm */
		if ( $this->pop_attr('postback', $attr, $postback) && $postback ){
			$attr['onchange'] = 'this.form.submit();';
		}

		$this->selected = $value;
		$this->pop_attr('selected', $attr, $this->selected);

		parent::__construct($key, $id, $name, null, null, $label, $attr);
	}

	/**
	 * Create new selection from array where key is option value and value is text presented.
	 */
	public static function from_array($form, $key, $data, $label=null, $attr=array()) {
		$ret = new FormSelect($form, $key, $label, $attr);
		foreach($data as $value => $text) {
			$ret->add($value, $text);
		}
		return $ret;
	}

	/**
	 * Create new form from array, applying callback to extract key and value.
	 *
	 * from_array_callback(..., array($a, $b, $c), function($x){
	 *   return array($x->key, $x->value);
	 * });
	 **/
	static public function from_array_callback($form, $key, $data, $callback, $label=null, $attr=array()){
		$ret = new FormSelect($form, $key, $label, $attr);
		foreach ( $data as $item ){
			list($value, $text) = $callback($item);
			$ret->add($value, $text);
		}
		return $ret;
	}

	/**
	 * Add a new option.
	 */
	public function add($value, $text, $attr=array()) {
		if ( $value == $this->selected ){
			$attr['selected'] = 'selected';
		}
		$attr['value'] = $value;

		$this->options[] = (object)[
			'attr' => $attr,
			'label' => $text,
		];
	}

	public function get_options(){
		return $this->options;
	}

	public function serialize_options(){
		return implode("\n", array_map(function($cur) {
			return "<option " . $this->serialize_attr($cur->attr) . ">{$cur->label}</option>";
		}, $this->options));
	}

	public function get_content(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		return '<select ' . $this->serialize_attr($attr) . ">\n" . $this->serialize_options() . "\n</select>\n";
	}
}
