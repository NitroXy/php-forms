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

	public function testTextField(){
		$this->generate(function($f){
			$f->text_field('foo', 'Test field');
		});
		$this->validate([
			['form_start'],
			['table_start'],
			['tr_start'],
			['th_start'], ['content', 'Test field'], ['th_end'],
			['td_start'], ['input', ['type' => 'text', 'name' => 'foo']],	['td_end'],
			['td_start'], ['td_end'],
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
			['th_start'], ['content', 'Test field'], ['th_end'],
			['td_start'], ['input', ['type' => 'text', 'name' => 'foo']],	['td_end'],
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
			['th_start'], ['content', 'Test field'], ['th_end'],
			['td_start'], ['input', ['type' => 'text', 'name' => 'foo']],	['td_end'],
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
			$f->group('Group', function($f){
				$f->text_field('foo', 'Field 1');
				$f->text_field('bar', 'Field 2');
				$f->text_field('baz', 'Field 3');
			});
		});
		$this->validate([
			['form_start'],
			['table_start'],
			['tr_start'],
			['th_start'], ['content', 'Group'], ['th_end'],
			['td_start'],
			['input', ['type' => 'text', 'name' => 'foo']],
			['input', ['type' => 'text', 'name' => 'bar']],
			['input', ['type' => 'text', 'name' => 'baz']],
			['td_end'],
			['td_start'], ['td_end'],
			['td_start'], ['td_end'],
			['tr_end'],
			['table_end'],
			['form_end'],
		]);
	}

}
