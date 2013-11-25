<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

	protected $_ci_utils_paths = array();
	protected $_ci_utils = array();

	public function __construct() {
		parent::__construct();
	}

	public function initialize($controller=null) {
        parent::initialize($controller);
        
        // config utils path and check autoloading
        $this->_ci_utils_paths = array(APPPATH, BASEPATH);
        $this->_ci_utils_autoloader();
    }

    public function util($utils = array()) {
        foreach ($this->_ci_prep_filename($utils, 'CI', true) as $util) {
            if (isset($this->_ci_utils[$util])) {
                continue;
            }
 
            $ext_util = APPPATH.'utils/'.config_item('subclass_prefix').$util.'.php';
 
            // Is this a util extension request?
            if (file_exists($ext_util)) {
                $base_util = BASEPATH.'utils/'.$util.'.php';
 
                if ( ! file_exists($base_util)) {
                    show_error('Unable to load the requested file: utils/'.$util.'.php');
                }
 
                include_once($ext_util);
                include_once($base_util);
 
                $this->_ci_utils[$util] = TRUE;
                log_message('debug', 'util loaded: '.$util);
                continue;
            }
 
            // Try to load the util
            foreach ($this->_ci_utils_paths as $path) {
                if (file_exists($path.'utils/'.$util.'.php')) {
                    include_once($path.'utils/'.$util.'.php');
 
                    $this->_ci_utils[$util] = TRUE;
                    log_message('debug', 'Util loaded: '.$util);
                    break;
                }
            }
 
            // unable to load the util
            if ( ! isset($this->_ci_utils[$util])) {
                show_error('Unable to load the requested file: utils/'.$util.'.php');
            }
        }
    }

    public function utils($utils) {
        foreach ($utils as $_util) $this->util($_util);  
    }

    protected function _ci_prep_filename($filename, $extension, $opposite=false) {
        if ($opposite == false) {
            return parent::_ci_prep_filename($filename, $extension);
        }
         
        // opposite prep in front
        if ( ! is_array($filename)) {
            return array($extension.ucfirst(str_replace('.php', '', str_replace($extension, '', $filename))));
        } else {
            foreach ($filename as $key => $val) {
                $filename[$key] = $extension.ucfirst(str_replace('.php', '', str_replace($extension, '', $val)));
            }
            return $filename;
        }
    }

    private function _ci_utils_autoloader() {
        if (defined('ENVIRONMENT') AND file_exists(APPPATH.'config/'.ENVIRONMENT.'/autoload.php')) {
            include(APPPATH.'config/'.ENVIRONMENT.'/autoload.php');
        } else {
            include(APPPATH.'config/autoload.php');
        }
 
        if ( ! isset($autoload)) {
            return FALSE;
        }
 
        // auto load utils 
        if (isset($autoload['utils']) AND count($autoload['utils']) > 0) {
            $this->utils($autoload['utils']);
        }
    }
}