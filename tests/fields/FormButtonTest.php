<?php

use NitroXy\PHPForms\Form;

class FormButtonTest extends PHPUnit_Framework_TestCase {
	public function testButtonSimple(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->button('label');
		}, ['layout' => $mock]);
		$this->assertCount(1, $mock->field); /* buttons are unnamed */
		$this->assertInstanceOf('NitroXy\PHPForms\FormButton', $mock->field[0]);
		$this->assertEquals('<button type="button">label</button>', $mock->field[0]->getContent());
	}

	public function testButtonType(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->button('label', ['type' => 'submit']);
		}, ['layout' => $mock]);
		$this->assertCount(1, $mock->field); /* buttons are unnamed */
		$this->assertInstanceOf('NitroXy\PHPForms\FormButton', $mock->field[0]);
		$this->assertEquals('<button type="submit">label</button>', $mock->field[0]->getContent());
	}

	public function testButtonAttr(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->button('label', ['ng-click' => 'foo()']);
		}, ['layout' => $mock]);
		$this->assertCount(1, $mock->field); /* buttons are unnamed */
		$this->assertInstanceOf('NitroXy\PHPForms\FormButton', $mock->field[0]);
		$this->assertEquals('<button ng-click="foo()" type="button">label</button>', $mock->field[0]->getContent());
	}

	public function testSubmitAlias(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->submit('label');
		}, ['layout' => $mock]);
		$this->assertCount(1, $mock->field); /* buttons are unnamed */
		$this->assertInstanceOf('NitroXy\PHPForms\FormButton', $mock->field[0]);
		$this->assertEquals('<button type="submit">label</button>', $mock->field[0]->getContent());
	}

	public function testSubmitConfirm(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->submit('label', ['confirm' => 'foo']);
		}, ['layout' => $mock]);
		$this->assertCount(1, $mock->field); /* buttons are unnamed */
		$this->assertInstanceOf('NitroXy\PHPForms\FormButton', $mock->field[0]);
		$this->assertEquals("return confirm('foo');", $mock->field[0]->attribute('onclick'));
	}
}
