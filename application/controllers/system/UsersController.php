<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category UsersController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class UsersController extends MI_Controller
{
    /**
     * Construct
     */
    function __construct()
    {
        parent::__construct();

        // Load CI Model
        $this->load->model('codeigniter/users_model');
    }

    function index()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST')
	{
	    if ($this->input->post('action') == 'delete')
	    {
		foreach ($this->input->post('users') as $user_id)
		{
		    $post_data = $this->users_model->delete($user_id);
		    if(is_array($post_data))
		    {
			$this->session->set_flashdata('error_message', $post_data['error']);
			$this->session->set_flashdata('error_code', $post_data['code']);
		    }
		}
	    }

            // flush memcache
            if (class_exists('Memcache'))
            {
                $memcache = new Memcache();
                $memcache->connect('localhost', 11211);
                $memcache->flush();
            }

	    redirect('/system/users');
	}

        $data = array();
        $data = array_merge($data, $this->data);

        $data['error_message'] = $this->session->flashdata('error_message');
	$data['error_code'] = $this->session->flashdata('error_code');

	if( !empty($data['error_message']))
	{
	    $this->firephp->error($data['error_message']);
	}

        $data['user_types'] = array(
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'developer' => 'Developer',
            'editor' => 'Editor',
            'moderator' => 'Moderator',
            'user' => 'User'
        );

        $data['users'] = $this->users_model->get();

        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
        $this->load->view('system/partials/header', $data);
        $this->load->view('system/users/index', $data);
        $this->load->view('system/partials/footer', $data);
    }

    function add()
    {
	// form submission via POST
	if ($this->input->server('REQUEST_METHOD') === 'POST')
	{
	    $clean_post = array();
	    foreach ($_POST as $key => $val)
	    {
		$clean_post[$key] = $this->security->xss_clean(utf8_cleaner($val));
	    }

	    $post_data = $this->users_model->add($clean_post);
	    if(is_array($post_data))
	    {
		$this->session->set_flashdata('error_message', $post_data['error']);
		$this->session->set_flashdata('error_code', $post_data['code']);
		$this->session->set_flashdata('post_data', serialize($clean_post));
	    }
	    unset($clean_post);

            // flush memcache
            if (class_exists('Memcache'))
            {
                $memcache = new Memcache();
                $memcache->connect('localhost', 11211);
                $memcache->flush();
            }

	    // redirect
	    redirect('/system/users');
	}
	// not form submission
	else
	{
	    $data = array();
	    $data = array_merge($data, $this->data);

	    $data['error_message'] = $this->session->flashdata('error_message');
	    $data['error_code'] = $this->session->flashdata('error_code');
	    $data['post_data'] = unserialize($this->session->flashdata('post_data'));

	    if( !empty($data['error_message']))
	    {
		$this->firephp->error($data['error_message']);
		$this->firephp->error($data['post_data']);
	    }

	    $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);

	    $this->load->view('system/partials/header', $data);
	    $this->load->view('system/users/add', $data);
	    $this->load->view('system/partials/footer', $data);
	}
    }

    function edit()
    {
	// form submission via POST
	if ($this->input->server('REQUEST_METHOD') === 'POST')
	{
	    $clean_post = array();
	    foreach ($_POST as $key => $val)
	    {
		$clean_post[$key] = $this->security->xss_clean(utf8_cleaner($val));
	    }

	    $post_data = $this->users_model->update($this->uri_array['id'], $clean_post);
	    if(is_array($post_data))
	    {
		$this->session->set_flashdata('error_message', $post_data['error']);
		$this->session->set_flashdata('error_code', $post_data['code']);
		$this->session->set_flashdata('post_data', serialize($clean_post));
	    }
	    unset($clean_post);

            // flush memcache
            if (class_exists('Memcache'))
            {
                $memcache = new Memcache();
                $memcache->connect('localhost', 11211);
                $memcache->flush();
            }

	    // redirect
	    redirect('/system/users');
	}
	// not form submission
	else
	{
	    $data = array();
	    $data = array_merge($data, $this->data);

	    $data['id'] = $this->uri_array['id'];
	    $data['user'] = $this->users_model->get($data['id']);

	    $data['error_message'] = $this->session->flashdata('error_message');
	    $data['error_code'] = $this->session->flashdata('error_code');
	    $data['post_data'] = unserialize($this->session->flashdata('post_data'));

	    if( !empty($data['error_message']))
	    {
		$this->firephp->error($data['error_message']);
		$this->firephp->error($data['post_data']);
	    }

	    $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);

	    $this->load->view('system/partials/header', $data);
	    $this->load->view('system/users/edit', $data);
	    $this->load->view('system/partials/footer', $data);
	}
    }
}

/* End of file UsersController.php */
/* Location: ./application/controllers/system/UsersController.php */