<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Helpers
 * @category FetchDevice
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
if (!function_exists('fetch_device'))
{
    function fetch_device($mobile, $device)
    {
        if ($mobile)
        {
            switch ($device)
            {
                case 'Apple iPad':
                case 'Apple iPhone':
                case 'Apple iPod Touch':
                case 'Apple iPhone Simulator':
                    $device = 'ios';
                    break;

                case 'Nexus One Android':
                case 'Pre 1.5 Android':
                case '1.5+ Android':
                    $device = 'android';
                    break;

                default:
                    $device = 'browser';
                    break;
            }
        }
        else
        {
            $device = 'browser';
        }
        return $device;
    }
}

/* End of file fetch_device_helper.php */
/* Location: ./application/helpers/fetch_device_helper.php */