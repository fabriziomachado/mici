<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category SettingsController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class SettingsController extends MI_Controller
{
    /**
     * Construct
     */
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
        $this->load->view('system/partials/header', $data);
        $this->load->view('system/settings', $data);
        $this->load->view('system/partials/footer', $data);
    }

    function flushcache()
    {
        $cache_path = $this->config->item('cache_path');
        $disk_cache_count = 0;
        foreach (glob($cache_path . '*') as $filename)
        {
            $disk_cache_count += 1;
        }

        $data = array();
        $data = array_merge($data, $this->data);

        $data['apc_installed'] = (function_exists('apc_clear_cache'))
            ? TRUE
            : FALSE;

        $data['memcache_installed'] = (class_exists('Memcache'))
            ? TRUE
            : FALSE;

        $data['diskcache_installed'] = ($disk_cache_count > 0)
            ? TRUE
            : FALSE;

        $data['disk_cache_count'] = ($disk_cache_count !== 1)
            ? $disk_cache_count . ' Cache Files'
            : $disk_cache_count . ' Cache File';

        if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->input->post('action') === 'flush')
        {
            if ($data['apc_installed'] && $this->input->post('apc') === 'yes')
            {
                apc_clear_cache();
                apc_clear_cache('user');
                apc_clear_cache('opcode');

                $data['cache_status'][] = 'APC Cache Cleared';
            }

            if ($data['memcache_installed'] && $this->input->post('memcache') === 'yes')
            {
                $memcache = new Memcache();
                $memcache->connect('localhost', 11211);
                $memcache->flush();

                $data['cache_status'][] = 'Memcache Cleared';
            }

            if ($data['diskcache_installed'] && $this->input->post('disk') === 'yes')
            {
                foreach (glob($cache_path . '*') as $filename)
                {
                    unlink($filename);
                }

                $data['cache_status'][] = 'Disk Cache Cleared';
            }
        }

        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
        $this->load->view('system/partials/header', $data);
        $this->load->view('system/settings/flushcache', $data);
        $this->load->view('system/partials/footer', $data);
    }
}

/* End of file SettingsController.php */
/* Location: ./application/controllers/system/SettingsController.php */