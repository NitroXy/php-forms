<?php

use NitroXy\PHPForms\Form;

class FieldTest extends PHPUnit_Framework_TestCase {
	public function testHiddenField(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->hiddenField('foo', '1');
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertEquals('hidden', $mock->field['foo']->attribute('type'));
		$this->assertEquals('1', $mock->field['foo']->attribute('value'));
	}

	public function testExplicitId(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->textField('foo', 'Label', ['id' => 'explicit_set_id']);
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertEquals('explicit_set_id', $mock->field['foo']->attribute('id'));
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testInvalidLabel(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->textField('foo', 5);
		}, ['layout' => $mock]);
	}

	public function testInputFields(){
		$mock = new MockLayout();
		$matrix = [
			'textField' => 'text',
			'passwordField' => 'password',
		];

		foreach ( $matrix as $func => $type){
			$form = Form::create('id', function($f) use ($func) {
				$f->$func('foo', 'Label');
			}, ['layout' => $mock]);
			$this->assertArrayHasKey('foo', $mock->field);
			$this->assertEquals($type, $mock->field['foo']->attribute('type'));
		}
	}

	public function testCustomField(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->customField('foo', 'email', 'Label');
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertEquals('email', $mock->field['foo']->attribute('type'));
	}

	public function testUploadField(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->uploadField('foo', 'Label');
			$f->uploadField('bar', 'Label', ['remove' => true]);
			$f->uploadField('baz', 'Label', ['current' => 'Preview']);
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertArrayNotHasKey('foo_remove', $mock->field);
		$this->assertArrayNotHasKey('foo_current', $mock->field);
		$this->assertArrayHasKey('bar', $mock->field);
		$this->assertArrayHasKey('bar_remove', $mock->field);
		$this->assertArrayNotHasKey('bar_current', $mock->field);
		$this->assertArrayHasKey('baz', $mock->field);
		$this->assertArrayNotHasKey('baz_remove', $mock->field);
		$this->assertArrayHasKey('baz_current', $mock->field);
		$this->assertEquals('file', $mock->field['foo']->attribute('type'));
	}
}
