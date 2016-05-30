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
}
