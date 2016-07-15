<?php

namespace NitroXy\PHPForms;

class FormFieldset extends FormContext implements FormFieldInterface {
	private $label;

	public function __construct($form, $label, $callback){
		parent::__construct($form);
		$this->label = $label;
		$callback($this);
	}

	public function render($layout, $res){
		/* do nothing if there is no fields */
		if ( count($this->fields) == 0 ){
			return;
		}

		$children = $this->fields;
		$layout->render_fieldset($this, function() use ($children, $layout, $res) {
			foreach ( $children as $field){
				$field->render($layout, $res);
			}
		});
	}

	public function layout_hints(){
		return 0;
	}

	public function get_label() { return $this->label; }
	public function get_content() { return false; }
	public function get_id() { return false; }

	public function get_container(){
		return null;
	}

	public function attribute($key, $default=false){
		return $default; /* for now, fieldsets has no attributes to read */
	}
}
