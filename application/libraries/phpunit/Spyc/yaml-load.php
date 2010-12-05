<?PHP

/**
 * @package Application
 * @subpackage Libraries
 * @category PHPUnit
 */

/**
 * Begin Document
 */

include('spyc.php');

$array = Spyc::YAMLLoad('spyc.yaml');

echo '<pre><a href="spyc.yaml">spyc.yaml</a> loaded into PHP:<br/>';
print_r($array);
echo '</pre>';


/* End of file yaml-load.php */
/* Location: ./application/libraries/phpunit/Spyc/yaml-load.php */