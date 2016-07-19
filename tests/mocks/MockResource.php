<?php

namespace NitroXy\PHPForms\Tests;

class MockResource extends \NitroXy\PHPForms\FormData {
	protected $error = [];

	public function setError($key, $message){
		$this->error[$key] = $message;
	}

	public function getErrorFor($key){
		return array_key_exists($key, $this->error) ? $this->error[$key] : false;
	}
}
