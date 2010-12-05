<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Config
 * @category Floodblocker
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

/**
 * Load INI File
 * 
 * Fetch Configuration for Framework INI File
 */
$framework_ini = (array) unserialize(FRAMEWORK_INI);

/**
 * Flood Blocker Log Path
 * 
 * Private writeable directory outside of web root where logs can be written
 */
$config['floodblocker_logs_path'] = $framework_ini['floodblocker']['logs_path'];

/**
 * Flood Blocker CRON File
 * 
 * Name of CRON file where timestamp for last CRON run will be stored
 */
$config['floodblocker_cron_file'] = '.time';

/**
 * Flood Blocker CRON Interval
 * 
 * How often CRON Job should look for old IP Addresses (in seconds).
 */
$config['floodblocker_cron_interval'] = 1800;

/**
 * Flood Blocker Logs Timeout
 * 
 * How long an IP Addresses has to be inactive before CRON Job should remove (in seconds).
 */
$config['floodblocker_logs_timeout'] = 3600;

/**
 * Flood Blocker Rules
 *
 * The idea here is that you are focusing on real users, not some script kiddie scraping
 * content from your website.  So with that in mind, figure out how long your websites
 * average load time would be on a fast internet connection, and how long it would take
 * for a user to realistically navigate from page to page.  Also keep in mind that you
 * may have some pagination where users are actually just clicking next to skip through
 * your sites content, so they may in fact be able to navigate more quickly than someone
 * reading normal page content.  Currently the fasted web pages load in about .5 seconds. A
 * normal page load with content and graphics may take up to two seconds.  The response time
 * of the user will vary, but you can assume that it will take them no less than .5 seconds
 * to click a link.  This means the fastest a human will realistically be able to load a page
 * from your site and navigate to the next page on your site will be no less than one second.
 * But thats a VERY conservative estimate.  More than likely the time between page loads will
 * be closer to 4-5 seconds at the users fastest.  But for now, we'll just go ahead and use
 * 2 seconds.  So lets right a rule for abuse if a user accesses the site more than they
 * could during a 30 second period...
 *
 * 	Rule 1:  15 Requests in 30 Seconds = Hacker
 *
 * Just having that one rule will work well enough to protect against most attackers.
 * But some may be more clever and just want to get your content so badly that they
 * scrape it over longer periods. So they write code to get one page every 5 seconds
 * over the course of 15 minutes.  Now most real users are not going to be clicking
 * a new link on your website every 5 seconds for 15 minutes straight. But if a script
 * did this with only rule one in place, it would never trip our flood protection. So
 * lets add another rule to protect against this kind of attack.
 *
 * 	Rule 2:  120 Requests in 600 Seconds = Hacker
 *
 * The nice thing about these rules as that they ALL apply.  Meaning that we are keeping track
 * of page requests per rule. So while they may not violate one, they may violate another.
 * The pages will only load if NO rules have been violated. Otherwise it triggers an error page.
 *
 * After a bit of research and live site tests, we came up with some pretty solid rules.
 * We kept in mind the quick users and the those spending some time on the site.  Noting
 * that the longer a user is on the site, the less frequently they click.
 *
 * Below is how we have configured our rules, which have been tested on some live websites.
 * You may, of course, need to tweak these to better suite your needs.
 *
 * 	KEY => VALUE:  SECONDS => REQUESTS:  15 => 30
 *
 * 	Rule 1 - maximum 15 requests in 30 seconds 	(   30 * .50 = 15  )
 * 	Rule 2 - maximum 27 requests in 1 minute 	(   60 * .45 = 27  )
 * 	Rule 3 - maximum 120 requests in 5 minutes 	(  300 * .40 = 120 )
 * 	Rule 4 - maximum 315 requests in 15 minutes (  900 * .35 = 315 )
 * 	Rule 5 - maximum 540 requests in 30 minutes ( 1800 * .30 = 540 )
 * 	Rule 6 - maximum 675 requests in 45 minutes ( 2700 * .25 = 675 )
 * 	Rule 7 - maximum 720 requests in 1 hour 	( 3600 * .20 = 720 )
 *
 * 	$config['floodblocker_rules'] = array(
 * 		30 => 15,
 * 		60 => 27,
 * 		300 => 120,
 * 		900 => 315,
 * 		1800 => 540,
 * 		2700 => 675,
 * 		3600 => 720
 * 	);
 */
$config['floodblocker_rules'] = array(
    30 => 500,
    60 => 1000,
    300 => 1500,
    900 => 2000,
    1800 => 2500,
    2700 => 3000,
    3600 => 3500
);

/**
 * Flood Blocker White List
 *
 * Known Search Bot Lists can be found here:
 *
 * @link http://www.iplists.com
 */
$config['floodblocker_whitelist'] = (isset($framework_ini['floodblocker']['whitelist']))
        ? $framework_ini['floodblocker']['whitelist']
        : array();

/* End of file floodblocker.php */
/* Location: ./application/config/floodblocker.php */