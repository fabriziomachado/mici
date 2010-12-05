<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Plugins
 * @category GeoLocation
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

/*
  Instructions:

  Load the plugin using:

  $this->load->plugin('geo_location');

  Once loaded you can get user's geo location details by IP address

  $ip = $this->input->ip_address();
  $geo_data = geolocation_by_ip($ip);

  echo "Country code : ".$geo_data['country_name']."\n";
  echo "Country name : ".$geo_data['city']."\n";
  ...


  NOTES:

  The get_geolocation function will use current IP address, if IP param is not given.

  RETURNED DATA

  The get_geolocation() function returns an associative array with this data:

  [array]
  (
  'ip'=>$ip,
  'country_code'=>$result->CountryCode,
  'country_name'=>$result->CountryName,
  'region_name'=>$result->RegionName,
  'city'=>$result->City,
  'zip_postal_code'=>$result->ZipPostalCode,
  'latitude'=>$result->Latitude,
  'longitude'=>$result->Longitude,
  'timezone'=>$result->Timezone,
  'gmtoffset'=>$result->Gmtoffset,
  'dstoffset'=>$result->Dstoffset
  )
 */


/**
 * Get Geo Location by Given/Current IP address
 *
 * @access    public
 * @param    string
 * @return    array
 */
if (!function_exists('get_geolocation'))
{
    function get_geolocation($ip)
    {
        @session_start();

        if (isset($_SESSION['geolocation']))
        {
            return $_SESSION['geolocation'];
        }
        else
        {
            $d = file_get_contents("http://www.ipinfodb.com/ip_query.php?ip=$ip&output=xml");

            //Use backup server if cannot make a connection
            if (!$d)
            {
                $backup = file_get_contents("http://backup.ipinfodb.com/ip_query.php?ip=$ip&output=xml");
                $result = new SimpleXMLElement($backup);
                if (!$backup)
                {
                    // Failed to open connection
                    return FALSE;
                }
            }
            else
            {
                $result = new SimpleXMLElement($d);
            }

            $geo = array(
                'ip' => $ip,
                'country_code' => (string) $result->CountryCode,
                'country_name' => (string) $result->CountryName,
                'region_name' => (string) $result->RegionName,
                'city' => (string) $result->City,
                'zip_postal_code' => (string) $result->ZipPostalCode,
                'latitude' => (float) $result->Latitude,
                'longitude' => (float) $result->Longitude,
                'timezone' => (float) $result->Timezone,
                'gmtoffset' => (float) $result->Gmtoffset,
                'dstoffset' => (float) $result->Dstoffset
            );

            $_SESSION['geolocation'] = $geo;

            //Return the data as an array
            return $geo;
        }
    }
}

/* End of file geolocation_helper.php */
/* Location: ./application/helpers/geolocation_helper.php */