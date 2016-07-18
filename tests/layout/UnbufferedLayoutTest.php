<?php

use NitroXy\PHPForms\Form;
use NitroXy\PHPForms\FormOptions;

class UnbufferedLayoutTest extends DOMParser_TestCase {
	protected static $layout = 'unbuffered';

	public function testId(){
		$this->formId = 'something';
		$this->generate(function($f){});
		$this->validate([
			['form_start', ['id' => 'something']],
			['form_end'],
		]);
	}

	public function testIdFalse(){
		$this->formId = false;
		$this->generate(function($f){});
		$this->validate([
			['form_start', ['id' => null]],
			['form_end'],
		]);
	}

	public function testHiddenField(){
		$this->generate(function($f){
			$f->hiddenField('foo', '1');
		});
		$this->validate([
			['form_start'],
			['input', ['type' => 'hidden', 'name' => 'foo', 'value' => 1]],
			['form_end'],
		]);
	}

	public function testUnbufferedNoAction(){
		$this->generate(function($f){
			$f->textField('foo', 'Test field');
		}, ['action' => false]);
		$this->validate([
			['label_start', ['for' => 'id_foo']], ['content', 'Test field'], ['label_end'],
			['input', ['type' => 'text', 'name' => 'foo']],
		]);
	}

	public function testButtonField(){
		$this->generate(function($f){
			$f->button('label 1');
			$f->button('label 2', 'action');
		});
		$this->validate([
			['form_start'],
			['button_start'], ['content', 'label 1'], ['button_end'],
			['button_start', ['name' => 'action']], ['content', 'label 2'], ['button_end'],
			['form_end'],
		]);
	}

	public function testFormArbitraryAttribute(){
		$this->generate(function($f){
			$f->hiddenField('foo', '1');
		}, ['attr' => ['foo' => 'bar']]);
		$this->validate([
			['form_start', ['foo' => 'bar']],
			['input', ['type' => 'hidden', 'name' => 'foo', 'value' => 1]],
			['form_end'],
		]);
	}

	public function testTextField(){
		$this->generate(function($f){
			$f->textField('foo', 'Test field', ['type' => 'email']);
		});
		$this->validate([
			['form_start'],
			['label_start', ['for' => 'id_foo']], ['content', 'Test field'], ['label_end'],
			['input', ['type' => 'email', 'name' => 'foo', 'id' => 'id_foo']],
			['form_end'],
		]);
	}

	public function testSelectField(){
		$this->generate(function($f){
			$f->select('foo', 'Test field', FormOptions::fromArray(['a', 'b', 'c']), ['selected' => 1]);
		});
		$this->validate([
			['form_start'],
			['label_start', ['for' => 'id_foo']], ['content', 'Test field'], ['label_end'],
			['select_start', ['name' => 'foo', 'id' => 'id_foo']],
			['option_start', ['value' => 0]], ['content', 'a'], ['option_end'],
			['option_start', ['value' => 1, 'selected' => '']], ['content', 'b'], ['option_end'],
			['option_start', ['value' => 2]], ['content', 'c'], ['option_end'],
			['select_end'],
			['form_end'],
		]);
	}

	public function testUnbufferedHintUnsupported(){
		$this->expectException(\PHPUnit_Framework_Error::class);
		$this->generate(function($f){
			$f->hint('Lorem ipsum');
		});
	}

	public function testUnbufferedGroupUnsupported(){
		$this->expectException(\PHPUnit_Framework_Error::class);
		$this->generate(function($f){
			$f->group(false, function($f){
				$f->hint('Lorem ipsum');
			});
		});
	}

	public function testUnbufferedFieldsetUnsupported(){
		$this->expectException(\PHPUnit_Framework_Error::class);
		$this->generate(function($f){
			$f->fieldset(false, function($f){
				$f->hint('Lorem ipsum');
			});
		});
	}

	public function testCheckboxShouldGenerateHidden(){
		$this->generate(function($f){
			$f->checkbox('foo', 'Text');
		});
		$this->validate([
			['form_start'],
			['input', ['name' => 'foo', 'value' => '0', 'type' => 'hidden']],
			['label_start'], ['content', 'Text'], ['label_end'],
			['input', ['type' => 'checkbox', 'name' => 'foo', 'id' => 'id_foo']],
			['form_end'],
		]);
	}

	public function testUnbufferedObject(){
		$obj = (object)['id' => 7, 'foo' => 'spam'];
		ob_start();
		Form::fromObject($obj, function($f){
			$f->textField('foo', 'Test field', ['type' => 'email']);
		}, ['layout' => 'unbuffered']);
		$this->html = ob_get_contents();
		ob_end_clean();
		$this->validate([
			['form_start'],
			['input', ['type' => 'hidden', 'name' => 'stdClass[id]', 'value' => 7]],
			['label_start', ['for' => 'stdClass_7_foo']], ['content', 'Test field'], ['label_end'],
			['input', ['type' => 'email', 'name' => 'stdClass[foo]', 'id' => 'stdClass_7_foo']],
			['form_end'],
		]);
	}
}
