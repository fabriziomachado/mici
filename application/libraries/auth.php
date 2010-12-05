<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category Auth
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
require_once('passwordhash.php');

class Auth
{
    const PHPASS_HASH_STRENGTH = 8;
    const PHPASS_HASH_PORTABLE = TRUE;

    const STATUS_ACTIVATED = '1';
    const STATUS_NOT_ACTIVATED = '0';

    private $error = array();

    var $ci;

    function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->model('codeigniter/auth/user_model');
        $this->ci->load->config('auth', TRUE);

        // Try to autologin
        $this->autologin();
    }

    /**
     * Login user on the site. Return TRUE if login is successful
     * (user exists and activated, password is correct), otherwise FALSE.
     *
     * @param	string	(username or email or both depending on settings in config file)
     * @param	string
     * @param	bool
     * @return	bool
     */
    function login($login, $password, $remember, $login_by_username, $login_by_email)
    {
        if ((strlen($login) > 0) && (strlen($password) > 0))
        {
            // Which function to use to login (based on config)
            if ($login_by_username && $login_by_email)
            {
                $get_user_func = 'get_user_by_login';
            }
            else if ($login_by_username)
            {
                $get_user_func = 'get_user_by_username';
            }
            else
            {
                $get_user_func = 'get_user_by_email';
            }

            // login ok
            if (!is_null($user = $this->ci->user_model->$get_user_func($login)))
            {
                // Does password match hash in database?
                $hasher = new PasswordHash(self::PHPASS_HASH_STRENGTH, self::PHPASS_HASH_PORTABLE);

                // password ok
                if ($hasher->CheckPassword($password, $user->password))
                {
                    // fail - banned
                    if ($user->banned == 1)
                    {
                        $this->error = array('banned' => $user->ban_reason);
                    }
                    else
                    {
                        $this->ci->session->set_userdata(array(
                            'user_id' => $user->id,
                            'role' => $user->role,
                            'username' => $user->username,
                            'email' => $user->email,
                            'name' => $user->display_name,
                            'status' => ($user->activated == 1)
                                ? self::STATUS_ACTIVATED
                                : self::STATUS_NOT_ACTIVATED
                        ));

                        // fail - not activated
                        if ($user->activated == 0)
                        {
                            $this->error = array('not_activated' => '');
                        }
                        // success
                        else
                        {
                            if ($remember)
                            {
                                $this->create_autologin($user->id);
                            }

                            $this->clear_login_attempts($login);
                            $this->ci->user_model->update_login_info($user->id, $this->ci->config->item('login_record_ip', 'auth'), $this->ci->config->item('login_record_time', 'auth'));
                            return TRUE;
                        }
                    }
                }
                // fail - wrong password
                else
                {
                    $this->increase_login_attempt($login);
                    $this->error = array('password' => 'auth_incorrect_password');
                }
            }
            // fail - wrong login
            else
            {
                $this->increase_login_attempt($login);
                $this->error = array('login' => 'auth_incorrect_login');
            }
        }
        return FALSE;
    }

    /**
     * Logout user from the site
     *
     * @return	void
     */
    function logout()
    {
        $user_data = array(
            'user_id' => NULL,
            'role' => NULL,
            'username' => NULL,
            'email' => NULL,
            'name' => NULL,
            'status' => NULL,
            'tracking' => NULL,
            'track_id' => NULL,
            'tracking_previuos_page' => NULL,
            'tracking_current_page' => NULL,
            'tracking_current_time' => NULL
        );
        $this->delete_autologin();
        $this->ci->session->set_userdata($user_data);
        $this->ci->session->unset_userdata($user_data);
        $this->ci->session->sess_destroy();
    }

    /**
     * Check if user logged in. Also test if user is activated or not.
     *
     * @param	bool
     * @return	bool
     */
    function is_logged_in($activated = TRUE)
    {
        return $this->ci->session->userdata('status') === ($activated
            ? self::STATUS_ACTIVATED
            : self::STATUS_NOT_ACTIVATED);
    }

    /**
     * Get user_id
     *
     * @return	string
     */
    function get_user_id()
    {
        return $this->ci->session->userdata('user_id');
    }

    /**
     * Get username
     *
     * @return	string
     */
    function get_username()
    {
        return $this->ci->session->userdata('username');
    }

    /**
     * Create new user on the site and return some data about it:
     * user_id, username, password, email, new_email_key (if any).
     *
     * @param	string
     * @param	string
     * @param	string
     * @param	bool
     * @return	array
     */
    function create_user($username, $email, $password, $email_activation)
    {
        if ((strlen($username) > 0) && !$this->ci->user_model->is_username_available($username))
        {
            $this->error = array('username' => 'auth_username_in_use');
        }
        elseif (!$this->ci->user_model->is_email_available($email))
        {
            $this->error = array('email' => 'auth_email_in_use');
        }
        else
        {
            // Hash password using phpass
            $hasher = new PasswordHash(self::PHPASS_HASH_STRENGTH, self::PHPASS_HASH_PORTABLE);
            $hashed_password = $hasher->HashPassword($password);

            $data = array(
                'username' => $username,
                'password' => $hashed_password,
                'email' => $email,
                'role' => $this->ci->config->item('default_role', 'auth'),
                'last_ip' => $this->ci->input->ip_address()
            );

            if ($email_activation)
            {
                $data['new_email_key'] = md5(rand() . microtime());
            }
            if (!is_null($res = $this->ci->user_model->create_user($data, !$email_activation)))
            {
                $data['user_id'] = $res['user_id'];
                $data['password'] = $password;
                unset($data['last_ip']);
                return $data;
            }
        }
        return NULL;
    }

    /**
     * Check if username available for registering.
     * Can be called for instant form validation.
     *
     * @param	string
     * @return	bool
     */
    function is_username_available($username)
    {
        return ((strlen($username) > 0) && $this->ci->user_model->is_username_available($username));
    }

    /**
     * Check if email available for registering.
     * Can be called for instant form validation.
     *
     * @param	string
     * @return	bool
     */
    function is_email_available($email)
    {
        return ((strlen($email) > 0) && $this->ci->user_model->is_email_available($email));
    }

    /**
     * Change email for activation and return some data about user:
     * user_id, username, email, new_email_key.
     * Can be called for not activated users only.
     *
     * @param	string
     * @return	array
     */
    function change_email($email)
    {
        $user_id = $this->ci->session->userdata('user_id');

        if (!is_null($user = $this->ci->user_model->get_user_by_id($user_id, FALSE)))
        {
            $data = array(
                'user_id' => $user_id,
                'username' => $user->username,
                'email' => $email
            );

            // leave activation key as is
            if ($user->email == $email)
            {
                $data['new_email_key'] = $user->new_email_key;
                return $data;
            }
            elseif ($this->ci->user_model->is_email_available($email))
            {
                $data['new_email_key'] = md5(rand() . microtime());
                $this->ci->user_model->set_new_email($user_id, $email, $data['new_email_key'], FALSE);
                return $data;
            }
            else
            {
                $this->error = array('email' => 'auth_email_in_use');
            }
        }
        return NULL;
    }

    /**
     * Activate user using given key
     *
     * @param	string
     * @param	string
     * @param	bool
     * @return	bool
     */
    function activate_user($user_id, $activation_key, $activate_by_email = TRUE)
    {
        $this->ci->user_model->purge_na($this->ci->config->item('email_activation_expire', 'auth'));

        if ((strlen($user_id) > 0) && (strlen($activation_key) > 0))
        {
            return $this->ci->user_model->activate_user($user_id, $activation_key, $activate_by_email);
        }
        return FALSE;
    }

    /**
     * Set new password key for user and return some data about user:
     * user_id, username, email, new_pass_key.
     * The password key can be used to verify user when resetting his/her password.
     *
     * @param	string
     * @return	array
     */
    function forgot_password($login)
    {
        if (strlen($login) > 0)
        {
            if (!is_null($user = $this->ci->user_model->get_user_by_login($login)))
            {
                $data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'new_pass_key' => md5(rand() . microtime())
                );

                $this->ci->user_model->set_password_key($user->id, $data['new_pass_key']);
                return $data;
            }
            else
            {
                $this->error = array('login' => 'auth_incorrect_email_or_username');
            }
        }
        return NULL;
    }

    /**
     * Check if given password key is valid and user is authenticated.
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    function can_reset_password($user_id, $new_pass_key)
    {
        if ((strlen($user_id) > 0) && (strlen($new_pass_key) > 0))
        {
            return $this->ci->user_model->can_reset_password($user_id, $new_pass_key, $this->ci->config->item('forgot_password_expire', 'auth'));
        }
        return FALSE;
    }

    /**
     * Replace user password (forgotten) with a new one (set by user)
     * and return some data about it: user_id, username, new_password, email.
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    function reset_password($user_id, $new_pass_key, $new_password)
    {
        if ((strlen($user_id) > 0) && (strlen($new_pass_key) > 0) && (strlen($new_password) > 0))
        {
            if (!is_null($user = $this->ci->user_model->get_user_by_id($user_id, TRUE)))
            {
                // Hash password using phpass
                $hasher = new PasswordHash(self::PHPASS_HASH_STRENGTH, self::PHPASS_HASH_PORTABLE);
                $hashed_password = $hasher->HashPassword($new_password);

                // success
                if ($this->ci->user_model->reset_password($user_id, $hashed_password, $new_pass_key, $this->ci->config->item('forgot_password_expire', 'auth')))
                {
                    // Clear all user's autologins
                    $this->ci->load->model('codeigniter/auth/user_autologin_model');
                    $this->ci->user_autologin_model->clear($user->id);

                    return array(
                        'user_id' => $user_id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'new_password' => $new_password
                    );
                }
            }
        }
        return NULL;
    }

    /**
     * Change user password (only when user is logged in)
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    function change_password($old_pass, $new_pass)
    {
        $user_id = $this->ci->session->userdata('user_id');

        if (!is_null($user = $this->ci->user_model->get_user_by_id($user_id, TRUE)))
        {
            // Check if old password correct
            $hasher = new PasswordHash(self::PHPASS_HASH_STRENGTH, self::PHPASS_HASH_PORTABLE);

            // success
            if ($hasher->CheckPassword($old_pass, $user->password))
            {
                // Hash new password using phpass
                $hashed_password = $hasher->HashPassword($new_pass);

                // Replace old password with new one
                $this->ci->user_model->change_password($user_id, $hashed_password);
                return TRUE;
            }
            // fail
            else
            {
                $this->error = array('old_password' => 'auth_incorrect_password');
            }
        }
        return FALSE;
    }

    /**
     * Change user email (only when user is logged in) and return some data about user:
     * user_id, username, new_email, new_email_key.
     * The new email cannot be used for login or notification before it is activated.
     *
     * @param	string
     * @param	string
     * @return	array
     */
    function set_new_email($new_email, $password)
    {
        $user_id = $this->ci->session->userdata('user_id');

        if (!is_null($user = $this->ci->user_model->get_user_by_id($user_id, TRUE)))
        {
            // Check if password correct
            $hasher = new PasswordHash(self::PHPASS_HASH_STRENGTH, self::PHPASS_HASH_PORTABLE);

            // success
            if ($hasher->CheckPassword($password, $user->password))
            {
                $data = array(
                    'user_id' => $user_id,
                    'username' => $user->username,
                    'new_email' => $new_email
                );

                if ($user->email == $new_email)
                {
                    $this->error = array('email' => 'auth_current_email');
                }
                // leave email key as is
                elseif ($user->new_email == $new_email)
                {
                    $data['new_email_key'] = $user->new_email_key;
                    return $data;
                }
                elseif ($this->ci->user_model->is_email_available($new_email))
                {
                    $data['new_email_key'] = md5(rand() . microtime());
                    $this->ci->user_model->set_new_email($user_id, $new_email, $data['new_email_key'], TRUE);
                    return $data;
                }
                else
                {
                    $this->error = array('email' => 'auth_email_in_use');
                }
            }
            // fail
            else
            {
                $this->error = array('password' => 'auth_incorrect_password');
            }
        }
        return NULL;
    }

    /**
     * Activate new email, if email activation key is valid.
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    function activate_new_email($user_id, $new_email_key)
    {
        if ((strlen($user_id) > 0) && (strlen($new_email_key) > 0))
        {
            return $this->ci->user_model->activate_new_email($user_id, $new_email_key);
        }
        return FALSE;
    }

    /**
     * Delete user from the site (only when user is logged in)
     *
     * @param	string
     * @return	bool
     */
    function delete_user($password)
    {
        $user_id = $this->ci->session->userdata('user_id');

        if (!is_null($user = $this->ci->user_model->get_user_by_id($user_id, TRUE)))
        {
            // Check if password correct
            $hasher = new PasswordHash(self::PHPASS_HASH_STRENGTH, self::PHPASS_HASH_PORTABLE);

            // success
            if ($hasher->CheckPassword($password, $user->password))
            {
                $this->ci->user_model->delete_user($user_id);
                $this->logout();
                return TRUE;
            }
            // fail
            else
            {
                $this->error = array('password' => 'auth_incorrect_password');
            }
        }
        return FALSE;
    }

    /**
     * Get error message.
     * Can be invoked after any failed operation such as login or register.
     *
     * @return	string
     */
    function get_error_message()
    {
        return $this->error;
    }

    /**
     * Save data for user's autologin
     *
     * @param	int
     * @return	bool
     */
    private function create_autologin($user_id)
    {
        $this->ci->load->helper('cookie');
        $key = substr(md5(uniqid(rand() . get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);

        $this->ci->load->model('codeigniter/auth/user_autologin_model');
        $this->ci->user_autologin_model->purge($user_id);

        if ($this->ci->user_autologin_model->set($user_id, md5($key)))
        {
            set_cookie(array(
                'name' => $this->ci->config->item('autologin_cookie_name', 'auth'),
                'value' => serialize(array('user_id' => $user_id, 'key' => $key)),
                'expire' => $this->ci->config->item('autologin_cookie_life', 'auth')
            ));
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Clear user's autologin data
     *
     * @return	void
     */
    private function delete_autologin()
    {
        $this->ci->load->helper('cookie');
        if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'auth'), TRUE))
        {
            $data = unserialize($cookie);

            $this->ci->load->model('codeigniter/auth/user_autologin_model');
            $this->ci->user_autologin_model->delete($data['user_id'], md5($data['key']));

            delete_cookie($this->ci->config->item('autologin_cookie_name', 'auth'));
        }
        if ($cookie = get_cookie($this->ci->config->item('sess_cookie_name'), TRUE))
        {
            delete_cookie($this->ci->config->item('sess_cookie_name'));
        }
    }

    /**
     * Login user automatically if he/she provides correct autologin verification
     *
     * @return	void
     */
    private function autologin()
    {
        // not logged in (as any user)
        if (!$this->is_logged_in() && !$this->is_logged_in(FALSE))
        {

            $this->ci->load->helper('cookie');
            if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'auth'), TRUE))
            {
                $data = unserialize($cookie);

                if (isset($data['key']) && isset($data['user_id']))
                {
                    $this->ci->load->model('codeigniter/auth/user_autologin_model');
                    if (!is_null($user = $this->ci->user_autologin_model->get($data['user_id'], md5($data['key']))))
                    {
                        // Login user
                        $this->ci->session->set_userdata(array(
                            'user_id' => $user->id,
                            'role' => $user->role,
                            'username' => $user->username,
                            'email' => $user->email,
                            'name' => $user->display_name,
                            'status' => self::STATUS_ACTIVATED
                        ));

                        // Renew users cookie to prevent it from expiring
                        set_cookie(array(
                            'name' => $this->ci->config->item('autologin_cookie_name', 'auth'),
                            'value' => $cookie,
                            'expire' => $this->ci->config->item('autologin_cookie_life', 'auth')
                        ));

                        $this->ci->user_model->update_login_info($user->id, $this->ci->config->item('login_record_ip', 'auth'), $this->ci->config->item('login_record_time', 'auth'));

                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }

    /**
     * Check if login attempts exceeded max login attempts (specified in config)
     *
     * @param	string
     * @return	bool
     */
    function is_max_login_attempts_exceeded($login)
    {
        if ($this->ci->config->item('login_count_attempts', 'auth'))
        {
            $this->ci->load->model('codeigniter/auth/login_attempt_model');
            return $this->ci->login_attempt_model->get_attempts_num($this->ci->input->ip_address(), $login) >= $this->ci->config->item('login_max_attempts', 'auth');
        }
        return FALSE;
    }

    /**
     * Check if login attempts exceeded max login attempts (specified in config)
     *
     * @param	string
     * @return	bool
     */
    function is_user_role($role)
    {
        $user_role = $this->ci->session->userdata('role');
        if ($user_role == $role)
        {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Increase number of attempts for given IP-address and login
     * (if attempts to login is being counted)
     *
     * @param	string
     * @return	void
     */
    private function increase_login_attempt($login)
    {
        if ($this->ci->config->item('login_count_attempts', 'auth'))
        {
            if (!$this->is_max_login_attempts_exceeded($login))
            {
                $this->ci->load->model('codeigniter/auth/login_attempt_model');
                $this->ci->login_attempt_model->increase_attempt($this->ci->input->ip_address(), $login);
            }
        }
    }

    /**
     * Clear all attempt records for given IP-address and login
     * (if attempts to login is being counted)
     *
     * @param	string
     * @return	void
     */
    private function clear_login_attempts($login)
    {
        if ($this->ci->config->item('login_count_attempts', 'auth'))
        {
            $this->ci->load->model('codeigniter/auth/login_attempt_model');
            $this->ci->login_attempt_model->clear_attempts($this->ci->input->ip_address(), $login, $this->ci->config->item('login_attempt_expire', 'auth'));
        }
    }
}

/* End of file auth.php */
/* Location: ./application/libraries/auth.php */