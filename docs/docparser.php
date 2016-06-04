<?php

abstract class Annotation {
	public static $register = [
		'param' => ['Parameters', 'AnnotationParam'],
		'option' => ['Attribute options', 'AnnotationOption'],
	];

	public $value;

	public function __construct($value){
		$this->value = $value;
	}

	abstract public function render();
}

class AnnotationParam extends Annotation {
	public $var = false;

	public function __construct($value){
		if ( preg_match('/^([$][a-z]+) (.*)/', $value, $match) ){
			$this->var = $match[1];
			parent::__construct($match[2]);
		} else {
			parent::__construct($value);
		}
	}

	public function render(){
		if ( $this->var !== false ){
			return "<code>{$this->var}</code> {$this->value}";
		} else {
			return $this->value;
		}
	}
}

class AnnotationOption extends Annotation {
	public $option = false;
	public $datatype = 'mixed';

	public function __construct($value){
		if ( preg_match('/^\'?([a-z]+)\'? (?:{([a-z]+)} )?(.*)/', $value, $match) ){
			$this->option = $match[1];
			$this->datatype = !empty($match[2]) ? $match[2] : 'mixed';
			parent::__construct($match[3]);
		} else {
			parent::__construct("'" . $value . "'");
		}
	}

	public function render(){
		if ( $this->option !== false ){
			return "<code>{$this->option}</code> <span class=\"label type-{$this->datatype}\">{$this->datatype}</span> {$this->value}";
		} else {
			return $this->value;
		}
	}
}

function phpdoc($method){
	$raw = $method->getDocComment();
	$doc = preg_replace('#^(/\*\* ?\n|\s*\*/? ?)#m', '', $raw);

	/* join multiline annotations */
	$doc = preg_replace('/\n\s*(?![@\n])/', ' ', $doc);

	/* extract annotations */
	$re_annotations = '/^@(option|param) (.*)$/m';
	preg_match_all($re_annotations, $doc, $annotations, PREG_SET_ORDER);
	$doc = preg_replace($re_annotations, '', $doc);

	/* group annotations */
	$grouped = [];
	foreach ( $annotations as list(, $type, $value) ){
		if ( !array_key_exists($type, Annotation::$register) ){
			continue;
		}
		if ( !array_key_exists($type, $grouped) ){
			$grouped[$type] = [];
		}
		list(, $processor) = Annotation::$register[$type];
		$grouped[$type][] = new $processor($value);
	}

	/* process annonations */
	foreach ( ['param', 'option'] as $type ){
		if ( !array_key_exists($type, $grouped) ) continue;
		list($title,) = Annotation::$register[$type];

		$doc .= "<strong>{$title}</strong>";
		$doc .= "<ul class=\"{$type}\">";
		foreach ( $grouped[$type] as $annotation ){
			$doc .= "<li>{$annotation->render()}</li>";
		}
		$doc .= '</ul>';
	}

	return $doc;
}
