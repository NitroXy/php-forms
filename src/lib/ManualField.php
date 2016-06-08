<?php

namespace NitroXy\PHPForms;

class ManualField implements FormField {
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
		$layout->render_field($this, $this->get_error($res));
	}

	public function get_error($res){
		if ( !($this->key && isset($res->errors[$this->key])) ) return false;
		return ucfirst($res->errors[$this->key][0]); /* get first error only */
	}

	public function get_name(){ return $this->key; }
	public function get_label(){ return $this->label; }
	public function get_content(){ return $this->content; }
	public function get_hint(){ return $this->hint; }
	public function get_addons(){ return [false, false]; }
	public function layout_hints(){ return 0; }
	public function get_id() { return false; }
	public function set_container($container){ $this->container = $container; }
	public function get_container(){ return $this->container; }

	public function attribute($key, $default=false){
		return $default; /* no sane way to get attributes from manual fields */
	}
}
