<?php

use NitroXy\PHPForms\FormUtils;

class AttributeTest extends PHPUnit_Framework_TestCase {
	public function testSerializeAttribute(){
		/* ensure nothing is generated for an empty array */
		$this->assertEquals('', FormUtils::serializeAttr([]));

		/* common case */
		$this->assertEquals('foo="bar"', FormUtils::serializeAttr(['foo' => 'bar']));

		/* ensure attributes is joined with spaces */
		$this->assertEquals('foo="bar" fred="barney"', FormUtils::serializeAttr(['foo' => 'bar', 'fred' => 'barney']));

		/* htmlspecialchars */
		$this->assertEquals('foo="b&quot;a&quot;r&apos;"', FormUtils::serializeAttr(['foo' => 'b"a"r\'']));

		/* numerical array (e.g. for classes) */
		$this->assertEquals('foo="a b c"', FormUtils::serializeAttr(['foo' => ['a', 'b', 'c']]));

		/* assoc array */
		$this->assertEquals('foo-a="spam" foo-b="ham"', FormUtils::serializeAttr(['foo' => ['a' => 'spam', 'b' => 'ham']]));

		/* recursive */
		$this->assertEquals('foo-a-spam="1" foo-a-ham="2"', FormUtils::serializeAttr(['foo' => ['a' => ['spam' => '1', 'ham' => '2']]]));

		/* boolean */
		$this->assertEquals('foo', FormUtils::serializeAttr(['foo' => true, 'bar' => false]));
	}

	public function testSerializeAttributeSorted(){
		$data = ['foo' => true, 'bar' => true];
		$this->assertEquals('foo bar', FormUtils::serializeAttr($data, ['foo', 'bar']));
		$this->assertEquals('bar foo', FormUtils::serializeAttr($data, ['bar', 'foo']));
	}
}
