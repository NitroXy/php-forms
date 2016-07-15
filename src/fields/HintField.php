<?php

namespace NitroXy\PHPForms;

class HintField implements FormFieldInterface {
	private $text = null;
	private $label = null;
	private $attr = array('class' => 'form-hint');
	protected $container = null;

	public function __construct($text, $label, $attr){
		$this->text = $text;
		$this->label = $label;
		$this->attr = array_merge($this->attr, $attr);
	}

	public function render($layout, $res) {
		$layout->render_hint($this);
	}

	public function get_hint(){ return false; }
	public function get_content(){ return $this->text; }
	public function get_label(){ return $this->label; }
	public function layout_hints(){ return 0; }
	public function get_id() { return false; }
	public function set_container($container){ $this->container = $container; }
	public function get_container(){ return $this->container; }

	public function attribute($key, $default=false){
		return array_key_exists($key, $this->attr) ? $this->attr[$key] : $default;
	}
}
