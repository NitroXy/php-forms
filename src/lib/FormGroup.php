<?php

namespace NitroXy\PHPForms;

class FormGroup extends FormContext implements FormFieldInterface {
	private $label;
	private $hint = false;

	public function __construct(FormContext $parent, $label, callable $callback, array $attr){
		parent::__construct($parent->form, $parent->builder);
		$this->label = $label;

		if ( array_key_exists('hint', $attr) ){
			$this->hint = $attr['hint'];
			unset($attr['hint']);
		}

		$this->apply($callback);
	}

	public function children(){
		return $this->fields;
	}

	public function render($layout, $res){
		/* do nothing if there is no fields */
		if ( count($this->fields) == 0 ){
			return;
		}

		$layout->render_group($this, $res);
	}

	public function get_label(){
		return $this->label;
	}

	public function get_hint(){
		return $this->hint;
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

	public function set_container($container){

	}

	public function attribute($key, $default=false){
		return $default; /* for now, groups has no attributes to read */
	}
}
