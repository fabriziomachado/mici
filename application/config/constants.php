<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Config
 * @category Constants
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/**
 * Framework Application Development
 *
 * Defined Application Constants for loading config file
 */
if (!defined('CENTRAL_SERVER'))
{
    $central_server_env = getenv('CENTRAL_SERVER')
        ? getenv('CENTRAL_SERVER')
        : FALSE;
    define('CENTRAL_SERVER', $central_server_env);
}
if (!defined('FRAMEWORK_APPLICATION_ENV'))
{
    $framework_application_env = getenv('FRAMEWORK_APPLICATION_ENV')
            ? getenv('FRAMEWORK_APPLICATION_ENV')
            : 'production';
    define('FRAMEWORK_APPLICATION_ENV', $framework_application_env);
}
if (!defined('FRAMEWORK_CONFIG_INI') || !getenv('FRAMEWORK_CONFIG_INI'))
{
    $framework_config_ini = getenv('FRAMEWORK_CONFIG_INI')
        ? getenv('FRAMEWORK_CONFIG_INI')
        : $_SERVER['DOCUMENT_ROOT'] . '/config/framework.ini';
    if (!file_exists($framework_config_ini))
    {
        exit('ERROR: Unable to locate configuration file.  Looking for ' . $framework_config_ini);
    }
    define('FRAMEWORK_CONFIG_INI', $framework_config_ini);
}

/**
 * Load INI File
 *
 * Fetch Configuration for Framework INI File
 */
if (!defined('FRAMEWORK_INI'))
{
    $framework_ini = serialize(parse_ini_file(FRAMEWORK_CONFIG_INI, TRUE));
    define('FRAMEWORK_INI', $framework_ini);
}

if (!defined('PAGE_TITLE_SEP'))
{
    define('PAGE_TITLE_SEP', ' &rsaquo; ');
}


/* End of file constants.php */
/* Location: ./application/config/constants.php */