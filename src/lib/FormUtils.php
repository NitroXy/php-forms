<?php

namespace NitroXy\PHPForms;

class FormUtils {
	/**
	 * Takes key-value array and serializes them to a string as
	 * 'key="value" foo="bar"'.
	 *
	 * ['foo' => 'bar']            becomes foo="bar".
	 * ['class' => ['foo', 'bar']  becomes class="foo bar"
	 * ['data' => ['foo' => 'bar'] becomes data-foo="bar"
	 */
	public static function serializeAttr($data){
		$attr = [];
		foreach ( $data as $key => $value ){
			if ( is_array($value) ){
				foreach ( static::_serializeAttrArray($key, $value) as $sub ){
					$value = htmlspecialchars($sub[1]);
					$attr[] = "{$sub[0]}=\"{$value}\"";
				}
			} else {
				if ( $value === true ){
					$attr[] = "$key";
				} else if ( $value === false ){
					/* ignore */
				} else {
					$value = htmlspecialchars($value);
					$attr[] = "$key=\"$value\"";
				}
			}
		}
		return implode(' ', $attr);
	}

	protected static function _serializeAttrArray($stem, $data){
		$item = [];

		/* test if assoc or numerical array */
		if ( count(array_filter(array_keys($data), 'is_string')) > 0 ){
			foreach ( $data as $key => $value ){
				if ( is_array($value) ){
					/* recursive */
					foreach ( static::_serializeAttrArray("$stem-$key", $value) as $sub ){
						$item[] = $sub;
					}
				} else {
					$item[] = array("$stem-$key", $value);
				}
			}
		} else {
			$item[] = array($stem, implode(' ', $data));
		}

		return $item;
	}
}
