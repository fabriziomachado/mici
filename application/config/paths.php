<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Config
 * @category Paths
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

/**
 * Load INI File
 *
 * Fetch Configuration for Framework INI File
 */
$framework_ini = (array) unserialize(FRAMEWORK_INI);

/**
 * Media Paths
 *
 * URL and Absolute Path to main media files directory that contains css, images and javascript.
 */
$config['media_url'] = $framework_ini['paths']['media_url'];
$config['media_abs_path'] = $framework_ini['paths']['media_abs_path'];

/**
 * Assets Paths
 *
 * URL and Absolute Path to main assets directory that contains user uploaded content.
 */
$config['assets_url'] = $framework_ini['paths']['assets_url'];
$config['assets_abs_path'] = $framework_ini['paths']['assets_abs_path'];

/* End of file paths.php */
/* Location: ./application/config/paths.php */