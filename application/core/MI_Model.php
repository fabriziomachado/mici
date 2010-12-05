<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Core
 * @category MI_Model
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class MI_Model extends CI_Model
{
    /*
     * Call the parent constructor
     *
     * @access  public
     * @return  void
     */
    function __construct()
    {
        parent::__construct();

        $this->load->config('paths');
        $this->media_url = $this->config->item('media_url');
        $this->assets_url = $this->config->item('assets_url');
        $this->base_url = $this->config->item('base_url');
        $this->assets_abs_path = $this->config->item('assets_abs_path');
    }
}

/* End of file MI_Model.php */
/* Location: ./application/core/MI_Model.php */