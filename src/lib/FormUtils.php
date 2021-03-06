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
	 *
	 * @param $data Data to serializeattr
	 * @param $order Array with predefined order keys should appear
	 * in. Keys not in this array will appear last in any order.
	 */
	public static function serializeAttr(array $data, array $order=[]){
		$attr = [];

		/* convert data to sorted array */
		$sorted = static::sortedAttr($data, $order);

		foreach ( $sorted as list($key, $value) ){
			if ( is_array($value) ){
				/* ignore empty arrays */
				if ( count($value) === 0 ){
					continue;
				}
				foreach ( static::_serializeAttrArray($key, $value) as $sub ){
					$value = htmlspecialchars($sub[1], ENT_QUOTES | ENT_HTML5);
					$attr[] = "{$sub[0]}=\"{$value}\"";
				}
			} else {
				if ( $value === true ){
					$attr[] = "$key";
				} else if ( $value === false ){
					/* ignore */
				} else {
					$value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
					$attr[] = "$key=\"$value\"";
				}
			}
		}
		return implode(' ', $attr);
	}

	protected static function sortedAttr(array $src, array $order){
		$sorted = [];

		/* sort from $order first */
		foreach ( $order as $key ){
			if ( !array_key_exists($key, $src) ) continue;
			$sorted[] = [$key, $src[$key]];
			unset($src[$key]);
		}

		/* append the rest in the order they appear in */
		foreach ( $src as $key => $value ){
			$sorted[] = [$key, $value];
		}

		return $sorted;
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
