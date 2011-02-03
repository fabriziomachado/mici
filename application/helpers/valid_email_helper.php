<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Helpers
 * @category ValidEmail
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */
/**
 * Begin Document
 */
if (!function_exists('valid_email'))
{

    function valid_email($email)
    {
        $is_valid = true;
        $at_index = strrpos($email, "@");
        if (is_bool($at_index) && !$at_index)
        {
            $is_valid = false;
        }
        else
        {
            $domain = substr($email, $at_index + 1);
            $local = substr($email, 0, $at_index);
            $local_len = strlen($local);
            $domain_len = strlen($domain);
            if ($local_len < 1 || $local_len > 64)
            {
                $is_valid = false;
            }
            else if ($domain_len < 1 || $domain_len > 255)
            {
                $is_valid = false;
            }
            else if ($local[0] == '.' || $local[$local_len - 1] == '.')
            {
                $is_valid = false;
            }
            else if (preg_match('/\\.\\./', $local))
            {
                $is_valid = false;
            }
            else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
            {
                $is_valid = false;
            }
            else if (preg_match('/\\.\\./', $domain))
            {
                $is_valid = false;
            }
            else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local)))
            {
                if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local)))
                {
                    $is_valid = false;
                }
            }
            if ($is_valid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A")))
            {
                $is_valid = false;
            }
        }
        return $is_valid;
    }
}

/* End of file valid_email_helper.php */
/* Location: ./application/helpers/valid_email_helper.php */