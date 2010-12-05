<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Helper
 * @category directory_details
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
if (!function_exists('directory_details'))
{
    function directory_details($path, $ext)
    {
        $data = array();
        if ($handle = opendir($path))
        {
            while (false !== ($file = readdir($handle)))
            {
                if (substr(strrchr($file, '.'), 1) == $ext)
                {
                    $lines = count(file($path . $file));
                    $size = bsize(filesize($path . $file));
                    $modified = date('l, F jS, Y - g:i A T', filemtime($path . $file));
                    $data[] = array(
                        'name' => $file,
                        'lines' => $lines,
                        'size' => $size,
                        'modified' => $modified,
                        'full_path' => $path . $file
                    );
                }
            }
            closedir($handle);
        }

        return $data;
    }
}

if (!function_exists('file_details'))
{
    function file_details($file)
    {
        $lines = count(file($file));
        $size = bsize(filesize($file));
        $modified = date('l, F jS, Y - g:i A T', filemtime($file));
        $data = array(
            'name' => basename($file),
            'lines' => $lines,
            'size' => $size,
            'modified' => $modified
        );
        return $data;
    }
}

/* End of file directory_details_helper.php */
/* Location: ./application/helpers/directory_details_helper.php */