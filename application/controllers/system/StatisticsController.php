<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category StatisticsController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class StatisticsController extends MI_Controller
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
        $this->load->view('system/statistics', $data);
        $this->load->view('system/partials/footer', $data);
    }

    function pageviews()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
        $this->load->view('system/partials/header', $data);
        $this->load->view('system/statistics', $data);
        $this->load->view('system/partials/footer', $data);
    }

    function visitors()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
        $this->load->view('system/partials/header', $data);
        $this->load->view('system/statistics', $data);
        $this->load->view('system/partials/footer', $data);
    }

    function userlogins()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
        $this->load->view('system/partials/header', $data);
        $this->load->view('system/statistics', $data);
        $this->load->view('system/partials/footer', $data);
    }
}

/* End of file StatisticsController.php */
/* Location: ./application/controllers/system/StatisticsController.php */