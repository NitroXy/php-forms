<?php

namespace NitroXy\PHPForms;

interface FormLayoutInterface {
	public function preamble($form);
	public function postamble($form);
	public function render_group($group, $res);
	public function render_hidden($field);
	public function render_field($field, $error);
	public function render_fieldset($fieldset, $children_cb);
	public function render_hint($field);
	public function render_static($field);
	public function begin();
	public function end();

	/* optional functions */
	// public function layout_name();
}
