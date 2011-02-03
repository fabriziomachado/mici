<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category DatabaseController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class DatabaseController extends MI_Controller
{
    /**
     * Construct
     */
    function __construct()
    {
        parent::__construct();

        $this->load->helper('conversion');
        $this->load->helper('directory_details');
    }

    function index()
    {
        $data = array();
        $data = array_merge($data, $this->data);
        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
        $this->load->view('system/partials/header', $data);
        $this->load->view('system/database/home', $data);
        $this->load->view('system/partials/footer', $data);
    }

    function schema()
    {
        $data = array();
        $data = array_merge($data, $this->data);
        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
        $this->load->view('system/partials/header', $data);
        $this->load->view('system/database/schema', $data);
        $this->load->view('system/partials/footer', $data);
    }

    function migration()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        // define developer path info
        $dev_fixtures_path = APPPATH . 'doctrine/dev_local/fixtures/';
        $dev_migrations_path = APPPATH . 'doctrine/dev_local/migrations/';
        $dev_models_path = APPPATH . 'doctrine/dev_local/models/';
        $dev_schema_merge_path = APPPATH . 'doctrine/dev_local/schemas/';
        $dev_schema_split_path = APPPATH . 'doctrine/orm_designer/yml/';

        // define live path info
        $live_fixtures_path = APPPATH . 'doctrine/fixtures/';
        $live_migrations_path = APPPATH . 'doctrine/migrations/';
        $live_models_path = APPPATH . 'models/doctrine/generated/';
        $live_schemas_path = APPPATH . 'doctrine/schemas/';

        // generate new fixtures from database
        if (isset($this->uri_array['generate']) && $this->uri_array['generate'] === 'fixtures')
        {
            // remove old files first
            foreach (glob($dev_fixtures_path . '*.yml') as $filename)
            {
                unlink($filename);
            }

            // try to make new files
            try
            {
                Doctrine_Core::dumpData($dev_fixtures_path);
            }
            // or throw error
            catch (Doctrine_Connection_Exception $e)
            {
                log_message('error', $e->getMessage());
            }

            // redirect
            redirect('/system/database/migration#fixtures');
        }
        // copy live schema history
        else if (isset($this->uri_array['copy']) && $this->uri_array['copy'] === 'schemas')
        {
            // remove old files first
            foreach (glob($dev_schema_merge_path . '*.yml') as $filename)
            {
                unlink($dev_schema_merge_path . basename($filename));
            }
            // copy new files
            foreach (glob($live_schemas_path . '*.yml') as $filename)
            {
                copy($filename, $dev_schema_merge_path . basename($filename));
            }

            // redirect
            redirect('/system/database/migration#schemas');
        }
        // copy live migration history
        else if (isset($this->uri_array['copy']) && $this->uri_array['copy'] === 'migrations')
        {
            // remove old files first
            foreach (glob($dev_migrations_path . '*.php') as $filename)
            {
                unlink($dev_migrations_path . basename($filename));
            }
            // copy new files
            foreach (glob($live_migrations_path . '*.php') as $filename)
            {
                copy($filename, $dev_migrations_path . basename($filename));
            }

            // redirect
            redirect('/system/database/migration#migrations');
        }
        // generate migration diff
        else if (isset($this->uri_array['generate']) && isset($this->uri_array['from']) && isset($this->uri_array['to']) && $this->uri_array['generate'] === 'migration')
        {
            // try to make new files
            try
            {
                Doctrine_Core::generateMigrationsFromDiff($dev_migrations_path, $dev_schema_merge_path.'schema'.$this->uri_array['from'].'.yml', $dev_schema_merge_path.'schema'.$this->uri_array['to'].'.yml');
            }
            // or throw error
            catch (Doctrine_Connection_Exception $e)
            {
                log_message('error', $e->getMessage());
            }

            // redirect
            redirect('/system/database/migration#migrations');
        }
        // generate models from selected schema file
        else if (isset($this->uri_array['generate']) && isset($this->uri_array['version']) && $this->uri_array['generate'] === 'models' && $this->uri_array['version'])
        {

            $schema_version = (int) $this->uri_array['version'];
            $schema_file = "{$dev_schema_merge_path}schema{$schema_version}.yml";
            $options = array(
                "generateBaseClasses" => TRUE,
                "generateTableClasses" => FALSE
            );

            // verify file exists before we send it to doctrine
            if (file_exists($schema_file))
            {

                // remove old files first
                foreach (glob($dev_models_path . '*.php') as $filename)
                {
                    unlink($filename);
                }
                foreach (glob($dev_models_path . 'generated/*.php') as $filename)
                {
                    unlink($filename);
                }

                // try to generate models
                try
                {
                    Doctrine::generateModelsFromYaml($schema_file, $dev_models_path, $options);
                }
                // or throw error
                catch (Doctrine_Connection_Exception $e)
                {
                    log_message('error', $e->getMessage());
                }
            }
            else
            {
                log_message('error', "{$schema_file} does not exist.");
            }

            // redirect
            redirect('/system/database/migration#models');
        }
        // delete selected fixtures
        else if (isset($this->uri_array['delete']) && $this->uri_array['delete'] === 'fixtures' && $this->input->post('files') && $this->input->post('fixtures_action') === 'delete' && $this->input->server('REQUEST_METHOD') === 'POST')
        {
            foreach ($this->input->post('files') as $file)
            {
                if (file_exists($dev_fixtures_path . $file))
                {
                    unlink($dev_fixtures_path . $file);
                }
            }

            // redirect
            redirect('/system/database/migration');
        }
        // delete selected migration files
        else if (isset($this->uri_array['delete']) && $this->uri_array['delete'] === 'migrations' && $this->input->post('migration_files') && $this->input->post('migration_action') === 'delete' && $this->input->server('REQUEST_METHOD') === 'POST')
        {
            foreach ($this->input->post('migration_files') as $file)
            {
                if (file_exists($dev_migrations_path . $file))
                {
                    unlink($dev_migrations_path . $file);
                }
            }

            // redirect
            redirect('/system/database/migration#migrations');
        }
        // delete selected schema files
        else if (isset($this->uri_array['delete']) && $this->uri_array['delete'] === 'schemas' && $this->input->post('schema_files') && $this->input->post('schema_action') === 'delete' && $this->input->server('REQUEST_METHOD') === 'POST')
        {
            foreach ($this->input->post('schema_files') as $file)
            {
                if (file_exists($dev_schema_merge_path . $file))
                {
                    unlink($dev_schema_merge_path . $file);
                }
            }

            // redirect
            redirect('/system/database/migration#schemas');
        }
        // view selected fixtures
        else if (isset($this->uri_array['view']) && isset($this->uri_array['file']) && $this->uri_array['view'] === 'fixture' && $this->uri_array['file'] != '')
        {
            $data['page_title'] = 'Local Migration Manager';
            $data['back_button'] = base_url() . 'system/database/migration';
            $data['file_type'] = 'yml';
            $data['file_name'] = $dev_fixtures_path . $this->uri_array['file'] . '.yml';
            $data['file_content'] = file_get_contents($data['file_name']);
            $data['file_details'] = file_details($data['file_name']);

            $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
            $this->load->view('system/partials/header', $data);
            $this->load->view('system/viewfile', $data);
            $this->load->view('system/partials/footer', $data);
        }
        // view selected models
        else if (isset($this->uri_array['view']) && isset($this->uri_array['file']) && $this->uri_array['view'] === 'model' && $this->uri_array['file'] != '')
        {
            $data['page_title'] = 'Local Migration Manager';
            $data['back_button'] = base_url() . 'system/database/migration#models';
            $data['file_type'] = 'php';
            $data['file_name'] = $dev_models_path . 'generated/' . $this->uri_array['file'] . '.php';
            $data['file_content'] = file_get_contents($data['file_name']);
            $data['file_details'] = file_details($data['file_name']);

            $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
            $this->load->view('system/partials/header', $data);
            $this->load->view('system/viewfile', $data);
            $this->load->view('system/partials/footer', $data);
        }
        // view selected orm yml
        else if (isset($this->uri_array['view']) && isset($this->uri_array['file']) && $this->uri_array['view'] === 'orm' && $this->uri_array['file'] != '')
        {
            $data['page_title'] = 'Local Migration Manager';
            $data['back_button'] = base_url() . 'system/database/migration#orm';
            $data['file_type'] = 'yml';
            $data['file_name'] = $dev_schema_split_path . $this->uri_array['file'] . '.yml';
            $data['file_content'] = file_get_contents($data['file_name']);
            $data['file_details'] = file_details($data['file_name']);

            $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
            $this->load->view('system/partials/header', $data);
            $this->load->view('system/viewfile', $data);
            $this->load->view('system/partials/footer', $data);
        }
        // view selected schema
        else if (isset($this->uri_array['view']) && isset($this->uri_array['file']) && $this->uri_array['view'] === 'schema' && $this->uri_array['file'] != '')
        {
            $data['page_title'] = 'Local Migration Manager';
            $data['back_button'] = base_url() . 'system/database/migration#schemas';
            $data['file_type'] = 'yml';
            $data['file_name'] = $dev_schema_merge_path . $this->uri_array['file'] . '.yml';
            $data['file_content'] = file_get_contents($data['file_name']);
            $data['file_details'] = file_details($data['file_name']);

            $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
            $this->load->view('system/partials/header', $data);
            $this->load->view('system/viewfile', $data);
            $this->load->view('system/partials/footer', $data);
        }
        // view selected migration
        else if (isset($this->uri_array['view']) && isset($this->uri_array['file']) && $this->uri_array['view'] === 'migration' && $this->uri_array['file'] != '')
        {
            $data['page_title'] = 'Local Migration Manager';
            $data['back_button'] = base_url() . 'system/database/migration#migrations';
            $data['file_type'] = 'php';
            $data['file_name'] = $dev_migrations_path . $this->uri_array['file'] . '.php';
            $data['file_content'] = file_get_contents($data['file_name']);
            $data['file_details'] = file_details($data['file_name']);

            $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
            $this->load->view('system/partials/header', $data);
            $this->load->view('system/viewfile', $data);
            $this->load->view('system/partials/footer', $data);
        }
        // merge ORM YML files into new schema
        else if (isset($this->uri_array['orm']) && isset($this->uri_array['merge']) && $this->uri_array['orm'] === 'designer' && $this->uri_array['merge'] === 'files')
        {
            // determine version number
            $dev_schema_merge_files = directory_details($dev_schema_merge_path, 'yml');
            $new_version = (count($dev_schema_merge_files) + 1);
            $new_schema = $dev_schema_merge_path . 'schema' . $new_version . '.yml';

            // this should not happen, but lets check if schema file already exists
            if (file_exists($new_schema))
            {
                unlink($new_schema);
            }

            // merge files
            foreach (glob($dev_schema_split_path . '*.yml') as $filename)
            {
                $file_contents = file_get_contents($filename);
                file_put_contents($new_schema, $file_contents, FILE_APPEND);
            }

            // check files to see if there was anything new actually created
            if ($new_version > 1)
            {
                $old_schema = $dev_schema_merge_path . 'version' . ($new_version - 1) . '.yml';
                if (file($old_schema) === file($new_schema))
                {
                    unlink($new_schema);

                    $message = 'The new Schema File you were attempting to create was the exact same as the the current Schema file. Your attempt was ignored and no new Schema file was created.';

                    log_message('error', $message);

                    $data['error'] = $message;
                    $data['redirect'] = 'system/database/migration#orm';
                    $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
                    $this->load->view('system/partials/header', $data);
                    $this->load->view('system/error', $data);
                    $this->load->view('system/partials/footer', $data);

                    return TRUE;
                }
            }

            // redirect
            redirect('/system/database/migration#schemas');
        }
        // get list of files for interface
        else
        {
            $data['dev_fixtures_files'] = directory_details($dev_fixtures_path, 'yml');
            $data['dev_migrations_files'] = directory_details($dev_migrations_path, 'php');
            $data['dev_models_files'] = directory_details($dev_models_path.'generated/', 'php');
            $data['dev_schema_merge_files'] = directory_details($dev_schema_merge_path, 'yml');
            $data['dev_schema_split_files'] = directory_details($dev_schema_split_path, 'yml');

            $data['live_fixtures_files'] = directory_details($live_fixtures_path, 'yml');
            $data['live_migrations_files'] = directory_details($live_migrations_path, 'php');
            $data['live_models_files'] = directory_details($live_models_path, 'php');
            $data['live_schemas_files'] = directory_details($live_schemas_path, 'yml');

            if (count($data['dev_models_files']) > 0)
            {
                $file_count = 0;
                foreach ($data['dev_models_files'] as $dev_file)
                {
                    $status = '';
                    if( !isset($data['live_models_files'][0]['full_path']))
                    {
                        $old = '';
                    }
                    else
                    {
                        $old = $data['live_models_files'][$file_count]['full_path'];
                    }

                    $new = $dev_file['full_path'];

                    if (file_exists($old) && file_exists($new))
                    {
                        $status = (file($old) === file($new))
                            ? 'same'
                            : 'different';
                    }

                    $status = (file_exists($live_models_path . $dev_file['name']))
                        ? $status
                        : 'missing';

                    switch ($status)
                    {
                        case 'same':
                            $status_icon = 'bullet_white';
                            $status_text = 'Same as Live Site Model';
                            break;

                        case 'different':
                            $status_icon = 'bullet_green';
                            $status_text = 'Newer than Live Site Model';
                            break;

                        case 'missing':
                            $status_icon = 'bullet_go';
                            $status_text = 'Model does not exist on Live Site';
                            break;
                    }

                    $data['dev_models_changes'][$dev_file['name']] = array(
                        'status' => $status,
                        'title' => $status_text,
                        'icon' => $status_icon
                    );
                    $file_count++;
                }
            }

            $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
            $this->load->view('system/partials/header', $data);
            $this->load->view('system/database/migration', $data);
            $this->load->view('system/partials/footer', $data);
        }
    }

    function update()
    {
        $data = array();
        $data = array_merge($data, $this->data);
        
        $migration = new Doctrine_Migration(APPPATH . 'doctrine/migrations/');

        $data['current_version'] = $migration->getCurrentVersion();
        $data['latest_version'] = $migration->getLatestVersion();

        if (isset($this->uri_array['version']) && $this->uri_array['version'] == $data['latest_version'])
        {
            // try migration
            try
            {
                $migration->migrate($data['latest_version']);
            }
            // or throw error
            catch (Doctrine_Migration_Exception $e)
            {
                $this->session->set_flashdata('error_message', 'There was an error while performing the Database Update. Access todays '.anchor('/system/logs/php/date/log-'.date('Y-m-d'), 'PHP Error Log').' for detailed information.');
                
                log_message('error', $e->getMessage());
            }

            // flush memcache
            if (class_exists('Memcache'))
            {
                $memcache = new Memcache();
                $memcache->connect('localhost', 11211);
                $memcache->flush();
            }

            // redirect
            redirect('/system/database/update');
        }

        $data['error_message'] = $this->session->flashdata('error_message');
        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);

        $this->load->view('system/partials/header', $data);
        $this->load->view('system/database/update', $data);
        $this->load->view('system/partials/footer', $data);
    }
}

/* End of file DatabaseController.php */
/* Location: ./application/controllers/system/DatabaseController.php */