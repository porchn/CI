<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CIUser 
{
	public static function get_info($uid) 
	{
		$userinfo = array();
        return new CIUserAttibutes($userinfo);
    }
    
    public static function get_auth_info($is_admin=false) 
    {
    	$authinfo = array();
    	return new CIUserAttibutes($authinfo);
    }
}

class CIUserAttibutes 
{	
	private $_userinfo;
	
	public function __construct($userinfo) 
	{
		$this->_userinfo = $userinfo;
	}
	
	public function get_attrs() 
	{
		if(is_array($this->_userinfo) && $this->_userinfo !== false) 
		{
			return $this->_userinfo;
		}
		return;
	}

	public function get_attr($attr=null) 
	{
		if (array_key_exists($attr, $this->_userinfo)) 
		{
            return $this->_userinfo[$attr];
        }
        return;
	}

}