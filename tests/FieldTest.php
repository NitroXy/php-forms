<?php

use NitroXy\PHPForms\Form;

require_once 'MockForm.php';

class FieldTest extends PHPUnit_Framework_TestCase {
	public function testHiddenField(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->hidden_field('foo', '1');
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertEquals('hidden', $mock->field['foo']->attribute('type'));
		$this->assertEquals('1', $mock->field['foo']->attribute('value'));
	}

	public function testInputFields(){
		$mock = new MockLayout();
		$matrix = [
			'text_field' => 'text',
			'password_field' => 'password',
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
			$f->custom_field('foo', 'email', 'Label');
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertEquals('email', $mock->field['foo']->attribute('type'));
	}

	public function testUploadField(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->upload_field('foo', 'Label');
			$f->upload_field('bar', 'Label', ['remove' => true]);
			$f->upload_field('baz', 'Label', ['current' => 'Preview']);
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
