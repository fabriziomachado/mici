<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Models
 * @category Maintenance_mode
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class Maintenance_mode extends MI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function status()
    {
        try
        {
            $q = Doctrine_Query::create()
                    ->select('*')
                    ->from('Maintenance m');

            $website = $q->fetchOne(array(), Doctrine_Core::HYDRATE_RECORD);

            if ($website->mode == 'enabled')
            {
                // get expired time
                $now = strtotime(date('Y-m-d H:i:s'));
                $expires = ($website->end_datetime)
                        ? strtotime($website->end_datetime)
                        : $now;

                // the maintenance mode is expired now, so lets correct it
                if ($expires <= $now && $website->recurring != 'yes')
                {
                    $this->disable();
                    return FALSE;
                }
            }

            return $website;
        }
        catch (Doctrine_Connection_Exception $e)
        {
            // log error message
            log_message('error', $e->getMessage());

            // redirect since there does not appear to be a database installed
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '.$this->base_url.'system/check');
            exit();
        }
    }

    function enable($message, $start_datetime, $end_datetime, $recurring)
    {
        try
        {
            Doctrine_Query::create()
                ->update('Maintenance')
                ->set('mode', '?', 'enabled')
                ->set('message', '?', $message)
                ->set('start_datetime', '?', $start_datetime)
                ->set('end_datetime', '?', $end_datetime)
                ->set('recurring', '?', $recurring)
                ->execute();
        }
        catch (Doctrine_Connection_Exception $e)
        {
            show_error($e->getMessage());
        }
    }

    function disable()
    {
        try
        {
            Doctrine_Query::create()
                ->update('Maintenance')
                ->set('mode', '?', 'disabled')
                ->set('start_datetime', 'null')
                ->set('end_datetime', 'null')
                ->set('recurring', 'null')
                ->execute();
        }
        catch (Doctrine_Connection_Exception $e)
        {
            show_error($e->getMessage());
        }
    }
}

/* End of file maintenance_mode.php */
/* Location: ./application/models/codeigniter/maintenance_mode.php */