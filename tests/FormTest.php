<?php

use NitroXy\PHPForms\Form;

class FormTest extends PHPUnit_Framework_TestCase {
	public function testFromArray(){
		$mock = new MockLayout();
		$data = ['foo' => 'bar'];
		$form = Form::fromArray('id', $data, function($f){
			$f->textField('foo', 'Label');
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertEquals('bar', $mock->field['foo']->attribute('value'));
	}

	public function testFromArrayNull(){
		$mock = new MockLayout();
		$form = Form::fromArray('id', null, function($f){
			$f->textField('foo', 'Label');
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertEquals(false, $mock->field['foo']->attribute('value'));
	}

	public function testFromObject(){
		$mock = new MockLayout();
		$data = (object)['foo' => 'bar'];
		$form = Form::fromObject($data, function($f){
			$f->textField('foo', 'Label');
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('stdClass[foo]', $mock->field, "Fields should use class prefix");
		$this->assertEquals('bar', $mock->field['stdClass[foo]']->attribute('value'));
	}

	public function testFromObjectNull(){
		$mock = new MockLayout();
		$form = Form::fromObject(null, function($f){
			$f->textField('foo', 'Label');
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field, "When null is used no prefix should be used for fields (like for arrays)");
		$this->assertEquals(false, $mock->field['foo']->attribute('value'));
	}

	public function testFromObjectPrefix(){
		$mock = new MockLayout();
		$data = (object)['foo' => 'bar'];
		$form = Form::fromObject($data, function($f){
			$f->textField('foo', 'Label');
		}, ['layout' => $mock, 'prefix' => 'prefix']);
		$this->assertArrayHasKey('prefix[foo]', $mock->field, "When prefix is set to a simple string [] should be appended");
		$this->assertEquals('bar', $mock->field['prefix[foo]']->attribute('value'));
	}

	public function testFromObjectPrefixFull(){
		$mock = new MockLayout();
		$data = (object)['foo' => 'bar'];
		$form = Form::fromObject($data, function($f){
			$f->textField('foo', 'Label');
		}, ['layout' => $mock, 'prefix' => 'prefix-%s']);
		$this->assertArrayHasKey('prefix-foo', $mock->field, "When prefix is set to a full string with %s it should be used as-is");
		$this->assertEquals('bar', $mock->field['prefix-foo']->attribute('value'));
	}

	public function testFromObjectId(){
		$mock = new MockLayout();
		$data = (object)['id' => 7];
		$form = Form::fromObject($data, function($f){
			$f->textField('foo', 'Label');
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('stdClass[id]', $mock->field, "ID field should be present");
		$this->assertEquals('stdClass_7', $mock->form_id, "When object has an ID it should be used as suffix");
	}

	public function testClassString(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'class' => 'foo bar']);
		$this->assertEquals('POST', $mock->form_attr['method']);
		$this->assertEquals(['form', 'mock', 'foo', 'bar'], $mock->form_attr['class']);
	}

	public function testClassArray(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'class' => ['foo', 'bar']]);
		$this->assertEquals('POST', $mock->form_attr['method']);
		$this->assertEquals(['form', 'mock', 'foo', 'bar'], $mock->form_attr['class']);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 */
	public function testLayoutMissing(){
		$form = Form::create('id', function($f){}, ['layout' => 'foobar']);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testLayoutInvalidClass(){
		$form = Form::create('id', function($f){}, ['layout' => new stdClass]);
	}

	public function testHttpMethodDefault(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock]);
		$this->assertEquals('POST', $mock->form_attr['method']);
	}

	public function testHttpMethodCase(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'method' => 'gEt']);
		$this->assertEquals('GET', $mock->form_attr['method']);
	}

	public function testHttpMethodGet(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'method' => 'get']);
		$this->assertEquals('GET', $mock->form_attr['method']);
		$this->assertArrayNotHasKey('_method', $mock->field);
	}

	public function testHttpMethodPost(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'method' => 'post']);
		$this->assertEquals('POST', $mock->form_attr['method']);
		$this->assertArrayNotHasKey('_method', $mock->field);
	}

	public function testHttpMethodOther(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'method' => 'patch']);
		$this->assertEquals('POST', $mock->form_attr['method']);
		$this->assertArrayHasKey('_method', $mock->field);
		$this->assertEquals('PATCH', $mock->field['_method']->getValue());
	}

	public function testHttpMethodOtherCustomField(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'method' => 'put', 'method_field_name' => 'foobar']);
		$this->assertEquals('POST', $mock->form_attr['method']);
		$this->assertArrayNotHasKey('_method', $mock->field);
		$this->assertArrayHasKey('foobar', $mock->field);
		$this->assertEquals('PUT', $mock->field['foobar']->getValue());
	}

	public function testFromBeginEnd(){
		/* it should always (try to) close layout */
		$mock = $this->getMockBuilder('MockLayout')->setMethods(['begin', 'end'])->getMock();
		$mock->expects($this->never())->method('begin');
		$mock->expects($this->once())->method('end');
		$form = Form::create('id', function($f){}, ['layout' => $mock]);
	}

	public function testFromPrePostAmble(){
		/* pre- and postamble should always be written ... */
		$mock = $this->getMockBuilder('MockLayout')->setMethods(['preamble', 'postamble'])->getMock();
		$mock->expects($this->once())->method('preamble');
		$mock->expects($this->once())->method('postamble');
		$form = Form::create('id', function($f){}, ['layout' => $mock]);
	}

	public function testFromPrePostAmbleNoAction(){
		/* ... unless action is explicitly disabled */
		$mock = $this->getMockBuilder('MockLayout')->setMethods(['preamble', 'postamble'])->getMock();
		$mock->expects($this->never())->method('preamble');
		$mock->expects($this->never())->method('postamble');
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'action' => false]);
	}

	public function testEmptyFieldset(){
		$mock = $this->getMockBuilder('MockLayout')->setMethods(['renderFieldset'])->getMock();
		$mock->expects($this->never())->method('renderFieldset');
		$form = Form::create('id', function($f){
			$f->fieldset(false, function($f){});
		}, ['layout' => $mock]);
	}

	public function testEmptyGroup(){
		$mock = $this->getMockBuilder('MockLayout')->setMethods(['renderGroup'])->getMock();
		$mock->expects($this->never())->method('renderGroup');
		$form = Form::create('id', function($f){
			$f->group(false, function($f){});
		}, ['layout' => $mock]);
	}

	public function testArbitraryAttr(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'foobar' => 'spam']);
		$this->assertArrayHasKey('foobar', $mock->form_attr);
		$this->assertEquals('spam', $mock->form_attr['foobar']);
	}

	public function testEnctypeDefault(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock]);
		$this->assertArrayNotHasKey('enctype', $mock->form_attr);
	}

	public function testEnctypeOption(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'enctype' => 'multipart/form-data']);
		$this->assertArrayHasKey('enctype', $mock->form_attr);
		$this->assertEquals('multipart/form-data', $mock->form_attr['enctype']);
	}

	public function testNoExtraAttr(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock]);
		$expected = ['class', 'method', 'action'];
		$this->assertEquals($expected, array_keys($mock->form_attr));

		/* id is not passed via attribute but should also be present */
		$this->assertEquals('id', $mock->form_id);
	}
}
