<?php

namespace NitroXy\PHPForms;

class FormContext {
	protected $fields = [];      /* all fields but hidden */
	protected $hidden = [];      /* only hidden fields */
	protected $form = null;      /* form object */
	protected $builder = null;   /* builder instance */

	public function __construct(Form $form, FormBuilder $builder){
		$this->form = $form;
		$this->builder = $builder;
	}

	public function unbuffered(){
		return $this->form->unbuffered();
	}

	public function form(){
		return $this->form;
	}

	public function generateData($key, array &$attr){
		return $this->form->generateData($key, $attr);
	}

	public function addField($field){
		$this->fields[] = $field;
		return $field;
	}

	public function hiddenField($key, $value=null, array $attr=[]){
		$this->form->hiddenField($key, $value, $attr);
	}

	public function fieldsFor($id, $obj, callable $callback){
		$this->form->fieldsFor($id, $obj, $callback);
	}

	public function apply(callable $callback){
		$oldContext = $this->builder->setContext($this);
		try {
			$callback($this->builder);
		} finally {
			$this->builder->setContext($oldContext);
		}
	}
}
