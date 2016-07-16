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
		$layout->renderHint($this);
	}

	public function getHint(){ return false; }
	public function getContent(){ return $this->text; }
	public function getLabel(){ return $this->label; }
	public function layoutHints(){ return 0; }
	public function getId() { return false; }
	public function setContainer($container){ $this->container = $container; }
	public function getContainer(){ return $this->container; }

	public function attribute($key, $default=false){
		return array_key_exists($key, $this->attr) ? $this->attr[$key] : $default;
	}
}
