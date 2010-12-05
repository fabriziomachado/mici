<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Models
 * @category Tag_model
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class Tag_model extends MI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
	try
	{
	    $tag = new Tag();
	    $tag->name = $data['name'];
            $tag->lc_name = strtolower($data['name']);
	    $tag->save();

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
	    $tag = Doctrine_Core::getTable('Tag')->findOneById($id);
	    $tag->name = $data['name'];
            $tag->lc_name = strtolower($data['name']);
	    $tag->save();

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
		    ->delete('Tag')
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

    public function get($id=NULL)
    {
	if ($id)
	{
	    try
	    {
		return Doctrine_Core::getTable('Tag')->findOneById($id);
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
			->from('Tag t')
			->orderBy('t.name ASC')
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

    public function get_all_tags($format=NULL)
    {
        try
        {
            $q = Doctrine_Query::create()
                    ->select('name')
                    ->from('Tag');

            $tags = $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

            return ($format == 'json')
                ? json_encode($tags)
                : $tags;
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

/* End of file tag_model.php */
/* Location: ./application/models/codeigniter/tag_model.php */