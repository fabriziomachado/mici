<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category SystemController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class SystemController extends MI_Controller
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

        $data['errorfiles'] = 0;
        $logpath = $this->config->item('log_path');

        $errorfiles = 0;
        if ($handle = opendir($logpath))
        {
            while (false !== ($file = readdir($handle)))
            {
                if (substr($file, 0, 4) == 'log-' && substr(strrchr($file, '.'), 1) == 'php')
                {
                    $errorfiles++;
                }
            }
            closedir($handle);
        }

        $data['errorfiles'] = $errorfiles;

        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
        $this->load->view('system/partials/header', $data);
        $this->load->view('system/dashboard', $data);
        $this->load->view('system/partials/footer', $data);
    }
}

/* End of file SystemController.php */
/* Location: ./application/controllers/system/SystemController.php */