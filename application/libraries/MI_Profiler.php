<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Library
 * @category MI_Profiler
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class MI_Profiler extends CI_Profiler
{
    /**
     * Overriding the default "Run the Profiler"
     *
     * @access	private
     * @return	string
     */
    function run()
    {
        $output = '';

        if (class_exists('Profiler_helper') && isset(Profiler_helper::$profiler_helper_instance))
        {
            Profiler_helper::profiler_helper_benchmark_results();
            $output .= Profiler_helper::$profiler_helper_instance->display(
                Profiler_helper::profiler_helper_gen_db_results(
                    Doctrine_Profiler_Hooks::$profiler->profile
                )
            );
        }

        return $output;
    }

}

/* End of file MI_Profiler.php */
/* Location: ./aplication/libraries/MI_Profiler.php */