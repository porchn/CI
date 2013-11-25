<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."libraries/extlibs/facebook/facebook".EXT;

class Fbauth 
{	
	public $appId;
	public $secret;
	public $userScope = 'photo_upload,publish_stream,email,user_birthday,user_likes,user_photos,user_about_me,read_friendlists';
	public $allowFileUpload = true;
	
	public $facebook;
	public $CI;

	public function __construct($config=array()) 
	{
		$this->CI =& get_instance();
		$this->initialize($config);
	}
	
	public function initialize($config) 
	{
		$this->appId = $config['app_id'];
		$this->secret = $config['secret_code'];
		$this->userScope = isset($config['user_scope']) ? $config['user_scope'] : $this->userScope;
		$this->allowFileUpload = isset($config['file_upload']) ? $config['file_upload'] : $this->allowFileUpload;

		$this->facebook = new Facebook(array(
		   'appId'  => $this->appId,
		   'secret' =>  $this->secret,
		   'fileUpload' => true,
		   'cookie' => true
		));
	}

	public function chkAuth($redirectUrl = '')
	{
		$chkUserAuth = $this->facebook->getUser();
		if (!$chkUserAuth) {
			$params = array(
					'scope' => $this->userScope,
					'redirect_uri' => $redirectUrl
				);
			$loginUrl = $this->facebook->getLoginUrl($params);
			echo '<script>window.top.location="'.$loginUrl.'"</script>';
			exit;
		}
		return true;
	}
	
	public function getUserProfile($accessToken=null) 
	{
		if (!is_null($accessToken)) 
		{
			$this->facebook->setAccessToken($accessToken);
			return $this->facebook->api('/me', 'GET');
		} 
		else 
		{
			if ($this->chkAuth()) 
			{
				$userProfile = $this->facebook->api('/me', 'GET');
				return $userProfile;
			}
			return true;
		}
	}
}

?>