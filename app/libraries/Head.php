<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."libraries/incs/head_lib".EXT;

class Head extends Head_Lib {
    	
	public function __construct() 
    {
		parent::__construct();	
	}
	
	public function render_html() 
    {
        if (in_array($this->doctype, $this->xml_doctypes)) 
        {
            return '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $this->meta_language . '" lang="' . $this->meta_language . '">' . parent::bump();
        } 
        elseif($this->doctype == 'html5') 
        {
            return '<html dir="ltr" lang="'.$this->meta_content.'">' . parent::bump();
        } 
        else
        {
	        return '<html>' . parent::bump();
        }
    }
    
    public function render_meta() 
    {
	    if ($this->use_meta == FALSE)
            return FALSE;
            
        $out = '';
       	if ($this->meta_content) 
        {
            $out .= $this->metaFormat('content-type', $this->meta_content, 'equiv').$this->bump();
        }
        
        if ($this->meta_description) 
        {
        	$out .= $this->metaFormat('description', $this->meta_description).$this->bump();
        }
        
        if ($this->meta_keywords) 
        {
            $out .= $this->metaFormat('keywords', $this->meta_keywords).$this->bump();
        }
        
        if (count($this->meta) > 0) 
        {
            foreach ($this->meta as $meta_item) 
            {
                $out .= $meta_item . $this->indent();
            }
        }
        return $out .= $this->bump(FALSE);
    } 

    private function metaFormat($name, $content, $type='') 
    {
	    if($this->doctype == 'html5') 
        {
		    if ($name == 'content-type') 
            {
				return '<meta charset="'.$content.'" />';			    
		    } 
            else 
            {
			    return '<meta name="'.$name.'" content="'.$content.'" />';
		    }
	    } 
        else 
        {
		    return meta($name, $content, $type);
	    }
    }
    
}