<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Helpers
 * @category Cache
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */
/**
 * Begin Document
 */
if (!function_exists('cache_clear'))
{

    /**
     * UFT8 Cleaner
     * 
     * Clean a string to store special characers correctly in the database
     * 
     * @param string $type Cache to be cleared. Can either be "memcached", "apc", or "disk"
     */
    function cache_clear($type)
    {
        $framework_ini = (array) unserialize(FRAMEWORK_INI);
        $memcache_port = ( !empty($framework_ini['database']['memcache_port']))
            ? $framework_ini['database']['memcache_port']
            : 11211;
        
        switch ($type)
        {
            case "memcached":
                if (class_exists('Memcache'))
                {
                    $memcache = new Memcache();
                    $memcache->connect('localhost', $memcache_port);
                    $memcache->flush();
                }
                break;

            case "apc":
                if (function_exists('apc_clear_cache'))
                {
                    apc_clear_cache();
                    apc_clear_cache('user');
                    apc_clear_cache('opcode');
                }
                break;

            case "disk":
                $CI = & get_instance();
                $cache_path = $CI->config->item('cache_path');
                foreach (glob($cache_path . '*') as $filename)
                {
                    unlink($filename);
                }
                break;
        }
    }
}

/* End of file cache_clearer_helper.php */
/* Location: ./application/helpers/cache_clearer_helper.php */