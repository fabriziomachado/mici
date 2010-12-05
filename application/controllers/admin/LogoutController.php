<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category LogoutController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class LogoutController extends MI_Controller
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
        $this->auth->logout();
        redirect('/admin/login/');
    }
}

/* End of file LogoutController.php */
/* Location: ./application/controllers/admin/LogoutController.php */