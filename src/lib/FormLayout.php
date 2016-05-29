<?php

namespace NitroXy\PHPForms;

interface FormLayout {
	public function preamble($form);
	public function postamble($form);
	public function render_group($group, $res);
	public function render_field($field, $error);
	public function render_fieldset($fieldset);
	public function render_hint($field);
	public function begin();
	public function end();

	/* optional functions */
	// public function layout_name();
}
