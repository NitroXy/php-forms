<?php

namespace NitroXy\PHPForms;

interface FormFieldInterface {
	public function render($layout, $res);
	public function layoutHints();
	public function getContent();
	public function getLabel();
	public function getId();
	public function getContainer();
	public function setContainer($container);

	/**
	 * Get attribute from field.
	 *
	 * @return Attribute value or $default if key doesn't exist.
	 */
	public function attribute($key, $default=false);
}
