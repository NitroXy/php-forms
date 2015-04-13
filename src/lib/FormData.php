<?php

namespace NitroXy\PHPForms;

class FormData {
	public function __construct($data){
		foreach ( $data as $key => $value ){
			$this->$key = $value;
		}
	}

	public function has_errors(){
		return false;
	}
};
