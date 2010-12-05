<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Helper
 * @category Profiler_helper
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class Profiler_helper
{
    static $profiler_helper_instance = NULL;

    static function profiler_helper_load()
    {
        $dir = dirname(__FILE__);
        Profiler_helper::$profiler_helper_instance = new Profiler(Profiler::getMicroTime());
    }

    static function profiler_helper_pre_controller()
    {
        Console::logMemory(FALSE, 'CI PRE CONTROLLER');
        Console::logSpeed('CI PRE CONTROLLER');
    }

    static function profiler_helper_post_controller_constructor()
    {
        Console::logMemory(FALSE, 'CI POST CONTROLLER CONSTRUCTOR');
        Console::logSpeed('CI POST CONTROLLER CONSTRUCTOR');
    }

    static function profiler_helper_post_controller()
    {
        Console::logMemory(FALSE, 'CI POST CONTROLLER');
        Console::logSpeed('CI POST CONTROLLER');
    }

    static function profiler_helper_gen_db_results($doctrine)
    {
        $db_obj = new stdClass();
        $db_obj->queries = array();
        $db_obj->queryCount = $doctrine['total_queries'];

        if (count($doctrine['total_queries']) == 0)
        {
            return $db_obj;
        }

        foreach ($doctrine['events'] as $event)
        {
            if ($event['params'])
            {
                $query = $event['query'];

                foreach ($event['params'] as $param)
                {
                    $query = preg_replace('|\?|', $param, $query, 1);
                }

                $query = array(
                    'sql' => $query,
                    'time' => $event['time']
                );
            }
            else
            {
                $query = array(
                    'sql' => $event['query'],
                    'time' => $event['time']
                );
            }

            array_push($db_obj->queries, $query);
        }

        return $db_obj;
    }

    static function profiler_helper_benchmark_results()
    {
        $CI = get_instance();
        $profile = array();
        foreach ($CI->benchmark->marker as $key => $val)
        {
            if (preg_match("/(.+?)_end/i", $key, $match))
            {
                if (isset($CI->benchmark->marker[$match[1] . '_end']) AND isset($CI->benchmark->marker[$match[1] . '_start']))
                {
                    $profile[$match[1]] = $CI->benchmark->elapsed_time($match[1] . '_start', $key);
                }
            }
        }

        foreach ($profile as $key => $val)
        {
            $key = ucwords(str_replace(array('_', '-'), ' ', $key));
            Console::log($key . ': ' . $val);
            Console::logSpeed($key . ': ' . $val);
        }

        Console::log('GET: ' . print_r($_GET, TRUE));
        Console::log('POST: ' . print_r($_POST, TRUE));
    }
}

class Profiler
{
    public $output = array();
    public $config = '';

    public function __construct($startTime, $config = '')
    {
        $framework_ini = parse_ini_file(FRAMEWORK_CONFIG_INI, TRUE);

        $this->startTime = $startTime;
        $this->config = $framework_ini['config']['base_url'];
    }

    public function gatherConsoleData()
    {
        $logs = Console::getLogs();
        if (isset($logs['console']))
        {
            foreach ($logs['console'] as $key => $log)
            {
                if ($log['type'] == 'log')
                {
                    $logs['console'][$key]['data'] = print_r($log['data'], true);
                }
                elseif ($log['type'] == 'memory')
                {
                    $logs['console'][$key]['data'] = $this->getReadableFileSize($log['data']);
                }
                elseif ($log['type'] == 'speed')
                {
                    $logs['console'][$key]['data'] = $this->getReadableTime(($log['data'] - $this->startTime) * 1000);
                }
            }
        }
        $this->output['logs'] = $logs;
    }

    public function gatherFileData()
    {
        $files = get_included_files();
        $fileList = array();
        $fileTotals = array(
            "count" => count($files),
            "size" => 0,
            "largest" => 0,
        );

        foreach ($files as $key => $file)
        {
            if (file_exists($file))
            {
                $size = filesize($file);
            }
            else
            {
                $size = 0;
            }
            $fileList[] = array(
                'name' => $file,
                'size' => $this->getReadableFileSize($size)
            );
            $fileTotals['size'] += $size;
            if ($size > $fileTotals['largest'])
            {
                $fileTotals['largest'] = $size;
            }
        }

        $fileTotals['size'] = $this->getReadableFileSize($fileTotals['size']);
        $fileTotals['largest'] = $this->getReadableFileSize($fileTotals['largest']);
        $this->output['files'] = $fileList;
        $this->output['fileTotals'] = $fileTotals;
    }

    public function gatherMemoryData()
    {
        $memoryTotals = array();
        $memoryTotals['used'] = $this->getReadableFileSize(memory_get_peak_usage());
        $memoryTotals['total'] = ini_get("memory_limit");
        $this->output['memoryTotals'] = $memoryTotals;
    }

    public function gatherQueryData()
    {
        $queryTotals = array();
        $queryTotals['count'] = 0;
        $queryTotals['time'] = 0;
        $queries = array();

        if ($this->db != '')
        {
            $queryTotals['count'] += $this->db->queryCount;
            foreach ($this->db->queries as $key => $query)
            {
                $query = $this->attemptToExplainQuery($query);
                $queryTotals['time'] += $query['time'];
                $query['time'] = $this->getReadableTime($query['time']);
                $queries[] = $query;
            }
        }

        $queryTotals['time'] = $this->getReadableTime($queryTotals['time']);
        $this->output['queries'] = $queries;
        $this->output['queryTotals'] = $queryTotals;
    }

    function attemptToExplainQuery($query)
    {
        if (strtolower(substr($query['sql'], 0, 6)) !== 'select')
        {
            return $query;
        }

        $ci = get_instance();
        if (is_object($ci->db))
        {
            $sql = 'EXPLAIN ' . $query['sql'];
            $rs = $ci->db->query($sql);
            $results = $rs->result_array();

            if (isset($results[0]))
            {
                $query['explain'] = $results[0];
            }
        }

        return $query;
    }

    public function gatherSpeedData()
    {
        $speedTotals = array();
        $speedTotals['total'] = $this->getReadableTime(($this->getMicroTime() - $this->startTime) * 1000);
        $speedTotals['allowed'] = ini_get("max_execution_time");
        $this->output['speedTotals'] = $speedTotals;
    }

    function getMicroTime()
    {
        $time = microtime();
        $time = explode(' ', $time);
        return $time[1] + $time[0];
    }

    public function getReadableFileSize($size, $retstring = null)
    {
        $sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        if ($retstring === null)
        {
            $retstring = '%01.2f %s';
        }

        $lastsizestring = end($sizes);

        foreach ($sizes as $sizestring)
        {
            if ($size < 1024)
            {
                break;
            }
            if ($sizestring != $lastsizestring)
            {
                $size /= 1024;
            }
        }
        if ($sizestring == $sizes[0])
        {
            $retstring = '%01d %s';
        }

        return sprintf($retstring, $size, $sizestring);
    }

    public function getReadableTime($time)
    {
        $ret = $time;
        $formatter = 0;
        $formats = array('ms', 's', 'm');

        if ($time >= 1000 && $time < 60000)
        {
            $formatter = 1;
            $ret = ($time / 1000);
        }

        if ($time >= 60000)
        {
            $formatter = 2;
            $ret = ($time / 1000) / 60;
        }

        $ret = number_format($ret, 3, '.', '') . ' ' . $formats[$formatter];
        return $ret;
    }

    public function display($db = '', $master_db = '')
    {
        $this->db = $db;
        $this->master_db = $master_db;
        $this->gatherConsoleData();
        $this->gatherFileData();
        $this->gatherMemoryData();
        $this->gatherQueryData();
        $this->gatherSpeedData();

        ob_start();
        profiler_helper_display($this->output, $this->config);
        $buffer = ob_get_contents();
        @ob_end_clean();
        return $buffer;
    }
}

class MySqlDatabase
{

    private $host;
    private $user;
    private $password;
    private $database;
    public $queryCount = 0;
    public $queries = array();
    public $conn;

    function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    function connect($new = false)
    {
        $this->conn = mysql_connect($this->host, $this->user, $this->password, $new);
        if (!$this->conn)
        {
            throw new Exception('We\'re working on a few connection issues.');
        }
    }

    function changeDatabase($database)
    {
        $this->database = $database;
        if ($this->conn)
        {
            if (!mysql_select_db($database, $this->conn))
            {
                throw new CustomException('We\'re working on a few connection issues.');
            }
        }
    }

    function lazyLoadConnection()
    {
        $this->connect(true);
        if ($this->database)
        {
            $this->changeDatabase($this->database);
        }
    }

    function query($sql)
    {
        if (!$this->conn)
        {
            $this->lazyLoadConnection();
        }

        $start = $this->getTime();
        $rs = mysql_query($sql, $this->conn);
        $this->queryCount += 1;
        $this->logQuery($sql, $start);

        if (!$rs)
        {
            throw new Exception('Could not execute query.');
        }
        return $rs;
    }

    function logQuery($sql, $start)
    {
        $query = array(
            'sql' => $sql,
            'time' => ($this->getTime() - $start) * 1000
        );
        array_push($this->queries, $query);
    }

    function getTime()
    {
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $start = $time;
        return $start;
    }

    public function getReadableTime($time)
    {
        $ret = $time;
        $formatter = 0;
        $formats = array('ms', 's', 'm');

        if ($time >= 1000 && $time < 60000)
        {
            $formatter = 1;
            $ret = ($time / 1000);
        }

        if ($time >= 60000)
        {
            $formatter = 2;
            $ret = ($time / 1000) / 60;
        }

        $ret = number_format($ret, 3, '.', '') . ' ' . $formats[$formatter];
        return $ret;
    }

    function __destruct()
    {
        @mysql_close($this->conn);
    }
}

$GLOBALS['debugger_logs'] = array();
$GLOBALS['debugger_logs']['logCount'] = 0;
$GLOBALS['debugger_logs']['memoryCount'] = 0;
$GLOBALS['debugger_logs']['errorCount'] = 0;
$GLOBALS['debugger_logs']['speedCount'] = 0;

class Console
{
    public static function log($data)
    {
        $logItem = array(
            "data" => $data,
            "type" => 'log'
        );
        $GLOBALS['debugger_logs']['console'][] = $logItem;
        $GLOBALS['debugger_logs']['logCount'] += 1;
    }

    public function logMemory($object = false, $name = 'PHP')
    {
        $memory = memory_get_usage();
        if ($object)
        {
            $memory = strlen(serialize($object));
        }
        $logItem = array(
            "data" => $memory,
            "type" => 'memory',
            "name" => $name,
            "dataType" => gettype($object)
        );
        $GLOBALS['debugger_logs']['console'][] = $logItem;
        $GLOBALS['debugger_logs']['memoryCount'] += 1;
    }

    public function logError($exception, $message)
    {
        $logItem = array(
            "data" => $message,
            "type" => 'error',
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        );
        $GLOBALS['debugger_logs']['console'][] = $logItem;
        $GLOBALS['debugger_logs']['errorCount'] += 1;
    }

    public function logSpeed($name = 'Point in Time')
    {
        $logItem = array(
            "data" => Profiler::getMicroTime(),
            "type" => 'speed',
            "name" => $name
        );
        $GLOBALS['debugger_logs']['console'][] = $logItem;
        $GLOBALS['debugger_logs']['speedCount'] += 1;
    }

    public function getLogs()
    {
        return $GLOBALS['debugger_logs'];
    }
}

function profiler_helper_display($output, $config)
{
    $ci = & get_instance();

    $media_url = $ci->config->item('media_url');

    $logCount = isset($output['logs']['console'])
        ? count($output['logs']['console'])
        : 0;
    $fileCount = count($output['files']);
    $memoryUsed = $output['memoryTotals']['used'];
    $queryCount = $output['queryTotals']['count'];
    $speedTotal = $output['speedTotals']['total'];

    $html = '<script type="text/javascript">var media_url = "' . $media_url . '";</script>' . "\n";
    $html .= '<script type="text/javascript" src="' . $media_url . 'profiler/js/profiler.js"></script>' . "\n";
    $html .= '<div id="profiler-container" class="profiler_helper hideDetails" style="display:none">' . "\n";
    $html .= '<div id="profiler_helper" class="console">' . "\n";
    $html .= '<table id="profiler-metrics" cellspacing="0">' . "\n";
    $html .= '<tr>' . "\n";
    $html .= '	<td class="green" onclick="changeTab(\'console\');">' . "\n";
    $html .= '		<var>' . $logCount . '</var>' . "\n";
    $html .= '		<h4>Console</h4>' . "\n";
    $html .= '	</td>' . "\n";
    $html .= '	<td class="blue" onclick="changeTab(\'speed\');">' . "\n";
    $html .= '		<var>' . $speedTotal . '</var>' . "\n";
    $html .= '		<h4>Load Time</h4>' . "\n";
    $html .= '	</td>' . "\n";
    $html .= '	<td class="purple" onclick="changeTab(\'queries\');">' . "\n";
    $html .= '		<var>' . $queryCount . ' Queries</var>' . "\n";
    $html .= '		<h4>Database</h4>' . "\n";
    $html .= '	</td>' . "\n";
    $html .= '	<td class="orange" onclick="changeTab(\'memory\');">' . "\n";
    $html .= '		<var>' . $memoryUsed . '</var>' . "\n";
    $html .= '		<h4>Memory Used</h4>' . "\n";
    $html .= '	</td>' . "\n";
    $html .= '	<td class="red" onclick="changeTab(\'files\');">' . "\n";
    $html .= '		<var>' . $fileCount . ' Files</var>' . "\n";
    $html .= '		<h4>Included</h4>' . "\n";
    $html .= '	</td>' . "\n";
    $html .= '</tr>' . "\n";
    $html .= '</table>' . "\n";
    $html .= '<div id="profiler-console" class="profiler-box">' . "\n";

    if ($logCount == 0)
    {
        $html .= '<h3>This panel has no log items.</h3>' . "\n";
    }
    else
    {
        $html .= '<table class="side" cellspacing="0"><tr><td class="alt1"><var>' . $output['logs']['logCount'] . '</var><h4>Logs</h4></td><td class="alt2"><var>' . $output['logs']['errorCount'] . '</var> <h4>Errors</h4></td></tr><tr><td class="alt3"><var>' . $output['logs']['memoryCount'] . '</var> <h4>Memory</h4></td><td class="alt4"><var>' . $output['logs']['speedCount'] . '</var> <h4>Speed</h4></td></tr></table><table class="main" cellspacing="0">' . "\n";

        $class = '';
        foreach ($output['logs']['console'] as $log)
        {
            $html .= '<tr class="log-' . $log['type'] . '"><td class="type">' . $log['type'] . '</td><td class="' . $class . '">' . "\n";
            if ($log['type'] == 'log')
            {
                $html .= '<div><pre>' . $log['data'] . '</pre></div>' . "\n";
            }
            elseif ($log['type'] == 'memory')
            {
                $html .= '<div><pre>' . $log['data'] . '</pre> <em>' . $log['dataType'] . '</em>: ' . $log['name'] . ' </div>' . "\n";
            }
            elseif ($log['type'] == 'speed')
            {
                $html .= '<div><pre>' . $log['data'] . '</pre> <em>' . $log['name'] . '</em></div>' . "\n";
            }
            elseif ($log['type'] == 'error')
            {
                $html .= '<div><em>Line ' . $log['line'] . '</em> : ' . $log['data'] . ' <pre>' . $log['file'] . '</pre></div>' . "\n";
            }

            $html .= '</td></tr>' . "\n";
            if ($class == '')
            {
                $class = 'alt';
            }
            else
            {
                $class = '';
            }
        }
        $html .= '</table>' . "\n";
    }

    $html .= '</div>' . "\n";
    $html .= '<div id="profiler-speed" class="profiler-box">' . "\n";

    if ($output['logs']['speedCount'] == 0)
    {
        $html .= '<h3>This panel has no log items.</h3>' . "\n";
    }
    else
    {
        $html .= '<table class="side" cellspacing="0"><tr><td><var>' . $output['speedTotals']['total'] . '</var><h4>Load Time</h4></td></tr><tr><td class="alt"><var>' . $output['speedTotals']['allowed'] . '</var> <h4>Max Execution Time</h4></td></tr></table><table class="main" cellspacing="0">' . "\n";

        $class = '';
        foreach ($output['logs']['console'] as $log)
        {
            if ($log['type'] == 'speed')
            {
                $html .= '<tr class="log-' . $log['type'] . '"><td class="' . $class . '"><div><pre>' . $log['data'] . '</pre> <em>' . $log['name'] . '</em></div></td></tr>' . "\n";
                if ($class == '')
                {
                    $class = 'alt';
                }
                else
                {
                    $class = '';
                }
            }
        }
        $html .= '</table>' . "\n";
    }

    $html .= '</div>' . "\n";
    $html .= '<div id="profiler-queries" class="profiler-box">' . "\n";

    if ($output['queryTotals']['count'] == 0)
    {
        $html .= '<h3>This panel has no log items.</h3>' . "\n";
    }
    else
    {
        $html .= '<table class="side" cellspacing="0"><tr><td><var>' . $output['queryTotals']['count'] . '</var><h4>Total Queries</h4></td></tr><tr><td class="alt"><var>' . $output['queryTotals']['time'] . '</var> <h4>Total Time</h4></td></tr><tr><td><var>0</var> <h4>Duplicates</h4></td></tr></table><table class="main" cellspacing="0">' . "\n";

        $class = '';
        foreach ($output['queries'] as $query)
        {
            $html .= '<tr><td class="' . $class . '">' . $query['sql'];
            if (isset($query['explain']))
            {
                $html .= '<em>Possible keys: <b>' . $query['explain']['possible_keys'] . '</b> &middot; Key Used: <b>' . $query['explain']['key'] . '</b> &middot; Type: <b>' . $query['explain']['type'] . '</b> &middot; Rows: <b>' . $query['explain']['rows'] . '</b> &middot; Speed: <b>' . $query['time'] . '</b></em>' . "\n";
            }
            $html .= '</td></tr>' . "\n";

            if ($class == '')
            {
                $class = 'alt';
            }
            else
            {
                $class = '';
            }
        }
        $html .= '</table>' . "\n";
    }

    $html .= '</div>' . "\n";

    $html .= '<div id="profiler-memory" class="profiler-box">' . "\n";

    if ($output['logs']['memoryCount'] == 0)
    {
        $html .= '<h3>This panel has no log items.</h3>' . "\n";
    }
    else
    {
        $html .= '<table class="side" cellspacing="0"><tr><td><var>' . $output['memoryTotals']['used'] . '</var><h4>Used Memory</h4></td></tr><tr><td class="alt"><var>' . $output['memoryTotals']['total'] . '</var> <h4>Total Available</h4></td></tr></table><table class="main" cellspacing="0">' . "\n";

        $class = '';
        foreach ($output['logs']['console'] as $log)
        {
            if ($log['type'] == 'memory')
            {
                $html .= '<tr class="log-' . $log['type'] . '"><td class="' . $class . '"><b>' . $log['data'] . '</b> <em>' . $log['dataType'] . '</em>: ' . $log['name'] . '</td></tr>' . "\n";
                if ($class == '')
                {
                    $class = 'alt';
                }
                else
                {
                    $class = '';
                }
            }
        }
        $html .= '</table>' . "\n";
    }

    $html .= '</div>' . "\n";
    $html .= '<div id="profiler-files" class="profiler-box">' . "\n";

    if ($output['fileTotals']['count'] == 0)
    {
        $html .= '<h3>This panel has no log items.</h3>' . "\n";
    }
    else
    {
        $html .= '<table class="side" cellspacing="0"><tr><td><var>' . $output['fileTotals']['count'] . '</var><h4>Total Files</h4></td></tr><tr><td class="alt"><var>' . $output['fileTotals']['size'] . '</var> <h4>Total Size</h4></td></tr><tr><td><var>' . $output['fileTotals']['largest'] . '</var> <h4>Largest</h4></td></tr></table><table class="main" cellspacing="0">' . "\n";

        $class = '';
        foreach ($output['files'] as $file)
        {
            $html .= '<tr><td class="' . $class . '"><b>' . $file['size'] . '</b> ' . $file['name'] . '</td></tr>' . "\n";
            if ($class == '')
            {
                $class = 'alt';
            }
            else
            {
                $class = '';
            }
        }
        $html .= '</table>';
    }

    $html .= '</div>' . "\n";
    $html .= '<table id="profiler-footer" cellspacing="0"><tr><td class="credit"><a href="http://www.manifestinteractive.com" target="_blank">Manifest Interactive, LLC</a></td><td class="actions"><a href="#" onclick="toggleDetails();return false">Details</a><a class="heightToggle" href="#" onclick="toggleHeight();return false">Height</a></td></tr></table>' . "\n";
    $html .= '</div></div>' . "\n";

    echo $html;
}

function profiler_helper_static($method)
{
    return call_user_func(array('Profiler_helper', $method));
}

/* End of file profiler_helper.php */
/* Location: ./application/helpers/profiler_helper.php */