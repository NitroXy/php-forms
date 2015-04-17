<?php

namespace NitroXy\PHPForms;

class FormGroup extends FormContainer implements FormField {
	private $label;
	private $hint = false;

	public function __construct($form, $label, $callback, $attr){
		parent::__construct($form);
		$this->label = $label;

		if ( array_key_exists('hint', $attr) ){
			$this->hint = $attr['hint'];
			unset($attr['hint']);
		}

		$callback($this);
	}

	public function children(){
		return $this->fields;
	}

	public function render($layout, $res){
		$layout->render_group($this, $res);
	}

	public function get_label(){
		return $this->label;
	}

	public function get_error($res){
		foreach ( $this->fields as $field ){
			if ( ($error=$field->get_error($res)) ){
				return $error;
			}
		}
		return false;
	}

	public function get_content(){
		return '';
	}

	public function layout_hints(){
		return 0;
	}

	public function get_id() { return false; }

	public function get_container(){
		return null;
	}

	public function attribute($key, $default=false){
		return $default; /* for now, groups has no attributes to read */
	}
}
