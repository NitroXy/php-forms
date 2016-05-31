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
