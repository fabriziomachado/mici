<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Helpers
 * @category ReferrerCheck
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
if (!function_exists('referrer_check'))
{
    function referrer_check($allowed, $referrer)
    {
        if (strstr($referrer, $allowed))
        {
            return TRUE;
        }

        return FALSE;
    }
}

/* End of file referrer_check_helper.php */
/* Location: ./application/helpers/referrer_check_helper.php */