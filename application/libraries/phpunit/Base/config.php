<?PHP if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category PHPUnit
 */

/**
 * Begin Document
 */

define('FSPATH', APPPATH.'libraries/PHPUnit/');

function foo_config()
{
    $config['phpunit']['prefix'] = 'PHPUnit';
    $config['phpunit']['active_plugins']= array('PHPUnit','ME');
    return $config['phpunit'];
}

/* End of file config.php */
/* Location: ./application/libraries/phpunit/Base/config.php */