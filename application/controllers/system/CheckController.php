<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category CheckController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class CheckController extends CI_Controller
{
    /**
     * Construct
     */
    function __construct()
    {
        parent::__construct();

        // load helpers to fetch device specific view and get URL info
        $this->load->helper('url');

        // load path config data
        $this->load->config('paths');

        // load required libraries
        $this->load->library('pear_doctrine');

        // set some basic data all controllers will need
        $this->media_url = $this->config->item('media_url');
        $this->data['media_url'] = $this->media_url;

        // set some basic data all controllers will need
        $this->data['section'] = $this->uri->segment(2);
        $this->data['subsection'] = $this->uri->segment(3);
    }

    function index()
    {
        $this->output->enable_profiler(TRUE);

        // check if php is above version 5.2
        $data['php_image'] = (version_compare(PHP_VERSION, '5.2', '>='))
            ? 'accept'
            : 'exclamation';

        $data['php_msg'] = (version_compare(PHP_VERSION, '5.2', '>='))
            ? ''
            : '<strong>ERROR:</strong> PHP 5.2 or higher is required for this framework.';

        // check if memcache is installed
        $data['memcache_image'] = (class_exists('Memcache'))
            ? 'accept'
            : 'error';

        $data['memcache_msg'] = (class_exists('Memcache'))
            ? ''
            : '<strong>NOTICE:</strong> Memcache is not installed on this server.  This will not cause any issues but the Framework can gain significant perfomance increases if installed.';

        // check if spl_autoload_register is installed
        $data['spl_image'] = (function_exists('spl_autoload_register'))
            ? 'accept'
            : 'exclamation';

    $data['spl_msg'] = (function_exists('spl_autoload_register'))
            ? ''
            : '<strong>ERROR:</strong> The function spl_autoload_register does not exist on this server.  This function is required to autoload class files.';

        // check if INI file exists
        $data['ini_image'] = (file_exists(FRAMEWORK_CONFIG_INI))
            ? 'accept'
            : 'exclamation';

        $data['ini_msg'] = (file_exists(FRAMEWORK_CONFIG_INI))
            ? ''
            : '<strong>ERROR:</strong> We are unable to locate the file: <b>' . FRAMEWORK_CONFIG_INI . '</b><br /><br />Please make sure this file exists.  A template of this file can be found in the _install folder.';

        // Check if database is actually installed
        $framework_ini = (array) unserialize(FRAMEWORK_INI);
        $database = $framework_ini['database']['database'];

        // check log path
        $data['log_path_image'] = (is_writable($framework_ini['config']['log_path']))
            ? 'accept'
            : 'exclamation';

        $data['log_path_msg'] = (is_writable($framework_ini['config']['log_path']))
            ? ''
            : "<strong>ERROR:</strong> Your Log Folder {$framework_ini['config']['log_path']} is not writable.  Verify that it exists and that it has write permissions.";

        // check cache path
        $data['cache_path_image'] = (is_writable($framework_ini['config']['cache_path']))
            ? 'accept'
            : 'exclamation';

        $data['cache_path_msg'] = (is_writable($framework_ini['config']['cache_path']))
            ? ''
            : "<strong>ERROR:</strong> Your Cache Folder {$framework_ini['config']['cache_path']} is not writable.  Verify that it exists and that it has write permissions.";

        $db_exists = FALSE;

        try
        {
            // Try to see if we can connect to the database
            $conn = Doctrine_Manager::getInstance()->connection();
            $conn->execute("USE {$database}");
            $db_exists = TRUE;
        }
        catch (Doctrine_Connection_Exception $e)
        {
            // OOPS! database did not exist, lets try to create it
            try
            {
                // Try seeing if we have permission to make the database
                Doctrine::createDatabases();

                $db_exists = 'created';
                $db_image = 'error';
                $db_msg = "<strong>NOTICE:</strong> The database '{$database}' did not exist, but <strong>we were able to create it for you.</strong>";
            }
            catch (Doctrine_Connection_Exception $e)
            {
                // OOPS! We do not have permission to automatically generate the database
                $db_msg = "<strong>ERROR:</strong> The database '{$database}' does not exist and we were <u><strong>unable</strong></u> to create it for you.<br /><br /><strong>Response Received:</strong><br /> {$e->getMessage()}";
                $db_image = 'exclamation';
            }
        }

        $data['db_image'] = ($db_exists === TRUE)
            ? 'accept'
            : $db_image;

        $data['db_msg'] = ($db_exists === TRUE)
            ? ''
            : $db_msg;

        $tables_exists = FALSE;

        // Check if tables are installed in database
        try
        {
            $conn = Doctrine_Manager::getInstance()->connection();
            $conn->execute("SELECT * FROM `migration_version` LIMIT 1;");
            $tables_exists = TRUE;
        }
        catch (Doctrine_Connection_Exception $e)
        {
            // OOPS! our migration_version table should be there, but it's not
            try
            {
                // Try seeing if we have permission to create the tables...
                $conn = Doctrine_Manager::getInstance()->connection();

                // turn off foreign key checks
                $conn->execute('SET FOREIGN_KEY_CHECKS = 0');

                // create tables from models
                Doctrine::createTablesFromModels();

                $tables_exists = 'created';
                $tables_image = 'error';
                $tables_msg .= "<strong>NOTICE:</strong> The framework tables did not exist, but <strong>we were able to create them for you.</strong>";

                // load fixtures
                if(file_exists(realpath(APPPATH . '/doctrine/fixtures/data.yml')))
                {
                    try
                    {
                        Doctrine::loadData(realpath(APPPATH . '/doctrine/fixtures/data.yml'));
                    }
                    catch (Doctrine_Exception $e)
                    {
                        $tables_msg .= "<br /><br /><strong>ERROR:</strong> There was an issue while trying to import fixtures.<br /><br /><strong>Response Received:</strong><br /> {$e->getMessage()}";
                    }
                }
                else
                {
                    $tables_msg .= "<br /><br /><strong>ERROR:</strong> Tried to import data from ".APPPATH . '/doctrine/fixtures/data.yml'." but that file does not exist.";
                }

                // turn back on foreign key checks
                $conn->execute('SET FOREIGN_KEY_CHECKS = 1');
            }
            catch (Doctrine_Connection_Exception $e)
            {
                // OOPS! We do not have permission to automatically generate the tables
                $tables_image = 'exclamation';
                $tables_msg = "<strong>ERROR:</strong> The framework tables did not exist, and we were <u><strong>unable</strong></u> to create them for you.<br /><br /><strong>Response Received:</strong><br /> {$e->getMessage()}";
            }
        }

        $data['tables_image'] = ($tables_exists === TRUE)
            ? 'accept'
            : $tables_image;

        $data['tables_msg'] = ($tables_exists === TRUE)
            ? ''
            : $tables_msg;

        $data['media_url'] = $this->media_url;

        $data['failure'] = (!version_compare(PHP_VERSION, '5.2', '>=') || !function_exists('spl_autoload_register') || !file_exists(FRAMEWORK_CONFIG_INI) || !is_writable($framework_ini['config']['log_path']) || !is_writable($framework_ini['config']['cache_path']) || !$db_exists || !$tables_exists)
            ? TRUE
            : FALSE;

        // load content and push contant data
        $this->load->view('system/check', $data);
    }
}

/* End of file CheckController.php */
/* Location: ./application/controllers/system/CheckController.php */