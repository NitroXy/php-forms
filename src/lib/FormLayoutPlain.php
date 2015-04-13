<?php

namespace NitroXy\PHPForms;

class FormLayoutPlain implements FormLayout {
	public function render_field($field, $error){
		$label = $field->get_label();
		$content = $field->get_content();
		$hint = $field->get_hint();

		echo '<div class="form-row">';
		if ( $label !== false )  echo "<span class=\"form-label\">$label</span>\n";
		if ( $content !== false) echo "<span class=\"form-field\">$content</span>\n";
		if($error !== false)     echo "<span class=\"form-error\">$error</span>\n";
		if($hint !== false)      echo "<span class=\"form-hint\">$hint</span>\n";
		echo '</div>';
	}

	public function render_hint($field){
		$this->render_field($field, false);
	}

	public function render_group($group, $res){
		$label = $group->get_label();

		echo '<div class=\"form-group\">';
		if ( $label !== false ) echo "<span class=\"form-label\">$label</span>\n";
		foreach ( $group->children() as $field ){
			echo $field->get_content();
		}
		echo '</div>';
	}

	public function render_fieldset($fieldset){

	}

	public function begin(){

	}

	public function end(){

	}
}
