<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category FaultController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class FaultController extends MI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $data['footer_menu'] = $this->load->view('_partials/browser/footer_menu', $data, TRUE);

        $error_code = 403;

        $this->output->set_status_header($error_code);
        $this->load->view('browser/error/'.$error_code, $data);
    }

    function code()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $data['footer_menu'] = $this->load->view('_partials/browser/footer_menu', $data, TRUE);

        $error_code = $this->uri->segment(3);

        $this->output->set_status_header($error_code);
        $this->load->view('browser/error/'.$error_code, $data);
    }
}

/* End of file FaultController.php */
/* Location: ./application/controllers/FaultController.php */