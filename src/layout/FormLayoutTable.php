<?php

namespace NitroXy\PHPForms;
use NitroXy\PHPForms\FormUtils;

class FormLayoutTable extends FormLayoutBase {
	public $closed = true;

	public function renderField($field, $error){
		$this->begin();

		$id = $field->getId();
		$label = $field->getLabel();
		$content = $field->getContent();
		$hint = $field->getHint();
		$hints = $field->layoutHints();
		$required = $field->attribute('required');

		/* prepare classes for row */
		$tr_class = [];
		if ( $required ) $tr_class[] = 'required';
		if ( $error ) $tr_class[] = 'have-error';
		$tr_class = FormUtils::serializeAttr(['class' => $tr_class]);

		/* addons */
		list($prefix, $suffix) = $field->getAddons();
		$have_addon = (boolean)($prefix || $suffix);
		if ( $prefix ) $prefix = "<span class=\"form-prefix\">{$prefix}</span>";
		if ( $suffix ) $suffix = "<span class=\"form-suffix\">{$suffix}</span>";

		if ( $field instanceof FormCheckbox ){
			echo "		<tr {$tr_class}>\n";
			if ( $label !== false ){
				echo "			<th class=\"form-label\"><label for=\"$id\">{$field->getText()}</label></th>\n";
				echo "			<td class=\"form-field\">$content</td>\n";
				echo "			<td class=\"form-hint\">$hint</td>\n";
				echo "			<td class=\"form-error\">$error</td>\n";
			} else {
				echo "			<td class=\"form-field\" colspan=\"4\"><label>{$content} {$field->getText()}</label></td>\n";
			}
			echo "		</tr>\n";
			return;
		}

		if ( !($hints & Form::LAYOUT_TWOROWS) ){
			echo "		<tr {$tr_class}>\n";
			if ( $label !== false ){
				echo "			<th class=\"form-label\"><label for=\"$id\">$label</label></th>\n";
				if ( $have_addon ){
					echo "			<td class=\"form-field\"><div class=\"form-addon\">{$prefix}{$content}{$suffix}</div></td>\n";
				} else {
					echo "			<td class=\"form-field\">$content</td>\n";
				}
				echo "			<td class=\"form-hint\">$hint</td>\n";
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

	public function renderGroup($group, $res){
		$this->begin();

		$label = $group->getLabel();
		$hint = $group->getHint();

		echo "		<tr class=\"form-group\">\n";
		if ( $label !== false ){
			echo "			<th class=\"form-label\">$label</th>\n";
			echo "			<td class=\"form-field\">";
		} else {
			echo "			<td class=\"form-field\" colspan=\"4\">";
		}

		foreach ( $group->children() as $field ){
			echo $field->getContent();
		}

		echo "			</td>\n";
		if ( $label !== false ){
			echo "			<td class=\"form-hint\">$hint</td>\n";
			echo "			<td class=\"form-error\"></td>\n";
		}
		echo "		</tr>\n";
	}

	public function renderHint($field){
		$this->begin();

		$label = $field->getLabel();
		$content = $field->getContent();

		echo "		<tr>\n";
		if ( $label !== false ){
			echo "			<th class=\"form-label\">$label</th>\n";
			echo "			<td class=\"form-field\" colspan=\"3\">$content</td>\n";
		} else {
			echo "			<td class=\"form-field\" colspan=\"4\">$content</td>\n";
		}
		echo "		</tr>\n";
	}

	public function renderStatic($field){
		$this->renderField($field, false);
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
