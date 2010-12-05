<?PHP if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category PHPUnit
 */

/**
 * Begin Document
 */

$libraryDir = APPPATH . 'libraries/PHPUnit/Spyc';

if (!is_dir($libraryDir))
{
    exit("Spyc must be located in \"$libraryDir\"");
}

require_once($libraryDir . '/spyc.php');

/* End of file Fixture.php */
/* Location: ./application/libraries/phpunit/Fixture.php */