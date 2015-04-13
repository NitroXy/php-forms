<?php

namespace NitroXy\PHPForms;

interface FormLayout {
	public function render_group($group, $res);
	public function render_field($field, $error);
	public function render_fieldset($fieldset);
	public function render_hint($field);
	public function begin();
	public function end();
}
