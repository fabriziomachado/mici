<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Config
 * @category Database
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

/**
 * Load INI File
 *
 * Fetch Configuration for Framework INI File
 *
 */
$framework_ini = (array) unserialize(FRAMEWORK_INI);

/*
  | -------------------------------------------------------------------
  | DATABASE CONNECTIVITY SETTINGS
  | -------------------------------------------------------------------
  | This file will contain the settings needed to access your database.
  |
  | For complete instructions please consult the 'Database Connection'
  | page of the User Guide.
  |
  | -------------------------------------------------------------------
  | EXPLANATION OF VARIABLES
  | -------------------------------------------------------------------
  |
  |	['hostname'] The hostname of your database server.
  |	['username'] The username used to connect to the database
  |	['password'] The password used to connect to the database
  |	['database'] The name of the database you want to connect to
  |	['dbdriver'] The database type. ie: mysql.  Currently supported: mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
  |	['dbprefix'] You can add an optional prefix, which will be added to the table name when using the  Active Record class
  |	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
  |	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
  |	['cache_on'] TRUE/FALSE - Enables/disables query caching
  |	['cachedir'] The path to the folder where cache files should be stored
  |	['char_set'] The character set used in communicating with the database
  |	['dbcollat'] The character collation used in communicating with the database
  |	['swap_pre'] A default table prefix that should be swapped with the dbprefix
  |	['autoinit'] Whether or not to automatically initialize the database.
  |	['stricton'] TRUE/FALSE - forces "Strict Mode" connections good for ensuring strict SQL while developing
  |
  | The $active_group variable lets you choose which connection group to
  | make active.  By default there is only one group (the 'default' group).
  |
  | The $active_record variables lets you determine whether or not to load
  | the active record class
 */

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = $framework_ini['database']['hostname'];
$db['default']['username'] = $framework_ini['database']['username'];
$db['default']['password'] = $framework_ini['database']['password'];
$db['default']['database'] = $framework_ini['database']['database'];
$db['default']['dbdriver'] = $framework_ini['database']['dbdriver'];
$db['default']['dbprefix'] = $framework_ini['database']['dbprefix'];
$db['default']['pconnect'] = $framework_ini['database']['pconnect'];
$db['default']['db_debug'] = $framework_ini['database']['db_debug'];
$db['default']['cache_on'] = $framework_ini['database']['cache_on'];
$db['default']['cachedir'] = $framework_ini['database']['cachedir'];
$db['default']['char_set'] = $framework_ini['database']['char_set'];
$db['default']['dbcollat'] = $framework_ini['database']['dbcollat'];
$db['default']['swap_pre'] = $framework_ini['database']['swap_pre'];
$db['default']['autoinit'] = $framework_ini['database']['autoinit'];
$db['default']['stricton'] = $framework_ini['database']['stricton'];

/* End of file database.php */
/* Location: ./application/config/database.php */