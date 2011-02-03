<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Core
 * @category MI_Exceptions
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class MI_Exceptions
{

    var $action;
    var $severity;
    var $message;
    var $filename;
    var $line;
    var $ob_level;
    var $framework_ini;
    var $log_threshold;
    var $levels = array(
        E_ERROR => 'Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parsing Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Runtime Notice'
    );

    /**
     * Constructor
     *
     */
    function MI_Exceptions()
    {
        $this->ob_level = ob_get_level();

        /**
         * Load INI File
         *
         * Fetch Configuration for Framework INI File
         */
        $this->framework_ini = (array) unserialize(FRAMEWORK_INI);
        $this->log_threshold = $framework_ini['config']['log_threshold'];
    }

    /**
     * Exception Logger
     *
     * This function logs PHP generated error messages
     *
     * @access	private
     * @param	string	the error severity
     * @param	string	the error string
     * @param	string	the error filepath
     * @param	string	the error line number
     * @return	string
     */
    function log_exception($severity, $message, $filepath, $line)
    {
        if($this->log_threshold > 0)
        {
            $severity = (!isset($this->levels[$severity]))
                    ? $severity
                    : $this->levels[$severity];

            if($this->log_threshold == 4)
            {
                log_message('error', 'Severity: ' . $severity . '  --> ' . $message . ' ' . $filepath . ' ' . $line, TRUE);
            }
            else if($severity != 'Notice' && $severity != 'Warning' && $severity != 'User Notice' && $severity != 'User Warning' && $severity != 'Runtime Notice')
            {
                log_message('error', 'Severity: ' . $severity . '  --> ' . $message . ' ' . $filepath . ' ' . $line, TRUE);
            }
        }
    }

    /**
     * 404 Page Not Found Handler
     *
     * @access	private
     * @param	string
     * @return	string
     */
    function show_404($page = '', $log_error = TRUE)
    {
        $heading = "404 Page Not Found";
        $message = "The page you requested was not found.";

        // By default we log this, but allow a dev to skip it
        if ($log_error)
        {
            log_message('error', '404 Page Not Found --> ' . $page);
        }

        echo $this->show_error($heading, $message, 'error_404', 404);
        exit;
    }

    /**
     * General Error Page
     *
     * This function takes an error message as input
     * (either as a string or an array) and displays
     * it using the specified template.
     *
     * @access	private
     * @param	string	the heading
     * @param	string	the message
     * @param	string	the template name
     * @return	string
     */
    function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        set_status_header($status_code);

        $message = '<p>' . implode('</p><p>', (!is_array($message))
                                ? array($message)
                                : $message) . '</p>';

        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }
        ob_start();
        include(APPPATH . 'views/browser/error/404'.EXT);
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }

    /**
     * Native PHP error handler
     *
     * @access	private
     * @param	string	the error severity
     * @param	string	the error string
     * @param	string	the error filepath
     * @param	string	the error line number
     * @return	string
     */
    function show_php_error($severity, $message, $filepath, $line)
    {
        $severity = (!isset($this->levels[$severity]))
                ? $severity
                : $this->levels[$severity];

        $filepath = str_replace("\\", "/", $filepath);

        // For safety reasons we do not show the full file path
        if (FALSE !== strpos($filepath, '/'))
        {
            $x = explode('/', $filepath);
            $filepath = $x[count($x) - 2] . '/' . end($x);
        }

        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }
        ob_start();
        include(APPPATH . 'errors/error_php' . EXT);
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
    }

}

/* End of file MI_Exceptions.php */
/* Location: ./application/core/MI_Exceptions.php */