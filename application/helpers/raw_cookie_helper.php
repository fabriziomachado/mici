<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Helpers
 * @category RawCookie
 * @author Mike Creighton
 */

/**
 * Begin Document
 */

/**
 * Set raw cookie
 *
 * Accepts six parameter, or you can submit an associative
 * array in the first parameter containing all the values.
 *
 * @access	public
 * @param	mixed
 * @param	string	the value of the cookie
 * @param	string	the number of seconds until expiration
 * @param	string	the cookie domain.  Usually:  .yourdomain.com
 * @param	string	the cookie path
 * @param	string	the cookie prefix
 * @return	void
 */
if (!function_exists('set_raw_cookie'))
{
    function set_raw_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '')
    {
        $CI = & get_instance();

        if (is_array($name))
        {
            foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'name') as $item)
            {
                if (isset($name[$item]))
                {
                    $$item = $name[$item];
                }
            }
        }

        if ($prefix == '' AND $CI->config->item('cookie_prefix') != '')
        {
            $prefix = $CI->config->item('cookie_prefix');
        }
        if ($domain == '' AND $CI->config->item('cookie_domain') != '')
        {
            $domain = $CI->config->item('cookie_domain');
        }
        if ($path == '/' AND $CI->config->item('cookie_path') != '/')
        {
            $path = $CI->config->item('cookie_path');
        }

        if (!is_numeric($expire))
        {
            $expire = time() - 86500;
        }
        else
        {
            if ($expire > 0)
            {
                $expire = time() + $expire;
            }
            else
            {
                $expire = 0;
            }
        }

        setrawcookie($prefix . $name, $value, $expire, $path, $domain, FALSE);
    }
}

/* End of file raw_cookie_helper.php */
/* Location: ./application/helpers/raw_cookie_helper.php */