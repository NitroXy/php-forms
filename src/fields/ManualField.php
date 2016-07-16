<?php

namespace NitroXy\PHPForms;

class ManualField implements FormFieldInterface {
	private $key = null;
	private $label = null;
	private $content = null;
	private $hint = null;
	protected $container = null;

	public function __construct($key, $label, $content, $hint){
		$this->key = $key;
		$this->label = $label;
		$this->content = $content;
		$this->hint = $hint;
	}

	public function render($layout, $res) {
		$layout->renderField($this, $this->getError($res));
	}

	public function getError($res){
		if ( !($this->key && isset($res->errors[$this->key])) ) return false;
		return ucfirst($res->errors[$this->key][0]); /* get first error only */
	}

	public function getName(){ return $this->key; }
	public function getLabel(){ return $this->label; }
	public function getContent(){ return $this->content; }
	public function getHint(){ return $this->hint; }
	public function getAddons(){ return [false, false]; }
	public function layoutHints(){ return 0; }
	public function getId() { return false; }
	public function setContainer($container){ $this->container = $container; }
	public function getContainer(){ return $this->container; }

	public function attribute($key, $default=false){
		return $default; /* no sane way to get attributes from manual fields */
	}
}
