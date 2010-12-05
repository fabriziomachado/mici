<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Models
 * @category Tracker
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class Tracker extends MI_Model
{
    var $ci;

    function __construct()
    {
        parent::__construct();

        // get codeigniter instance
        $this->ci =& get_instance();
    }

    function init()
    {
        if ($this->session->userdata('session_id') && $this->uri->segment(1) != 'system' && $this->uri->segment(1) != 'admin')
        {
            try
            {
                // fetch users ip address
                $ip = $this->input->ip_address();

                if($ip != '0.0.0.0' && $ip != '127.0.0.1')
                {
                    // load helper
                    $this->load->helper('geolocation');

                    // use helper to get geo location
                    $geo = get_geolocation($ip);
                }

                // set user id if there is one
                $user_id = ($this->session->userdata('user_id'))
                    ? $this->session->userdata('user_id')
                    : NULL;

                // store data
                $access = new Access();
                $access->session_id = $this->session->userdata('session_id');
                $access->user_id = $user_id;
                $access->user_agent = $this->input->user_agent();
                $access->ip_address = $ip;

                if($geo)
                {
                    $access->country_code = $geo['country_code'];
                    $access->country_name = $geo['country_name'];
                    $access->region_name = $geo['region_name'];
                    $access->city = $geo['city'];
                    $access->zip_postal_code = $geo['zip_postal_code'];
                    $access->latitude = $geo['latitude'];
                    $access->longitude = $geo['longitude'];
                }
            
            
                $access->save();

                return ($access->id)
                    ? (int)$access->id
                    : NULL;
            }
            catch (Doctrine_Connection_Exception $e)
            {
                $this->firephp->error($e->getMessage());
                //log_message('error', $e->getMessage());
            }
        }
    }

    function track()
    {
        global $BM;

        if ($this->session->userdata('track_id') && $this->uri->segment(1) != 'system' && $this->uri->segment(1) != 'admin' && $this->session->userdata('tracking_previuos_page') != $this->session->userdata('tracking_current_page'))
        {
            $mem_usage = memory_get_usage();
            $elapsed = $BM->elapsed_time('total_execution_time_start', 'total_execution_time_end');
            $pagetime = ($this->session->userdata('tracking_previuos_time'))
                ? ($this->session->userdata('tracking_current_time') - $this->session->userdata('tracking_previuos_time'))
                : 0;

            // store data
            $activity = new Activity();
            $activity->access_id = $this->session->userdata('track_id');
            if($this->uri->segment(1))
            {
                $activity->url_segment_1 = $this->uri->segment(1);
            }
            if($this->uri->segment(2))
            {
                $activity->url_segment_2 = $this->uri->segment(2);
            }
            if($this->uri->segment(3))
            {
                $activity->url_segment_3 = $this->uri->segment(3);
            }
            if($this->uri->segment(4))
            {
                $activity->url_segment_4 = $this->uri->segment(4);
            }
            if($this->uri->segment(5))
            {
                $activity->url_segment_5 = $this->uri->segment(5);
            }
            if($this->uri->segment(6))
            {
                $activity->url_segment_6 = $this->uri->segment(6);
            }
            if($this->uri->segment(7))
            {
                $activity->url_segment_7 = $this->uri->segment(7);
            }
            if($this->uri->segment(8))
            {
                $activity->url_segment_8 = $this->uri->segment(8);
            }
            if($this->uri->segment(9))
            {
                $activity->url_segment_9 = $this->uri->segment(9);
            }
            if($this->uri->segment(10))
            {
                $activity->url_segment_10 = $this->uri->segment(10);
            }
            $activity->previuos_page = $this->session->userdata('tracking_previuos_page');
            $activity->time_on_previuos_page = $pagetime;
            $activity->current_page = $this->session->userdata('tracking_current_page');
            $activity->page_load_time = $elapsed;
            $activity->memory_usage = ($mem_usage/1048576);

            try
            {
                $activity->save();
            }
            catch (Doctrine_Connection_Exception $e)
            {
                log_message('error', $e->getMessage());
            }
        }
    }
}

/* End of file tracker.php */
/* Location: ./application/models/codeigniter/tracker.php */