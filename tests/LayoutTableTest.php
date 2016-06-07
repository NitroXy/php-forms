<?php

require_once 'DOMParser.php';

class LayoutTableTest extends DOMParser_TestCase {
	public function testHiddenField(){
		$this->generate(function($f){
			$f->hidden_field('foo', '1');
		});
		$this->validate([
			['form_start'],
			['input', ['type' => 'hidden', 'name' => 'foo', 'value' => 1]],
			['form_end'],
		]);
	}

	public function testFormArbitraryAttribute(){
		$this->generate(function($f){
			$f->hidden_field('foo', '1');
		}, ['attr' => ['foo' => 'bar']]);
		$this->validate([
			['form_start', ['foo' => 'bar']],
			['input', ['type' => 'hidden', 'name' => 'foo', 'value' => 1]],
			['form_end'],
		]);
	}

	public function testTextField(){
		$this->generate(function($f){
			$f->text_field('foo', 'Test field');
		});
		$this->validate([
			['form_start'],
			['table_start'],
			['tr_start'],
			['th_start'], ['label_start', ['for' => 'id_foo']], ['content', 'Test field'], ['label_end'], ['th_end'],
			['td_start'], ['input', ['type' => 'text', 'name' => 'foo', 'id' => 'id_foo']],	['td_end'],
			['td_start'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['table_end'],
			['form_end'],
		]);
	}

	public function testHint(){
		$this->generate(function($f){
			$f->hint('Lorem ipsum', false);
			$f->hint('Lorem ipsum', 'Hint field');
			$f->text_field('foo', 'Test field', ['hint' => 'Lorem ipsum']);
		});
		$this->validate([
			['form_start'],
			['table_start'],
			['tr_start'],
			['td_start', ['colspan' => '4']], ['content', 'Lorem ipsum'], ['td_end'],
			['tr_end'],
			['tr_start'],
			['th_start'], ['content', 'Hint field'], ['th_end'],
			['td_start', ['colspan' => '3']], ['content', 'Lorem ipsum'],	['td_end'],
			['tr_end'],
			['tr_start'],
			['th_start'], ['label_start', ['for' => 'id_foo']], ['content', 'Test field'], ['label_end'], ['th_end'],
			['td_start'], ['input', ['type' => 'text', 'name' => 'foo', 'id' => 'id_foo']],	['td_end'],
			['td_start'], ['content', 'Lorem ipsum'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['table_end'],
			['form_end'],
		]);
	}

	public function testFieldsetNoLegend(){
		$this->generate(function($f){
			$f->fieldset(false, function($f){
				$f->text_field('foo', 'Test field');
			});
		});
		$this->validate([
			['form_start'],
			['fieldset_start'],
			['table_start'],
			['tr_start'],
			['th_start'], ['label_start', ['for' => 'id_foo']], ['content', 'Test field'], ['label_end'], ['th_end'],
			['td_start'], ['input', ['type' => 'text', 'name' => 'foo', 'id' => 'id_foo']],	['td_end'],
			['td_start'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['table_end'],
			['fieldset_end'],
			['form_end'],
		]);
	}

	public function testFieldsetWithLegend(){
		$this->generate(function($f){
			$f->fieldset('Legend', function($f){
				$f->text_field('foo', 'Test field');
			});
		});
		$this->validate([
			['form_start'],
			['fieldset_start'],
			['legend_start'], ['content', 'Legend'], ['legend_end'],
			['table_start'],
			['tr_start'],
			['th_start'], ['label_start', ['for' => 'id_foo']], ['content', 'Test field'], ['label_end'], ['th_end'],
			['td_start'], ['input', ['type' => 'text', 'name' => 'foo', 'id' => 'id_foo']],	['td_end'],
			['td_start'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['table_end'],
			['fieldset_end'],
			['form_end'],
		]);
	}

	public function testGroup(){
		$this->generate(function($f){
			$f->group('Group 1', function($f){
				$f->text_field('foo', 'Field 1');
				$f->text_field('bar', 'Field 2');
				$f->text_field('baz', 'Field 3');
			});
			$f->group('Group 2', function($f){
				$f->text_field('ham', 'Field 1');
			}, ['hint' => 'Lorem ipsum']);
		});
		$this->validate([
			['form_start'],
			['table_start'],
			['tr_start'],
			['th_start'], ['content', 'Group 1'], ['th_end'],
			['td_start'],
			['input', ['type' => 'text', 'name' => 'foo', 'id' => 'id_foo']],
			['input', ['type' => 'text', 'name' => 'bar', 'id' => 'id_bar']],
			['input', ['type' => 'text', 'name' => 'baz', 'id' => 'id_baz']],
			['td_end'],
			['td_start'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['tr_start'],
			['th_start'], ['content', 'Group 2'], ['th_end'],
			['td_start'],
			['input', ['type' => 'text', 'name' => 'ham', 'id' => 'id_ham']],
			['td_end'],
			['td_start'], ['content', 'Lorem ipsum'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['table_end'],
			['form_end'],
		]);
	}

	public function testRequired(){
		$this->generate(function($f){
			$f->text_field('foo', 'Test field', ['required' => false]);
			$f->text_field('bar', 'Test field', ['required' => true]);
		});
		$this->validate([
			['form_start'],
			['table_start'],
			['tr_start', ['class' => null]],
			['th_start'], ['label_start', ['for' => 'id_foo']], ['content', 'Test field'], ['label_end'], ['th_end'],
			['td_start'], ['input', ['type' => 'text', 'name' => 'foo', 'id' => 'id_foo']],	['td_end'],
			['td_start'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['tr_start', ['class' => 'required']],
			['th_start'], ['label_start', ['for' => 'id_bar']], ['content', 'Test field'], ['label_end'], ['th_end'],
			['td_start'], ['input', ['type' => 'text', 'name' => 'bar', 'id' => 'id_bar']],	['td_end'],
			['td_start'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['table_end'],
			['form_end'],
		]);
	}

	public function testCheckboxShouldGenerateHidden(){
		$this->generate(function($f){
			$f->checkbox('foo', 'Text');
		});
		$this->validate([
			['form_start'],
			['input', ['name' => 'foo', 'value' => '0', 'type' => 'hidden']],
			['table_start'],
			['tr_start', ['class' => null]],
			['th_start'], ['label_start'], ['content', 'Text'], ['label_end'], ['th_end'],
			['td_start'], ['input', ['type' => 'checkbox', 'name' => 'foo', 'id' => 'id_foo']],	['td_end'],
			['td_start'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['table_end'],
			['form_end'],
		]);
	}

	public function testCheckboxWithHint(){
		$this->generate(function($f){
			$f->checkbox('foo', 'Text', null, ['hint' => 'Lorem ipsum']);
		});
		$this->validate([
			['form_start'],
			['input', ['name' => 'foo', 'value' => '0', 'type' => 'hidden']],
			['table_start'],
			['tr_start', ['class' => null]],
			['th_start'], ['label_start'], ['content', 'Text'], ['label_end'], ['th_end'],
			['td_start'], ['input', ['type' => 'checkbox', 'name' => 'foo', 'id' => 'id_foo']],	['td_end'],
			['td_start'], ['content', 'Lorem ipsum'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['table_end'],
			['form_end'],
		]);
	}

	public function testCheckboxWithoutLabel(){
		$this->generate(function($f){
			$f->checkbox('foo', 'Text', false);
		});
		$this->validate([
			['form_start'],
			['input', ['name' => 'foo', 'value' => '0', 'type' => 'hidden']],
			['table_start'],
			['tr_start', ['class' => null]],
			['td_start'], ['label_start'], ['input', ['type' => 'checkbox', 'name' => 'foo', 'id' => 'id_foo']], ['content', 'Text'], ['label_end'], ['td_end'],
			['tr_end'],
			['table_end'],
			['form_end'],
		]);
	}

	public function testCheckboxInGroup(){
		$this->generate(function($f){
			$f->group('Group label', function($f){
				$f->checkbox('foo', 'Field label');
			});
		});
		$this->validate([
			['form_start'],
			['input', ['name' => 'foo', 'value' => '0', 'type' => 'hidden']],
			['table_start'],
			['tr_start'],
			['th_start'], ['content', 'Group label'], ['th_end'],
			['td_start'], ['label_start'], ['input', ['type' => 'checkbox', 'name' => 'foo', 'id' => 'id_foo']],	['content', 'Field label'], ['label_end'], ['td_end'],
			['td_start'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['table_end'],
			['form_end'],
		]);
	}

	public function testLayoutHintTworow(){
		$this->generate(function($f){
			$f->textarea('foo', 'Field label', ['tworow' => true, 'hint' => 'Lorem ipsum']);
		});
		$this->validate([
			['form_start'],
			['table_start'],
			['tr_start'],
			['th_start', ['colspan' => 4]], ['label_start', ['for' => 'id_foo']], ['content', 'Field label'], ['label_end'], ['th_end'],
			['tr_end'],
			['tr_start'],
			['td_start', ['colspan' => 2]], ['textarea_start', ['name' => 'foo', 'id' => 'id_foo']],	['textarea_end'], ['td_end'],
			['td_start'], ['content', 'Lorem ipsum'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['table_end'],
			['form_end'],
		]);
	}

	public function testLayoutHintTworowFill(){
		$this->generate(function($f){
			$f->textarea('foo', 'Field label', ['tworow' => true, 'fill' => true, 'hint' => 'Lorem ipsum']);
		});
		$this->validate([
			['form_start'],
			['table_start'],
			['tr_start'],
			['th_start', ['colspan' => 2]], ['label_start', ['for' => 'id_foo']], ['content', 'Field label'], ['label_end'], ['th_end'],
			['td_start'], ['content', 'Lorem ipsum'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['tr_start'],
			['td_start', ['colspan' => 4]], ['textarea_start', ['name' => 'foo', 'id' => 'id_foo']],	['textarea_end'], ['td_end'],
			['tr_end'],
			['table_end'],
			['form_end'],
		]);
	}
}
