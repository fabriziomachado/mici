<?PHP if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category PHPUnit
 */

/**
 * Begin Document
 */
 
class Fixture
{
    function __construct()
    {
        if ( !defined('CIUnit_Version') )
        {
            exit('can\'t load fixture library class when not in test mode!');
        }
    }

    /**
    * loads fixture data $fixt into corresponding table
    */
    function load($table, $fixt)
    {
        $this->_assign_db();
        $this->CI->db->simple_query('truncate table ' . $table . ';');

        foreach ( $fixt as $id => $row )
        {
            foreach ($row as $key=>$val)
            {
                if ($val !== '')
                {
                    $row["`$key`"] = $val;
                }
                unset($row[$key]);
            }
            $this->CI->db->insert($table, $row);
        }

        $nbr_of_rows = sizeof($fixt);
        log_message('debug', "Data fixture for db table '{$table}' loaded - {$nbr_of_rows} rows");
    }

    private function _assign_db()
    {
        if ( !isset($this->CI->db) || !isset($this->CI->db->database))
        {
            $this->CI = &get_instance();
            $this->CI->db = $this->CI->config->item('db');
        }

        $len = strlen($this->CI->db->database);

        if ( substr($this->CI->db->database, $len-5, $len) != '_test' )
        {
            die("\nSorry, the name of your test database must end on '_test'.\nThis prevents deleting important data by accident.\n");
        }
    }

}

/* End of file Fixture.php */
/* Location: ./application/libraries/phpunit/Fixture.php */