<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."libraries/incs/template_lib".EXT;

class CI_Template extends CI_Template_lib {
	
    private $_set_template = FALSE;
    private $_template;
    
    var $css_core = array();
    var $css_view = array();
    var $js_core = array();
    var $js_view = array();
    
    public function __construct() 
    {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->library('head'); // load head library
    }
    
    public function set_template($group) 
    {
		$this->_set_template = $group;
        parent::set_template($group);
        $this->set_config();
        $this->set_head();
        $this->set_region();   	
    }
    
    private function set_config() 
    {
        include(APPPATH . 'config/template' . EXT);
       
        $_config = $template;
        if ($this->_set_template === FALSE)
            $this->_set_template = $_config['active_template'];
        
        $this->_template = $_config[$this->_set_template];
    }
    
    private function set_head() 
    {
        if (isset($this->_template['doctype']) && $this->_template['doctype'] != '')
            $this->CI->head->doctype = $this->_template['doctype'];
            
        if (isset($this->_template['use_favicon']) && $this->_template['use_favicon'] != '')
            $this->CI->head->use_favicon = $this->_template['use_favicon'];
            
        if (isset($this->_template['favicon_location']) && $this->_template['favicon_location'] != '')
            $this->CI->head->favicon_location = $this->_template['favicon_location'];
            
        if (isset($this->_template['meta_content']) && $this->_template['meta_content'] != '')
            $this->CI->head->meta_content = $this->_template['meta_content'];
            
        if (isset($this->_template['meta_language']) && $this->_template['meta_language'] != '')
            $this->CI->head->meta_language = $this->_template['meta_language'];
            
        if (isset($this->_template['meta_author']) && $this->_template['meta_author'] != '')
            $this->CI->head->meta_author = $this->_template['meta_author'];
            
        if (isset($this->_template['meta_description']) && $this->_template['meta_description'] != '')
            $this->CI->head->meta_description = $this->_template['meta_description'];
            
        if (isset($this->_template['meta_keywords']) && $this->_template['meta_keywords'] != '')
            $this->CI->head->meta_keywords = $this->_template['meta_keywords'];
            
        if (isset($this->_template['title']) && $this->_template['title'] != '')
            $this->CI->head->title = $this->_template['title'];
		
		if (isset($this->_template['body_id']) && $this->_template['body_id'] != '') 
        	$this->CI->head->body_id = $this->_template['body_id'];

		// set header Css
		if (count($this->_template['css']) > 0) 
		{
            foreach ($this->_template['css'] as $item)
            {
            	$item[1] = isset($item[1]) ? $item[1] : 'css_core';
            	$item[2] = isset($item[2]) ? $item[2] : 'link';
            	$item[3] = isset($item[3]) ? $item[3] : 'screen';
               	$this->add_css($item[0], $item[1], $item[2], $item[3]);
            }
		}
		
		// set header Javascript
        if (count($this->_template['js']) > 0) 
        {
            foreach ($this->_template['js'] as $item) 
            {
            	$item[1] = isset($item[1]) ? $item[1] : 'js_core';
            	$item[2] = isset($item[2]) ? $item[2] : 'import';
            	$item[3] = isset($item[3]) ? $item[3] : false;
               	$this->add_js($item[0], $item[1], $item[2], $item[3]);
            }
		}
    }
    
    private function set_region() 
    {	
	   	$regions_map = isset($this->_template['regions_map']) ? $this->_template['regions_map'] : '';
		
        if ($regions_map !== FALSE && $regions_map != '') 
        {
        	require APPPATH."controllers/template/".$regions_map.EXT;
        	$obj_class = new $regions_map;
        	
        	foreach($this->_template['regions'] as $method_name) 
        	{
				if(method_exists($obj_class, $method_name)) 
				{	
					call_user_func_array(array($obj_class, $method_name), array());
				}			
			}
        }
        return true;
    }
    
    public function render($region = NULL, $buffer = FALSE, $parse = FALSE) 
    {
        if (is_null($region)) {
            $template = parent::render(NULL, TRUE, $parse) . "\n";

            $template_core = $this->_template['template_core'];

            if ($buffer === FALSE) 
            {
                $render = $this->CI->load->view($template_core, array('template' => $template), TRUE);
                echo $render;
            } 
            else 
            {
                $render = $this->CI->load->view($template_core, array('template' => $template), TRUE);
                return $render;
            }
        } 
        else 
        {
            if ($buffer === FALSE) 
            {
                $render = parent::render($region, TRUE, $parse);
                echo $render;
            } 
            else 
            {
                $render = parent::render($region, $buffer, $parse);
                return $render;
            }
        }
    }
    
    public function add_css($style, $group = 'css_core', $type = 'link', $media = FALSE) 
    {
    	$css = null;
    	$filepath = null;
    	
    	if (!is_url($style)) 
    	{
    		$tmp_filedir = $this->CI->config->item('assets_dir').'/'.$style;
	    	if(file_exists($tmp_filedir)) 
	    	{
		    	$filepath = site_assets_url($style);
	    	} 
	    	else 
	    	{
		    	show_error('Sorry this file"'. site_assets_url( $style ) .'" file does not exist.');
	    	}
    	} 
    	else 
    	{
	    	$filepath = $style;
    	} 	
    	
    	switch ($type) 
    	{
	    	case 'link':
	    		$css = '<link type="text/css" rel="stylesheet" href="'. $filepath .'"';
	            if ($media) 
	            {
	               $css .= ' media="'. $media .'"';
	            }
	            $css .= ' />';
	            
	            break;
	        case 'import':
	        	$css = '<style type="text/css">@import url('. $filepath .');</style>';
	        	break;
	        case 'embed':
	        	$css = '<style type="text/css">';
	        	$css .= $style;
	        	$css .= '</style>';
	        	break;
    	}
    	
    	if ($css != NULL && !in_array( $css, $this->css_core ) && !in_array( $css, $this->css_view )) 
    	{
         	if ($group == 'css_core') 
         	{
         		//$this->write('_styles', $css);	
         		$this->css_core[] = $css;
         	} 
         	else 
         	{
         		$this->css_view[] = $css;
         	}
         }
         return TRUE;
         	
	}  
	
	public function add_js($script, $group = 'js_core', $type = 'import', $defer = FALSE) 
	{
		$js = null;
		$filepath = null;
		
		if (!is_url( $script )) 
		{
			$tmp_filedir = $this->CI->config->item('assets_dir').'/'.$script;
			if ($tmp_filedir) 
			{
	      		$filepath = site_assets_url( $script ); 
	      	} 
	      	else 
	      	{
	      		show_error('Sorry this file"'. site_asset_url( $script ) .'" file does not exist.');
	      	}
      	} 
      	else 
      	{
      		$filepath = $script;
      	}
		
		switch ($type) 
		{
			case 'import':
				$js = '<script type="text/javascript" src="'. $filepath .'"';
	            if ($defer) 
	            {
	               $js .= ' defer="defer"';
	            }
	            $js .= "></script>";
	            break;
	         case 'embed':
	         	$js = '<script type="text/javascript"';
	            if ($defer) 
	            {
	               $js .= ' defer="defer"';
	            }
	            $js .= ">";
	            $js .= $script;
	            $js .= '</script>';
	            break;
		}
		
		 // Add to js array if it doesn't already exist
      
		if ($js != NULL && !in_array($js, $this->js_core) && !in_array($js, $this->js_view)) 
		{
	         if ($group == 'js_core') 
	         {
	         	//$this->write('_scripts', $js);	
	         	$this->js_core[] = $js;
	         } 
	         else 
	         {
	           	$this->js_view[] = $js;
	         }
         }
      
         return TRUE;
		
	}
	
	public function display_js($type='js_core') 
	{
		switch ($type) 
		{
			case "js_core" : 
				if (!empty($this->js_core)) 
				{
					foreach($this->js_core as $j) 
					{
						echo $j."\n";
					}
				}
			break;
			case "js_view":
				if (!empty($this->js_view)) 
				{
					foreach($this->js_view as $j) 
					{
						echo $j."\n";
					}
				}
		} 
	}
	
	public function display_css($type='css_core') 
	{
		switch($type) 
		{
			case "css_core" :
				if (!empty($this->css_core)) 
				{
					foreach($this->css_core as $c) 
					{
						echo $c."\n";
					}
				}
			break; 
			case "css_view":
				if(!empty($this->css_view)) 
				{
					foreach($this->css_view as $c) 
					{
						echo $c."\n";
					}
				}
		}
	}

	public function add_seo_tag($value=array()) 
	{
		if (isset($value['title']) && @trim($value['title']) != '') 
		{
			$this->CI->head->title = trim($value['title']);
		}

		if (isset($value['keywords']) && @trim($value['keywords']) != '') 
		{
			$this->CI->head->meta_keywords = trim($value['keywords']);
		}

		if (isset($value['description']) && @trim($value['description']) != '') 
		{
			$this->CI->head->meta_description = trim($value['description']);
		}
	}

	public function add_fb_tag($value=array()) 
	{
		$og_tag = array();
		if (isset($value['title']) && @trim($value['title']) != '') 
		{
			$og_tag[] = meta('og:title', trim($value['title']), 'property');
		}

		if (isset($value['description']) && @trim($value['description']) != '') 
		{
			$og_tag[] = meta('og:description', trim($value['description']), 'property');
		}

		if (isset($value['image']) && @trim($value['image']) != '') 
		{
			$og_tag[] = meta('og:image', trim($value['image']), 'property');
		}

		if(isset($og_tag[0]))
			$this->CI->head->meta = $og_tag;
	}
} 