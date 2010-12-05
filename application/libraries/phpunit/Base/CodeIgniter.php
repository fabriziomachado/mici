<?PHP if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category PHPUnit
 */

/**
 * Begin Document
 */
 
define('CI_VERSION',	'1.7.2');

/**
 *  Load the global functions
 */
require(APPPATH.'libraries/PHPUnit/Base/Common.php');

/**
 *  Load the compatibility override functions
 */
require(BASEPATH.'codeigniter/Compat'.EXT);

/**
 *  Load the framework constants
 */
require(APPPATH.'config/constants'.EXT);

/**
 *  Define a custom error handler so we can log PHP errors
 */
set_error_handler('_exception_handler');

if ( ! is_php('5.3'))
{
	@set_magic_quotes_runtime(0);
}

/**
 *  Start the timer
 */

$BM =& load_class('Benchmark');
$BM->mark('total_execution_time_start');
$BM->mark('loading_time_base_classes_start');

/**
 *  Instantiate the hooks class
 */

$EXT =& load_class('Hooks');

/**
 *  Is there a "pre_system" hook?
 */
$EXT->_call_hook('pre_system');

/**
 *  Instantiate the base classes
 */

$CFG =& load_class('Config');
$URI =& load_class('URI');
$RTR =& load_class('Router');
$GLOBALS['OUT'] =& load_class('Output');
$OUT = &$GLOBALS['OUT'];

/**
 *	Is there a valid cache file?  If so, we're done...
 */

if ($EXT->_call_hook('cache_override') === FALSE)
{
	if ($OUT->_display_cache($CFG, $URI) == TRUE)
	{
		exit;
	}
}

/**
 *  Load the remaining base classes
 */

$IN		=& load_class('Input');
$LANG	=& load_class('Language');

/**
 *  Load the app controller and local controller
 *
 *  Note: Due to the poor object handling in PHP 4 we'll
 *  conditionally load different versions of the base
 *  class.  Retaining PHP 4 compatibility requires a bit of a hack.
 *
 *  Note: The Loader class needs to be included first
 */
if ( ! is_php('5.0.0'))
{
	load_class('Loader', FALSE);
	require(BASEPATH.'codeigniter/Base4'.EXT);
}
else
{
    require(FSPATH.'Base/Base5'.EXT);
}

load_class('Controller', FALSE);

if(defined('CIUnit_Version') === FALSE){

    if ( ! file_exists(APPPATH.'controllers/'.$RTR->fetch_directory().$RTR->fetch_class().EXT))
    {
        show_error('Unable to load your default controller.  Please make sure the controller specified in your Routes.php file is valid.');
    }

    include(APPPATH.'controllers/'.$RTR->fetch_directory().$RTR->fetch_class().EXT);

    $BM->mark('loading_time_base_classes_end');


    /**
     *  Security check
     *
     *  None of the functions in the app controller or the
     *  loader class can be called via the URI, nor can
     *  controller functions that begin with an underscore
     */
    $class  = $RTR->fetch_class();
    $method = $RTR->fetch_method();

    if ( ! class_exists($class)
        OR $method == 'controller'
        OR strncmp($method, '_', 1) == 0
        OR in_array(strtolower($method), array_map('strtolower', get_class_methods('Controller')))
        )
    {
        show_404("{$class}/{$method}");
    }

    /**
     *  Is there a "pre_controller" hook?
     */
    $EXT->_call_hook('pre_controller');

    /**
     *  Instantiate the controller and call requested method
     */

    $BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_start');

    $CI = new $class();

    if ($RTR->scaffolding_request === TRUE)
    {
        if ($EXT->_call_hook('scaffolding_override') === FALSE)
        {
            $CI->_ci_scaffolding();
        }
    }
    else
    {
        /**
         *  Is there a "post_controller_constructor" hook?
         */
        $EXT->_call_hook('post_controller_constructor');

        if (method_exists($CI, '_remap'))
        {
            $CI->_remap($method);
        }
        else
        {
            if ( ! in_array(strtolower($method), array_map('strtolower', get_class_methods($CI))))
            {
                show_404("{$class}/{$method}");
            }

            call_user_func_array(array(&$CI, $method), array_slice($URI->rsegments, 2));
        }
    }

    $BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_end');

    /**
     *  Is there a "post_controller" hook?
     */
    $EXT->_call_hook('post_controller');

    /**
     *  Send the final rendered output to the browser
     */

    if ($EXT->_call_hook('display_override') === FALSE)
    {
        $OUT->_display();
    }

    /**
     *  Is there a "post_system" hook?
     */
    $EXT->_call_hook('post_system');

    /**
     *  Close the DB connection if one exists
     */
    if (class_exists('CI_DB') AND isset($CI->db))
    {
        $CI->db->close();
    }

}

/* End of file CodeIgniter.php */
/* Location: ./application/libraries/phpunit/Base/CodeIgniter.php */