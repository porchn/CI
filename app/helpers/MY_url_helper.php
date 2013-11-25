<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('is_url') ) 
{
	function is_url($url) 
	{
	    return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}
}

if ( !function_exists('site_assets_url') ) 
{
	function site_assets_url($url = '') 
	{
		$CI =& get_instance();
		if (is_array($url)) 
		{
			$url = implode('/', $url);
		}
		return ($url == '') ? $CI->config->slash_item('assets_url') : $CI->config->slash_item('assets_url').trim($url, '/');	
	}
}

if ( !function_exists('site_url') ) 
{
	function site_url($url = '') 
	{
		$CI =& get_instance();
		if (is_array($url)) 
		{
			$url = implode('/', $url);
		}
		return ($url == '') ? $CI->config->item('base_url') : $CI->config->slash_item('base_url').trim($url, '/');	
	}
}

if ( !function_exists('site_vendor_url') ) 
{
	function site_vendor_url($url = '')
	{
		$CI =& get_instance();
		if (is_array($url)) 
		{
			$url = implode('/', $url);
		}
		return ($url == '') ? $CI->config->item('vendor_url') : $CI->config->slash_item('vendor_url').trim($url, '/');	
	}
}

?>