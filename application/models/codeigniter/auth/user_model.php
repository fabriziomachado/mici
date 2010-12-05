<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Models
 * @category User_model
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

/**
 * User_model
 *
 * This model represents user authentication data. It operates the following tables:
 * - TABLE -- user account data,
 * - TABLE_PROFILE -- user profiles
 *
 */
class User_model extends MI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get user record by Id
     *
     * @param	int
     * @param	bool
     * @return	object
     */
    public function get_user_by_id($user_id, $activated)
    {

        $q = Doctrine_Query::create()
                ->from('User u')
                ->select('u.*')
                ->where('u.id = ?', $user_id)
                ->andWhere('activated = ?', $activated)
                ->safeUseResultMemcache(FALSE)
                ->execute();

        if ($q->count() == 1)
        {
            return $q[0];
        }
        return NULL;
    }

    /**
     * Get user record by login (username or email)
     *
     * @param	string
     * @return	object
     */
    public function get_user_by_login($login)
    {
        $q = Doctrine_Query::create()
                ->from('User u')
                ->select('u.*')
                ->where('LOWER(username) = ?', strtolower($login))
                ->orWhere('LOWER(email) = ?', strtolower($login))
                ->safeUseResultMemcache(FALSE)
                ->execute();

        if ($q->count() === 1)
        {
            return $q[0];
        }
        return NULL;
    }

    /**
     * Get user record by username
     *
     * @param	string
     * @return	object
     */
    public function get_user_by_username($username)
    {
        if ($user = Doctrine::getTable('User')->findOneByUsername(strtolower($username))->safeUseResultMemcache(FALSE))
        {
            return $user;
        }
        return NULL;
    }

    /**
     * Get user record by email
     *
     * @param	string
     * @return	object
     */
    public function get_user_by_email($email)
    {
        if ($user = Doctrine::getTable('User')->findOneByEmail(strtolower($email))->safeUseResultMemcache(FALSE))
        {
            return $user;
        }
        return NULL;
    }

    /**
     * Check if username is available for registering
     *
     * @param	string
     * @return	bool
     */
    public function is_username_available($username)
    {
        if (Doctrine::getTable('User')->findOneByUsername($username)->safeUseResultMemcache(FALSE) === FALSE)
        {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Check if email available for registering
     *
     * @param	string
     * @return	bool
     */
    public function is_email_available($email)
    {
        if (Doctrine::getTable('User')->findOneByEmail($email)->safeUseResultMemcache(FALSE) === FALSE)
        {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Create new user record
     *
     * @param	array
     * @param	bool
     * @return	array
     */
    public function create_user($data, $activated = TRUE)
    {
        $data['activated'] = $activated
            ? 1
            : 0;

        $user = new User();
        $user->username = $data['username'];
        $user->password = $data['password'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->last_ip = $data['last_ip'];

        if (isset($data['new_email_key']))
        {
            $user->new_email_key = $data['new_email_key'];
        }

        $user->save();
        $user_id = $user->id;

        if (!is_null($user_id))
        {
            $profile = new UserProfile();
            $profile->user_id = $user_id;
            $profile->save();

            return array('user_id' => $user_id);
        }

        return NULL;
    }

    /**
     * Activate user if activation key is valid.
     * Can be called for not activated users only.
     *
     * @param	int
     * @param	string
     * @param	bool
     * @return	bool
     */
    function activate_user($user_id, $activation_key, $activate_by_email)
    {
        if ($activate_by_email)
        {
            $q = Doctrine_Query::create()
                    ->from('User')
                    ->select('*')
                    ->where('id = ?', $user_id)
                    ->andWhere('new_email_key = ?', $activation_key)
                    ->andWhere('activated = ?', 0)
                    ->safeUseResultMemcache(FALSE);
        }
        else
        {
            $q = Doctrine_Query::create()
                    ->from('User')
                    ->select('*')
                    ->where('id = ?', $user_id)
                    ->andWhere('new_password_key = ?', $activation_key)
                    ->andWhere('activated = ?', 0)
                    ->safeUseResultMemcache(FALSE);
        }

        $q->execute();
        if ($q->count() === 1)
        {
            if ($user = Doctrine::getTable('User')->findOneById($user_id))
            {
                $user->activated = 1;
                $user->new_email_key = NULL;
                $user->save();

                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Purge table of non-activated users
     *
     * @param	int
     * @return	void
     */
    public function purge_na($expire_period = 172800)
    {
        $q = Doctrine_Query::create()
                ->delete('User')
                ->where('activated = ?', 0)
                ->andWhere('UNIX_TIMESTAMP(created_at) < ?', time() - $expire_period)
                ->safeUseResultMemcache(FALSE)
                ->execute();
    }

    /**
     * Delete user record
     *
     * @param	int
     * @return	bool
     */
    public function delete_user($user_id)
    {
        if ($this->delete_profile($user_id) > 0)
        {
            $q = Doctrine_Query::create()
                    ->delete('User')
                    ->where('id = ?', $user_id)
                    ->safeUseResultMemcache(FALSE)
                    ->execute();

            return TRUE;
        }
        return FALSE;
    }

    /**
     * Set new password key for user.
     * This key can be used for authentication when resetting user's password.
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    public function set_password_key($user_id, $new_pass_key)
    {
        if ($user = Doctrine::getTable('User')->findOneById($user_id))
        {
            $user->new_password_key = $new_pass_key;
            $user->new_password_requested = date('Y-m-d H:i:s');
            $user->save();

            return TRUE;
        }
        return FALSE;
    }

    /**
     * Check if given password key is valid and user is authenticated.
     *
     * @param	int
     * @param	string
     * @param	int
     * @return	void
     */
    public function can_reset_password($user_id, $new_pass_key, $expire_period = 900)
    {
        $q = Doctrine_Query::create()
                ->from('User')
                ->select('*')
                ->where('id = ?', $user_id)
                ->andWhere('new_password_key = ?', $new_pass_key)
                ->andWhere('UNIX_TIMESTAMP(new_password_requested) > ?', (time() - $expire_period))
                ->safeUseResultMemcache(FALSE)
                ->execute();

        if ($q->count() === 1)
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Change user password if password key is valid and user is authenticated.
     *
     * @param	int
     * @param	string
     * @param	string
     * @param	int
     * @return	bool
     */
    public function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900)
    {
        $q = Doctrine_Query::create()
                ->from('User')
                ->select('*')
                ->where('id = ?', $user_id)
                ->andWhere('new_password_key = ?', $new_pass_key)
                ->andWhere('UNIX_TIMESTAMP(new_password_requested) >= ?', time() - $expire_period)
                ->safeUseResultMemcache(FALSE)
                ->execute();

        if ($q->count() === 1)
        {
            $user = $q[0];
            $user->password = $new_pass;
            $user->new_password_key = NULL;
            $user->new_password_requested = NULL;
            $user->save();

            return TRUE;
        }
        return FALSE;
    }

    /**
     * Change user password
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    public function change_password($user_id, $new_pass)
    {
        if ($user = Doctrine::getTable('User')->findOneById($user_id))
        {
            $user->password = $new_pass;
            $user->save();

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Set new email for user (may be activated or not).
     * The new email cannot be used for login or notification before it is activated.
     *
     * @param	int
     * @param	string
     * @param	string
     * @param	bool
     * @return	bool
     */
    public function set_new_email($user_id, $new_email, $new_email_key, $activated)
    {

        if ($user = Doctrine::getTable('User')->findOneById($user_id))
        {
            if ($user->activated === 1)
            {
                $user->email = $new_email;
            }
            else
            {
                $user->new_email = $new_email;
            }

            $user->new_email_key = $new_email_key;
            $user->save();

            return TRUE;
        }
        return FALSE;
    }

    /**
     * Activate new email (replace old email with new one) if activation key is valid.
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    public function activate_new_email($user_id, $new_email_key)
    {
        $q = Doctrine_Query::create()
                ->from('User')
                ->select('*')
                ->where('id = ?', $user_id)
                ->andWhere('new_email_key = ?', $new_email_key)
                ->safeUseResultMemcache(FALSE)
                ->execute();

        if ($q->count() === 1)
        {
            $user = $q[0];
            $user->email = $user->new_email;
            $user->new_email = NULL;
            $user->new_email_key = NULL;
            $user->save();

            return TRUE;
        }
        return FALSE;
    }

    /**
     * Update user login info, such as IP-address or login time, and
     * clear previously generated (but not activated) passwords.
     *
     * @param	int
     * @param	bool
     * @param	bool
     * @return	void
     */
    public function update_login_info($user_id, $record_ip, $record_time)
    {
        $user = Doctrine::getTable('User')->findOneById($user_id);
        $user->new_password_key = NULL;
        $user->new_password_requested = NULL;

        if ($record_ip)
        {
            $user->last_ip = $this->input->ip_address();
        }
        if ($record_time)
        {
            $user->last_login = date('Y-m-d H:i:s');
        }

        $user->save();
    }

    /**
     * Ban user
     *
     * @param	int
     * @param	string
     * @return	void
     */
    public function ban_user($user_id, $reason = NULL)
    {
        $user = Doctrine::getTable('User')->findOneById($user_id);
        $user->banned = 1;
        $user->ban_reason = $reason;
        $user->save();
    }

    /**
     * Unban user
     *
     * @param	int
     * @return	void
     */
    public function unban_user($user_id)
    {
        $user = Doctrine::getTable('User')->findOneById($user_id);
        $user->banned = 0;
        $user->ban_reason = NULL;
        $user->save();
    }

    /**
     * Delete user profile
     *
     * @param	int
     * @return	void
     */
    private function delete_profile($user_id)
    {
        Doctrine_Query::create()
            ->delete('Autologin')
            ->where('user_id = ?', $user_id)
            ->safeUseResultMemcache(FALSE)
            ->execute();

        Doctrine_Query::create()
            ->delete('UserProfile')
            ->where('user_id = ?', $user_id)
            ->safeUseResultMemcache(FALSE)
            ->execute();

        return TRUE;
    }
}

/* End of file user_model.php */
/* Location: ./application/models/codeigniter/auth/user_model.php */