<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category MessageController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class MessageController extends MI_Controller
{
    /**
     * Construct
     */
    function __construct()
    {
	parent::__construct();

	// Load CI Models
	$this->load->model('codeigniter/message_model');
    }

    function index()
    {
	redirect('/admin/message/center');
    }

    function center()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $data['sidebar'] = $this->load->view('admin/partials/sidebar', $data, TRUE);

        $this->load->view('admin/partials/header', $data);
        $this->load->view('admin/message/center/index', $data);
        $this->load->view('admin/partials/footer', $data);
    }

    function spamfilter()
    {
        // check if we are adding a filter
        if($this->uri_array['add'] == 'filter')
        {
            if ($this->input->server('REQUEST_METHOD') === 'POST')
            {
                $clean_post = array();
                foreach ($_POST as $key => $val)
                {
                    $clean_post[$key] = $this->security->xss_clean(utf8_cleaner($val));
                }

                $post_data = $this->message_model->add_filter($clean_post);
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
                redirect('/admin/message/spamfilter');
            }
            else
            {
                $data = array();
                $data = array_merge($data, $this->data);

                $data['error_message'] = $this->session->flashdata('error_message');
                $data['error_code'] = $this->session->flashdata('error_code');
                $data['post_data'] = unserialize($this->session->flashdata('post_data'));
                $data['add_or_edit'] = 'Add';

                if( !empty($data['error_message']))
                {
                    $this->firephp->error($data['error_message']);
                    $this->firephp->error($data['post_data']);
                }

                $data['sidebar'] = $this->load->view('admin/partials/sidebar', $data, TRUE);

                $this->load->view('admin/partials/header', $data);
                $this->load->view('admin/message/spamfilter/detail', $data);
                $this->load->view('admin/partials/footer', $data);
            }
        }
        // check if we are editing a filter
        else if($this->uri_array['edit'] == 'filter')
        {
            if ($this->input->server('REQUEST_METHOD') === 'POST')
            {
                $clean_post = array();
                foreach ($_POST as $key => $val)
                {
                    $clean_post[$key] = $this->security->xss_clean(utf8_cleaner($val));
                }

                $post_data = $this->message_model->update_filter($this->uri_array['id'], $clean_post);
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
                redirect('/admin/message/spamfilter');
            }
            else
            {
                $data = array();
                $data = array_merge($data, $this->data);

                $data['error_message'] = $this->session->flashdata('error_message');
                $data['error_code'] = $this->session->flashdata('error_code');
                $data['post_data'] = unserialize($this->session->flashdata('post_data'));
                $data['add_or_edit'] = 'Edit';

                if( !empty($data['error_message']))
                {
                    $this->firephp->error($data['error_message']);
                    $this->firephp->error($data['post_data']);
                }

                $data['id'] = $this->uri_array['id'];
                $data['filter'] = $this->message_model->get_filter($data['id']);

                $data['sidebar'] = $this->load->view('admin/partials/sidebar', $data, TRUE);

                $this->load->view('admin/partials/header', $data);
                $this->load->view('admin/message/spamfilter/detail', $data);
                $this->load->view('admin/partials/footer', $data);
            }
        }
        else
        {
            if ($this->input->server('REQUEST_METHOD') === 'POST')
            {
                if ($this->input->post('action') == 'delete')
                {
                    foreach ($this->input->post('filters') as $filter_id)
                    {
                        $post_data = $this->message_model->delete_filter($filter_id);
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

                redirect('/admin/message/spamfilter');
            }
            else
            {
                $data = array();
                $data = array_merge($data, $this->data);

                $data['filters'] = $this->message_model->get_filter();

                $data['error_message'] = $this->session->flashdata('error_message');
                $data['error_code'] = $this->session->flashdata('error_code');

                $data['display_message'] = $this->session->flashdata('display_message');
                $data['display_message_type'] = $this->session->flashdata('display_message_type');

                if( !empty($data['error_message']))
                {
                    $this->firephp->error($data['error_message']);
                }

                $data['sidebar'] = $this->load->view('admin/partials/sidebar', $data, TRUE);

                $this->load->view('admin/partials/header', $data);
                $this->load->view('admin/message/spamfilter/index', $data);
                $this->load->view('admin/partials/footer', $data);
            }
        }
    }
}

/* End of file MessageController.php */
/* Location: ./application/controllers/admin/MessageController.php */