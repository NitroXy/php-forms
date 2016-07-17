<?php

namespace NitroXy\PHPForms;

class Form extends FormContext {
	const LAYOUT_TWOROWS =  1;
	const LAYOUT_FILL = 2;

	static public $defaultBuilder = FormBuilder::class;

	static protected $base_options = array(
		'method' => 'post',                  /* form method (get or post) */
		'method_field_name' => '_method',    /* name of the method field when method isn't GET or POST. */
		'action' => '',                      /* form action (if set to false no <form> wrapper will be generated (you must manually set it) */
		'layout' => 'table',                 /* layout engine, one of {plain, table, bootstrap, unbuffered} or a class extending FormLayout. If unbuffered fields is written directly. */
		'prefix' => false,                   /* use a custom prefix in front of all names, default is nothing for arrays and class name for objects */
		'class' => [],                       /* additional classes (accepts string or array) */
	);

	public $id = "";
	public $attr = ['class' => ['form']];

	private $res = null;
	private $name_pattern = '%s';
	private $layout = null;
	private $callback = null;
	private $options = [];
	private $unbuffered = false;

	public function __construct() {
		$builderClass = static::$defaultBuilder;
		$builder = new $builderClass;
		$builder->setContext($this);
		parent::__construct($this, $builder);
	}

	/**
	 * Override to set defaults for subclassed form.
	 * Should return an array with options. See $base_options.
	 */
	static protected function defaultOptions(){
		return [];
	}

	/**
	 * Override to support CSRF tokens.
	 * It should return a string with the CSRF token or false to disable. Tokens
	 * is automatically appended to all forms but it will not validate it (user
	 * must check for presence and validate)
	 */
	static protected function csrfToken(){
		return false;
	}

	/**
	 * Create a form bound to an key-value array.
	 */
	static public function fromArray($id, array $array=null, callable $callback, array $options=[]){
		$form = static::createInstance(false, null);
		$form->parseOptions($options);
		$form->callback = $callback;
		$form->id = $id;
		$form->attr['id'] = $form->id;
		$form->res = new FormData($array);
		$form->render();
	}

	/**
	 * Create a form bound to a BasicObject.
	 *
	 * Name will use class name as prefix, e.g name="Foo[field]".
	 */
	static public function fromObject($obj, callable $callback, array $options=[]){
		$form = static::createInstance(false, null);
		$form->setNamePattern($obj);
		$form->parseOptions($options);
		$form->callback = $callback;
		$form->id = get_class($obj);
		$form->attr['id'] = $form->id;
		$form->attr['class'][] = get_class($obj);
		$form->res = $obj ? $obj : new FormData();

		/* use a unique html id if the object has an id, makes it possible to use form for multiple objects of same type */
		if ( !empty($obj->id) ){
			$form->id .= '_' . $obj->id;
		}

		$empty = [];
		$id = $form->getValue('id', $empty);
		if ( !empty($id) ){
			$form->hiddenField("id");
		}
		$form->render();
	}

	/**
	 * Create a resource-less form.
	 */
	static public function create($id, $callback, array $options=[]){
		$form = static::createInstance(false, null);
		$form->parseOptions($options);
		$form->callback = $callback;
		$form->id = $id;
		$form->attr['id'] = $form->id;
		$form->res = null;
		$form->render();
	}

	/**
	 * Creates an instance of the called form class.
	 * Just like "new Form()" but works with late static binding so an inherited
	 * class can call "MyForm::fromObject(..)" and still get a MyForm instance.
	 */
	static private function createInstance($id, $callback, array $options=[]){
		$classname = get_called_class();
		return new $classname($id, $callback, $options);
	}

	protected function popAttr($key, &$attr, &$value){
		if ( array_key_exists($key, $attr) ){
			$value = $attr[$key];
			unset($attr[$key]);
			return true;
		}
		return false;
	}

	private function parseOptions($user){
		$options = array_merge(
			static::$base_options,
			static::defaultOptions(),
			$user
		);

		/* extract non-attribute options */
		$this->popAttr('method_field_name', $options, $this->options['method_field_name']);

		/* layout */
		$this->popAttr('layout', $options, $layout);
		$this->setLayout($layout);

		/* rewrite requested action to GET/POST and store original method */
		$this->popAttr('method', $options, $method);
		$this->options['requested_method'] = $method;
		$this->attr['method'] = static::parseMethod($method);

		/* custom prefix */
		$this->popAttr('prefix', $options, $prefix);
		if ( $prefix ){
			$this->setNamePattern($prefix);
		}

		/* classes require deeper merge: classes has already been added */
		$this->popAttr('class', $options, $class);
		$this->addClass($class);

		/* merge form attributes */
		$attr = [];
		$this->popAttr('attr', $options, $attr);
		$this->attr = array_merge(
			$this->attr,
			$options,
			$attr
		);
	}

	protected function setNamePattern($prefix){
		if ( is_object($prefix) ){
			$this->name_pattern = get_class($prefix) . '[%s]';
		} else if ( is_string($prefix) ){
			if ( strstr($prefix, '%s') === false ){
				$this->name_pattern = $prefix . '[%s]';
			} else {
				$this->name_pattern = $prefix;
			}
		} else {
			$this->name_pattern = '%s';
		}
	}

	protected function addClass($class){
		if ( is_string($class) ){
			$class = explode(' ', $class);
		}
		$this->attr['class'] = array_merge($this->attr['class'], $class);
	}

	private function parseMethod($method){
		$method = strtoupper($method);
		switch ( $method ){
			case 'GET':
			case 'POST':
				return $method;
			default:
				return 'POST';
		}
	}

	private function setLayout($layout){
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
		} else if ( !$layout instanceof FormLayoutInterface ){
			trigger_error("Layout must either be string or a class implementing FormLayout", E_USER_ERROR);
		} else {
			if ( method_exists($layout, 'layoutName') ){
				$class = $layout->layoutName();
			} else {
				$class = get_class($layout);
			}
		}

		/* use layout class so it is possible to style a single layout */
		$this->addClass($class);

		$this->layout = $layout;
	}

	public function unbuffered(){
		return $this->unbuffered;
	}

	protected function render(){
		$cb = $this->callback;

		$this->start();
		$this->apply($cb);
		$this->end();
	}

	/**
	 * This creates a subform.
	 * Example:
	 * new Form("foo", function($f) {
	 *	 $f->fieldsFor($object_type_derp, function($fo) {
	 *		$fo->textField('moo');
	 *	 });
	 *	 $f->fieldsFor($object_type_herp, function($fo) {
	 *		$fo->textField('boo');
	 *	 });
	 *	});
	 *
	 * This would generate the form with id "foo" and the fields
	 * Derp[moo] (textField)
	 * Herp[boo] (textField)
	 * (given that Derp and Herp are the class-name of the models given to fieldsFor
	 */
	public function fieldsFor($id, $obj, callable $callback) {
		$old = [$this->res, $this->id, $this->name_pattern];
		$this->id = $id;
		$this->name_pattern = $id . '[%s]';
		if ( $obj instanceof \BasicObject ) {
			$this->res = $obj;
			$this->hiddenField("id");
		} else {
			$this->res = new FormData($obj);
		}
		$this->apply($callback);
		list($this->res, $this->id, $this->name_pattern) = $old;
	}

	/**
	 * Overridden hidden so child-containers can call this.
	 */
	public function hiddenField($key, $value=null, array $attr=[]) {
		if ( $value !== null ){
			$attr['value'] = $value;
		}
		$this->hidden[] = $this->builder->factory("hidden", $key, false, $attr);
	}

	public function methodField() {
		$this->hiddenField($this->options['method_field_name'], strtoupper($this->options['requested_method']));
	}

	protected function start() {
		if ( $this->unbuffered ){
			if ( $this->attr['action'] !== false ){
				$attr = FormUtils::serializeAttr($this->attr);
				echo "<form $attr>\n";
			}
			return;
		}

		if ( $this->attr['action'] !== false ){
			$this->layout->preamble($this);
		}
	}

	protected function end() {
		$method = strtoupper($this->options['requested_method']);
		if ( $method !== "GET" ) {
			$has_csrf = false;
			foreach( $this->hidden as $field) {
				if($field->getName() == "csrf_token") {
					$has_csrf = true;
					break;
				}
			}

			if ( !$has_csrf && ($csrf_token=$this->csrfToken()) ) {
				$this->hiddenField("csrf_token", $csrf_token);
			}

			/* use a special _method field to allow using other HTTP methods
			 * such as PATCH and DELETE. */
			if ( $method !== 'POST' ){
				$this->methodField();
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

	private function getValue($key, array &$attr) {
		if ( array_key_exists('value', $attr) ){
			$value = $attr['value'];
			unset($attr['value']);
			return $value;
		}

		if ( !isset($this->res->$key) ) return null;
		return $this->res->$key;
	}

	public function generateId($key, array &$attr){
		if ( array_key_exists('id', $attr) ){
			$id = $attr['id'];
			unset($attr['id']);
			return $id;
		}

		if ( empty($key) ) return null;
		return "{$this->id}_$key";
	}

	public function generateName($key, array &$attr){
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

	public function generateData($key, array &$attr){
		$id = $this->generateId($key, $attr);
		$name = $this->generateName($key, $attr);
		$value = $this->getValue($key, $attr);
		return array($id, $name, $value);
	}
}
