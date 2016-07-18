<?php

namespace NitroXy\PHPForms;

class FormLayoutUnbuffered implements FormLayoutInterface {
	protected $preambleWritten = false;
	
	public function preamble($form){
		if ( $this->preambleWritten || $form->attr['action'] === false ) return;

		$sattr = FormUtils::serializeAttr($form->attr);
		echo "<form $sattr>\n";
		
		$this->preambleWritten = true;
	}
	
	public function postamble($form){

	}
	
	public function renderGroup($group, $res){

	}
	
	public function renderHidden($field){

	}
	
	public function renderField($field, $error){
		$id = $field->getId();
		$label = $field->getLabel();

		if ( $field instanceof FormCheckbox ){
			$label = $field->getText();
		}

		if( $label !== false) {
			if ( $id !== false ){
				echo "<label for=\"{$id}\">{$label}</label>\n";
			} else {
				echo "<label>{$label}</label>\n";
			}
		}
		
		echo $field->getContent() . "\n";
	}
	
	public function renderFieldset($fieldset, $children_cb){

	}
	
	public function renderHint($field){

	}
	
	public function renderStatic($field){

	}
	
	public function begin(){

	}
	
	public function end(){

	}

	public function layoutName(){
		return 'unbuffered';
	}
}
