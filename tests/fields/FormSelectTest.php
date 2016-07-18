<?php

use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormSelect;
use NitroXy\PHPForms\FormOptions;

class FormSelectTest extends PHPUnit_Framework_TestCase {
	public function testSelectSimple(){
		$field = new FormSelect('key', 'id', 'name', '7', 'label', []);
		$this->assertEquals("<select name=\"name\" id=\"id\">\n\n</select>", $field->getContent());
	}

	public function testSelectValues(){
		$field = new FormSelect('key', 'id', 'name', '7', 'label', ['options' => FormOptions::fromArray([1,2,3])]);
		$this->assertEquals("<select name=\"name\" id=\"id\">\n<option value=\"0\">1</option>\n<option value=\"1\">2</option>\n<option value=\"2\">3</option>\n</select>", $field->getContent());
	}

	public function testSelectSelected(){
		$field = new FormSelect('key', 'id', 'name', '1', 'label', ['options' => FormOptions::fromArray([1,2,3])]);
		$this->assertEquals("<select name=\"name\" id=\"id\">\n<option value=\"0\">1</option>\n<option value=\"1\" selected>2</option>\n<option value=\"2\">3</option>\n</select>", $field->getContent());
	}

	public function testSelectExplicit(){
		$field = new FormSelect('key', 'id', 'name', '7', 'label', ['options' => FormOptions::fromArray([1,2,3]), 'selected' => 2]);
		$this->assertEquals("<select name=\"name\" id=\"id\">\n<option value=\"0\">1</option>\n<option value=\"1\">2</option>\n<option value=\"2\" selected>3</option>\n</select>", $field->getContent());
	}

	public function testNull(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select('foo', 'label', null);
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertInstanceOf('NitroXy\PHPForms\FormSelect', $mock->field['foo']);
		$this->assertCount(0, $mock->field['foo']->getOptions());
	}

	public function testFromArray(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select('foo', 'label', FormOptions::fromArray(['a', 'b', 'c']));
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertInstanceOf('NitroXy\PHPForms\FormSelect', $mock->field['foo']);
		$options = $mock->field['foo']->getOptions();
		$this->assertCount(3, $options);
		$this->assertEquals(['value' => 0, 'label' => 'a'], (array)$options[0]);
		$this->assertEquals(['value' => 1, 'label' => 'b'], (array)$options[1]);
		$this->assertEquals(['value' => 2, 'label' => 'c'], (array)$options[2]);
	}

	public function testFromArrayCallback(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select('foo', 'label', FormOptions::fromArrayCallback(['a', 'b', 'c'], function($x){
				return [$x, $x];
			}));
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertInstanceOf('NitroXy\PHPForms\FormSelect', $mock->field['foo']);
		$options = $mock->field['foo']->getOptions();
		$this->assertCount(3, $options);
		$this->assertEquals(['value' => 'a', 'label' => 'a'], (array)$options[0]);
		$this->assertEquals(['value' => 'b', 'label' => 'b'], (array)$options[1]);
		$this->assertEquals(['value' => 'c', 'label' => 'c'], (array)$options[2]);
	}

	public function testForcedSelected(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select('foo', 'label', FormOptions::fromArray(['a', 'b', 'c']), ['selected' => 1]);
		}, ['layout' => $mock]);
		$this->assertArrayHasKey('foo', $mock->field);
		$this->assertInstanceOf('NitroXy\PHPForms\FormSelect', $mock->field['foo']);
		$this->assertEquals(1, $mock->field['foo']->getValue());
	}

	public function testPostback(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->select('foo', 'label', FormOptions::fromArray([]), ['postback' => true]);
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
			$f->select('foo', 5, FormOptions::fromArray([]));
		}, ['layout' => $mock]);
	}
}
