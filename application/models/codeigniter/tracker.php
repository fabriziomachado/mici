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
        // Leave if this is a robot... $this->ci->agent->is_robot is not working for some reason
        if (is_array($this->ci->agent->robots) AND count($this->ci->agent->robots) > 0)
        {
            foreach ($this->ci->agent->robots as $key => $val)
            {
                if (preg_match("|".preg_quote($key)."|i", $this->ci->input->user_agent()))
                {
                    return TRUE;
                }
            }
        }

        if ($this->ci->session->userdata('session_id') && $this->ci->uri->segment(1) != 'system' && $this->ci->uri->segment(1) != 'admin')
        {
            try
            {
                // fetch users ip address
                $ip = $this->ci->input->ip_address();

                if($ip != '0.0.0.0' && $ip != '127.0.0.1')
                {
                    $framework_ini = (array) unserialize(FRAMEWORK_INI);
                    $api_key = $framework_ini['auth']['ipinfodb_apikey'];

                    // load helper
                    $this->ci->load->helper('geolocation');

                    // use helper to get geo location
                    $geo = get_geolocation($ip, $api_key);
                }

                // set user id if there is one
                $user_id = ($this->ci->session->userdata('user_id'))
                    ? $this->ci->session->userdata('user_id')
                    : NULL;

                // store data
                $access = new Access();
                $access->session_id = $this->ci->session->userdata('session_id');
                $access->user_id = $user_id;
                $access->user_agent = $this->ci->input->user_agent();
                $access->ip_address = $ip;
                $access->referrer = $this->ci->agent->referrer();

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
            }
        }
    }

    function track($track_id=NULL)
    {
        global $BM;

        // Leave if this is a robot... $this->ci->agent->is_robot is not working for some reason
        if (is_array($this->ci->agent->robots) AND count($this->ci->agent->robots) > 0)
        {
            foreach ($this->ci->agent->robots as $key => $val)
            {
                if (preg_match("|".preg_quote($key)."|i", $this->ci->input->user_agent()))
                {
                    return TRUE;
                }
            }
        }

        if ($track_id && $this->ci->uri->segment(1) != 'system' && $this->ci->uri->segment(1) != 'admin' && ($this->ci->session->userdata('tracking_previuos_page') != $this->ci->session->userdata('tracking_current_page') || !$this->ci->session->userdata('tracking_current_page') || !$this->ci->session->userdata('tracking_previuos_page')) )
        {
            $mem_usage = memory_get_usage();
            $elapsed = $BM->elapsed_time('total_execution_time_start', 'total_execution_time_end');
            $pagetime = ($this->ci->session->userdata('tracking_previuos_time'))
                ? ($this->ci->session->userdata('tracking_current_time') - $this->ci->session->userdata('tracking_previuos_time'))
                : 0;

            $url_segment_1 = ($this->ci->uri->segment(1))
                ? $this->ci->uri->segment(1)
                : 'home';

            // store data
            $activity = new Activity();
            $activity->access_id = $track_id;
            $activity->url_segment_1 = $url_segment_1;

            if($this->ci->uri->segment(2))
            {
                $activity->url_segment_2 = $this->ci->uri->segment(2);
            }
            if($this->ci->uri->segment(3))
            {
                $activity->url_segment_3 = $this->ci->uri->segment(3);
            }
            if($this->ci->uri->segment(4))
            {
                $activity->url_segment_4 = $this->ci->uri->segment(4);
            }
            if($this->ci->uri->segment(5))
            {
                $activity->url_segment_5 = $this->ci->uri->segment(5);
            }
            if($this->ci->uri->segment(6))
            {
                $activity->url_segment_6 = $this->ci->uri->segment(6);
            }
            if($this->ci->uri->segment(7))
            {
                $activity->url_segment_7 = $this->ci->uri->segment(7);
            }
            if($this->ci->uri->segment(8))
            {
                $activity->url_segment_8 = $this->ci->uri->segment(8);
            }
            if($this->ci->uri->segment(9))
            {
                $activity->url_segment_9 = $this->ci->uri->segment(9);
            }
            if($this->ci->uri->segment(10))
            {
                $activity->url_segment_10 = $this->ci->uri->segment(10);
            }

            $activity->previuos_page = ($this->ci->session->userdata('tracking_previuos_page'))
                ? $this->ci->session->userdata('tracking_previuos_page')
                : NULL;
            $activity->time_on_previuos_page = $pagetime;
            $activity->current_page = ($this->ci->uri->uri_string())
                ? $this->ci->uri->uri_string()
                : '/';
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