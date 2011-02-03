<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Models
 * @category UserAutologin
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

/**
 * User_autologin_model
 *
 * This model represents user autologin data. It can be used
 * for user verification when user claims his autologin passport.
 *
 */
class User_autologin_model extends MI_Model
{
    function __construct($id = null)
    {
        parent::__construct('key_' . $id);
    }

    /**
     * Get user data for auto-logged in user.
     * Return NULL if given key or user ID is invalid.
     *
     * @param	int
     * @param	string
     * @return	object
     */
    function get($user_id, $key)
    {
        $q = Doctrine_Query::create()
                ->from('User u')
                ->select('u.id, u.username')
                ->leftJoin('u.autologin a')
                ->where('a.user_id = ?', $user_id)
                ->andWhere('a.key_id = ?', $key)
                ->execute();

        if ($q->count() === 1)
        {
            return $q[0];
        }

        return NULL;
    }

    /**
     * Save data for user's autologin
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    function set($user_id, $key)
    {
        $ua = new Autologin();
        $ua->user_id = $user_id;
        $ua->key_id = $key;
        $ua->user_agent = substr($this->input->user_agent(), 0, 149);
        $ua->last_ip = $this->input->ip_address();
        if ($ua->save())
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Delete user's autologin data
     *
     * @param	int
     * @param	string
     * @return	void
     */
    public function delete($user_id, $key)
    {
        Doctrine_Query::create()
            ->delete('Autologin')
            ->where('user_id = ?', $user_id)
            ->andWhere('key_id = ?', $key)
            ->execute();
    }

    /**
     * Delete all autologin data for given user
     *
     * @param	int
     * @return	void
     */
    function clear($user_id)
    {
        Doctrine_Query::create()
            ->delete('Autologin')
            ->where('user_id = ?', $user_id)
            ->execute();
    }

    /**
     * Purge autologin data for given user and login conditions
     *
     * @param	int
     * @return	void
     */
    public function purge($user_id)
    {
        Doctrine_Query::create()
            ->delete('Autologin')
            ->where('user_id = ?', $user_id)
            ->andWhere('user_agent = ?', substr($this->input->user_agent(), 0, 149))
            ->andWhere('last_ip = ?', $this->input->ip_address())
            ->execute();
    }
}

/* End of file user_autologin_model.php */
/* Location: ./application/models/codeigniter/auth/user_autologin_model.php */