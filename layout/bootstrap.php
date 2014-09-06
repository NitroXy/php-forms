<?php

class FormLayoutBootstrap implements FormLayout {
	public function render_field($field, $error){
		$id = $field->get_id();
		$label = $field->get_label();
		$content = static::field_content($field);
		$hint = $field->get_hint();

		$class = 'form-group';
		if ( $field instanceof FormCheckbox ){
			$class = 'checkbox';
		}

		if ( $error ){
			$class .= ' has-error';
			$hint = $hint ? "$hint<br/>$error" : $error;
		}

		echo "<div class=\"$class\">";
		if ( $label ){
			echo "	<label for=\"$id\" class=\"control-label\">$label</label>";
		}
		echo "	$content";
		if ( $hint ){
			echo "	<span class=\"help-block\">$hint</span>";
		}
		echo '</div>';
	}

	public function render_fieldset($fieldset){}
	public function render_hint($field){}
	public function begin(){}
	public function end(){}

	public function field_class(){
		return array('form-control');
	}

	static private function has_class($field, $pattern){
		if ( !isset($field->attr['class']) ) return false;
		$class = $field->attr['class'];
		if ( !is_array($class) ){
			$class = explode(' ', $class);
		}
		foreach ( $class as $cur ){
			if ( preg_match("/^$pattern.+\$/", $cur) ) return true;
		}
		return false;
	}

	static private function field_content($field){
		if ( $field instanceof FormUpload || $field instanceof FormCheckbox ){
			return $field->get_content();
		}

		if ( $field instanceof FormButton ){
			$class = array('btn');
			if ( !static::has_class($field, 'btn-.+') ){
				$class[] = 'btn-primary';
			}
			return $field->get_content(array('class' => $class));
		}

		return $field->get_content(array('class' => 'form-control'));
	}

	public function render_group($group, $res){
		$label = $group->get_label();

		echo '<div class="form-group">';
		if ( $label ){
			echo "	<label>$label</label>";
		}
		echo '	<div>';
		foreach ( $group->children() as $field ){
			echo static::field_content($field);
		}
		echo '	</div>';
		echo '</div>';
	}
}
