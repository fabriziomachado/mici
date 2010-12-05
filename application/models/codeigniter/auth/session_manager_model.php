<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Models
 * @category SessionManager
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class Session_manager_model extends MI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Session Read
     *
     * @param string $session_id
     * @param string $ip_address
     * @param string $user_agent
     * @return object
     */
    public function sess_read($session_id, $ip_address, $user_agent)
    {
        $q = Doctrine_Query::create()
                ->from('Session')
                ->select('user_data')
                ->where('session_id = ?', $session_id)
                ->andWhere('ip_address = ?', $ip_address)
                ->andWhere('user_agent = ?', $user_agent)
                ->safeUseResultMemcache(FALSE)
                ->execute();

        if ($q->count() == 1)
            return $q[0];
        return NULL;
    }

    /**
     * Session Write
     *
     * @param string $session_id
     * @param string $last_activity
     * @param string $user_data
     * @return object
     */
    public function sess_write($session_id, $last_activity, $user_data)
    {
        if ($sess = Doctrine::getTable('Session')->findOneBySessionId($session_id))
        {
            $sess->last_activity = $last_activity;
            $sess->user_data = $user_data;
            $sess->save();

            return TRUE;
        }
        return FALSE;
    }

    /**
     * Session Create
     *
     * @param string $session_id
     * @param string $ip_address
     * @param string $last_activity
     * @param string $user_data
     * @return object
     */
    public function sess_create($session_id, $ip_address, $user_agent, $last_activity)
    {
        $sess = new Session();
        $sess->session_id = $session_id;
        $sess->ip_address = $ip_address;
        $sess->user_agent = $user_agent;
        $sess->last_activity = $last_activity;
        if ($sess->save())
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Session Update
     *
     * @param string $old_sessid
     * @param string $new_sessid
     * @return object
     */
    public function sess_update($old_sessid, $new_sessid)
    {
        if ($sess = Doctrine::getTable('Session')->findOneBySessionId($old_sessid))
        {
            $sess->session_id = $new_sessid;
            $sess->save();

            return TRUE;
        }
        return FALSE;
    }

    /**
     * Session Destroy
     *
     * @param string $session_id
     * @return	object
     */
    public function sess_destroy($session_id)
    {
        Doctrine_Query::create()
            ->delete('Session')
            ->where('session_id = ?', $session_id)
            ->safeUseResultMemcache(FALSE)
            ->execute();
    }

    /**
     * Garbage Collector
     *
     * @param string $expire
     * @return	object
     */
    public function garbage_collector($expire)
    {
        Doctrine_Query::create()
            ->delete('Session')
            ->where('last_activity < ?', $expire)
            ->safeUseResultMemcache(FALSE)
            ->execute();
    }
}

/* End of file session_manager_model.php */
/* Location: ./application/models/codeigniter/auth/session_manager_model.php */