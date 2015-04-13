<?php

use NitroXy\PHPForms\FormUtils;

class AttributeTest extends PHPUnit_Framework_TestCase {
	public function testSerializeAttribute(){
		/* ensure nothing is generated for an empty array */
		$this->assertEquals('', FormUtils::serialize_attr([]));

		/* common case */
		$this->assertEquals('foo="bar"', FormUtils::serialize_attr(['foo' => 'bar']));

		/* ensure attributes is joined with spaces */
		$this->assertEquals('foo="bar" fred="barney"', FormUtils::serialize_attr(['foo' => 'bar', 'fred' => 'barney']));

		/* htmlspecialchars */
		$this->assertEquals('foo="b&quot;a&quot;r"', FormUtils::serialize_attr(['foo' => 'b"a"r']));

		/* numerical array (e.g. for classes) */
		$this->assertEquals('foo="a b c"', FormUtils::serialize_attr(['foo' => ['a', 'b', 'c']]));

		/* assoc array */
		$this->assertEquals('foo-a="spam" foo-b="ham"', FormUtils::serialize_attr(['foo' => ['a' => 'spam', 'b' => 'ham']]));

		/* recursive */
		$this->assertEquals('foo-a-spam="1" foo-a-ham="2"', FormUtils::serialize_attr(['foo' => ['a' => ['spam' => '1', 'ham' => '2']]]));
	}
}
