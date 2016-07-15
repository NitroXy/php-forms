<?php

namespace NitroXy\PHPForms;

class FormInput implements FormFieldInterface {
	protected $key;
	protected $id;
	protected $name;
	protected $label;
	protected $tworow = 0;
	protected $fill = 0;
	protected $icon = false;
	protected $prefix = false;
	protected $suffix = false;
	protected $container = null;
	protected $attr = array();

	/**
	 * @param $key used when fetching value.
	 * @param $id element id (e.g. id_$key)
	 * @param $name element name (e.g. Class[$key])
	 */
	public function __construct($key, $id, $name, $value, $type, $label, $attr) {
		$this->key = $key;
		$this->id = $id;
		$this->name = $name;
		$this->label = $label;
		$this->hint = null;

		/* add confirmation dialog for submit buttons */
		if ( $this->pop_attr('confirm', $attr, $confirm) ){
			$attr['onclick'] = "return confirm('".htmlspecialchars($confirm, ENT_QUOTES)."');";
		}

		$this->pop_attr('hint', $attr, $this->hint);
		$this->pop_attr('tworow', $attr, $this->tworow);
		$this->pop_attr('fill', $attr, $this->fill);
		$this->pop_attr('icon', $attr, $this->icon);
		$this->pop_attr('prefix', $attr, $this->prefix);
		$this->pop_attr('suffix', $attr, $this->suffix);

		if ( $value !== null ) $attr['value'] = $value;
		if (    $id !== null ) $attr['id'] = $id;
		if (  $name !== null ) $attr['name'] = $name;

		/* allow customizing type (e.g. html5 types such as number) */
		if ( !array_key_exists('type', $attr) && $type !== null ) {
			$attr['type'] = $type;
		}

		$this->attr = $attr;
	}

	/**
	 * Read and remove $key from attribute array.
	 * @return true if attribute existed.
	 */
	protected function pop_attr($key, &$attr, &$value){
		if ( array_key_exists($key, $attr) ){
			$value = $attr[$key];
			unset($attr[$key]);
			return true;
		}
		return false;
	}

	public function render($layout, $res) {
		if ( !(array_key_exists('type', $this->attr) && $this->attr['type'] === 'hidden') ){
			$layout->render_field($this, $this->get_error($res));
		} else {
			$layout->render_hidden($this);
		}
	}

	public function get_id() {
		return $this->id;
	}

	public function get_label(){
		return $this->label;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_icon(){
		return $this->icon;
	}

	public function get_addons(){
		return [$this->prefix, $this->suffix];
	}

	public function get_value(){
		return array_key_exists('value', $this->attr) ? $this->attr['value'] : null;
	}

	public function get_content(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		if ( $attr['type'] == 'password' && isset($attr['autocomplete']) && $attr['autocomplete'] == 'off' ){
			unset($attr['value']);
		}
		return "<input " . $this->serialize_attr($attr) . " />";
	}

	public function get_error($res){
		if ( !($this->key && isset($res->errors[$this->key])) ) return false;
		return ucfirst($res->errors[$this->key][0]); /* get first error only */
	}

	public function get_hint(){
		return $this->hint;
	}

	public function attribute($key, $default=false){
		return array_key_exists($key, $this->attr) ? $this->attr[$key] : $default;
	}

	public function layout_hints(){
		return
			($this->tworow ? Form::LAYOUT_TWOROWS : 0) |
			($this->fill   ? Form::LAYOUT_FILL : 0 );
	}

	protected function serialize_attr($data=null){
		return FormUtils::serialize_attr($data ?: $this->attr);
	}

	public function set_container($container){
		$this->container = $container;
	}

	public function get_container(){
		return $this->container;
	}
}
