<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Helpers
 * @category NiceTrim
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */
/**
 * Begin Document
 */
if (!function_exists('nice_trim'))
{
    function nice_trim($string, $words=15, $closure='...')
    {
	$array = preg_split("/[\s]+/", $string, $words+1);
        if(count($array) <= $words)
        {
            return $string;
        }
        else
        {
            $array = array_slice($array, 0, $words);
            return join(' ', $array).$closure;
        }
    }
}

/* End of file nice_trim.php */
/* Location: ./application/helpers/nice_trim.php */