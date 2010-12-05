<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Models
 * @category Users_model
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

class Users_model extends MI_Model
{
    const PHPASS_HASH_STRENGTH = 8;
    const PHPASS_HASH_PORTABLE = FALSE;

    function __construct()
    {
        parent::__construct();
    }

    public function get($id=NULL)
    {
	if ($id)
	{
	    try
	    {
		$user = Doctrine_Core::getTable('User')->findOneById($id);

		return $user;
	    }
	    catch (Doctrine_Connection_Exception $e)
	    {
		log_message('error', $e->getMessage());
		return array(
		    'error' => $e->getMessage(),
		    'code' => $e->getCode()
		);
	    }
	}
	else
	{
	    try
	    {
		return Doctrine_Query::create()
			->from('User u')
			->orderBy('u.display_name ASC')
			->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	    }
	    catch (Doctrine_Connection_Exception $e)
	    {
		log_message('error', $e->getMessage());
		return array(
		    'error' => $e->getMessage(),
		    'code' => $e->getCode()
		);
	    }
	}
    }

    /**
     * Create new user record
     *
     * @param	array
     * @param	bool
     * @return	array
     */
    public function add($data)
    {
        try
	{
            $hasher = new PasswordHash(self::PHPASS_HASH_STRENGTH, self::PHPASS_HASH_PORTABLE);
            $hashed_password = $hasher->HashPassword($data['password']);

            $user = new User();
            $user->username = $data['username'];
            $user->password = $hashed_password;
            $user->display_name = $data['display_name'];
            $user->email = $data['email'];
            $user->role = $data['role'];
            $user->activated = 1;
            $user->save();
            
            return TRUE;
	}
	catch (Doctrine_Connection_Exception $e)
	{
	    log_message('error', $e->getMessage());
	    return array(
		'error' => $e->getMessage(),
		'code' => $e->getCode()
	    );
	}
    }

    public function delete($id)
    {
	try
	{
	    Doctrine_Query::create()
                ->delete('User')
                ->where('id = ?', $id)
                ->execute();

	    return TRUE;
	}
	catch (Doctrine_Connection_Exception $e)
	{
	    log_message('error', $e->getMessage());
	    return array(
		'error' => $e->getMessage(),
		'code' => $e->getCode()
	    );
	}
    }

    public function update($id, $data)
    {
	try
	{
	    

            $user = Doctrine_Core::getTable('User')->findOneById($id);
            $user->username = $data['username'];

            if($data['password'] != '')
            {
                $hasher = new PasswordHash(self::PHPASS_HASH_STRENGTH, self::PHPASS_HASH_PORTABLE);
                $hashed_password = $hasher->HashPassword($data['password']);
                $user->password = $hashed_password;
            }
            
            $user->display_name = $data['display_name'];
            $user->email = $data['email'];
            $user->role = $data['role'];
            $user->save();

            return TRUE;
	}
	catch (Doctrine_Connection_Exception $e)
	{
	    log_message('error', $e->getMessage());
	    return array(
		'error' => $e->getMessage(),
		'code' => $e->getCode()
	    );
	}
    }
}

/* End of file users_model.php */
/* Location: ./application/models/codeigniter/users_model.php */