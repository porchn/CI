<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ExtLibs
{
	function index()
        {
			ini_set('include_path',ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'third_party');
		}
}

?>