<?php

namespace NitroXy\PHPForms;

class FormLayoutPlain extends FormLayoutBase {
	public function renderField($field, $error){
		$label = $field->getLabel();
		$content = $field->getContent();
		$hint = $field->getHint();
		$required = $field->attribute('required');

		$class = ['form-row'];

		if ( $required ){
			$class[] = 'required';
		}

		$row_attr = FormUtils::serializeAttr(['class' => $class]);

		if ( $field instanceof FormInput ){
			list($prefix, $suffix) = $field->getAddons();
			$have_addon = (boolean)($prefix || $suffix);
			if ( $prefix ) $prefix = "<span class=\"form-prefix\">{$prefix}</span>";
			if ( $suffix ) $suffix = "<span class=\"form-suffix\">{$suffix}</span>";
		} else {
			$have_addon = false;
		}

		echo "<div {$row_attr}>\n";
		if ( $label !== false )  echo "<span class=\"form-label\">$label</span>\n";
		if ( $have_addon ){
			echo "<span class=\"form-field\"><span class=\"form-addon\">{$prefix}{$content}{$suffix}</span></span>\n";
		} else if ( $content !== false ){
			echo "<span class=\"form-field\">$content</span>\n";
		}
		if($error !== false)     echo "<span class=\"form-error\">$error</span>\n";
		if($hint !== false)      echo "<span class=\"form-hint\">$hint</span>\n";
		echo '</div>';
	}

	public function renderHint($field){
		$this->renderField($field, false);
	}

	public function renderStatic($field){
		$this->renderField($field, false);
	}

	public function renderGroup($group, $res){
		$label = $group->getLabel();

		echo '<div class=\"form-group\">';
		if ( $label !== false ) echo "<span class=\"form-label\">$label</span>\n";
		foreach ( $group->children() as $field ){
			echo $field->getContent();
		}
		echo '</div>';
	}

	public function begin(){

	}

	public function end(){

	}
}
