<?php

namespace NitroXy\PHPForms;

class LinkField extends StaticField {
	protected $href = null;

	public function __construct($label, $attr){
		$this->popAttr('text', $attr, $text);
		parent::__construct($text, $label, $attr);
	}

	public function render($layout, $res) {
		$layout->renderStatic($this);
	}

	public function getContent(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		$this->popAttr('icon', $attr, $icon); /* layout reads icon data, puts html back into attr */
		return "<a " . $this->serializeAttr($attr) . ">$icon{$this->text}</a>";
	}

	public function getHint(){ return false; }
	public function getLabel(){ return $this->label; }
	public function layoutHints(){ return 0; }
	public function getId() { return false; }
}
