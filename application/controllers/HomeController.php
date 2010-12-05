<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category HomeController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class HomeController extends MI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $data = array();
        $data = array_merge($data, $this->data);
        $this->load->view('browser/main/home', $data);
    }
}

/* End of file HomeController.php */
/* Location: ./application/controllers/HomeController.php */