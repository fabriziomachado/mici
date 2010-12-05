<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category LogsController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class LogsController extends MI_Controller
{
    /**
     * Construct
     */
    function __construct()
    {
        parent::__construct();
    }

    function php()
    {
        $this->load->helper('conversion');

        $data = array();
        $data = array_merge($data, $this->data);

        // trash the log files that were selected
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $logfiles = $this->input->post('logs');
            foreach ($logfiles as $logfile)
            {
                // check if log file exists and trash it if it does
                $file = $this->config->item('log_path') . $logfile;
                if (file_exists($file))
                {
                    unlink($file);
                }
            }
        }

        if (isset($this->uri_array['date']))
        {
            $data['date'] = substr($this->uri_array['date'], 4, 14);
            $data['errors'] = array();

            // get log file
            $logfile = $this->config->item('log_path') . $this->uri_array['date'] . '.php';
            $data['logfile'] = $logfile;

            // get log file data
            if (file_exists($logfile))
            {
                $size = filesize($logfile);
                $handle = fopen($logfile, "rb");
                $contents = fread($handle, $size);
                $lines = explode("\n", $contents);

                // loop through lines
                $count = 0;
                foreach ($lines as $line)
                {
                    // skip first two lines as they are not errors
                    if ($count > 1)
                    {
                        // check for empty lines
                        if (strlen($line) > 0)
                        {
                            $line = preg_replace("|<a href='function\.([a-z-]+)'>|", "<a href='http://php.net/manual/en/function.\\1.php' target='_blank'>", $line);
                            $line = preg_replace("|ERROR|", "<span style='color:red; font-weight: bold;'>ERROR</span>", $line);
                            $line = preg_replace("|DEBUG|", "<span style='color: #999;'>DEBUG</span>", $line);
                            $data['errors'][] = $line;
                        }
                    }
                    $count++;
                }

                // close file
                fclose($handle);
            }
        }
        // generate list of php error log files
        else
        {
            $data['errorfiles'] = array();
            $logpath = $this->config->item('log_path');

            if ($handle = opendir($logpath))
            {
                while (false !== ($file = readdir($handle)))
                {
                    if (substr($file, 0, 4) == 'log-' && substr(strrchr($file, '.'), 1) == 'php')
                    {
                        $lines = (count(file($logpath . $file)) - 2);
                        $size = bsize(filesize($logpath . $file));
                        $modified = date('g:i:s A T', filemtime($logpath . $file));
                        $data['errorfiles'][] = array(
                            'name' => $file,
                            'lines' => $lines,
                            'size' => $size,
                            'modified' => $modified,
                            'path' => $logpath . $file
                        );
                    }
                }
                closedir($handle);
            }
        }

        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
        $this->load->view('system/partials/header', $data);
        $this->load->view('system/logs', $data);
        $this->load->view('system/partials/footer', $data);
    }

    function index()
    {
        // trash the log files that were selected
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $logfiles = $this->input->post('logs');
            foreach ($logfiles as $logfile)
            {
                // check if log file exists and trash it if it does
                $file = $this->config->item('log_path') . $logfile;
                if (file_exists($file))
                {
                    unlink($file);
                }
            }
        }

        $data = array();
        $data = array_merge($data, $this->data);

        $this->load->helper('conversion');

        $data['sidebar'] = $this->load->view('system/partials/sidebar', $data, TRUE);
        $this->load->view('system/partials/header', $data);
        $this->load->view('system/logs', $data);
        $this->load->view('system/partials/footer', $data);
    }
}

/* End of file LogsController.php */
/* Location: ./application/controllers/system/LogsController.php */