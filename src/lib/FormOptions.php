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
	public static function from_array(array $data) {
		$ret = new FormOptions();
		foreach($data as $value => $text) {
			$ret->add($value, $text);
		}
		return $ret;
	}

	/**
	 * Get options from array, applying callback to extract key and value.
	 *
	 * from_array_callback([$a, $b, $c], function($x){
	 *   return [$x->key, $x->value];
	 * });
	 **/
	static public function from_array_callback(array $data, callable $callback) {
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

	public function get_options(){
		return $this->options;
	}

	public function serialize_options($selected){
		return implode("\n", array_map(function($cur) use ($selected) {
			$attr = ['value' => $cur->value];
			if ( $cur->value === $selected ){
				$attr['selected'] = true;
			}
			$sattr = FormUtils::serialize_attr($attr);
			return "<option {$sattr}>{$cur->label}</option>";
		}, $this->options));
	}
}
