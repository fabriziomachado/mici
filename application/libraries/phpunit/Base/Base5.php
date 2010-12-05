<?PHP if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category PHPUnit
 */

/**
 * Begin Document
 */

/**
 * CI_BASE - For PHP 5
 *
 * This file contains some code used only when CodeIgniter is being
 * run under PHP 5.  It allows us to manage the CI super object more
 * gracefully than what is possible with PHP 4.
 *
 * @package		CodeIgniter
 * @subpackage	codeigniter
 * @category	front-controller
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/
 */

class CI_Base
{
	public static $instance;

	public function CI_Base()
	{
		self::$instance =& $this;
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
}

function &get_instance()
{
	return CI_Base::get_instance();
}

/* End of file Base5.php */
/* Location: ./application/libraries/phpunit/Base/Base5.php */