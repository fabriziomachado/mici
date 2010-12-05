<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Hooks
 * @category Doctrine_Profiler_Hooks
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class Doctrine_Profiler_Hooks
{
    public static $profiler;
    public $profile;

    public function profiler_start()
    {
        self::$profiler = new Doctrine_Connection_Profiler();
        foreach (Doctrine_Manager::getInstance()->getConnections() as $conn)
        {
            $conn->setListener(self::$profiler);
        }
    }

    public function profiler_end()
    {
        $time = 0;
        $events = array();
        foreach (self::$profiler as $event)
        {
            $time += $event->getElapsedSecs();
            if ($event->getName() == 'query' || $event->getName() == 'execute')
            {
                $event_details = array(
                    'type' => $event->getName(),
                    'query' => $event->getQuery(),
                    'time' => sprintf('%f', $event->getElapsedSecs())
                );

                if (count($event->getParams()))
                {
                    $event_details['params'] = $event->getParams();
                }

                $events[] = $event_details;
            }
        }

        self::$profiler->profile = array(
            'total_queries' => count($events),
            'events' => $events,
            'time' => $time,
            'memory' => memory_get_peak_usage()
        );
    }

}

/* End of file doctrine_profiler_hooks.php */
/* Location: ./application/hooks/doctrine_profiler_hooks.php */