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

	public function render_fieldset($fieldset){}
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

		return $field->get_content(array('class' => 'form-control'));
	}

	public function render_group($group, $res){
		$label = $group->get_label();

		echo '<div class="form-group">';
		if ( $label ){
			echo "	<label>$label</label>";
		}
		echo '	<div class="clearfix">';
		foreach ( $group->children() as $field ){
			if ( $field instanceof FormCheckbox ){
				echo $field->get_content(array(), array('class' => 'checkbox-inline'));
			} else {
				echo static::field_content($field);
			}
		}
		echo '	</div>';
		echo '</div>';
	}
}
