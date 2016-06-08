<?php

namespace NitroXy\PHPForms;

class StaticField extends FormInput {
	protected $text = null;

	public function __construct($text, $label, $attr){
		parent::__construct(false, false, false, null, null, $label, $attr);
		$this->text = $text;
	}

	public function render($layout, $res) {
		$layout->render_static($this);
	}

	public function get_content(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		$this->pop_attr('icon', $attr, $icon); /* layout reads icon data, puts html back into attr */
		return $icon . $this->text;
	}

	public function get_hint(){ return false; }
	public function get_label(){ return $this->label; }
	public function layout_hints(){ return 0; }
	public function get_id() { return false; }
}
