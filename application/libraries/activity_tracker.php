<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category Activity_tracker
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class Activity_tracker
{
    var $ci;

    function __construct()
    {
        // get codeigniter instance
        $this->ci = & get_instance();

        // load activity model
        $this->ci->load->model('codeigniter/tracker');
    }

    function track()
    {
        if(substr($_SERVER['HTTP_USER_AGENT'], 0, 11) != 'ApacheBench' && substr($_SERVER['HTTP_USER_AGENT'], 0, 5) != 'Siege')
        {
            $this->ci->session->set_userdata(array(
                'tracking_previuos_page' => $this->ci->session->userdata('tracking_current_page')
            ));

            if ($this->ci->session->userdata('tracking') !== 'yes')
            {
                $track_id = $this->ci->tracker->init();
                if($track_id)
                {
                    $this->ci->session->set_userdata(array(
                        'tracking' => 'yes',
                        'track_id' => (int)$track_id
                    ));
                }
            }

            if ($this->ci->session->userdata('track_id'))
            {
                if ($this->ci->session->userdata('tracking_current_page'))
                {
                    $this->ci->session->set_userdata(array(
                        'tracking_previuos_page' => $this->ci->session->userdata('tracking_current_page'),
                        'tracking_previuos_time' => $this->ci->session->userdata('tracking_current_time')
                    ));
                }

                $current_page = rtrim($this->ci->uri->uri_string(), '/');

                $this->ci->session->set_userdata(array(
                    'tracking_current_page' => $current_page,
                    'tracking_current_time' => microtime(TRUE)
                ));

                $this->ci->tracker->track();
            }
        }
    }
}

/* End of file activity_tracker.php */
/* Location: ./application/libraries/activity_tracker.php */