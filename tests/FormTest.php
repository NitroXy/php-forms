<?php

use NitroXy\PHPForms\Form;

require_once 'MockForm.php';

class FormTest extends PHPUnit_Framework_TestCase {
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

}
