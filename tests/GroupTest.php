<?php

use NitroXy\PHPForms\Form;

class GroupTest extends PHPUnit_Framework_TestCase {
	public function testGroup(){
		$mock = new MockLayout();
		$form = Form::create('id', function($f){
			$f->textField('c', null);
			$f->group("group1", function($f){
				$f->textField('a', null);
			});
			$f->group("group2", function($f){
				$f->textField('b', null);
			});
		}, ['layout' => $mock]);

		$this->assertEquals(1, $mock->opened, "Layout preamble must be opened exactly once");
		$this->assertEquals(1, $mock->closed, "Layout postamble must be closed exactly once");
		$this->assertArrayHasKey('group1', $mock->group);
		$this->assertArrayHasKey('group2', $mock->group);
		$this->assertArrayHasKey('a', $mock->group['group1']);
		$this->assertArrayHasKey('b', $mock->group['group2']);
	}
}
