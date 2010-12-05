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

        // Load CI Model
        $this->load->model('codeigniter/maintenance_mode');
    }

    function index()
    {
        // form submission via POST
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $mode = $this->input->post('mm');
            $message = $this->input->post('message');
            $start_date = $this->input->post('start-date') . ' ' . $this->input->post('start-time');
            $end_date = $this->input->post('end-date') . ' ' . $this->input->post('end-time');
            $recurring = $this->input->post('recurring');

            if ($mode == 'enabled')
            {
                $this->maintenance_mode->enable($message, $start_date, $end_date, $recurring);
            }
            else if ($mode == 'disabled')
            {
                $this->maintenance_mode->disable();
            }

            // redirect
            redirect('/admin/settings');
        }

        $data = array();
        $data = array_merge($data, $this->data);

        $status = $this->maintenance_mode->status();

        $data['mode'] = $status->mode;
        $data['message'] = $status->message;
        if ($status->start_datetime)
        {
            list($start_date, $start_time) = explode(' ', $status->start_datetime);
            $data['start_date'] = $start_date;
            $data['start_time'] = $start_time;
        }
        if ($status->end_datetime)
        {
            list($end_date, $end_time) = explode(' ', $status->end_datetime);
            $data['end_date'] = $end_date;
            $data['end_time'] = $end_time;
        }
        if ($status->recurring)
        {
            $data['recurring'] = $status->recurring;
        }

        $data['select_time'] = $this->load->view('admin/partials/select_time', NULL, TRUE);
        $data['sidebar'] = $this->load->view('admin/partials/sidebar', $data, TRUE);

        $this->load->view('admin/partials/header', $data);
        $this->load->view('admin/settings', $data);
        $this->load->view('admin/partials/footer', $data);
    }
}

/* End of file SettingsController.php */
/* Location: ./application/controllers/admin/SettingsController.php */