<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('debug') ) 
{
	function debug($str) {
	    print "<pre>";
		print_r($str);
		print "</pre>";
	}
}