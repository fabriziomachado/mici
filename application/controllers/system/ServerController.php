<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category ServerController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class ServerController extends MI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $this->load->model('codeigniter/frontend_model');
        $data['pingdom_clients'] = $this->frontend_model->get_pingdom_clients();

        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);

        $this->load->view('system/partials/header', $data);
        $this->load->view('system/server/index', $data);
        $this->load->view('system/partials/footer', $data);
    }

    function status()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $this->load->model('codeigniter/frontend_model');
        $data['pingdom_clients'] = $this->frontend_model->get_pingdom_clients();

        $data['ss_client'] = $this->uri_array['client'];

        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);

        $this->load->view('system/partials/header', $data);
        $this->load->view('system/server/status', $data);
        $this->load->view('system/partials/footer', $data);
    }
}

/* End of file ServerController.php */
/* Location: ./application/controllers/ServerController.php */