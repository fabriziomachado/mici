<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category AdminController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class AdminController extends MI_Controller
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

        $data['sidebar'] = $this->load->view('admin/partials/sidebar', $data, TRUE);
        $this->load->view('admin/partials/header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/partials/footer', $data);
    }

    function entities()
    {
        $this->load->view('admin/entities');
    }
}

/* End of file AdminController.php */
/* Location: ./application/controllers/admin/AdminController.php */