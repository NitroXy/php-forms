<?php

namespace FormBuilderTest;

use \NitroXy\PHPForms\Form;
use \NitroXy\PHPForms\FormBuilder;
use \NitroXy\PHPForms\FormContext;
use \NitroXy\PHPForms\FormFieldInterface;
use \NitroXy\PHPForms\Tests\MockLayout;

class MyField implements FormFieldInterface {
	public function render($layout, $res){
		$layout->renderField($this, null);
	}

	public function layoutHints(){}
	public function getContent(){}
	public function getLabel(){}
	public function getId(){}
	public function getContainer(){}
	public function setContainer($container){}
	public function attribute($key, $default=false){}
	public function getName(){ return false; }
}

class MyBuilder extends FormBuilder {
	public function wrappedManual($label){
		return $this->manual(false, $label, 'custom');
	}

	/* change defaults of builtin */
	public function button($text, array $attr=[]){
		$attr = array_merge(['type' => 'submit'], $attr);
		return parent::button($text, $attr);
	}

	public function somethingCustom(){
		$field = new MyField();
		$this->addField($field);
	}
}

class MyForm extends Form {
	public static $defaultBuilderClass = MyBuilder::class;
}

class FormBuilderTest extends \PHPUnit_Framework_TestCase {
	public $builder;
	public $form;

	public function setUp(){
		$this->builder = new FormBuilder;
		$this->form = $this->createMock(Form::class);
	}

	public function expose($method){
		$class = new \ReflectionClass(FormBuilder::class);
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
		$ctx1 = new \NitroXy\PHPForms\FormContext($this->form, $this->builder);
		$ctx2 = new \NitroXy\PHPForms\FormContext($this->form, $this->builder);
		$this->assertEquals(null, $this->builder->setContext($ctx1));
		$this->assertEquals(spl_object_hash($ctx1), spl_object_hash($this->builder->setContext($ctx2)));
	}

	public function testAddFieldShouldCallContext(){
		$field = new MyField();
		$context = $this->context(['addField']);
		$context->method('addField')
						->with($this->equalTo($field))
						->will($this->returnArgument(0));
		$this->builder->setContext($context);
		$this->assertEquals($field, $this->expose('addField')->invokeArgs($this->builder, [$field]));
	}

	public function testUnbufferedShouldAskContext(){
		$context = $this->context(['unbuffered']);
		$context->expects($this->once())
						->method('unbuffered')
						->willReturn('foo');
		$this->builder->setContext($context);
		$this->assertEquals('foo', $this->expose('unbuffered')->invoke($this->builder));
	}

	public function testDefaultBuilder(){
		$mock = new MockLayout();
		$form = MyForm::create('id', function($f){
			//$f->wrappedManual('label');
			//$f->button('label');
			$f->somethingCustom();
		}, ['layout' => $mock]);
		$this->assertCount(1, $mock->field);
	}
}
