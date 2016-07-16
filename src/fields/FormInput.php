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
	public function __construct($key, $id, $name, $value, $type, $label, array $attr) {
		$this->key = $key;
		$this->id = $id;
		$this->name = $name;
		$this->label = $label;
		$this->hint = null;

		/* add confirmation dialog for submit buttons */
		if ( $this->popAttr('confirm', $attr, $confirm) ){
			$attr['onclick'] = "return confirm('".htmlspecialchars($confirm, ENT_QUOTES)."');";
		}

		$this->popAttr('hint', $attr, $this->hint);
		$this->popAttr('tworow', $attr, $this->tworow);
		$this->popAttr('fill', $attr, $this->fill);
		$this->popAttr('icon', $attr, $this->icon);
		$this->popAttr('prefix', $attr, $this->prefix);
		$this->popAttr('suffix', $attr, $this->suffix);

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
	protected function popAttr($key, &$attr, &$value){
		if ( array_key_exists($key, $attr) ){
			$value = $attr[$key];
			unset($attr[$key]);
			return true;
		}
		return false;
	}

	public function render($layout, $res) {
		if ( !(array_key_exists('type', $this->attr) && $this->attr['type'] === 'hidden') ){
			$layout->renderField($this, $this->getError($res));
		} else {
			$layout->renderHidden($this);
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getLabel(){
		return $this->label;
	}

	public function getName() {
		return $this->name;
	}

	public function getIcon(){
		return $this->icon;
	}

	public function getAddons(){
		return [$this->prefix, $this->suffix];
	}

	public function getValue(){
		return array_key_exists('value', $this->attr) ? $this->attr['value'] : null;
	}

	public function getContent(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		if ( $attr['type'] == 'password' && isset($attr['autocomplete']) && $attr['autocomplete'] == 'off' ){
			unset($attr['value']);
		}
		return "<input " . $this->serializeAttr($attr) . " />";
	}

	public function getError($res){
		if ( !($this->key && isset($res->errors[$this->key])) ) return false;
		return ucfirst($res->errors[$this->key][0]); /* get first error only */
	}

	public function getHint(){
		return $this->hint;
	}

	public function attribute($key, $default=false){
		return array_key_exists($key, $this->attr) ? $this->attr[$key] : $default;
	}

	public function layoutHints(){
		return
			($this->tworow ? Form::LAYOUT_TWOROWS : 0) |
			($this->fill   ? Form::LAYOUT_FILL : 0 );
	}

	protected function serializeAttr($data=null){
		return FormUtils::serializeAttr($data ?: $this->attr);
	}

	public function setContainer($container){
		$this->container = $container;
	}

	public function getContainer(){
		return $this->container;
	}
}
