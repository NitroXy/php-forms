<?php

namespace NitroXy\PHPForms;
use NitroXy\PHPForms\FormUtils;

class FormLayoutTable extends FormLayoutBase {
	public $closed = true;

	public function render_field($field, $error){
		$this->begin();

		$id = $field->get_id();
		$label = $field->get_label();
		$content = $field->get_content();
		$hint = $field->get_hint();
		$hints = $field->layout_hints();
		$required = $field->attribute('required');
		$tr_class = FormUtils::serialize_attr($required ? ['class' => 'required'] : []);

		if ( $field instanceof FormCheckbox ){
			echo "		<tr {$tr_class}>\n";
			if ( $label !== false ){
				echo "			<th class=\"form-label\"><label for=\"$id\">{$field->get_text()}</label></th>\n";
				echo "			<td class=\"form-field\">$content</td>\n";
				echo "			<td class=\"form-hint\" >$hint</td>\n";
				echo "			<td class=\"form-error\">$error</td>\n";
			} else {
				echo "			<td class=\"form-field\" colspan=\"4\"><label>{$content} {$field->get_text()}</label></td>\n";
			}
			echo "		</tr>\n";
			return;
		}

		if ( !($hints & Form::LAYOUT_TWOROWS) ){
			echo "		<tr {$tr_class}>\n";
			if ( $label !== false ){
				echo "			<th class=\"form-label\"><label for=\"$id\">$label</label></th>\n";
				echo "			<td class=\"form-field\">$content</td>\n";
				echo "			<td class=\"form-hint\" >$hint</td>\n";
				echo "			<td class=\"form-error\">$error</td>\n";
			} else {
				echo "			<td class=\"form-field\" colspan=\"4\">$content</td>\n";
			}
			echo "		</tr>\n";
		} else if ( $hints & Form::LAYOUT_FILL ){
			echo "		<tr {$tr_class}>\n";
			echo "			<th class=\"form-label tworow\" colspan=\"2\" valign=\"top\"><label for=\"$id\">$label</label></th>\n";
			echo "			<td class=\"form-hint\"  valign=\"top\">$hint</td>\n";
			echo "			<td class=\"form-error\" valign=\"top\">$error</td>\n";
			echo "		</tr>\n";
			echo "		<tr {$tr_class}>\n";
			echo "			<td class=\"form-field\" colspan=\"4\">$content</td>\n";
			echo "		</tr>\n";
		} else {
			echo "		<tr {$tr_class}>\n";
			echo "			<th class=\"form-label tworow\" colspan=\"4\"><label for=\"$id\">$label</label></th>\n";
			echo "		</tr>\n";
			echo "		<tr {$tr_class}>\n";
			echo "			<td class=\"form-field\" valign=\"top\" colspan=\"2\">$content</td>\n";
			echo "			<td class=\"form-hint\"  valign=\"top\">$hint</td>\n";
			echo "			<td class=\"form-error\" valign=\"top\">$error</td>\n";
			echo "		</tr>\n";
		}
	}

	public function render_group($group, $res){
		$this->begin();

		$label = $group->get_label();

		echo "		<tr class=\"form-group\">\n";
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

	public function render_hint($field){
		$this->begin();

		$label = $field->get_label();
		$content = $field->get_content();

		echo "		<tr>\n";
		if ( $label !== false ){
			echo "			<th class=\"form-label\">$label</th>\n";
			echo "			<td class=\"form-field\" colspan=\"3\">$content</td>\n";
		} else {
			echo "			<td class=\"form-field\" colspan=\"4\">$content</td>\n";
		}
		echo "		</tr>\n";
	}

	public function render_static($field){
		$this->render_field($field, false);
	}

	public function begin(){
		if ( $this->closed ){
			$this->closed = false;
			echo "	<table class=\"layout\">\n";
		}
	}

	public function end(){
		if ( !$this->closed ){
			$this->closed = true;
			echo "	</table>\n";
		}
	}
}
