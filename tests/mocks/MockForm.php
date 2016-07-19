<?php

namespace NitroXy\PHPForms\Tests;

use \NitroXy\PHPForms\FormData;

class MockForm extends \NitroXy\PHPForms\Form {
	static public function createMock($id, $callback, array $options=[]){
		$resource = new FormData([]);
		$form = static::createInstance($id, $callback);
		$form->popAttr('resource', $options, $resource);
		$form->parseOptions($options);
		$form->res = $resource;
		$form->render();
	}
}
