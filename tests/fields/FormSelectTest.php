<?php

use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormOptions;

class FormSelectTest extends PHPUnit_Framework_TestCase {
	public function testFromArray(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select('foo', 'label', FormOptions::from_array(['a', 'b', 'c']));
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertInstanceOf('NitroXy\PHPForms\FormSelect', $mock->field['foo']);
		$options = $mock->field['foo']->get_options();
		$this->assertCount(3, $options);
		$this->assertEquals(['value' => 0, 'label' => 'a'], (array)$options[0]);
		$this->assertEquals(['value' => 1, 'label' => 'b'], (array)$options[1]);
		$this->assertEquals(['value' => 2, 'label' => 'c'], (array)$options[2]);
	}

	public function testFromArrayCallback(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select('foo', 'label', FormOptions::from_array_callback(['a', 'b', 'c'], function($x){
				return [$x, $x];
			}));
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertInstanceOf('NitroXy\PHPForms\FormSelect', $mock->field['foo']);
		$options = $mock->field['foo']->get_options();
		$this->assertCount(3, $options);
		$this->assertEquals(['value' => 'a', 'label' => 'a'], (array)$options[0]);
		$this->assertEquals(['value' => 'b', 'label' => 'b'], (array)$options[1]);
		$this->assertEquals(['value' => 'c', 'label' => 'c'], (array)$options[2]);
	}

	public function testForcedSelected(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select('foo', 'label', FormOptions::from_array(['a', 'b', 'c']), ['selected' => 1]);
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertInstanceOf('NitroXy\PHPForms\FormSelect', $mock->field['foo']);
		$this->assertEquals(1, $mock->field['foo']->get_value());
	}

	public function testPostback(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select('foo', 'label', FormOptions::from_array([]), ['postback' => true]);
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertEquals('this.form.submit();', $mock->field['foo']->attribute('onchange'));
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testInvalidLabel(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select(FormOptions::from_array($f, 'foo', [], 5));
		}, ['layout' => $mock]);
	}
}
