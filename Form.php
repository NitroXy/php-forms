<?php

define('LAYOUT_TWOROWS', 1);
define('LAYOUT_FILL', 2);

/**
 * Takes key-value array and serializes them to a string as
 * 'key="value" foo="bar"'.
 */
function serialize_attr($data){
	$attr = array();
	foreach ( $data as $key => $value ){
		$attr[] = "$key=\"$value\"";
	}
	return implode(' ', $attr);
}

class Form extends FormContainer {
	static private $defaults = array(
		'method' => 'post',      /* form method (get or post) */
		'action' => '',          /* form action */
		'enctype' => false,      /* form encoding */
		'layout' => 'table',     /* layout engine, one of {plain, p, table, unbuffered} or a class extending FormLayout. If unbuffered fields is written directly. */
		'prefix' => false,       /* use a custom prefix in front of all names, default is nothing for arrays and class name for objects */
		'style' => '',           /* add custom style to form, e.g. width */
		'class' => array(),      /* additional classes (accepts string or array) */
	);

	private $id="";
	private $res = null;
	private $name_pattern = '%s';
	private $layout = null;
	private $callback = null;
	private $attr = array('class' => array('form'));
	private $unbuffered = false;

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
		$options = array_merge(static::$defaults, $user);

		/* split classes given as string */
		if ( !is_array($options['class']) ){
			$options['class'] = explode(' ', $options['class']);
		}

		/* required */
		$this->attr['method'] = $options['method'];
		$this->attr['action'] = $options['action'];

		/* optional */
		$this->set_layout($options);
		if (    $options['class'] ) $this->attr['class'] = array_merge($this->attr['class'], $options['class']);
		if (    $options['style'] ) $this->attr['style'] = $options['style'];
		if (   $options['prefix'] ) $this->name_pattern = $options['prefix'] . '[%s]';
		if (  $options['enctype'] ) $this->attr['enctype'] = $options['enctype'];
	}

	private function set_layout($options){
		$layout = $options['layout'];

		if ( isset($options['p']) ){
			$layout = $options['p'] ? 'p' : 'table';
		}

		if ( !is_callable($layout) ){
			switch ( $layout ){
			case 'table': $layout = new FormLayoutTable(); break;
			case 'p': $layout = new FormLayoutP(); break;
			case 'plain': $layout = new FormLayout(); break;
			case 'unbuffered': $this->unbuffered = true; break;
			default:
				trigger_error_caller("Form class called with unknown layout `$layout'", E_USER_NOTICE);
				$layout = new FormLayout();
			}
		}

		$this->layout = $layout;
	}

	protected function unbuffered(){
		return $this->unbuffered;
	}

	private function render(){
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
	public function hidden_field($key, $value=null) {
		$attr = array();
		if ( $value !== null ){
			$attr['value'] = $value;
		}
		$this->hidden[] = $this->factory("hidden", $key, false, $attr);
	}

	public function csrf_token() {
		$this->hidden_field("csrf_token", csrf_token());
	}

	private function start() {
		if($this->res != null && $this->res->has_errors()) {
			$ret = '';
			foreach($this->res->errors as $key => $errors) {
				$prefix = '';
				if ( $key != "base" ) {
					$prefix = "Fel i fältet $key: ";
				}
				foreach($errors as $e) {
					$ret .= "$prefix$e<br />";
				}
			}
			flash('error', $ret);
		}

		/* create array of key=value pairs */
		$attr = array();
		foreach ( $this->attr as $key => $value ){
			if ( is_array($value) ) $value = implode(" ", $value);
			$attr[] = "$key=\"$value\"";
		}
		$attr = implode(' ', $attr);

		echo "<form id=\"{$this->id}\" $attr>\n";
	}

	private function end() {

		if(strtolower($this->attr['method']) != "get") {
			$has_csrf = false;
			foreach( $this->hidden as $field) {
				if($field->get_name() == "csrf_token") {
					$has_csrf = true;
					break;
				}
			}
			if(!$has_csrf) {
				$this->csrf_token();
			}
		}

		if ( $this->unbuffered ){
			echo "</form>\n";
			return;
		}

		/* output all hidden fields */
		foreach ( $this->hidden as $field ){
			echo "	" . $field->get_content() . "\n";
		}

		/* output regular fields */
		foreach ( $this->fields as $field ){
			$field->render($this->layout, $this->res);
		}
		$this->layout->end();

		echo "</form>\n";
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

		/* special case for CSRF token, should always be named as such (no prefix) */
		if ( $key == 'csrf_token' ){
			return 'csrf_token';
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

class FormContainer {
	protected $fields = array(); /* all fields but hidden */
	protected $hidden = array(); /* only hidden fields */

	public function __construct($form){
		$this->form = $form;
	}

	/**
	 * Common options:
	 *
	 * @option 'value' If value is set as an attribute it forces the value (irregardless of the object)
	 */
	public function factory($type, $key, $label=null, array $attr=array()){
		if ( $label && !is_string($label) ){
			trigger_error_caller("Label must be string");
		}
		list($id, $name, $value) = $this->generate_data($key, $attr);
		switch ( $type ){
		case 'hidden': $field = new FormInput($key, false, $name, $value, 'hidden', false, $attr); break;
		case 'submit': $field = new FormInput(false, $id, $name, $label, 'submit', false, $attr); break;
		case 'textarea': $field = new TextAreaField($key, $id, $name, $value, $label, $attr); break;
		case 'hint': $field = new HintField($key, $label, $attr); break;
		default: $field = new FormInput($key, $id, $name, $value, $type, $label, $attr); break;
		}

		if ( $this->unbuffered() ){
			if($field->get_label() !== false) {
				echo "<label for='{$field->get_id()}'>{$field->get_label()}</label>\n";
			}
			echo $field->get_content() . "\n";
		}

		return $field;
	}

	protected function unbuffered(){
		return $this->form->unbuffered();
	}

	/**
	 * Add hidden field (to parent container).
	 *
	 * @param $value If set it overrides regular value.
	 */
	public function hidden_field($key, $value=null) {
		$this->form->hidden_field($key, $value);
	}

	public function text_field($key, $label=null, array $attr=array()){
		$this->fields[] = $this->factory("text", $key, $label, $attr);
	}

	public function password_field($key, $label=null, array $attr=array()) {
		$this->fields[] = $this->factory("password", $key, $label, $attr);
	}

	public function custom_field($key, $type, $label=null, array $attr=array()) {
		$this->fields[] = $this->factory($type, $key, $label, $attr);
	}

	public function select($sel){
		$this->fields[] = $sel;

		if ( $this->unbuffered() ){
			if($sel->get_label() !== false) {
				echo "<label for='{$sel->get_id()}'>{$sel->get_label()}</label>\n";
			}
			echo $sel->get_content()."\n";
		}
	}

	/**
	 * Add a help text.
	 */
	public function hint($text, $label=null, array $attr=array()) {
		$this->fields[] = $this->factory("hint", $text, $label, $attr);
	}

	public function manual($key, $label, $content, $hint){
		$field = new ManualField($key, $label, $content, $hint);
		$this->fields[] = $field;

		if ( $this->unbuffered() ){
			echo $field->get_content() . "\n";
		}
	}

	public function upload_field($key, $label=null, array $attr=array()) {
		$remove = false;
		if ( array_key_exists('remove', $attr) ){
			$remove = $attr['remove'];
			unset($attr['remove']);
		}

		$attr['name'] = $key; /* fulhack för att PHP är CP */
		$upload = $this->factory("file", $key, $label, $attr);
		$this->fields[] = $upload;

		if ( $remove ){
			$attr = array();
			list($id, $name, $value) = $this->generate_data($key . '_remove', $attr);
			$this->fields[] = new ManualField("{$key}_remove", '', "<input type='checkbox' class='checkbox' name='$name' id='$id' value='1' /> <label for='$id'>Ta bort</label>", false);
		}
	}

	/**
	 * Create a button group where the buttons is aligned horizontaly.
	 * @param label If === false, diable label and have buttons occupy the space.
	 */
	public function group($label, $callback, array $attr=array()){
		if ( $this->unbuffered() ){
			trigger_error_caller("Cannot use Form groups in unbuffered mode", E_USER_ERROR);
		}
		$this->fields[] = new FormGroup($this, $label, $callback, $attr);
	}

	public function fieldset($label, $callback){
		if ( $this->unbuffered() ){
			trigger_error_caller("Cannot use Form fieldsets in unbuffered mode", E_USER_ERROR);
		}
		$this->fields[] = new FormFieldset($this, $label, $callback);
	}

	/**
	 * Submit button.
	 *
	 * Use 'class' => 'link-submit' to get a submit button looking like a link
	 *
	 * @option 'confirm' adds onclick="return confirm(..);"
	 */
	public function submit($text, $key=null, array $attr=array()) {
		$this->fields[] = $this->factory('submit', $key, $text, $attr);
	}

	/**
	 * Create textarea.
	 *
	 * @option 'tworow' if true the label appears above the textfield.
	 * @option 'fill' when using tworow layout this fills the entire row (not just label + content)
	 */
	public function textarea($key, $label=null, array $attr=array()){
		$this->fields[] = $this->factory('textarea', $key, $label, $attr);
	}

	public function checkbox($key, $text, $label=null, array $attr=array()) {
		list($id, $name, $value) = $this->generate_data($key, $attr);
		$this->hidden_field($key, '0');
		$field = new ManualField($key, $label, "<input type='checkbox' class='checkbox' name='$name' id='$id' value='1' ".($value?"checked='checked'":"")." /> <label for='$id'>$text</label>", isset($attr['hint']) ? $attr['hint'] : null,$name);
		$this->fields[] = $field;

		if ( $this->unbuffered() ){
			echo $field->get_content() . "\n";
		}
	}

	public function fields_for($id, $obj, $method){
		$this->form->fields_for($id, $obj, $method, $this);
	}

	public function generate_data($key, array &$attr){
		return $this->form->generate_data($key, $attr);
	}
}

class FormData {
	public function __construct($data){
		foreach ( $data as $key => $value ){
			$this->$key = $value;
		}
	}

	public function has_errors(){
		return false;
	}
};

class FormLayout {
	public function add_row($label, $field, $error, $hint){
		if($label !== false) echo "	<span class=\"form-label\">$label</span>\n";
		echo "	<span class=\"form-field\">{$field->get_content()}</span>\n";
		if($error !== false) echo "	<span class=\"form-error\">$error</span>\n";
		if($hint !== false) echo "	<span class=\"form-hint\">$hint</span>\n";
	}

	public function end(){
		/* do nothing */
	}
}

class FormLayoutP extends FormLayout {
	public function add_row($label, $field, $error, $hint){
		echo "	<p>\n";
		if($label !== false) echo "	<span class=\"form-label\">$label</span>\n";
		echo "	<span class=\"form-field\">{$field->get_content()}</span>\n";
		if($error !== false) echo "	<span class=\"form-error\">$error</span>\n";
		if($hint !== false) echo "	<span class=\"form-hint\">$hint</span>\n";
		echo "	</p>\n";
	}
}

class FormLayoutTable extends FormLayout {
	public $closed = true;

	public function add_row($label, $field, $error, $hint){
		if ( $this->closed ){
			$this->closed = false;
			echo "	<table class=\"layout\">\n";
		}

		$hints = $field->layout_hints();

		if ( !($hints & LAYOUT_TWOROWS) ){
			echo "		<tr>\n";
			if ( $label !== false ){
				echo "			<th class=\"form-label\" valign=\"top\">$label</th>\n";
				echo "			<td class=\"form-field\" valign=\"top\">{$field->get_content()}</td>\n";
				echo "			<td class=\"form-hint\"  valign=\"top\">$hint</td>\n";
				echo "			<td class=\"form-error\" valign=\"top\">$error</td>\n";
			} else {
				echo "			<td class=\"form-field\" colspan=\"4\">{$field->get_content()}</td>\n";
			}
			echo "		</tr>\n";
		} else if ( $hints & LAYOUT_FILL ){
			echo "		<tr>\n";
			echo "			<th class=\"form-label tworow\" colspan=\"2\" valign=\"top\">$label</th>\n";
			echo "			<td class=\"form-hint\"  valign=\"top\">$hint</td>\n";
			echo "			<td class=\"form-error\" valign=\"top\">$error</td>\n";
			echo "		</tr>\n";
			echo "		<tr>\n";
			echo "			<td class=\"form-field\" colspan=\"4\">{$field->get_content()}</td>\n";
			echo "		</tr>\n";
		} else {
			echo "		<tr>\n";
			echo "			<th class=\"form-label tworow\" colspan=\"4\">$label</th>\n";
			echo "		</tr>\n";
			echo "		<tr>\n";
			echo "			<td class=\"form-field\" valign=\"top\" colspan=\"2\">{$field->get_content()}</td>\n";
			echo "			<td class=\"form-hint\"  valign=\"top\">$hint</td>\n";
			echo "			<td class=\"form-error\" valign=\"top\">$error</td>\n";
			echo "		</tr>\n";
		}
	}

	public function end(){
		if ( $this->closed ) return;
		$this->closed = true;
		echo "	</table>\n";
	}
}

interface FormField {
	public function render($layout, $res);
	public function layout_hints();
	public function get_content();
	public function get_label();
	public function get_id();
}

class FormGroup extends FormContainer implements FormField {
	private $label;
	private $hint = false;

	public function __construct($form, $label, $callback, $attr){
		parent::__construct($form);
		$this->label = $label;

		if ( array_key_exists('hint', $attr) ){
			$this->hint = $attr['hint'];
			unset($attr['hint']);
		}

		$callback($this);
	}

	public function render($layout, $res){
		$layout->add_row(
			$this->get_label(),
			$this,
			$this->get_error($res),
			$this->hint);
	}

	public function get_label(){
		return $this->label;
	}

	public function get_error($res){
		foreach ( $this->fields as $field ){
			if ( ($error=$field->get_error($res)) ){
				return $error;
			}
		}
		return false;
	}

	public function get_content(){
		return implode('', array_map(function($f) { return '<span class="form-group">' . $f->get_content() . '</span>'; }, $this->fields));
	}

	public function layout_hints(){
		return 0;
	}

	public function get_id() { return false; }
}

class FormFieldset extends FormContainer implements FormField {
	private $label;

	public function __construct($form, $label, $callback){
		parent::__construct($form);
		$this->label = $label;
		$callback($this);
	}

	public function render($layout, $res){
		/* do nothing if there is no fields */
		if ( count($this->fields) == 0 ){
			return;
		}

		echo "	<fieldset>\n";
		if ( $this->label ){
			echo "		<legend>{$this->label}</legend>\n";
		}
		foreach ( $this->fields as $field){
			$field->render($layout, $res);
		}
		$layout->end();

		echo "	</fieldset>\n";
	}

	public function layout_hints(){
		return 0;
	}

	public function get_label() { return false; }
	public function get_content() { return false; }
	public function get_id() { return false; }
}

class FormInput implements FormField {
	private $key;
	private $id;
	private $name;
	private $label;
	private $tworow = 0;
	private $fill = 0;

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

		if (  $type != null ) $attr['type'] = $type;
		if ( $value !== null ) $attr['value'] = $value;
		if (    $id != null ) $attr['id'] = $id;
		if (  $name != null ) $attr['name'] = $name;
		$this->attr = $attr;
	}

	/**
	 * Read and remove $key from attribute array.
	 * @return true if attribute existed.
	 */
	private function pop_attr($key, &$attr, &$value){
		if ( array_key_exists($key, $attr) ){
			$value = $attr[$key];
			unset($attr[$key]);
			return true;
		}
		return false;
	}

	public function render($layout, $res) {
		$layout->add_row(
			$this->get_label(),
			$this,
			$this->get_error($res),
			$this->get_hint());
	}

	public function get_id() {
		return $this->id;
	}

	public function get_label(){
		if ( !$this->label ) return false;
		return "<label for=\"{$this->id}\">{$this->label}</label>";
	}

	public function get_name() {
		return $this->name;
	}

	public function get_content(){
		return "<input " . $this->serialize_attr() . " />";
	}

	public function get_error($res){
		if ( !($this->key && isset($res->errors[$this->key])) ) return false;
		return ucfirst($res->errors[$this->key][0]); /* get first error only */
	}

	private function get_hint(){
		return $this->hint;
	}

	public function layout_hints(){
		return
			($this->tworow ? LAYOUT_TWOROWS : 0) |
			($this->fill ? LAYOUT_FILL : 0 );
	}

	protected function serialize_attr($data=null){
		return serialize_attr($data ?: $this->attr);
	}
}

class TextAreaField extends FormInput {
	private $value = '';

	public function __construct($key, $id, $name, $value, $label, $attr) {
		parent::__construct($key, $id, $name, null, null, $label, $attr);
		$this->value = $value;
	}

	public function get_content(){
		return "<textarea " . $this->serialize_attr() . " >{$this->value}</textarea>";
	}
}

class HintField implements FormField {
	private $text = null;
	private $label = null;
	private $attr = array('class' => 'form-hint');

	public function __construct($text, $label, $attr){
		$this->text = $text;
		$this->label = $label;
		$this->attr = array_merge($this->attr, $attr);
	}

	public function render($layout, $res) {
		$layout->add_row(
			$this->get_label(),
			$this,
			false,
			false);
	}

	public function get_content(){
		return '<p ' . serialize_attr($this->attr) . ">{$this->text}</p>";
	}

	public function get_label(){ return $this->label; }
	public function layout_hints(){ return 0; }
	public function get_id() { return false; }
}

class ManualField implements FormField {
	private $key = null;
	private $label = null;
	private $content = null;
	private $hint = null;

	public function __construct($key, $label, $content, $hint){
		$this->key = $key;
		$this->label = $label;
		$this->content = $content;
		$this->hint = $hint;
	}

	public function render($layout, $res) {
		$layout->add_row(
			$this->get_label(),
			$this,
			$this->get_error($res),
			$this->get_hint());
	}

	public function get_error($res){
		if ( !($this->key && isset($res->errors[$this->key])) ) return false;
		return ucfirst($res->errors[$this->key][0]); /* get first error only */
	}

	public function get_label(){ return $this->label; }
	public function get_content(){ return $this->content; }
	public function get_hint(){ return $this->hint; }
	public function layout_hints(){ return 0; }
	public function get_id() { return false; }
}
