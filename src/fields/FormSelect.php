<?php

namespace NitroXy\PHPForms;

class FormSelect extends FormInput {
	private $selected;
	private $options = null;

	/**
	 * Create a selection input field.
	 *
	 * @option 'postback' if true it will submit the form when changed.
	 * @option 'selected' forces key to be selected.
	 */
	public function __construct($key, $id, $name, $value, $label=null, array $attr=[]){
		/* if postback is enabled, add onchange which submits the forrm */
		if ( $this->popAttr('postback', $attr, $postback) && $postback ){
			$attr['onchange'] = 'this.form.submit();';
		}

		$this->selected = $value;
		$this->popAttr('selected', $attr, $this->selected);
		$this->popAttr('options', $attr, $this->options);

		/* fill with empty options */
		if ( $this->options === null ){
			$this->options = new FormOptions();
		}

		parent::__construct($key, $id, $name, $value, null, $label, $attr);

		/* FormInput sets value which select shouldn't have */
		unset($this->attr['value']);
	}

	public function getOptions(){
		return $this->options->getOptions();
	}

	public function getValue(){
		return $this->selected;
	}

	public function getContent(array $extra_attr = array()){
		$attr = array_merge_recursive($extra_attr, $this->attr);
		$sattr = $this->serializeAttr($attr, ['name', 'id']);
		$soptions = $this->options->serializeOptions($this->selected);
		return "<select {$sattr}>\n{$soptions}\n</select>";
	}
}
