<?php

use NitroXy\PHPForms\Form;

class DOMParser_TestCase extends PHPUnit_Framework_TestCase {
	const START = 1;
	const END = 2;
	const SELF = 3;

	protected $html;

	public function setUp(){
		$this->html = null;
	}

	protected function onNotSuccessfulTest(Exception $e){
		if ( $this->html ){
			echo "\n";
			echo $this->html;
		}
		throw $e;
	}

	protected function generate($func){
		ob_start();
		Form::create('id', $func, ['layout' => 'table']);
		$this->html = ob_get_contents();
		ob_end_clean();
	}

	protected function validate($nodes){
		preg_match_all('#(</?(\w+).*?/?[>])([^<]*)#', $this->html, $matches, PREG_SET_ORDER);

		foreach ( $matches as $match ){
			$string = $match[1];
			$tag = $match[2];
			$content = trim($match[3]);

			$this->assertTrue(count($nodes) > 0, "Expected no more elements, got '{$string}'");
			$expected = array_shift($nodes);

			$type = static::element_type($string);
			$event = static::event_name($tag, $type);

			$this->assertEquals($expected[0], $event, "Matching tags");

			if ( !empty($content) ){
				$this->assertTrue(count($nodes) > 0, "Expected no more elements, got '{$string}'");
				$expected = array_shift($nodes);
				$this->assertEquals($expected[0], 'content', "Element contains text content");
				$this->assertEquals($expected[1], $content, "Matching content");
			}
		}

		$this->assertFalse(count($nodes) > 0, "Expected more elements, missing " . array_shift($nodes)[0] . ' event');
	}

	protected static function element_type($string){
		if ( substr($string, 0, 2) === '</' ){
			return self::END;
		} else if ( substr($string, -2) === '/>' ){
			return self::SELF;
		} else {
			return self::START;
		}
	}

	protected static function event_name($tag, $type){
		$suffix = '';
		switch ( $type ){
			case self::START: $suffix = '_start'; break;
			case self::END:   $suffix = '_end'; break;
		}
		return "{$tag}{$suffix}";
	}
}
