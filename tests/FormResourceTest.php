<?php

namespace FormResourceTest;

use \NitroXy\PHPForms\Form;
use \NitroXy\PHPForms\FormData;
use \NitroXy\PHPForms\Tests\MockLayout;

class MyResource extends FormData {
	public function getErrorFor($field){
		return 'field error';
	}
}

class MyForm extends Form {
	public static $defaultResourceClass = MyResource::class;
}

class FormResourceTest extends \PHPUnit_Framework_TestCase {
	public function testShouldReturnOldContext(){
		$mock = new MockLayout();
		$resource = $this->getMockBuilder(MyResource::class)
		                 ->enableProxyingToOriginalMethods()
		                 ->setConstructorArgs([null])
		                 ->getMock();
		$resource->expects($this->once())->method('getErrorFor');
		$form = Form::create('id', function($f){
			$f->textfield('foo');
		}, ['layout' => $mock]);
		$this->assertCount(1, $mock->field);
		$this->assertInstanceOf('NitroXy\PHPForms\FormInput', $mock->field['foo']);
		$this->assertEquals('field error', $mock->field['foo']->getError($resource));
	}
}
