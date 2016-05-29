<?php

class MockLayout implements NitroXy\PHPForms\FormLayout {
	public $form_id;
	public $form_attr;
	public $field = [];

	public function preamble($form){
		$this->form_id = $form;
		$this->form_attr = $form->attr;
	}

	public function postamble($form){

	}

	public function render_group($group, $res){

	}

	public function render_hidden($field){
		$this->field[$field->get_name()] = $field;
	}

	public function render_field($field, $error){
		$this->field[$field->get_name()] = $field;
	}

	public function render_fieldset($fieldset){

	}

	public function render_hint($field){

	}

	public function begin(){

	}

	public function end(){

	}

	public function layout_name(){
		return 'mock';
	}
}
