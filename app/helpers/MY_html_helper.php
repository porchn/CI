<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('meta')) 
{
	function meta($name = '', $content = '', $type = 'name', $newline = "\n") 
	{
		// Since we allow the data to be passes as a string, a simple array
		// or a multidimensional one, we need to do a little prepping.
		if ( ! is_array($name)) 
		{
			$name = array(array('name' => $name, 'content' => $content, 'type' => $type, 'newline' => $newline));
		} 
		else 
		{
			// Turn single array into multidimensional
			if (isset($name['name'])) 
			{
				$name = array($name);
			}
		}
		
		$str = '';
		foreach ($name as $meta) 
		{
			$type		= ( ! isset($meta['type']) OR $meta['type'] == 'name') ? 'name' :$meta['type'];
			$name		= ( ! isset($meta['name']))		? ''	: $meta['name'];
			$content	= ( ! isset($meta['content']))	? ''	: $meta['content'];
			$newline	= ( ! isset($meta['newline']))	? "\n"	: $meta['newline'];
			
			$str .= '<meta '.$type.'="'.$name.'" content="'.$content.'" />'.$newline;
		}

		return $str;
	}
}

/* End of file MY_html_helper.php */
/* Location: ./application/helpers/html_helper.php */