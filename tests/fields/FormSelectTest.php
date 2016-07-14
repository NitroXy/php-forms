<?php

use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormSelect;

class FormSelectTest extends PHPUnit_Framework_TestCase {
	public function testFromArray(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select(FormSelect::from_array($f, 'foo', ['a', 'b', 'c'], 'label'));
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertInstanceOf('NitroXy\PHPForms\FormSelect', $mock->field['foo']);
		$options = $mock->field['foo']->get_options();
		$this->assertCount(3, $options);
		$this->assertEquals(['attr' => ['value' => 0, 'selected' => 'selected'], 'label' => 'a'], (array)$options[0]);
		$this->assertEquals(['attr' => ['value' => 1], 'label' => 'b'], (array)$options[1]);
		$this->assertEquals(['attr' => ['value' => 2], 'label' => 'c'], (array)$options[2]);
	}

	public function testFromArrayCallback(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select(FormSelect::from_array_callback($f, 'foo', ['a', 'b', 'c'], function($x){
				return [$x, $x];
			}));
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertInstanceOf(FormSelect::class, $mock->field['foo']);
		$options = $mock->field['foo']->get_options();
		$this->assertCount(3, $options);
		$this->assertEquals(['attr' => ['value' => 'a'], 'label' => 'a'], (array)$options[0]);
		$this->assertEquals(['attr' => ['value' => 'b'], 'label' => 'b'], (array)$options[1]);
		$this->assertEquals(['attr' => ['value' => 'c'], 'label' => 'c'], (array)$options[2]);
	}

	public function testForcedSelected(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select(FormSelect::from_array($f, 'foo', ['a', 'b', 'c'], 'label', ['selected' => 1]));
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertInstanceOf(FormSelect::class, $mock->field['foo']);
		$options = $mock->field['foo']->get_options();
		$this->assertCount(3, $options);
		$this->assertEquals(['attr' => ['value' => 0], 'label' => 'a'], (array)$options[0]);
		$this->assertEquals(['attr' => ['value' => 1, 'selected' => 'selected'], 'label' => 'b'], (array)$options[1]);
		$this->assertEquals(['attr' => ['value' => 2], 'label' => 'c'], (array)$options[2]);
	}

	public function testPostback(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select(FormSelect::from_array($f, 'foo', [], 'label', ['postback' => true]));
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertEquals('this.form.submit();', $mock->field['foo']->attribute('onchange'));
	}
}
