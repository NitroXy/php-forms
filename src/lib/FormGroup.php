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

		$layout->renderGroup($this, $res);
	}

	public function getLabel(){
		return $this->label;
	}

	public function getHint(){
		return $this->hint;
	}

	public function getError($res){
		foreach ( $this->fields as $field ){
			if ( ($error=$field->getError($res)) ){
				return $error;
			}
		}
		return false;
	}

	public function getContent(){
		return '';
	}

	public function layoutHints(){
		return 0;
	}

	public function getId() { return false; }

	public function getContainer(){
		return null;
	}

	public function setContainer($container){

	}

	public function attribute($key, $default=false){
		return $default; /* for now, groups has no attributes to read */
	}
}
