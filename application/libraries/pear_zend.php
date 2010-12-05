<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category Pear_zend
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class Pear_zend
{
    /**
     * Constructor
     */
    function __construct()
    {
        // Add local Zend library to include direcotry
        set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());

        // Prepare Autoload of Zend PEAR library
        require_once 'Zend/Loader/Autoloader.php';

        if (!class_exists('Zend_Loader_Autoloader'))
        {
            show_error('Zend PEAR Library Not Installed.');
        }
        else
        {
            Zend_Loader_Autoloader::getInstance();
        }
    }
}

/* End of file pear_zend.php */
/* Location: ./application/libraries/pear_zend.php */