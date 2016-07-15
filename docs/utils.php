<?php

function htmlify($filename){
	ob_start();
	include($filename);
	$html = ob_get_contents();
	ob_end_clean();
	$geshi = new GeSHi(trim($html), 'html5');
	$geshi->set_header_type(GESHI_HEADER_PRE);
	$geshi->enable_keyword_links(false);
	echo $geshi->parse_code();
}

function display($filename){
	/* get source code but remove boilerplate */
	$code = file_get_contents($filename);
	$code = preg_replace('#<\?php( /\*~\*/.*\?[>])?#ims', '', $code);
	$geshi = new GeSHi(trim($code), 'php');
	echo $geshi->parse_code();
}

function code($code, $lang){
	$geshi = new GeSHi(trim($code), $lang);
	$geshi->enable_keyword_links(false);
	echo $geshi->parse_code();
}

function prototype_default_val($arg){
	$val = $arg->getDefaultValue();
	if ( is_array($val) && count($val) === 0 ){
		return '[]';
	}
	return var_export($val, true);
}

function prototype($method){
	$args = implode(', ', array_map(function($arg){
		$str = '';
		if ( $arg->isCallable() ){
			$str .= 'callable $';
		} else if ( $arg->isArray() ){
			$str .= 'array $';
		} else {
			$str .= '$';
		}
		$str .= $arg->name;
		if ( $arg->isDefaultValueAvailable() ){
			$str .= ' = ' . prototype_default_val($arg);
		}
		return $str;
	}, $method->getParameters()));
	return "{$method->name}({$args})";
}
