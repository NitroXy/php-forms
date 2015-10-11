<?php

namespace NitroXy\PHPForms;

class FormLayoutTable implements FormLayout {
	public $closed = true;

	public function render_field($field, $error){
		if ( $this->closed ){
			$this->begin();
		}

		$label = $field->get_label();
		$content = $field->get_content();
		$hint = $field->get_hint();
		$hints = $field->layout_hints();

		if ( !($hints & Form::LAYOUT_TWOROWS) ){
			echo "		<tr>\n";
			if ( $label !== false ){
				echo "			<th class=\"form-label\">$label</th>\n";
				echo "			<td class=\"form-field\">$content</td>\n";
				echo "			<td class=\"form-hint\" >$hint</td>\n";
				echo "			<td class=\"form-error\">$error</td>\n";
			} else {
				echo "			<td class=\"form-field\" colspan=\"4\">$content</td>\n";
			}
			echo "		</tr>\n";
		} else if ( $hints & Form::LAYOUT_FILL ){
			echo "		<tr>\n";
			echo "			<th class=\"form-label tworow\" colspan=\"2\" valign=\"top\">$label</th>\n";
			echo "			<td class=\"form-hint\"  valign=\"top\">$hint</td>\n";
			echo "			<td class=\"form-error\" valign=\"top\">$error</td>\n";
			echo "		</tr>\n";
			echo "		<tr>\n";
			echo "			<td class=\"form-field\" colspan=\"4\">$content</td>\n";
			echo "		</tr>\n";
		} else {
			echo "		<tr>\n";
			echo "			<th class=\"form-label tworow\" colspan=\"4\">$label</th>\n";
			echo "		</tr>\n";
			echo "		<tr>\n";
			echo "			<td class=\"form-field\" valign=\"top\" colspan=\"2\">$content</td>\n";
			echo "			<td class=\"form-hint\"  valign=\"top\">$hint</td>\n";
			echo "			<td class=\"form-error\" valign=\"top\">$error</td>\n";
			echo "		</tr>\n";
		}
	}

	public function render_group($group, $res){
		$label = $group->get_label();

		echo "		<tr>\n";
		if ( $label !== false ){
			echo "			<th class=\"form-label\">$label</th>\n";
			echo "			<td class=\"form-field\">";
		} else {
			echo "			<td class=\"form-field\" colspan=\"4\">";
		}

		foreach ( $group->children() as $field ){
			echo $field->get_content();
		}

		echo "			</td>\n";
		if ( $label !== false ){
			echo "			<td class=\"form-hint\" ></td>\n";
			echo "			<td class=\"form-error\"></td>\n";
		}
		echo "		</tr>\n";
	}

	public function render_fieldset($fieldset){

	}

	public function render_hint($field){
		$this->render_field($field, false);
	}

	public function begin(){
		$this->closed = false;
		echo "	<table class=\"layout\">\n";
	}

	public function end(){
		if ( $this->closed ) return;
		$this->closed = true;
		echo "	</table>\n";
	}
}