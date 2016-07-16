<?php

namespace NitroXy\PHPForms;

interface FormLayoutInterface {
	public function preamble($form);
	public function postamble($form);
	public function renderGroup($group, $res);
	public function renderHidden($field);
	public function renderField($field, $error);
	public function renderFieldset($fieldset, $children_cb);
	public function renderHint($field);
	public function renderStatic($field);
	public function begin();
	public function end();

	/* optional functions */
	// public function layoutName();
}
