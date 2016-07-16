<?php

/**
 * @codeCoverageIgnore
 */
class MockLayout implements NitroXy\PHPForms\FormLayoutInterface {
	public $form_id;
	public $form_attr;
	public $group = [];
	public $field = [];
	public $opened = 0;
	public $closed = 0;

	public function preamble($form){
		$this->form_id = $form->id;
		$this->form_attr = $form->attr;
		$this->opened++;
	}

	public function postamble($form){
		$this->closed++;
	}

	public function renderGroup($group, $res){
		$children = [];
		foreach ( $group->children() as $child ){
			$children[$child->getName()] = $child;
		}
		$this->group[$group->getLabel()] = $children;
	}

	public function renderHidden($field){
		$this->field[$field->getName()] = $field;
	}

	public function renderField($field, $error){
		$name = $field->getName();
		if ( $name ){
			$this->field[$name] = $field;
		} else {
			$this->field[] = $field;
		}
	}

	public function renderFieldset($fieldset, $children_cb){
		$children_cb();
	}

	public function renderHint($field){

	}

	public function renderStatic($field){
		$this->field[$field->getName()] = $field;
	}

	public function begin(){

	}

	public function end(){

	}

	public function layoutName(){
		return 'mock';
	}
}
