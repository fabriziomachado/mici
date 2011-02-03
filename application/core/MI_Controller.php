<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Core
 * @category MI_Controller
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class MI_Controller extends CI_Controller
{
    /*
     * Call the parent constructor
     *
     * @access  public
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        @session_start();
		
        // load doctrine first
        $this->load->library('pear_doctrine');

        // check maintenace mode before loading anything else
        if ($this->uri->segment(1) !== 'admin' && $this->uri->segment(1) !== 'system')
        {
            $this->load->model('codeigniter/maintenance_mode');
            $this->load->helper('conversion');
            $status = $this->maintenance_mode->status();

            if ($status)
            {
                if ($status->mode == 'enabled')
                {
                    $seconds = strtotime($status->start_datetime) - strtotime(date('Y-m-d H:i:s'));
                    if ($seconds > 0)
                    {
                        if ($seconds <= 600)
                        {
                            $this->data['maintenance_starts_at'] = date('F jS, Y @ g:i A T', strtotime($status->start_datetime));
                            $this->data['maintenance_ends_at'] = date('F jS, Y @ g:i A T', strtotime($status->end_datetime));
                            $this->data['maintenance_starts_in'] = time_diff(strtotime($status->start_datetime), strtotime(date('Y-m-d H:i:s')));
                        }
                        log_message('info', 'Maintenance Mode Starts in ' . $this->data['maintenance_starts_in']);
                    }
                    else
                    {
                        // get time left until maintenance mode is over
                        $timeleft = time_diff(strtotime($status->end_datetime), strtotime(date('Y-m-d H:i:s')));
                        $seconds = strtotime($status->end_datetime) - strtotime(date('Y-m-d H:i:s'));
                        if ($seconds > 0)
                        {
                            // Load up the coming soon message to be displayed.
                            $this->load->config('paths');
                            $this->media_url = $this->config->item('media_url');

                            // can't load a view here, but we can fetch the file contents and display them
                            $message = file_get_contents(APPPATH . 'views/browser/maintenance/index.php');
                            $message = str_replace('[MM_MEDIA_URL]', $this->media_url . 'maintenance/', $message);
                            $message = str_replace('[MM_ENDS_AT]', date('F jS, Y @ g:i A T', strtotime($status->end_datetime)), $message);
                            $message = str_replace('[MM_START_TIME]', date('Y/m/d H:i:s', strtotime($status->start_datetime)), $message);
                            $message = str_replace('[MM_END_TIME]', date('Y/m/d H:i:s', strtotime($status->end_datetime)), $message);
                            $message = str_replace('[MM_MESSAGE]', $status->message, $message);
                            $message = str_replace('[MM_YEAR]', date("Y"), $message);
                            $message = str_replace('[MM_TIME_LEFT]', $timeleft, $message);
                            exit($message);
                        }
                    }
                }
            }
        }

        if (FRAMEWORK_APPLICATION_ENV === 'development')
        {
            $this->output->enable_profiler(FALSE);
        }
        else
        {
            if(substr($_SERVER['HTTP_USER_AGENT'], 0, 11) != 'ApacheBench' && substr($_SERVER['HTTP_USER_AGENT'], 0, 5) != 'Siege')
            {
                $this->load->config('floodblocker');
                $this->load->library('floodblocker');
            }
        }

        // setup firephp
        $this->load->library('firephp');
        if ($this->config->item('log_threshold') >= 1 && FRAMEWORK_APPLICATION_ENV === 'development')
        {
            $this->firephp->setEnabled(TRUE);
        }
        else
        {
            $this->firephp->setEnabled(FALSE);
        }

        // load path config data
        $this->load->config('auth');
        $this->load->config('paths');

        // load helpers
        $this->load->helper('url');
        $this->load->helper('referrer_check');
        $this->load->helper('utf8_cleaner');

        // load required libraries
        $this->load->library('security');
        $this->load->library('user_agent');
        $this->load->library('session');
        $this->load->library('auth');
        $this->load->library('form_validation');
        $this->load->library('activity_tracker');
        $this->load->library('user_agent');

        // load required languages
        $this->lang->load('auth');

        // set some basic data all controllers will need
        $this->media_url = $this->config->item('media_url');
        $this->assets_url = $this->config->item('assets_url');
        $this->base_url = $this->config->item('base_url');
        $this->assets_abs_path = $this->config->item('assets_abs_path');

        $this->data['media_url'] = $this->media_url;
        $this->data['assets_url'] = $this->assets_url;
        $this->data['base_url'] = $this->base_url;
        $this->data['assets_abs_path'] = $this->assets_abs_path;
        $this->data['section'] = $this->uri->segment(1);
        $this->data['subsection'] = $this->uri->segment(2);

        $this->data['browser'] = get_browser(null, true);

        // automatically convert URI to associative array based on router paths
        $this->uri_array = $this->router->uri_array;

        if ($this->uri->segment(1) == 'admin' || $this->uri->segment(1) == 'system')
        {
            // set some basic data all controllers will need
            $this->data['section'] = $this->uri->segment(2);
            $this->data['subsection'] = $this->uri->segment(3);
            $this->data['name'] = $this->session->userdata('name');

            // check if we need to authenticate user
            if (!$this->auth->is_logged_in())
            {
                if ($this->uri->segment(2) !== 'login')
                {
                    redirect('/' . $this->uri->segment(1) . '/login');
                }
            }
            else if (!$this->auth->is_user_role('super_admin') && !$this->auth->is_user_role('admin'))
            {
                redirect('/' . $this->uri->segment(1) . '/logout');
            }

            // set class data for view
            switch (TRUE)
            {
                case ($this->data['section'] == '' || $this->data['section'] == 'index'):
                    $this->data['dashboard_class'] = ' current';
                    break;

                default:
                    $this->data[$this->data['section'] . '_class'] = ' current';
                    if ($this->data['subsection'] != '')
                    {
                        $this->data[$this->data['section'] . '_' . $this->data['subsection'] . '_class'] = array('class' => 'current');
                    }
                    break;
            }
        }

        // Make sure Doctrine DQL callbacks don't prevent items with expires on or publish on from being shown in the admin interface
        Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, false);

        // track activity
        $this->activity_tracker->track();
    }

    function show_404()
    {
        $this->output->set_status_header('404');
        $this->load->view('browser/error/404', $this->data);
    }

    function clear_all_caches()
    {
        $this->load->helper('cache_clearer');
        
        // Flush all cache.
        cache_clear('memcached');
        cache_clear('apc');
        cache_clear('disk');
    }
}

/* End of file MI_Controller.php */
/* Location: ./application/core/MI_Controller.php */