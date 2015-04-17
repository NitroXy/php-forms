<?php

namespace NitroXy\PHPForms;

interface FormField {
	public function render($layout, $res);
	public function layout_hints();
	public function get_content();
	public function get_label();
	public function get_id();
	public function get_container();

	/**
	 * Get attribute from field.
	 *
	 * @return Attribute value or $default if key doesn't exist.
	 */
	public function attribute($key, $default=false);
}
