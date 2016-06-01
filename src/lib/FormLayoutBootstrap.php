<?php

namespace NitroXy\PHPForms;

class FormLayoutBootstrap extends FormLayoutBase {
	public function render_field($field, $error){
		$id = $field->get_id();
		$label = $field->get_label();
		$content = static::field_content($field);
		$hint = $field->get_hint();
		$required = $field->attribute('required') ? '<em>*</em>' : '';

		$class = 'form-group';
		if ( $error ){
			$class .= ' has-error';
			$hint = $hint ? "$hint<br/>$error" : $error;
		}

		if ( $field instanceof FormCheckbox ){
			echo "<div class=\"checkbox\">";
			echo "	<label for=\"$id\" class=\"control-label\">";
			echo "		$content";
			echo "		{$field->get_text()}";
			echo "	</label>";
			if ( $hint ){
				echo "	<span class=\"help-block\">$hint</span>";
			}
			echo '</div>';
			return;
		}

		echo "<div class=\"$class\">";
		if ( $label ){
			echo "	<label for=\"$id\" class=\"control-label\">{$label}{$required}</label>";
		}
		echo "	$content";
		if ( $hint ){
			echo "	<span class=\"help-block\">$hint</span>";
		}
		echo '</div>';
	}

	public function render_static($field){
		$id = $field->get_id();
		$label = $field->get_label();
		$content = $field->get_content();
		$hint = $field->get_hint();

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

	public function render_hint($field){
		if ( $field->get_label() ){
			$this->render_static($field);
			return;
		}

		$content = $field->get_content();
		echo "<p>$content</p>";
	}

	public function begin(){}
	public function end(){}

	public function field_class(){
		return array('form-control');
	}

	static private function has_class($field, $pattern){
		if ( !($class = $field->attribute('class')) ) return false;
		if ( !is_array($class) ){
			$class = explode(' ', $class);
		}
		foreach ( $class as $cur ){
			if ( preg_match("/^$pattern.+\$/", $cur) ) return true;
		}
		return false;
	}

	static private function field_content($field){
		if ( $field instanceof FormUpload ){
			return $field->get_content();
		}

		if ( $field instanceof FormCheckbox ){
			return $field->get_content();
		}

		if ( $field instanceof StaticField ){
			if ( $icon = $field->get_icon() ){
				$icon = "<span class=\"$icon\"></span>";
			}
			return $field->get_content(array('icon' => $icon));
		}

		if ( $field instanceof FormButton ){
			$class = array('btn');
			if ( !static::has_class($field, 'btn-.+') ){
				$class[] = 'btn-primary';
			}
			if ( $icon = $field->get_icon() ){
				$icon = "<span class=\"$icon\"></span>";
			}
			return $field->get_content(array('class' => $class, 'icon' => $icon));
		}

		return $field->get_content(['class' => 'form-control']);
	}

	static private function column_class($columns){
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

	static private function field_has_class($field, $needle){
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

	public function render_group($group, $res){
		$label = $group->get_label();
		$children = $group->children();
		$column_class = static::column_class(count($children));
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
					echo "		" . $field->get_content(array(), array('class' => 'checkbox-inline')) . "\n";
				} else {
					echo "		" . static::field_content($field) . "\n";
				}
			}
			echo "	</div>\n";
			echo "</div>\n";
			return;
		}

		/* output row with balanced columns */
		echo "	<div class=\"row\">\n";
		foreach ( $group->children() as $field ){
			$class = static::field_has_class($field, '/col-(xs|sm|md|lg)-[0-9]+/') ?: $column_class;
			echo "		<div class=\"{$class}\">\n";
			echo "			" . static::field_content($field) . "\n";
			echo "		</div>\n";
		}
		echo "	</div>\n";
		echo "</div>\n";
	}
}
