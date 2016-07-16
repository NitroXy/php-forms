<?php

namespace NitroXy\PHPForms;

class FormFieldset extends FormContext implements FormFieldInterface {
	private $label;

	public function __construct(FormContext $parent, $label, callable $callback){
		parent::__construct($parent->form, $parent->builder);
		$this->label = $label;
		$this->apply($callback);
	}

	public function render($layout, $res){
		/* do nothing if there is no fields */
		if ( count($this->fields) == 0 ){
			return;
		}

		$children = $this->fields;
		$layout->renderFieldset($this, function() use ($children, $layout, $res) {
			foreach ( $children as $field){
				$field->render($layout, $res);
			}
		});
	}

	public function layoutHints(){
		return 0;
	}

	public function getLabel() { return $this->label; }
	public function getContent() { return false; }
	public function getId() { return false; }

	public function getContainer(){
		return null;
	}

	public function setContainer($container){

	}

	public function attribute($key, $default=false){
		return $default; /* for now, fieldsets has no attributes to read */
	}
}
