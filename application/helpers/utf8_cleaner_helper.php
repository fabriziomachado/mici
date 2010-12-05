<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Helpers
 * @category UTF8_Cleaner
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */
/**
 * Begin Document
 */
if (!function_exists('utf8_cleaner'))
{
    /**
     * UFT8 Cleaner
     * 
     * Clean a string to store special characers correctly in the database
     * 
     * @param string $string String of text to be cleaned
     * @param bool $encode_tags Boolean as to whether or not HTML tags should be encoded
     * @return string
     */
    function utf8_cleaner($string, $encode_tags=FALSE)
    {
        // check that this posted variable is a string first
        if(is_string($string))
        {
            $result = '';
            for ($i = 0; $i < strlen($string); $i++)
            {
                $char = $string[$i];
                $ascii = ord($char);

                // one-byte character
                if ($ascii < 128)
                {
                    $result .= ($encode_tags)
                        ? htmlentities($char)
                        : $char;
                }
                // non-utf8 character or not a start byte
                else if ($ascii < 192)
                {

                }
                // two-byte character
                else if ($ascii < 224)
                {
                    $result .= htmlentities(substr($string, $i, 2), ENT_QUOTES, 'UTF-8');
                    $i++;
                }
                // three-byte character
                else if ($ascii < 240)
                {
                    $ascii1 = ord($string[$i+1]);
                    $ascii2 = ord($string[$i+2]);
                    $unicode = (15 & $ascii) * 4096 + (63 & $ascii1) * 64 + (63 & $ascii2);
                    $result .= "&#$unicode;";
                    $i += 2;
                }
                // four-byte character
                else if ($ascii < 248)
                {
                    $ascii1 = ord($string[$i+1]);
                    $ascii2 = ord($string[$i+2]);
                    $ascii3 = ord($string[$i+3]);
                    $unicode = (15 & $ascii) * 262144 + (63 & $ascii1) * 4096 + (63 & $ascii2) * 64 + (63 & $ascii3);
                    $result .= "&#$unicode;";
                    $i += 3;
                }
            }
            return $result;
        }
        // not a string, so return original $string
        else
        {
            return $string;
        }
    }
}

/* End of file utf8_cleaner_helper.php */
/* Location: ./application/helpers/utf8_cleaner_helper.php */