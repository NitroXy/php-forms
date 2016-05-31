<?php

/**
 * @codeCoverageIgnore
 */
class MockLayout implements NitroXy\PHPForms\FormLayout {
	public $form_id;
	public $form_attr;
	public $group = [];
	public $field = [];
	public $opened = 0;
	public $closed = 0;

	public function preamble($form){
		$this->form_id = $form;
		$this->form_attr = $form->attr;
		$this->opened++;
	}

	public function postamble($form){
		$this->closed++;
	}

	public function render_group($group, $res){
		$children = [];
		foreach ( $group->children() as $child ){
			$children[$child->get_name()] = $child;
		}
		$this->group[$group->get_label()] = $children;
	}

	public function render_hidden($field){
		$this->field[$field->get_name()] = $field;
	}

	public function render_field($field, $error){
		$this->field[$field->get_name()] = $field;
	}

	public function render_fieldset($fieldset, $children_cb){
		$children_cb();
	}

	public function render_hint($field){

	}

	public function render_static($field){
		$this->field[$field->get_name()] = $field;
	}

	public function begin(){

	}

	public function end(){

	}

	public function layout_name(){
		return 'mock';
	}
}
