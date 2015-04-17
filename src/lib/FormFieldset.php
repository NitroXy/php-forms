<?php

namespace NitroXy\PHPForms;

class FormFieldset extends FormContainer implements FormField {
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

		echo "	<fieldset>\n";
		if ( $this->label ){
			echo "		<legend>{$this->label}</legend>\n";
		}
		foreach ( $this->fields as $field){
			$field->render($layout, $res);
		}
		$layout->end();

		echo "	</fieldset>\n";
	}

	public function layout_hints(){
		return 0;
	}

	public function get_label() { return false; }
	public function get_content() { return false; }
	public function get_id() { return false; }

	public function get_container(){
		return null;
	}

	public function attribute($key, $default=false){
		return $default; /* for now, fieldsets has no attributes to read */
	}
}
