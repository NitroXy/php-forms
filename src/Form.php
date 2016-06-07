<?php

namespace NitroXy\PHPForms;

class Form extends FormContainer {
	const LAYOUT_TWOROWS =  1;
	const LAYOUT_FILL = 2;

	static protected $base_options = array(
		'method' => 'post',                  /* form method (get or post) */
		'method_field_name' => '_method',    /* name of the method field when method isn't GET or POST. */
		'action' => '',                      /* form action (if set to false no <form> wrapper will be generated (you must manually set it) */
		'enctype' => false,                  /* form encoding */
		'layout' => 'table',                 /* layout engine, one of {plain, table, bootstrap, unbuffered} or a class extending FormLayout. If unbuffered fields is written directly. */
		'prefix' => false,                   /* use a custom prefix in front of all names, default is nothing for arrays and class name for objects */
		'style' => '',                       /* add custom style to form, e.g. width */
		'class' => [],                       /* additional classes (accepts string or array) */
		'data' => [],                        /* extra data attributes */
		'attr' => [],                        /* extra arbitrary attributes */
	);

	public $id = "";
	public $attr = ['class' => ['form']];

	private $res = null;
	private $name_pattern = '%s';
	private $layout = null;
	private $callback = null;
	private $options = null;
	private $unbuffered = false;

	/**
	 * Override to set defaults for subclassed form.
	 * Should return an array with options. See $base_options.
	 */
	static protected function default_options(){
		return array();
	}

	/**
	 * Override to support CSRF tokens.
	 * It should return a string with the CSRF token or false to disable. Tokens
	 * is automatically appended to all forms but it will not validate it (user
	 * must check for presence and validate)
	 */
	static protected function csrf_token(){
		return false;
	}

	/**
	 * Create a form bound to an key-value array.
	 */
	static public function from_array($id, $array, $callback, array $options=array()){
		$form = static::create_instance(false, null);
		$form->parse_options($options);
		$form->callback = $callback;
		$form->id = $id;
		$form->res = new FormData($array);
		$form->render();
	}

	/**
	 * Create a form bound to a BasicObject.
	 *
	 * Name will use class name as prefix, e.g name="Foo[field]".
	 */
	static public function from_object($obj, $callback, array $options=array()){
		$form = static::create_instance(false, null);
		$form->parse_options($options);
		$form->callback = $callback;
		$form->id = get_class($obj);
		$form->attr['class'][] = get_class($obj);
		$form->res = $obj;

		/* use a unique html id if the object has an id, makes it possible to use form for multiple objects of same type */
		if ( !empty($obj->id) ){
			$form->id .= '_' . $obj->id;
		}

		if ( !isset($options['prefix']) ){
			$form->name_pattern = get_class($obj) . '[%s]';
		}

		/** @todo lookup real field name */
		$empty = array();
		$id = $form->get_value('id', $empty);
		if ( !empty($id) ){
			$form->hidden_field("id");
		}
		$form->render();
	}

	/**
	 * Create a resource-less form.
	 */
	static public function create($id, $callback, array $options=array()){
		$form = static::create_instance(false, null);
		$form->parse_options($options);
		$form->callback = $callback;
		$form->id = $id;
		$form->res = null;
		$form->render();
	}

	/**
	 * Creates an instance of the called form class.
	 * Just like "new Form()" but works with late static binding so an inherited
	 * class can call "MyForm::from_object(..)" and still get a MyForm instance.
	 */
	static private function create_instance($id, $callback, array $options=array()){
		$classname = get_called_class();
		return new $classname($id, $callback, $options);
	}

	/**
	 * Skapar ett formulär.
	 *
	 * $x = User::from_id(123);
	 * new Form($x, function($f){
	 *   $f->text_field('name');
	 * }, array(...));
	 *
	 * @param id BasicObject instance or a unique string.
	 * @param callback En funktion som lägger till fält.
	 * @param options
	 **/
	public function __construct($id, $callback, array $options=array()) {
		parent::__construct($this);

		if ( $id === false ) return; /* called using from_{array,object} */
		deprecated("Calling Form without explicit call to `from_object' or `from_array' is deprecated.");

		$this->parse_options($options);
		$this->callback = $callback;

		if($id instanceof BasicObject) {
			$this->id=get_class($id);
			$this->res = $id;
			$this->hidden_field("id");
		} else {
			$this->id=$id;
		}

		$this->render();
	}

	private function parse_options($user){
		$options = array_merge(static::$base_options, static::default_options(), $user);

		/* split classes given as string */
		if ( !is_array($options['class']) ){
			$options['class'] = explode(' ', $options['class']);
		}

		/* store raw options */
		$this->options = $options;

		/* required */
		$this->attr['method'] = static::parse_method($options['method']);
		$this->attr['action'] = $options['action'];

		/* optional */
		$this->set_layout($options);
		if (    $options['class'] ) $this->attr['class'] = array_merge($this->attr['class'], $options['class']);
		if (    $options['style'] ) $this->attr['style'] = $options['style'];
		if (   $options['prefix'] ) $this->name_pattern = $options['prefix'] . '[%s]';
		if (  $options['enctype'] ) $this->attr['enctype'] = $options['enctype'];
		if (     $options['data'] ) $this->attr['data'] =  $options['data'];

		/* arbitrary options */
		if ( $options['attr'] ){
			$this->attr = array_merge($this->attr, $options['attr']);
		}
	}

	private function parse_method($method){
		$method = strtoupper($method);
		switch ( $method ){
			case 'GET':
			case 'POST':
				return $method;
			default:
				return 'POST';
		}
	}

	private function set_layout($options){
		$layout = $options['layout'];
		$class = $layout;

		if ( is_string($layout) ){
			switch ( $layout ){
			case 'table': $layout = new FormLayoutTable(); break;
			case 'plain': $layout = new FormLayoutPlain(); break;
			case 'bootstrap': $layout = new FormLayoutBootstrap(); break;
			case 'unbuffered': $this->unbuffered = true; break;
			default:
				trigger_error("Form class called with unknown layout `$layout'", E_USER_NOTICE);
				$layout = new FormLayoutPlain();
			}
		} else if ( !$layout instanceof FormLayout ){
			trigger_error("Layout must either be string or a class implementing FormLayout", E_USER_ERROR);
		} else {
			if ( method_exists($layout, 'layout_name') ){
				$class = $layout->layout_name();
			} else {
				$class = get_class($layout);
			}
		}

		/* use layout class so it is possible to style a single layout */
		$this->attr['class'] = array_merge($this->attr['class'], array($class));

		$this->layout = $layout;
	}

	protected function unbuffered(){
		return $this->unbuffered;
	}

	protected function render(){
		$cb = $this->callback;

		$this->start();
		$cb($this);
		$this->end();
	}

	/**
	 * This creates a subform.
	 * Example:
	 * new Form("foo", function($f) {
	 *	 $f->fields_for($object_type_derp, function($fo) {
	 *		$fo->text_field('moo');
	 *	 });
	 *	 $f->fields_for($object_type_herp, function($fo) {
	 *		$fo->text_field('boo');
	 *	 });
	 *	});
	 *
	 * This would generate the form with id "foo" and the fields
	 * Derp[moo] (text_field)
	 * Herp[boo] (text_field)
	 * (given that Derp and Herp are the class-name of the models given to fields_for
	 */
	public function fields_for($id, $obj, $method, $target=null) {
		$old = array($this->res, $this->id, $this->name_pattern);
		$this->id = $id;
		$this->name_pattern = $id . '[%s]';
		if ( $obj instanceof BasicObject ) {
			$this->res = $obj;
			$this->hidden_field("id");
		} else {
			$this->res = new FormData($obj);
		}
		$method($target ?: $this);
		list($this->res, $this->id, $this->name_pattern) = $old;
	}

	/**
	 * Overridden hidden so child-containers can call this.
	 */
	public function hidden_field($key, $value=null, array $attr=array()) {
		if ( $value !== null ){
			$attr['value'] = $value;
		}
		$this->hidden[] = $this->factory("hidden", $key, false, $attr);
	}

	public function method_field() {
		$this->hidden_field($this->options['method_field_name'], strtoupper($this->options['method']));
	}

	protected function start() {
		if ( $this->unbuffered ){
			if ( $this->attr['action'] !== false ){
				$attr = FormUtils::serialize_attr($this->attr);
				echo "<form id=\"{$this->id}\" $attr>\n";
			}
			return;
		}

		if ( $this->attr['action'] !== false ){
			$this->layout->preamble($this);
		}
	}

	protected function end() {
		$method = strtoupper($this->options['method']);
		if ( $method !== "GET" ) {
			$has_csrf = false;
			foreach( $this->hidden as $field) {
				if($field->get_name() == "csrf_token") {
					$has_csrf = true;
					break;
				}
			}

			if ( !$has_csrf && ($csrf_token=$this->csrf_token()) ) {
				$this->hidden_field("csrf_token", $csrf_token);
			}

			/* use a special _method field to allow using other HTTP methods
			 * such as PATCH and DELETE. */
			if ( $method !== 'POST' ){
				$this->method_field();
			}
		}

		if ( $this->unbuffered ){
			if ( $this->attr['action'] !== false ){
				echo "</form>\n";
			}
			return;
		}

		/* output all hidden fields */
		foreach ( $this->hidden as $field ){
			$field->render($this->layout, $this->res);
		}

		/* output regular fields */
		foreach ( $this->fields as $field ){
			$field->render($this->layout, $this->res);
		}
		$this->layout->end();

		if ( $this->attr['action'] !== false ){
			$this->layout->postamble($this);
		}
	}

	private function get_value($key, array &$attr) {
		if ( array_key_exists('value', $attr) ){
			$value = $attr['value'];
			unset($attr['value']);
			return $value;
		}

		if ( !isset($this->res->$key) ) return null;
		return $this->res->$key;
	}

	public function generate_id($key, array &$attr){
		if ( array_key_exists('id', $attr) ){
			$id = $attr['id'];
			unset($attr['id']);
			return $id;
		}

		if ( empty($key) ) return null;
		return "{$this->id}_$key";
	}

	public function generate_name($key, array &$attr){
		if ( array_key_exists('name', $attr) ){
			$name = $attr['name'];
			unset($attr['name']);
			return $name;
		}

		/* special cases for fields (like CSRF token) which should always
		 * be named as such (no prefix) */
		if ( $key === 'csrf_token' || $key === $this->options['method_field_name'] ){
			return $key;
		}

		if ( empty($key) ) return null;
		return sprintf($this->name_pattern, $key);
	}

	public function generate_data($key, array &$attr){
		$id = $this->generate_id($key, $attr);
		$name = $this->generate_name($key, $attr);
		$value = $this->get_value($key, $attr);
		return array($id, $name, $value);
	}
}

class TextAreaField extends FormInput {
	private $value = '';

	public function __construct($key, $id, $name, $value, $label, $attr) {
		parent::__construct($key, $id, $name, null, null, $label, $attr);
		$this->value = $value;
	}

	public function get_content(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		$value = htmlspecialchars($this->value);
		return "<textarea " . $this->serialize_attr($attr) . " >{$value}</textarea>";
	}
}

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

class HintField implements FormField {
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
	public function layout_hints(){ return 0; }
	public function get_id() { return false; }
	public function set_container($container){ $this->container = $container; }
	public function get_container(){ return $this->container; }

	public function attribute($key, $default=false){
		return $default; /* no sane way to get attributes from manual fields */
	}
}
