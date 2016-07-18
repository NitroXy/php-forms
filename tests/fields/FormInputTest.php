<?php

use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormInput;

class FormInputTest extends PHPUnit_Framework_TestCase {
	public function testFormInputSimple(){
		$field = new FormInput('key', 'id', 'name', '7', 'text', 'label', []);
		$this->assertEquals('<input type="text" name="name" id="id" value="7" />', $field->getContent());
	}
	
	public function testFormInputGetters(){
		$field = new FormInput('key', 'id', 'name', '7', 'text', 'label', []);
		$this->assertEquals('id', $field->getId());
		$this->assertEquals('name', $field->getName());
		$this->assertEquals('7', $field->getValue());
		$this->assertEquals('label', $field->getLabel());
		$this->assertEquals(false, $field->getIcon());
		$this->assertEquals([false, false], $field->getAddons());
		$this->assertEquals(false, $field->getHint());
		$this->assertEquals(0, $field->layoutHints());
	}
	
	public function testFormInputAddons(){
		$field = new FormInput('key', 'id', 'name', '7', 'text', 'label', [
			'prefix' => 'prefix',
			'suffix' => 'suffix',
		]);
		$this->assertEquals(['prefix', 'suffix'], $field->getAddons());
	}
	
	public function testFormInputIcon(){
		$field = new FormInput('key', 'id', 'name', '7', 'text', 'label', [
			'icon' => 'icon',
		]);
		$this->assertEquals('icon', $field->getIcon());
	}
	
	public function testFormInputHint(){
		$field = new FormInput('key', 'id', 'name', '7', 'text', 'label', [
			'hint' => 'hint',
		]);
		$this->assertEquals('hint', $field->getHint());
	}
	
	public function testFormInputLayoutHints(){
		$tworow = new FormInput('key', 'id', 'name', '7', 'text', 'label', ['tworow' => true]);
		$fill = new FormInput('key', 'id', 'name', '7', 'text', 'label', ['tworow' => true, 'fill' => true]);
		$this->assertEquals(Form::LAYOUT_TWOROWS, $tworow->layoutHints());
		$this->assertEquals(Form::LAYOUT_TWOROWS | Form::LAYOUT_FILL, $fill->layoutHints());
	}
	
	public function testFormInputTypeAttr(){
		$field = new FormInput('key', 'id', 'name', '7', 'text', 'label', ['type' => 'email']);
		$this->assertEquals('<input type="email" name="name" id="id" value="7" />', $field->getContent(), "Type attribute should override type argument");
	}

	public function testFormInputMissingId(){
		$field = new FormInput('key', null, 'name', '7', 'text', 'label', []);
		$this->assertEquals('<input type="text" name="name" value="7" />', $field->getContent(), "Id null should not yield id attribute");
	}	

	public function testFormInputMissingName(){
		$field = new FormInput('key', 'id', null, '7', 'text', 'label', []);
		$this->assertEquals('<input type="text" id="id" value="7" />', $field->getContent(), "Name null should not yield name attribute");
	}	

	public function testFormInputMissingValue(){
		$field = new FormInput('key', 'id', 'name', null, 'text', 'label', []);
		$this->assertEquals('<input type="text" name="name" id="id" />', $field->getContent(), "Value null should not yield value attribute");
	}	

	public function testFormInputPasswordValue(){
		$field = new FormInput('key', 'id', 'name', '7', 'password', 'label', []);
		$this->assertEquals('<input type="password" name="name" id="id" />', $field->getContent(), "Password fields should not have value attribute");
	}

	public function testFormInputError(){
		$resource = $this->getMockBuilder(stdClass::class)
		                 ->setMethods(['getErrorFor'])
		                 ->getMock();
		$resource->expects($this->once())
		         ->method('getErrorFor')
		         ->with($this->equalTo('key'))
		         ->will($this->returnValue('error'));
		$field = new FormInput('key', 'id', 'name', '7', 'text', 'label', []);
		$this->assertEquals('error', $field->getError($resource), "Error should be fetched from resource");
	}
}
