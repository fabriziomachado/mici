<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category FloodBlocker
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class FloodBlocker
{

    /**
     * The directory where log files will be saved. Must have permissions to write.
     *
     * @var string
     */
    private $logs_path;
    /**
     * IP address of current connection. REMOTE_ADDR will be used by default.
     *
     * @var string
     */
    private $ip_addr;
    /**
     * An associative array of [$interval=>$limit] format, where $limit is the number of possible requests during $interval seconds.
     *
     * @var array
     */
    private $rules;
    /**
     * The name of the cron file. Must begin with dot. Default filename is '.time'.
     *
     * @var string
     */
    private $cron_file;
    /**
     * Cron execution interval in seconds. 1800 secs (30 mins) by default.
     *
     * @var integer
     */
    private $cron_interval;
    /**
     * After how many of seconds to consider a file as old? By default the files will consider as old after 7200 secs (2 hours).
     *
     * @var integer
     */
    private $logs_timeout;
    /**
     * Known search bots. These should be allowed to spider your site quickly.
     *
     * @var array
     */
    private $whitelist;

    /**
     * Constructor.
     *
     */
    function __construct()
    {
        // connect to codeigniter
        $CI = & get_instance();

        $this->whitelist = $CI->config->item('floodblocker_whitelist');
        $this->logs_path = str_replace('\\', '/', $CI->config->item('floodblocker_logs_path'));

        if (!is_dir($this->logs_path))
        {
            show_error('Flood Blocker Error: Incorrect logs_path directory specified in ' . FRAMEWORK_CONFIG_INI . '. ' . $this->logs_path . ' does not exist.');
        }
        
        if (substr($this->logs_path, -1) != '/')
        {
            $this->logs_path .= '/';
        }

        $this->ip_addr = $CI->input->ip_address();
        $this->cron_file = $CI->config->item('floodblocker_cron_file');
        $this->cron_interval = $CI->config->item('floodblocker_cron_interval');
        $this->logs_timeout = $CI->config->item('floodblocker_logs_timeout');
        $this->rules = $CI->config->item('floodblocker_rules');

        if (!$this->_checkFlood())
        {
            header('Retry-After: ' . $this->logs_timeout);
            show_error('Your access has been temporarily restricted due to heavy usage.', 503);
        }
    }

    /**
     * Used to check flooding. Generally this function acts as private method and will be called internally by public methods. However, it can be called directly when storing logs in db.
     *
     * @param  string  $info	$interval=>$time, $interval=>$count array
     * @return boolean
     */
    private function _rawCheck(&$info)
    {
        $no_flood = TRUE;

        foreach ($this->rules as $interval => $limit)
        {
            if (!isset($info[$interval]))
            {
                $info[$interval]['time'] = time();
                $info[$interval]['count'] = 0;
            }

            $info[$interval]['count'] += 1;

            if ((time() - $info[$interval]['time']) > $interval)
            {
                $info[$interval]['count'] = 1;
                $info[$interval]['time'] = time();
            }

            if ($info[$interval]['count'] > $limit)
            {
                $info[$interval]['time'] = time();
                $no_flood = FALSE;
            }
        }
        return $no_flood;
    }

    /**
     * Checks flooding. Must be called after setting up all necessary properties.
     *
     * @return boolean
     */
    private function _checkFlood()
    {
        $ip = explode('.', $this->ip_addr);
        $check = $ip[0] . "." . $ip[1] . "." . $ip[2];

        if (in_array($check, $this->whitelist))
        {
            return TRUE;
        }
        else
        {
            $this->_checkCron();
            $path = $this->logs_path . $this->ip_addr;

            if (!($f = fopen($path, 'a+')))
            {
                log_message('error', 'Log file access error! Check permissions to write.');
            }
            flock($f, LOCK_EX);

            $info = fread($f, filesize($path) + 10);
            $info = unserialize($info);
            $result = $this->_rawCheck($info);

            ftruncate($f, 0);
            fwrite($f, serialize($info));
            fflush($f);
            flock($f, LOCK_UN);
            fclose($f);

            return $result;
        }
    }

    /**
     * Checks the cron file and calls _cronJob() to delete old entries from logs directory if the time-out is reached.
     *
     */
    private function _checkCron()
    {
        if (substr($this->cron_file, 0, 1) != '.')
        {
            log_message('error', 'The name of cron file must begin with dot.');
            return;
        }
        $path = $this->logs_path . $this->cron_file;

        if (!($f = fopen($path, 'a+')))
        {
            log_message('error', 'Cron file access error! Check permissions to write.');
            return;
        }
        flock($f, LOCK_EX);

        $last_cron = fread($f, filesize($path) + 10);
        $last_cron = abs(intval($last_cron));

        if (time() - $last_cron > $this->cron_interval)
        {
            $this->_cronJob();
            $last_cron = time();
        }

        ftruncate($f, 0);
        fwrite($f, $last_cron);
        fflush($f);
        flock($f, LOCK_UN);
        fclose($f);
    }

    /**
     * Deletes all old files from logs directory, except the files starting with dot.
     *
     */
    private function _cronJob()
    {
        $path = $this->logs_path;
        if (!($dir_hndl = opendir($this->logs_path)))
        {
            log_message('error', 'Unable to perform the cron job.');
            return;
        }
        while ($fname = readdir($dir_hndl))
        {
            if (substr($fname, 0, 1) == '.')
            {
                continue;
            }
            clearstatcache();
            $ftm = filemtime($path . $fname);
            if ((time() - $ftm) > $this->logs_timeout)
            {
                @unlink($path . $fname);
            }
        }
        closedir($dir_hndl);
    }
}

/* End of file floodblocker.php */
/* Location: ./application/libraries/floodblocker.php */