<?php

use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormBuilder;
use NitroXy\PHPForms\FormContext;

class FormBuilderTest extends PHPUnit_Framework_TestCase {
	public $builder;
	public $form;

	public function setUp(){
		$this->builder = new FormBuilder;
		$this->form = $this->createMock(Form::class);
	}

	public function expose($method){
		$class = new ReflectionClass(FormBuilder::class);
		$method = $class->getMethod($method);
		$method->setAccessible(true);
		return $method;
	}

	public function context($methods){
		return $this->getMockBuilder(FormContext::class)
								->setConstructorArgs([$this->form, $this->builder])
		            ->setMethods($methods)
		            ->getMock();
	}

	public function testShouldReturnOldContext(){
		$ctx1 = new NitroXy\PHPForms\FormContext($this->form, $this->builder);
		$ctx2 = new NitroXy\PHPForms\FormContext($this->form, $this->builder);
		$this->assertEquals(null, $this->builder->setContext($ctx1));
		$this->assertEquals(spl_object_hash($ctx1), spl_object_hash($this->builder->setContext($ctx2)));
	}

	public function testAddFieldShouldCallContext(){
		$context = $this->context(['addField']);
		$context->method('addField')
						->with($this->equalTo('foo'))
						->will($this->returnArgument(0));
		$this->builder->setContext($context);
		$this->assertEquals('foo', $this->expose('addField')->invokeArgs($this->builder, ['foo']));
	}

	public function testUnbufferedShouldAskContext(){
		$context = $this->context(['unbuffered']);
		$context->expects($this->once())
						->method('unbuffered')
						->willReturn('foo');
		$this->builder->setContext($context);
		$this->assertEquals('foo', $this->expose('unbuffered')->invoke($this->builder));
	}
}
