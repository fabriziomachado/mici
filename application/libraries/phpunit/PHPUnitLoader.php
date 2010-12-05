<?PHP if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category PHPUnit
 */

/**
 * Begin Document
 */

class PHPUnitLoader extends CI_Loader
{
    var $_ci_loaded_files = array();

    function __construct()
    {
        parent::__construct();
    }

    /**
	 * Load class
	 *
	 * This function loads the requested class.
	 *
	 * @access	private
	 * @param 	string	the item that is being loaded
	 * @param	mixed	any additional parameters
	 * @return 	void
	 */
	function _ci_load_class($class, $params = NULL, $object_name = NULL)
	{
		$class = str_replace(EXT, '', $class);
        $prefix = config_item('subclass_prefix');
        $is_fooclass = FALSE;

        $folders = explode('/', $class);
        if (count($folders) > 1)
        {
            $class = array_pop($folders);
            $folders = join('/', $folders).'/';
        }
        else
        {
            $folders = '';
        }
		
		foreach (array(ucfirst($class), strtolower($class)) as $class)
		{
			$subclass = APPPATH.'libraries/'.$folders.$prefix.$class.EXT;

			if (file_exists($subclass))
			{
				$baseclass = BASEPATH.'libraries/'.ucfirst($class).EXT;
                if (file_exists(FSPATH.config_item('PHPUnit_prefix').$class.EXT))
                {
                    require(APPPATH.'libraries/phpunit/PHPUnit'.$class.EXT);
                    $is_fooclass = TRUE;
                }

				if ( !file_exists($baseclass) )
				{
					log_message('error', "Unable to load the requested class: ".$class);
					show_error("Unable to load the requested class: ".$class);
				}

				$include_files = TRUE;

				if (in_array($subclass, $this->_ci_loaded_files))
				{
                    if ( !defined('CIUnit_Version') )
                    {
                        $is_duplicate = TRUE;
                        log_message('debug', $class." class already loaded. Second attempt ignored.");
                        return;
                    }
                    else
                    {
                        $include_files = FALSE;
                    }
				}
                if ($include_files)
                {
    				include($baseclass);
    				include($subclass);
    				$this->_ci_loaded_files[] = $subclass;
                }

				return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
			}

			$is_duplicate = FALSE;
			foreach (array(BASEPATH.'libraries/', APPPATH.'libraries/', FSPATH) as $path)
			{
				$filepath = $path.$folders.$class.EXT;

				if ( ! file_exists($filepath))
				{
					continue;
				}

                $include_files = TRUE;
				if (in_array($filepath, $this->_ci_loaded_files))
				{
                    if(!defined('CIUnit_Version'))
                    {
                        $is_duplicate = TRUE;
                        log_message('debug', $class." class already loaded. Second attempt ignored.");
                        return;
                    }
                    else
                    {
                        $include_files = FALSE;
                    }
				}
                if ($include_files)
                {
    				include($filepath);
    				$this->_ci_loaded_files[] = $filepath;
                }
				return $this->_ci_init_class($class, '', $params, $object_name);
			}
		}

		if ($folders == '')
		{
			$path = strtolower($class).'/'.$class;
			return $this->_ci_load_class($path, $params);
		}

		if ($is_duplicate == FALSE)
		{
			log_message('error', "Unable to load the requested class: ".$class);
			show_error("Unable to load the requested class: ".$class);
		}
	}

     /**
	 * Instantiates a class
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	null
	 */
	function _ci_init_class($class, $prefix = '', $config = FALSE, $object_name = NULL)
	{
		if ($config === NULL)
		{
			foreach(array(ucfirst($class), strtolower($class)) as $clsName)
			{
    			if (file_exists(APPPATH.'config/'.$clsName.EXT))
    			{
    				include(APPPATH.'config/'.$clsName.EXT);
    			}
			}
		}

		if ($prefix == '')
		{
			$name = (class_exists('CI_'.$class)) ? 'CI_'.$class : $class;
		}
		else
		{
			$name = $prefix.$class;
		}

		$class = strtolower($class);
		if (is_null($object_name))
		{
			$classvar = ( ! isset($this->_ci_varmap[$class])) ? $class : $this->_ci_varmap[$class];
		}
		else
		{
			$classvar = $object_name;
		}
		
		$CI =& get_instance();
		if ($config !== NULL)
		{
            if (!defined('CIUnit_Version'))
            {
			    $CI->$classvar = new $name($config);
            }
            elseif (!isset($CI->$classvar))
            {
                $CI->$classvar = new $name($config);
            }
		}
		else
		{
            if (!defined('CIUnit_Version'))
            {
			    $CI->$classvar = new $name;
            }
            elseif (!isset($CI->$classvar))
            {
                $CI->$classvar = new $name($config);
            }
		}
		
		$this->_ci_classes[$class] = $classvar;
	}

     /**
	 * Database Loader
	 *
	 * @access	public
	 * @param	string	the DB credentials
	 * @param	bool	whether to return the DB object
	 * @param	bool	whether to enable active record (this allows us to override the config setting)
	 * @return	object
	 */
	function database($params = '', $return = FALSE, $active_record = FALSE)
	{
        if (isset($this->_ci_db) and !$return)
		{
		    $CI =& get_instance();
            $CI->db = $this->_ci_db;
        }
		else
		{
    		if (class_exists('CI_DB') AND $return == FALSE AND $active_record == FALSE)
    		{
    			return FALSE;
    		}

    		require_once(BASEPATH.'database/DB'.EXT);

            $db =& DB($params, $active_record);

            $my_driver = config_item('subclass_prefix').'DB_'.$db->dbdriver.'_driver';
            $my_driver_file = APPPATH.'libraries/'.$my_driver.EXT;

            if (file_exists($my_driver_file))
            {
                require_once($my_driver_file);
                $db =& new $my_driver(get_object_vars($db));
            }

            if ($return === TRUE)
            {
                return $db;
            }
            
			$CI =& get_instance();

            $CI->db = '';
            $CI->db = $db;
            $this->_ci_db =$CI->db;
        }
		$this->_ci_assign_to_models();
	}

	/**
	 * Autoloader
	 *
	 * The config/autoload.php file contains an array that permits sub-systems,
	 * libraries, plugins, and helpers to be loaded automatically.
	 *
	 * @access	private
	 * @param	array
	 * @return	void
	 */
	function _ci_autoloader()
	{
        include(APPPATH.'config/autoload'.EXT);

		if ( ! isset($autoload))
		{
			return FALSE;
		}

		if (count($autoload['config']) > 0)
		{
			$CI =& get_instance();
			foreach ($autoload['config'] as $key => $val)
			{
				$CI->config->load($val);
			}
		}

		foreach (array('helper', 'plugin', 'language') as $type)
		{
			if (isset($autoload[$type]) AND count($autoload[$type]) > 0)
			{
				$this->$type($autoload[$type]);
			}
		}

		if ( ! isset($autoload['libraries']))
		{
			$autoload['libraries'] = $autoload['core'];
		}

		if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0)
		{
			if (in_array('database', $autoload['libraries']))
			{
				$this->database();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
			}
			if (in_array('scaffolding', $autoload['libraries']))
			{
				$this->scaffolding();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('scaffolding'));
			}
			foreach ($autoload['libraries'] as $item)
			{
				$this->library($item);
			}
		}
		if (isset($autoload['model']))
		{
			$this->model($autoload['model']);
		}
	}
    
   /*
    * Can load a view file from an absolute path and
    * relative to the CodeIgniter index.php file
    * Handy if you have views outside the usual CI views dir
    */
    function viewfile($viewfile, $vars = array(), $return = FALSE)
    {
		return $this->_ci_load(
            array('_ci_path' => $viewfile,
                '_ci_vars' => $this->_ci_object_to_array($vars),
                '_ci_return' => $return
			)
        );
    }
}

/* End of file PHPUnitLoader.php */
/* Location: ./application/libraries/phpunit/PHPUnitLoader.php */