<?php

namespace NitroXy\PHPForms;

class FormContext {
	protected $fields = array(); /* all fields but hidden */
	protected $hidden = array(); /* only hidden fields */

	public function __construct($form){
		$this->form = $form;
	}

	/**
	 * Generates any kind of input, used by most other fields. Note that
	 * this function should not be called directly, use
	 * <code>custom_field()</code> instead. Common options for all
	 * fields:
	 *
	 * @option 'value' {string} If value is set as an attribute it
	 *         forces the value (irregardless of the object).
	 * @option 'required' {boolean} Set field to required (using HTML
	 *         <code>required</code> attribute).
	 * @option 'prefix' {string} Add a prefix addon.
	 * @option 'suffix' {string} Add a suffix addon.
	 */
	public function factory($type, $key, $label=null, array $attr=array()){
		if ( $label && !is_string($label) ){
			trigger_error("Label must be string");
		}
		list($id, $name, $value) = $this->generate_data($key, $attr);
		switch ( $type ){
		case 'hidden': $field = new FormInput($key, false, $name, $value, 'hidden', false, $attr); break;
		case 'button': $field = new FormButton(false, $id, $name, $label, 'button', false, $attr); break;
		case 'submit': $field = new FormButton(false, $id, $name, $label, 'submit', false, $attr); break;
		case 'textarea': $field = new TextAreaField($key, $id, $name, $value, $label, $attr); break;
		case 'static': $field = new StaticField($value, $label, $attr); break;
		case 'link': $field = new LinkField($label, $attr); break;
		case 'hint': $field = new HintField($key, $label, $attr); break;
		case 'file': $field = new FormInput($key, $id, $name, $value, $type, $label, $attr); break;
		case 'checkbox': $field = new FormCheckbox($key, $id, $name, $value, $type, $label, $attr); break;
		case 'select': $field = new FormSelect($key, $id, $name, $value, $label, $attr); break;
		default: $field = new FormInput($key, $id, $name, $value, $type, $label, $attr); break;
		}

		/* remember containing object */
		$field->set_container($this);

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
	 * Add hidden field. All hiddens are placed at the beginning of the form no matter where used.
	 *
	 * @param $value If set the value is used instead of reading from the resource.
	 */
	public function hidden_field($key, $value=null, array $attr=array()){
		$this->form->hidden_field($key, $value, $attr);
	}

	/**
	 * Regular "text" input.
	 *
	 * @option 'type' {string} HTML type attribute, e.g. <code>email</code> or <code>tel</code>.
	 */
	public function text_field($key, $label=null, array $attr=array()){
		$this->fields[] = $this->factory("text", $key, $label, $attr);
	}


	/**
	 * Password field.
	 */
	public function password_field($key, $label=null, array $attr=array()) {
		$this->fields[] = $this->factory("password", $key, $label, $attr);
	}

	/**
	 * Wrapper around <code>factory</code>.
	 */
	public function custom_field($key, $type, $label=null, array $attr=array()) {
		$this->fields[] = $this->factory($type, $key, $label, $attr);
	}

	/**
	 * Select (dropdown) field. Used in conjunction with <code>FormOptions</code>.
	 *
	 * @param $options Instance of FormOptions.
	 * @option postback {boolean} Automatically submit form when value changes. (default: false)
	 */
	public function select($key, $label, FormOptions $options, array $attr=[]){
		$attr['options'] = $options;
		$this->fields[] = $this->factory('select', $key, $label, $attr);
	}

	/**
	 * Add a help text.
	 */
	public function hint($text, $label=null, array $attr=array()) {
		$this->fields[] = $this->factory("hint", $text, $label, $attr);
	}

	/**
	 * Create a manual field from HTML.
	 *
	 * @param $content Any HTML.
	 */
	public function manual($key, $label, $content, $hint){
		$field = new ManualField($key, $label, $content, $hint);
		$this->fields[] = $field;

		if ( $this->unbuffered() ){
			echo $field->get_content() . "\n";
		}
	}

	/**
	 * File upload field.
	 *
	 * @option remove {boolean} If true a checkbox to remove the current value will be added.
	 * @option current {html} If set to non-false the content will be displayed as the
	 *                 current value, e.g can be set to &lt;img ..&gt; to display the
	 *                 current uploaded image.
	 */
	public function upload_field($key, $label=null, array $attr=array()) {
		$remove = false;
		$current = false;

		if ( array_key_exists('remove', $attr) ){
			$remove = $attr['remove'];
			unset($attr['remove']);
		}

		if ( array_key_exists('current', $attr) ){
			$current = $attr['current'];
			unset($attr['current']);
		}

		$attr['name'] = $key; /* fulhack för att PHP är CP */
		$upload = $this->factory("file", $key, $label, $attr);
		$this->fields[] = $upload;

		if ( $current !== false ){
			$attr = array();
			list($id, $name,) = $this->generate_data($key . '_current', $attr);
			$this->fields[] = new ManualField("{$key}_current", '', "<label>$current</label>", false);
		}

		if ( $remove ){
			$attr = array();
			list($id, $name,) = $this->generate_data($key . '_remove', $attr);
			$this->fields[] = new ManualField("{$key}_remove", '', "<label><input type='checkbox' name='$name' id='$id' value='1' />Ta bort</label>", false);
		}
	}

	/**
	 * Create a field group where all fields is aligned horizontaly,
   * useful for buttons, checkboxes and radiobuttons.
	 *
	 * @param $callback A new rendering context.
	 */
	public function group($label, callable $callback, array $attr=array()){
		if ( $this->unbuffered() ){
			trigger_error("Cannot use Form groups in unbuffered mode", E_USER_ERROR);
		}
		$this->fields[] = new FormGroup($this, $label, $callback, $attr);
	}

	/**
	 * Form fieldset.
	 *
	 * @param $callback A new rendering context.
	 */
	public function fieldset($label, callable $callback){
		if ( $this->unbuffered() ){
			trigger_error("Cannot use Form fieldsets in unbuffered mode", E_USER_ERROR);
		}
		$this->fields[] = new FormFieldset($this, $label, $callback);
	}

	/**
	 * Submit button.
	 *
	 * @option 'confirm' {string} Adds a javascript confirmation prompt before submit/click: <code>onclick="return confirm(...);"</code>
	 */
	public function submit($text, array $attr=[]) {
		$this->fields[] = $this->factory('submit', null, $text, $attr);
	}

	/**
	 * Generic button.
	 *
	 * @option 'type' {string} Should be a valid HTML button value
   *         (e.g. <code>submit</code> or <code>button</code>).
	 */
	public function button($text, array $attr=[]) {
		$this->fields[] = $this->factory('button', null, $text, $attr);
	}

	/**
	 * Display a value from the resource but provides no editable field.
	 */
	public function static_value($key, $label=false, array $attr=array()){
		$this->fields[] = $this->factory('static', $key, $label, $attr);
	}

	/**
	 * Similar to static but provides a link as well.
	 */
	public function link($text, $href, $label=false, array $attr=array()){
		$this->fields[] = $this->factory('link', false, $label, array_merge(array('text' => $text, 'href' => $href), $attr));
	}

	/**
	 * Create textarea.
	 *
	 * @option 'tworow' {boolean} Layout hint to use two rows having the
	 *          label on one row label and the textfield below.
	 * @option 'fill' {boolean} Layout hint used together with
	 *         <code>'tworow'</code> to fill the entire row (not just
	 *         label + content).
	 */
	public function textarea($key, $label=null, array $attr=array()){
		$this->fields[] = $this->factory('textarea', $key, $label, $attr);
	}

	/**
	 * Checkbox field.
	 */
	public function checkbox($key, $text, $label=null, array $attr=array()) {
		$this->hidden_field($key, '0');
		$attr['text'] = $text;
		$this->fields[] = $this->factory('checkbox', $key, $label, $attr);
	}

	/**
	 * Changes the resource object to another object. Used to generate
   * forms for multiple object at the same times. Objects doesn't have
   * to be of the same type but ID must be unique.
	 *
	 * @param $callback A new rendering context.
	 */
	public function fields_for($id, $obj, callable $callback){
		$this->form->fields_for($id, $obj, $callback, $this);
	}

	protected function generate_data($key, array &$attr){
		return $this->form->generate_data($key, $attr);
	}
}
