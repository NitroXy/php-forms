<?php

namespace NitroXy\PHPForms;

class FormData {
	protected $data;

	public function __construct($data){
		if ( $data === null ){
			$this->data = new \stdClass();
			return;
		} else if ( is_array($data) ){
			$this->data = (object)$data;
		} else {
			$this->data = $data;
		}
	}

	public function getValueFor($key){
		return isset($this->data->$key) ? $this->data->$key : false;
	}

	public function getId(){
		return $this->getValueFor('id');
	}

	public function getErrorFor($field){
		return false;
	}
};
