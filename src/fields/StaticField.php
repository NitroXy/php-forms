<?php

namespace NitroXy\PHPForms;

class StaticField extends FormInput {
	protected $text = null;

	public function __construct($text, $label, $attr){
		parent::__construct(false, false, false, null, null, $label, $attr);
		$this->text = $text;
	}

	public function render($layout, $res) {
		$layout->renderStatic($this);
	}

	public function getContent(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		$this->popAttr('icon', $attr, $icon); /* layout reads icon data, puts html back into attr */
		return $icon . $this->text;
	}

	public function getHint(){ return false; }
	public function getLabel(){ return $this->label; }
	public function layoutHints(){ return 0; }
	public function getId() { return false; }
}
