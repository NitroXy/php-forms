<?php

use NitroXy\PHPForms\Form;

require_once 'MockForm.php';

class FormTest extends PHPUnit_Framework_TestCase {
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
		$this->assertEquals('PATCH', $mock->field['_method']->get_value());
	}

	public function testHttpMethodOtherCustomField(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'method' => 'put', 'method_field_name' => 'foobar']);
		$this->assertEquals('POST', $mock->form_attr['method']);
		$this->assertArrayNotHasKey('_method', $mock->field);
		$this->assertArrayHasKey('foobar', $mock->field);
		$this->assertEquals('PUT', $mock->field['foobar']->get_value());
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
		$mock = $this->getMockBuilder('MockLayout')->setMethods(['render_fieldset'])->getMock();
		$mock->expects($this->never())->method('render_fieldset');
		$form = Form::create('id', function($f){
			$f->fieldset(false, function($f){});
		}, ['layout' => $mock]);
	}

	public function testEmptyGroup(){
		$mock = $this->getMockBuilder('MockLayout')->setMethods(['render_group'])->getMock();
		$mock->expects($this->never())->method('render_group');
		$form = Form::create('id', function($f){
			$f->group(false, function($f){});
		}, ['layout' => $mock]);
	}

	public function testArbitraryAttr(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){}, ['layout' => $mock, 'foobar' => 'spam']);
		$this->assertEquals('spam', $mock->form_attr['foobar']);
	}
}
