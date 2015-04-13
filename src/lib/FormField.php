<?php

namespace NitroXy\PHPForms;

interface FormField {
	public function render($layout, $res);
	public function layout_hints();
	public function get_content();
	public function get_label();
	public function get_id();
	public function get_container();
}
