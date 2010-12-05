<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category PearDoctrine
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 * @author John Kramlich <John@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class Pear_doctrine
{
    /**
     * Constructor
     *
     * Create a new client object and fill defaults
     */
    function __construct()
    {
        $framework_ini = (array) unserialize(FRAMEWORK_INI);
        $dbdriver = $framework_ini['database']['dbdriver'];
        $username = $framework_ini['database']['username'];
        $password = $framework_ini['database']['password'];
        $hostname = $framework_ini['database']['hostname'];
        $database = $framework_ini['database']['database'];

        // Fetch PEAR Library
        require_once APPPATH . 'libraries/Doctrine/Doctrine.php';

        // Check if Doctrine Exists
        if (class_exists('Doctrine_Core'))
        {
            // Autoload Doctrine Classes
            spl_autoload_register(array('Doctrine', 'autoload'));
            spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));

            // Check if Database Object Exists
            if (!empty($dbdriver) && !empty($username) && !empty($hostname) && !empty($database))
            {
                // see if we can connect to the database
                try
                {
                    if(strpos($hostname, ':'))
                    {
                        list($host, $socket) = explode(':', $hostname);
                        $pdo = new PDO("mysql:host={$host};dbname={$database};unix_socket={$socket}", $username, $password);
                    }
                    else
                    {
                        $pdo = new PDO("mysql:host={$hostname};dbname={$database}", $username, $password);
                    }

                    unset($pdo);
                }
                // database does not exist, lets see if we can create it
                catch(PDOException $e)
                {
                    if( !strpos($_SERVER['QUERY_STRING'], 'system/check'))
                    {
                        // log error message
                        log_message('error', $e->getMessage());

                        // redirect since there does not appear to be a database installed
                        header('HTTP/1.1 301 Moved Permanently');
                        header('Location: '.$framework_ini['config']['base_url'].'system/check');
                        exit('Redirecting to /system/check ...');
                    }
                }

                if(strpos($hostname, ':'))
                {
                    list($host, $socket) = explode(':', $hostname);
                    $pdo = new PDO("mysql:host={$host};dbname={$database};unix_socket={$socket}", $username, $password);
                    $conn = Doctrine_Manager::connection($pdo, 'default');
                }
                else
                {
                    $conn = Doctrine_Manager::connection("{$dbdriver}://{$username}:{$password}@{$hostname}/{$database}", 'default');
                }
                
                $manager = Doctrine_Manager::getInstance();

                // Configure Doctrine
                $manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, TRUE);
                $manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, TRUE);
                $manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE);
                $manager->setAttribute(Doctrine::ATTR_IDXNAME_FORMAT, '%s');
                $manager->setAttribute(Doctrine::ATTR_DEFAULT_COLUMN_OPTIONS, array('notnull' => FALSE, 'unsigned' => TRUE));
                $manager->setAttribute(Doctrine::ATTR_DEFAULT_IDENTIFIER_OPTIONS, array('name' => 'id', 'type' => 'integer', 'length' => 4));
                $manager->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, TRUE);

                // Loading of models must be done after configuration of Doctrine.  Otherwise, base classes will not get loaded properly
                Doctrine_Core::loadModels(APPPATH . '/models/doctrine/');

                // Add Memcache Support if it is Installed
                if (class_exists('Memcache'))
                {
                    // Create Cache Driver
                    $cacheDriver = new Doctrine_Cache_Memcache(
                        array(
                            'servers' => array(
                                array(
                                    'host' => 'localhost',
                                    'port' => 11211,
                                    'persistent' => TRUE
                                )
                            ),
                            'compression' => TRUE
                        )
                    );

                    // Add Caching Abilities and set default Cache Lifespan to 60 seconds
                    $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
                    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);
                    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE_LIFESPAN, 60);
                }
            }
            else
            {
                // No Database Object
                show_error('Doctrine Failed to find Database Connection Information.');
            }
        }
        else
        {
            // No Doctrine Library
            show_error('Doctrine PEAR Library Not Installed.');
        }
    }
}

/* End of file pear_doctrine.php */
/* Location: ./application/libraries/pear_doctrine.php */