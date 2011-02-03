<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Models
 * @category Login_attempt_model
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

/**
 * Login_attempt
 *
 * This model serves to watch on all attempts to login on the site
 * (to protect the site from brute-force attack to user database)
 *
 */
class Login_attempt_model extends MI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Increase Attempt
     *
     * Increase number of attempts for given IP-address and login
     *
     * @param string $ip_address
     * @param string $login
     * @return void
     */
    function increase_attempt($ip_address, $login)
    {
        $login_attempt = new LoginAttempt();
        $login_attempt->ip_address = $ip_address;
        $login_attempt->login = $login;
        $login_attempt->save();
    }

    /**
     * Clear Attempts
     *
     * Clear all attempt records for given IP-address and login.
     * Also purge obsolete login attempts (to keep DB clear).
     *
     * @param string $ip_address
     * @param string $login
     * @param int $expire_period
     * @return void
     */
    public function clear_attempts($ip_address, $login, $expire_period = 86400)
    {
        Doctrine_Query::create()
            ->delete('LoginAttempt')
            ->where('ip_address = ?', $ip_address)
            ->andWhere('login = ?', $login)
            ->orWhere('UNIX_TIMESTAMP(time) < ?', time() - $expire_period)
            ->execute();
    }

    /**
     * Get Attempts Number
     *
     * Get number of attempts to login occured from given IP-address or login
     *
     * @param string $ip_address
     * @param string $login
     * @return int
     */
    function get_attempts_num($ip_address, $login)
    {
        if (strlen($login) > 0)
        {
            $q = Doctrine_Query::create()
                    ->from('LoginAttempt')
                    ->select('*')
                    ->where('ip_address = ?', $ip_address)
                    ->orWhere('login = ?', $login)
                    ->execute();
        }
        else
        {
            $q = Doctrine_Query::create()
                    ->from('LoginAttempt')
                    ->select('*')
                    ->where('ip_address = ?', $ip_address)
                    ->execute();
        }
        return $q->count();
    }

}
/* End of file login_attempt_model.php */
/* Location: ./application/models/codeigniter/auth/login_attempt_model.php */