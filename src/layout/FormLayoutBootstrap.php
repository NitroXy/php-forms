<?php

namespace NitroXy\PHPForms;
use NitroXy\PHPForms\FormUtils;

class FormLayoutBootstrap extends FormLayoutBase {
	public function renderField($field, $error){
		$id = $field->getId();
		$label = $field->getLabel();
		$content = static::fieldContent($field);
		$hint = $field->getHint();
		$required = $field->attribute('required');

		/* addons */
		list($prefix, $suffix) = $field->getAddons();
		$have_addon = (boolean)($prefix || $suffix);
		if ( $prefix ) $prefix = "<div class=\"input-group-addon\">{$prefix}</div>";
		if ( $suffix ) $suffix = "<div class=\"input-group-addon\">{$suffix}</div>";

		$class = ['form-group'];
		if ( $error ){
			$class[] = 'has-error';
			$hint = $hint ? "$hint<br/>$error" : $error;
		}

		if ( $required ){
			$class[] = 'required';
		}

		$group_attr = FormUtils::serializeAttr(['class' => $class]);

		if ( $field instanceof FormCheckbox ){
			echo "<div class=\"checkbox\">";
			echo "	<label for=\"$id\" class=\"control-label\">";
			echo "		$content";
			echo "		{$field->getText()}";
			echo "	</label>";
			if ( $hint ){
				echo "	<span class=\"help-block\">$hint</span>";
			}
			echo '</div>';
			return;
		}

		echo "<div {$group_attr}>";
		if ( $label ){
			echo "	<label for=\"$id\" class=\"control-label\">{$label}</label>";
		}
		if ( $have_addon ){
			echo "	<div class=\"input-group\">{$prefix}{$content}{$suffix}</div>";
		} else {
			echo "	$content";
		}
		if ( $hint ){
			echo "	<span class=\"help-block\">$hint</span>";
		}
		echo '</div>';
	}

	public function renderStatic($field){
		$id = $field->getId();
		$label = $field->getLabel();
		$content = $field->getContent();
		$hint = $field->getHint();

		echo "<div class=\"form-group\">";
		if ( $label ){
			echo "	<label for=\"$id\" class=\"control-label\">$label</label>";
		}
		echo "	<p class=\"form-control-static\">$content</p>";
		if ( $hint ){
			echo "	<span class=\"help-block\">$hint</span>";
		}
		echo '</div>';
	}

	public function renderHint($field){
		if ( $field->getLabel() ){
			$this->renderStatic($field);
			return;
		}

		$content = $field->getContent();
		echo "<p>$content</p>";
	}

	public function begin(){}
	public function end(){}

	public function fieldClass(){
		return array('form-control');
	}

	static private function hasClass($field, $pattern){
		if ( !($class = $field->attribute('class')) ) return false;
		if ( !is_array($class) ){
			$class = explode(' ', $class);
		}
		foreach ( $class as $cur ){
			if ( preg_match("/^$pattern.+\$/", $cur) ) return true;
		}
		return false;
	}

	static private function fieldContent($field){
		if ( $field instanceof FormUpload ){
			return $field->getContent();
		}

		if ( $field instanceof FormCheckbox ){
			return $field->getContent();
		}

		if ( $field instanceof StaticField ){
			if ( $icon = $field->getIcon() ){
				$icon = "<span class=\"$icon\"></span>";
			}
			return $field->getContent(array('icon' => $icon));
		}

		if ( $field instanceof FormButton ){
			$class = array('btn');
			if ( !static::hasClass($field, 'btn-.+') ){
				$class[] = 'btn-primary';
			}
			if ( $icon = $field->getIcon() ){
				$icon = "<span class=\"$icon\"></span>";
			}
			return $field->getContent(array('class' => $class, 'icon' => $icon));
		}

		return $field->getContent(['class' => 'form-control']);
	}

	static private function columnClass($columns){
		switch ($columns){
			case 1:
				return 'col-xs-12';
			case 2:
				return 'col-xs-6';
			case 3:
				return 'col-xs-4';
			case 4:
				return 'col-xs-3';
			case 5:
			case 6:
				return 'col-xs-2';
			default:
				return 'col-xs-1';
		}
	}

	static private function fieldHasClass($field, $needle){
		$class = $field->attribute('class', []);
		if ( is_string($class) ){
			$class = explode(' ', $class);
		}
		foreach ( $class as $haystack ){
			if ( preg_match($needle, $haystack, $match) ){
				return $match[0];
			}
		}
		return false;
	}

	public function renderGroup($group, $res){
		$label = $group->getLabel();
		$children = $group->children();
		$column_class = static::columnClass(count($children));
		$inline_only = array_reduce($children, function($all, $x){
			return $all ? ($x instanceof FormButton || $x instanceof FormCheckbox) : false;
		}, true);

		echo "<div class=\"form-group\">\n";
		if ( $label ){
			echo "	<label>$label</label>\n";
		}

		/* special case when there is only inline elements in the group (buttons or checkboxes) */
		if ( $inline_only ){
			echo "	<div class=\"clearfix\">\n";
			foreach ( $group->children() as $field ){
				if ( $field instanceof FormCheckbox ){
					echo "		" . $field->getContent(array(), array('class' => 'checkbox-inline')) . "\n";
				} else {
					echo "		" . static::fieldContent($field) . "\n";
				}
			}
			echo "	</div>\n";
			echo "</div>\n";
			return;
		}

		/* output row with balanced columns */
		echo "	<div class=\"row\">\n";
		foreach ( $group->children() as $field ){
			$class = static::fieldHasClass($field, '/col-(xs|sm|md|lg)-[0-9]+/') ?: $column_class;
			echo "		<div class=\"{$class}\">\n";
			echo "			" . static::fieldContent($field) . "\n";
			echo "		</div>\n";
		}
		echo "	</div>\n";
		echo "</div>\n";
	}
}
