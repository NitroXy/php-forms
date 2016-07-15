<?php

namespace NitroXy\PHPForms;

class LinkField extends StaticField {
	protected $href = null;

	public function __construct($label, $attr){
		$this->pop_attr('text', $attr, $text);
		parent::__construct($text, $label, $attr);
	}

	public function render($layout, $res) {
		$layout->render_static($this);
	}

	public function get_content(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		$this->pop_attr('icon', $attr, $icon); /* layout reads icon data, puts html back into attr */
		return "<a " . $this->serialize_attr($attr) . ">$icon{$this->text}</a>";
	}

	public function get_hint(){ return false; }
	public function get_label(){ return $this->label; }
	public function layout_hints(){ return 0; }
	public function get_id() { return false; }
}
