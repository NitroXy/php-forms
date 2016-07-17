<?php

namespace NitroXy\PHPForms;

class FormData {
	public function __construct(array $data = null){
		if ( $data === null ) return;

		foreach ( $data as $key => $value ){
			$this->$key = $value;
		}
	}

	public function has_errors(){
		return false;
	}
};
