<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category LoginController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class LoginController extends MI_Controller
{
    var $ci;

    /**
     * Construct
     */
    function __construct()
    {
        parent::__construct();

        $this->ci = & get_instance();
    }

    function index()
    {
        // logged in
        if ($this->auth->is_logged_in())
        {
            redirect('/system');
        }
        else
        {
            $this->ci->load->helper('cookie');
            delete_cookie($this->ci->config->item('sess_cookie_name'));

            $data['login_by_username'] = ($this->config->item('login_by_username') && $this->config->item('use_username'));
            $data['login_by_email'] = $this->config->item('login_by_email');

            $this->form_validation->set_rules(
                'login',
                'Login',
                'trim|required|xss_clean'
            );
            $this->form_validation->set_rules(
                'password',
                'Password',
                'trim|required|xss_clean'
            );
            $this->form_validation->set_rules(
                'remember',
                'Remember me',
                'integer'
            );

            // Get login for counting attempts to login
            if ($this->config->item('login_count_attempts') && ($login = $this->input->post('login')))
            {
                $login = $this->security->xss_clean($login);
            }
            else
            {
                $login = '';
            }

            $data['errors'] = array();

            // validation ok
            if ($this->input->post('submit'))
            {
                $login = $this->input->post('login');
                $password = $this->input->post('password');
                $remember = $this->input->post('remember');

                $data['login'] = $this->security->xss_clean($login);
                $data['remember'] = $this->security->xss_clean($remember);

                if ($login == '')
                {
                    $data['errors'][] = 'Missing Username';
                }
                else if ($password == '')
                {
                    $data['errors'][] = 'Missing Password';
                }
            }
            if ($this->form_validation->run())
            {
                // success
                if ($this->auth->login(
                        $this->form_validation->set_value('login'),
                        $this->form_validation->set_value('password'),
                        $this->form_validation->set_value('remember'),
                        $data['login_by_username'],
                        $data['login_by_email']
                    )
                )
                {
                    redirect('/system');
                }
                else
                {
                    $errors = $this->auth->get_error_message();

                    // banned user
                    if (isset($errors['banned']))
                    {
                        $this->_show_message($this->lang->line('auth_message_banned') . ' ' . $errors['banned']);
                        return;
                    }
                    // not activated user
                    else if (isset($errors['not_activated']))
                    {
                        redirect('/account/send_again/');
                    }
                    // fail
                    else
                    {
                        foreach ($errors as $k => $v)
                        {
                            $data['errors'][$k] = $this->lang->line($v);
                        }
                    }
                }
            }

            $data['media_url'] = $this->media_url;

            // load content and push contant data
            $this->load->view('system/login', $data);
        }
    }
}

/* End of file LoginController.php */
/* Location: ./application/controllers/system/LoginController.php */