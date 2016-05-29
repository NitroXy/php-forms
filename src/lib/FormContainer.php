<?php

namespace NitroXy\PHPForms;

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
	 * Add hidden field (to parent container).
	 *
	 * @param $value If set it overrides regular value.
	 */
	public function hidden_field($key, $value=null, array $attr=array()){
		$this->form->hidden_field($key, $value, $attr);
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

	/**
	 * File upload field.
	 *
	 * @option remove If true a checkbox to remove the current value will be added.
	 * @option current If set to non-false the content will be displayed as the
	 *                 current value, e.g can be set to <img ..>  to display the
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
			list($id, $name, $value) = $this->generate_data($key . '_current', $attr);
			$this->fields[] = new ManualField("{$key}_remove", '', "<label>$current</label>", false);
		}

		if ( $remove ){
			$attr = array();
			list($id, $name, $value) = $this->generate_data($key . '_remove', $attr);
			$this->fields[] = new ManualField("{$key}_remove", '', "<label><input type='checkbox' name='$name' id='$id' value='1' />Ta bort</label>", false);
		}
	}

	/**
	 * Create a button group where the buttons is aligned horizontaly.
	 * @param label If === false, diable label and have buttons occupy the space.
	 */
	public function group($label, $callback, array $attr=array()){
		if ( $this->unbuffered() ){
			trigger_error("Cannot use Form groups in unbuffered mode", E_USER_ERROR);
		}
		$this->fields[] = new FormGroup($this, $label, $callback, $attr);
	}

	public function fieldset($label, $callback){
		if ( $this->unbuffered() ){
			trigger_error("Cannot use Form fieldsets in unbuffered mode", E_USER_ERROR);
		}
		$this->fields[] = new FormFieldset($this, $label, $callback);
	}

	/**
	 * Submit button.
	 *
	 * @option 'confirm' adds onclick="return confirm(..);"
	 */
	public function submit($text, $key=null, array $attr=array()) {
		$this->fields[] = $this->factory('submit', $key, $text, $attr);
	}

	/**
	 * Generic button.
	 */
	public function button($text, $key=null, array $attr=array()) {
		$this->fields[] = $this->factory('button', $key, $text, $attr);
	}

	public function static_value($key, $label=false, array $attr=array()){
		$this->fields[] = $this->factory('static', $key, $label, $attr);
	}

	public function link($text, $href, $label=false, array $attr=array()){
		$this->fields[] = $this->factory('link', false, $label, array_merge(array('text' => $text, 'href' => $href), $attr));
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
		$this->hidden_field($key, '0');
		$attr['text'] = $text;
		$this->fields[] = $this->factory('checkbox', $key, $label, $attr);
	}

	public function fields_for($id, $obj, $method){
		$this->form->fields_for($id, $obj, $method, $this);
	}

	public function generate_data($key, array &$attr){
		return $this->form->generate_data($key, $attr);
	}
}
