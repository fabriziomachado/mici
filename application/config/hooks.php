<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Config
 * @category Hooks
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

/*
  | -------------------------------------------------------------------------
  | Hooks
  | -------------------------------------------------------------------------
  | This file lets you define "hooks" to extend CI without hacking the core
  | files.  Please see the user guide for info:
  |
  |	http://codeigniter.com/user_guide/general/hooks.html
  |
 */

// This is required to load the Exceptions library early enough
$hook['pre_system'][] = array(
    'function' => 'load_exceptions',
    'filename' => 'errorhandler.php',
    'filepath' => 'hooks',
);

$hook['pre_system'][] = array(
    'class' => NULL,
    'function' => 'profiler_helper_static',
    'filename' => 'profiler_helper.php',
    'filepath' => 'helpers',
    'params' => 'profiler_helper_load'
);

$hook['pre_controller'][] = array(
    'class' => NULL,
    'function' => 'profiler_helper_static',
    'filename' => 'profiler_helper.php',
    'filepath' => 'helpers',
    'params' => 'profiler_helper_pre_controller'
);

$hook['post_controller_constructor'][] = array(
    'class' => NULL,
    'function' => 'profiler_helper_static',
    'filename' => 'profiler_helper.php',
    'filepath' => 'helpers',
    'params' => 'profiler_helper_post_controller_constructor'
);

$hook['post_controller_constructor'][] = array(
    'class' => NULL,
    'function' => 'profiler_helper_static',
    'filename' => 'profiler_helper.php',
    'filepath' => 'helpers',
    'params' => 'profiler_helper_benchmark_results'
);

$hook['post_controller'][] = array(
    'class' => NULL,
    'function' => 'profiler_helper_static',
    'filename' => 'profiler_helper.php',
    'filepath' => 'helpers',
    'params' => 'profiler_helper_post_controller'
);

$hook['post_controller_constructor'][] = array(
    'class' => 'Doctrine_Profiler_Hooks',
    'function' => 'profiler_start',
    'filename' => 'doctrine_profiler_hooks.php',
    'filepath' => 'hooks',
);

$hook['post_controller'][] = array(
    'class' => 'Doctrine_Profiler_Hooks',
    'function' => 'profiler_end',
    'filename' => 'doctrine_profiler_hooks.php',
    'filepath' => 'hooks',
);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */