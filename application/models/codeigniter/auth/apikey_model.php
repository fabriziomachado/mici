<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Models
 * @category Apikey_Model
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class Apikey_model extends MI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get user record by Id
     *
     * @param string $key
     * @return mixed
     */
    public function validate_key($key)
    {
        if ($apiuser = Doctrine::getTable('Apikey')->findOneByApikey($key)->safeUseResultMemcache(FALSE))
        {
            if ($apiuser->status === 'active')
            {
                return TRUE;
            }
            else if ($apiuser->status !== 'created')
            {
                return 'This API Key has been ' . $apiuser->status . '.';
            }
        }
        return FALSE;
    }

}
/* End of file apikey_model.php */
/* Location: ./application/models/codeigniter/auth/apikey_model.php */