<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Helpers
 * @category GeoLocation
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */
/**
 * Begin Document
 */
if (!function_exists('bsize'))
{
    function bsize($s)
    {
        foreach (array('', 'K', 'M', 'G') as $i => $k)
        {
            if ($s < 1024)
            {
                break;
            }
            $s/=1024;
        }
        return round($s, 2) . " {$k}B";
    }
}

if (!function_exists('duration'))
{
    function duration($ts)
    {
        $time = time();
        $years = (int) ((($time - $ts) / (7 * 86400)) / 52.177457);
        $rem = (int) (($time - $ts) - ($years * 52.177457 * 7 * 86400));
        $weeks = (int) (($rem) / (7 * 86400));
        $days = (int) (($rem) / 86400) - $weeks * 7;
        $hours = (int) (($rem) / 3600) - $days * 24 - $weeks * 7 * 24;
        $mins = (int) (($rem) / 60) - $hours * 60 - $days * 24 * 60 - $weeks * 7 * 24 * 60;
        $str = '';

        if ($years == 1)
        {
            $str .= "$years year, ";
        }
        if ($years > 1)
        {
            $str .= "$years years, ";
        }
        if ($weeks == 1)
        {
            $str .= "$weeks week, ";
        }
        if ($weeks > 1)
        {
            $str .= "$weeks weeks, ";
        }
        if ($days == 1)
        {
            $str .= "$days day,";
        }
        if ($days > 1)
        {
            $str .= "$days days,";
        }
        if ($hours == 1)
        {
            $str .= " $hours hour and";
        }
        if ($hours > 1)
        {
            $str .= " $hours hours and";
        }
        if ($mins == 1)
        {
            $str .= " 1 minute";
        }
        else
        {
            $str .= " $mins minutes";
        }
        return $str;
    }
}

if (!function_exists('time_diff'))
{
    function time_diff($start, $end)
    {
        $years = (int) ((($start - $end) / (7 * 86400)) / 52.177457);
        $rem = (int) (($start - $end) - ($years * 52.177457 * 7 * 86400));
        $weeks = (int) (($rem) / (7 * 86400));
        $days = (int) (($rem) / 86400) - $weeks * 7;
        $hours = (int) (($rem) / 3600) - $days * 24 - $weeks * 7 * 24;
        $mins = (int) (($rem) / 60) - $hours * 60 - $days * 24 * 60 - $weeks * 7 * 24 * 60;
        $str = '';

        if ($years == 1)
        {
            $str .= "$years year, ";
        }
        if ($years > 1)
        {
            $str .= "$years years, ";
        }
        if ($weeks == 1)
        {
            $str .= "$weeks week, ";
        }
        if ($weeks > 1)
        {
            $str .= "$weeks weeks, ";
        }
        if ($days == 1)
        {
            $str .= "$days day,";
        }
        if ($days > 1)
        {
            $str .= "$days days,";
        }
        if ($hours == 1)
        {
            $str .= " $hours hour";
        }
        if ($hours > 1)
        {
            $str .= " $hours hours";
        }
        if ($mins == 1)
        {
            $str .= " 1 minute";
        }
        else
        {
            if ($mins > 0)
            {
                $str .= " $mins minutes";
            }
        }

        if (($start - $end) < 3600)
        {
            $str .= " and " . (($start - $end) - ($mins * 60)) . " seconds";
        }

        return $str;
    }
}

/* End of file conversion_helper.php */
/* Location: ./application/helpers/conversion_helper.php */