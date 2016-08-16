<?php

namespace NitroXy\PHPForms;

class FormOptions implements \IteratorAggregate {
	protected $options = [];

	public function getIterator() {
		return $this->options;
	}

	/**
	 * Get options from assoc array.
	 */
	public static function fromArray(array $data) {
		$ret = new FormOptions();
		foreach($data as $value => $text) {
			$ret->add($value, $text);
		}
		return $ret;
	}

	/**
	 * Get options from array, applying callback to extract key and value.
	 *
	 * fromArrayCallback([$a, $b, $c], function($x){
	 *   return [$x->key, $x->value];
	 * });
	 **/
	static public function fromArrayCallback(array $data, callable $callback) {
		$ret = new FormOptions();
		foreach ( $data as $item ){
			list($value, $text) = $callback($item);
			$ret->add($value, $text);
		}
		return $ret;
	}

	/**
	 * Manually add new option.
	 */
	public function add($value, $label) {
		$this->options[] = (object)[
			'label' => $label,
			'value' => $value,
		];
	}

	public function getOptions(){
		return $this->options;
	}

	public function serializeOptions($selected){
		return implode("\n", array_map(function($cur) use ($selected) {
			$attr = ['value' => $cur->value];
			if ( $cur->value == $selected ){
				$attr['selected'] = true;
			}
			$sattr = FormUtils::serializeAttr($attr);
			$label = htmlspecialchars($cur->label);
			return "<option {$sattr}>{$label}</option>";
		}, $this->options));
	}
}
