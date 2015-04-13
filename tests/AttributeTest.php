<?php

use NitroXy\PHPForms\FormUtils;

class AttributeTest extends PHPUnit_Framework_TestCase {
	public function testSerializeAttribute(){
		$this->assertEquals('foo="bar"', FormUtils::serialize_attr(['foo' => 'bar']));
		$this->assertEquals('foo="b&quot;a&quot;r"', FormUtils::serialize_attr(['foo' => 'b"a"r']));
		$this->assertEquals('foo="a b c"', FormUtils::serialize_attr(['foo' => ['a', 'b', 'c']]));
		$this->assertEquals('foo-a="spam" foo-b="ham"', FormUtils::serialize_attr(['foo' => ['a' => 'spam', 'b' => 'ham']]));
		$this->assertEquals('foo-a-spam="1" foo-a-ham="2"', FormUtils::serialize_attr(['foo' => ['a' => ['spam' => '1', 'ham' => '2']]]));
	}
}
