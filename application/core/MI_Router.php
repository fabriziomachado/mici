<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Core
 * @category MI_Router
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class MI_Router extends CI_Router
{
    /*
     * Suffix in controller name
     *
     * @var String
     */

    private $_suffix = "Controller";
    var $error_controller = 'ErrorController';
    var $error_method_404 = 'error_404';

    /*
     * Call the parent constructor
     *
     * @access  public
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        // start tracking URI to make associative array based on router paths
        $this->uri_index = 2;
        $this->uri_array = $this->uri->uri_to_assoc($this->uri_index);
    }

    /**
     * Validates the supplied segments.
     *
     * Attempts to determine the path to the controller.
     *
     * @access   private
     * @param    array
     * @return   array
     */
    function _validate_request($segments)
    {
        // overwrite defailt segments to append suffix
        $org_segments = array_slice($segments, 0);
        $segments[0] = ucfirst($segments[0]) . $this->_suffix;

        // support files in root of controllers folder
        if (file_exists(APPPATH . 'controllers/' . $segments[0] . EXT))
        {
            // update uri info
            $this->uri_index += 1;
            $this->uri_array = $this->uri->uri_to_assoc($this->uri_index);
            return $segments;
        }

        // overwrite default segment
        $segments[0] = $org_segments[0];

        // check if this controller is actually a directory
        if (is_dir(APPPATH . 'controllers/' . $segments[0]))
        {
            // were inside a folder so increase uri_index
            $this->uri_index += 1;

            // set the directory and update segments
            $this->set_directory($segments[0]);
            $segments = array_slice($segments, 1);

            // check to see if this is a subdirectory and update our info
            $uri_index_add = 0;

            // keep track of folder paths so we can trim them later
            $ltrim = '';

            // loop through sub folders
            while (count($segments) > 0 && is_dir(APPPATH . 'controllers/' . $this->directory . $segments[0]))
            {
                // appaned directory for left trim later
                $ltrim .= $this->directory;

                // reset router data
                $this->set_directory($this->directory . $segments[0]);
                $segments = array_slice($segments, 1);

                // were inside another folder so increase uri_index
                $this->uri_index += 1;
            }

            // check to see if there are any methods passed
            if (count($segments) > 0)
            {
                // try to figure out what controller to load, lets try default first
                $default_controller = ucfirst($segments[0]) . $this->_suffix;

                // see if default controller exists
                if (!file_exists(APPPATH . 'controllers/' . $this->fetch_directory() . $default_controller . EXT))
                {
                    // that file did not exist so lets check for folder depth and a controller matching the name of the folder
                    $default_controller = ucfirst(rtrim(ltrim($this->fetch_directory(), $ltrim), '/')) . $this->_suffix;
                    if (!file_exists(APPPATH . 'controllers/' . $this->fetch_directory() . $default_controller . EXT))
                    {
                        // that file did not exist either so lets see if there is a controller with the default name
                        $default_controller = ucfirst($this->default_controller) . $this->_suffix;
                        if (!file_exists(APPPATH . 'controllers/' . $this->fetch_directory() . $default_controller . EXT))
                        {
                            // no controllers found so show 404 error
                            show_404($default_controller);
                        }
                    }

                    // update our router segment
                    array_unshift($segments, $default_controller);
                }
                // it did exist, lets clean up our router segment
                else
                {
                    array_unshift($segments, $default_controller);
                    array_splice($segments, 1, 1);
                    $this->uri_index += 1;
                }
            }
            // otherwise lets set some defaults
            else
            {
                $default_controller = ucfirst(rtrim(ltrim($this->fetch_directory(), $ltrim), '/')) . $this->_suffix;

                // make sure file exists
                if (!file_exists(APPPATH . 'controllers/' . $this->fetch_directory() . $default_controller . EXT))
                {
                    $default_controller = ucfirst($this->default_controller) . $this->_suffix;
                    if (!file_exists(APPPATH . 'controllers/' . $this->fetch_directory() . $default_controller . EXT))
                    {
                        show_404($default_controller);
                    }
                }

                // reset router data
                $segments[0] = $default_controller;

                // reset router data
                $this->default_controller = $default_controller;
                $this->set_class($default_controller);
                $this->set_method('index');
            }

            // set uri_array data for controllers to use
            $this->uri_array = $this->uri->uri_to_assoc($this->uri_index);

            return $segments;
        }

        return $this->error_404();
    }

    function error_404()
    {
        $this->directory = "";
        $segments = array();
        $segments[] = $this->error_controller;
        $segments[] = $this->error_method_404;
        return $segments;
    }

    function show_404()
    {
        include(APPPATH . 'controllers/' . $this->error_controller . EXT);
        call_user_func(array($this->error_controller, $this->error_method_404));
    }
}

/* End of file MI_Router.php */
/* Location: ./application/core/MI_Router.php */