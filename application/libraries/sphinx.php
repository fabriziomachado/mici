<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category Sphinx
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class Sphinx
{
    /**
     * Searchd commands
     */
    const SEARCHD_COMMAND_SEARCH = 0;
    const SEARCHD_COMMAND_EXCERPT = 1;
    const SEARCHD_COMMAND_UPDATE = 2;
    const SEARCHD_COMMAND_KEYWORDS = 3;
    const SEARCHD_COMMAND_PERSIST = 4;
    const SEARCHD_COMMAND_STATUS = 5;
    const SEARCHD_COMMAND_QUERY = 6;
    const SEARCHD_COMMAND_FLUSHATTRS = 7;

    /**
     * Current client-side command implementation versions
     */
    const VER_COMMAND_SEARCH = 0x116;
    const VER_COMMAND_EXCERPT = 0x100;
    const VER_COMMAND_UPDATE = 0x102;
    const VER_COMMAND_KEYWORDS = 0x100;
    const VER_COMMAND_STATUS = 0x100;
    const VER_COMMAND_QUERY = 0x100;
    const VER_COMMAND_FLUSHATTRS = 0x100;

    /**
     * Searchd status codes
     */
    const SEARCHD_OK = 0;
    const SEARCHD_ERROR = 1;
    const SEARCHD_RETRY = 2;
    const SEARCHD_WARNING = 3;

    /**
     * Match modes
     */
    const SPH_MATCH_ALL = 0;
    const SPH_MATCH_ANY = 1;
    const SPH_MATCH_PHRASE = 2;
    const SPH_MATCH_BOOLEAN = 3;
    const SPH_MATCH_EXTENDED = 4;
    const SPH_MATCH_FULLSCAN = 5;
    const SPH_MATCH_EXTENDED2 = 6;

    /**
     * Ranking modes (ext2 only)
     */
    const SPH_RANK_PROXIMITY_BM25 = 0;
    const SPH_RANK_BM25 = 1;
    const SPH_RANK_NONE = 2;
    const SPH_RANK_WORDCOUNT = 3;
    const SPH_RANK_PROXIMITY = 4;
    const SPH_RANK_MATCHANY = 5;
    const SPH_RANK_FIELDMASK = 6;
    const SPH_RANK_SPH04 = 7;
    const SPH_RANK_TOTAL = 8;

    /**
     * Sort modes
     */
    const SPH_SORT_RELEVANCE = 0;
    const SPH_SORT_ATTR_DESC = 1;
    const SPH_SORT_ATTR_ASC = 2;
    const SPH_SORT_TIME_SEGMENTS = 3;
    const SPH_SORT_EXTENDED = 4;
    const SPH_SORT_EXPR = 5;

    /**
     * Filter types
     */
    const SPH_FILTER_VALUES = 0;
    const SPH_FILTER_RANGE = 1;
    const SPH_FILTER_FLOATRANGE = 2;

    /**
     * Attribute types
     */
    const SPH_ATTR_INTEGER = 1;
    const SPH_ATTR_TIMESTAMP = 2;
    const SPH_ATTR_ORDINAL = 3;
    const SPH_ATTR_BOOL = 4;
    const SPH_ATTR_FLOAT = 5;
    const SPH_ATTR_BIGINT = 6;
    const SPH_ATTR_STRING = 7;
    const SPH_ATTR_MULTI = 0x40000000;

    /**
     * Grouping functions
     */
    const SPH_GROUPBY_DAY = 0;
    const SPH_GROUPBY_WEEK = 1;
    const SPH_GROUPBY_MONTH = 2;
    const SPH_GROUPBY_YEAR = 3;
    const SPH_GROUPBY_ATTR = 4;
    const SPH_GROUPBY_ATTRPAIR = 5;

    /**
     * searchd host (default is 'localhost')
     */
    private $_host;
    /**
     * searchd port (default is 9312)
     */
    private $_port;
    /**
     * how many records to seek from result-set start (default is 0)
     */
    private $_offset;
    /**
     * how many records to return from result-set starting at offset (default is 20)
     */
    private $_limit;
    /**
     * query matching mode (default is self::SPH_MATCH_ALL)
     */
    private $_mode;
    /**
     * per-field weights (default is 1 for all fields)
     */
    private $_weights;
    /**
     * match sorting mode (default is self::SPH_SORT_RELEVANCE)
     */
    private $_sort;
    /**
     * attribute to sort by (defualt is '')
     */
    private $_sort_by;
    /**
     * min ID to match (default is 0, which means no limit)
     */
    private $_min_id;
    /**
     * max ID to match (default is 0, which means no limit)
     */
    private $_max_id;
    /**
     * search filters
     */
    private $_filters;
    /**
     * group-by attribute name
     */
    private $_group_by;
    /**
     * group-by function (to pre-process group-by attribute value with)
     */
    private $_group_func;
    /**
     * group-by sorting clause (to sort groups in result set with)
     */
    private $_group_sort;
    /**
     * group-by count-distinct attribute
     */
    private $_group_distinct;
    /**
     * max matches to retrieve
     */
    private $_max_matches;
    /**
     * cutoff to stop searching at (default is 0)
     */
    private $_cut_off;
    /**
     * distributed retries count
     */
    private $_retry_count;
    /**
     * distributed retries delay
     */
    private $_retry_delay;
    /**
     * geographical anchor point
     */
    private $_anchor;
    /**
     * per-index weights
     */
    private $_index_weights;
    /**
     * ranking mode (default is self::SPH_RANK_PROXIMITY_BM25)
     */
    private $_ranker;
    /**
     * max query time, milliseconds (default is 0, do not limit)
     */
    private $_max_query_time;
    /**
     * per-field-name weights
     */
    private $_field_weights;
    /**
     * per-query attribute values overrides
     */
    private $_overrides;
    /**
     * select-list (attributes or expressions, with optional aliases)
     */
    private $_select;
    /**
     * last error message
     */
    private $_error;
    /**
     * last warning message
     */
    private $_warning;
    /**
     * connection error vs remote error flag
     */
    private $_connection_error;
    /**
     * requests array for multi-query
     */
    private $_requests;
    /**
     * stored mbstring encoding
     */
    private $_mb_encode;
    /**
     * whether $result['matches'] should be a hash or an array
     */
    private $_array_result;
    /**
     * connect timeout
     */
    private $_timeout;

    /**
     * Constructor
     * 
     * Create a new client object and fill defaults
     */
    function __construct()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        {
            $this->_host = '127.0.0.1';
        }
        else
        {
            $this->_host = 'localhost';
        }

        $this->_port = 9312;
        $this->_path = FALSE;
        $this->_socket = FALSE;
        $this->_offset = 0;
        $this->_limit = 20;
        $this->_mode = self::SPH_MATCH_ALL;
        $this->_weights = array();
        $this->_sort = self::SPH_SORT_RELEVANCE;
        $this->_sort_by = '';
        $this->_min_id = 0;
        $this->_max_id = 0;
        $this->_filters = array();
        $this->_group_by = '';
        $this->_group_func = self::SPH_GROUPBY_DAY;
        $this->_group_sort = '@group desc';
        $this->_group_distinct = '';
        $this->_max_matches = 1000;
        $this->_cut_off = 0;
        $this->_retry_count = 0;
        $this->_retry_delay = 0;
        $this->_anchor = array();
        $this->_index_weights = array();
        $this->_ranker = self::SPH_RANK_PROXIMITY_BM25;
        $this->_max_query_time = 0;
        $this->_field_weights = array();
        $this->_overrides = array();
        $this->_select = '*';
        $this->_error = '';
        $this->_warning = '';
        $this->_connection_error = FALSE;
        $this->_requests = array();
        $this->_mb_encode = '';
        $this->_array_result = FALSE;
        $this->_timeout = 0;
    }

    /**
     * Destructor
     */
    function __destruct()
    {
        if ($this->_socket !== FALSE)
        {
            fclose($this->_socket);
        }
    }

    /**
     * Pack 64-bit signed
     *
     * @access 	private
     * @param  	integer	$v
     * @return 	string
     */
    private function _pack_signed_64bit($v)
    {
        assert(is_numeric($v));

        if (PHP_INT_SIZE >= 8)
        {
            $v = (int) $v;
            return pack('NN', $v >> 32, $v & 0xFFFFFFFF);
        }
        if (is_int($v))
        {
            return pack('NN', $v < 0 ? -1 : 0, $v);
        }
        if (function_exists('bcmul'))
        {
            if (bccomp($v, 0) == -1)
            {
                $v = bcadd('18446744073709551616', $v);
            }
            $h = bcdiv($v, '4294967296', 0);
            $l = bcmod($v, '4294967296');
            return pack('NN', (float) $h, (float) $l);
        }

        $p = max(0, strlen($v) - 13);
        $lo = abs((float) substr($v, $p));
        $hi = abs((float) substr($v, 0, $p));

        $m = $lo + $hi * 1316134912.0;
        $q = floor($m / 4294967296.0);
        $l = $m - ($q * 4294967296.0);
        $h = $hi * 2328.0 + $q;

        if ($v < 0)
        {
            if ($l == 0)
            {
                $h = 4294967296.0 - $h;
            }
            else
            {
                $h = 4294967295.0 - $h;
                $l = 4294967296.0 - $l;
            }
        }
        return pack('NN', $h, $l);
    }

    /**
     * Pack 64-bit unsigned
     *
     * @access 	private
     * @param  	integer	$v
     * @return 	string
     */
    private function _pack_unsigned_64bit($v)
    {
        assert(is_numeric($v));

        if (PHP_INT_SIZE >= 8)
        {
            assert($v >= 0);

            if (is_int($v))
            {
                return pack('NN', $v >> 32, $v & 0xFFFFFFFF);
            }
            if (function_exists('bcmul'))
            {
                $h = bcdiv($v, 4294967296, 0);
                $l = bcmod($v, 4294967296);
                return pack('NN', $h, $l);
            }

            $p = max(0, strlen($v) - 13);
            $lo = (int) substr($v, $p);
            $hi = (int) substr($v, 0, $p);

            $m = $lo + $hi * 1316134912;
            $l = $m % 4294967296;
            $h = $hi * 2328 + (int) ($m / 4294967296);

            return pack('NN', $h, $l);
        }

        if (is_int($v))
        {
            return pack('NN', 0, $v);
        }
        if (function_exists('bcmul'))
        {
            $h = bcdiv($v, '4294967296', 0);
            $l = bcmod($v, '4294967296');
            return pack('NN', (float) $h, (float) $l);
        }

        $p = max(0, strlen($v) - 13);
        $lo = (float) substr($v, $p);
        $hi = (float) substr($v, 0, $p);

        $m = $lo + $hi * 1316134912.0;
        $q = floor($m / 4294967296.0);
        $l = $m - ($q * 4294967296.0);
        $h = $hi * 2328.0 + $q;

        return pack('NN', $h, $l);
    }

    /**
     * Unpack 64-bit unsigned
     *
     * @access 	private
     * @param  	string	$v
     * @return 	integer
     */
    private function _unpack_unsigned_64bit($v)
    {
        list($hi, $lo) = array_values(unpack('N*N*', $v));

        if (PHP_INT_SIZE >= 8)
        {
            if ($hi < 0)
            {
                $hi += ( 1 << 32);
            }
            if ($lo < 0)
            {
                $lo += ( 1 << 32);
            }
            if ($hi <= 2147483647)
            {
                return ($hi << 32) + $lo;
            }
            if (function_exists('bcmul'))
            {
                return bcadd($lo, bcmul($hi, '4294967296'));
            }

            $C = 100000;
            $h = ((int) ($hi / $C) << 32) + (int) ($lo / $C);
            $l = (($hi % $C) << 32) + ($lo % $C);

            if ($l > $C)
            {
                $h += (int) ($l / $C);
                $l = $l % $C;
            }
            if ($h == 0)
            {
                return $l;
            }
            return sprintf('%d%05d', $h, $l);
        }

        if ($hi == 0)
        {
            if ($lo > 0)
            {
                return $lo;
            }
            return sprintf('%u', $lo);
        }

        $hi = sprintf('%u', $hi);
        $lo = sprintf('%u', $lo);

        if (function_exists('bcmul'))
        {
            return bcadd($lo, bcmul($hi, '4294967296'));
        }

        $hi = (float) $hi;
        $lo = (float) $lo;

        $q = floor($hi / 10000000.0);
        $r = $hi - $q * 10000000.0;
        $m = $lo + $r * 4967296.0;
        $mq = floor($m / 10000000.0);
        $l = $m - $mq * 10000000.0;
        $h = $q * 4294967296.0 + $r * 429.0 + $mq;

        $h = sprintf('%.0f', $h);
        $l = sprintf('%07.0f', $l);
        if ($h == '0')
        {
            return sprintf('%.0f', (float) $l);
        }
        return $h . $l;
    }

    /**
     * Unpack 64-bit signed
     *
     * @access 	private
     * @param  	string	$v
     * @return 	integer
     */
    private function _unpack_signed_64bit($v)
    {
        list($hi, $lo) = array_values(unpack('N*N*', $v));

        if (PHP_INT_SIZE >= 8)
        {
            if ($hi < 0)
            {
                $hi += ( 1 << 32);
            }
            if ($lo < 0)
            {
                $lo += ( 1 << 32);
            }
            return ($hi << 32) + $lo;
        }
        if ($hi == 0)
        {
            if ($lo > 0)
            {
                return $lo;
            }
            return sprintf('%u', $lo);
        }
        elseif ($hi == -1)
        {
            if ($lo < 0)
            {
                return $lo;
            }
            return sprintf('%.0f', $lo - 4294967296.0);
        }

        $neg = '';
        $c = 0;
        if ($hi < 0)
        {
            $hi = ~$hi;
            $lo = ~$lo;
            $c = 1;
            $neg = '-';
        }

        $hi = sprintf('%u', $hi);
        $lo = sprintf('%u', $lo);

        if (function_exists('bcmul'))
        {
            return $neg . bcadd(bcadd($lo, bcmul($hi, '4294967296')), $c);
        }

        $hi = (float) $hi;
        $lo = (float) $lo;

        $q = floor($hi / 10000000.0);
        $r = $hi - $q * 10000000.0;
        $m = $lo + $r * 4967296.0;
        $mq = floor($m / 10000000.0);
        $l = $m - $mq * 10000000.0 + $c;
        $h = $q * 4294967296.0 + $r * 429.0 + $mq;
        if ($l == 10000000)
        {
            $l = 0;
            $h += 1;
        }

        $h = sprintf('%.0f', $h);
        $l = sprintf('%07.0f', $l);
        if ($h == '0')
        {
            return $neg . sprintf('%.0f', (float) $l);
        }
        return $neg . $h . $l;
    }

    /**
     * Fix unit
     *
     * @access 	private
     * @param  	integer	$value
     * @return 	integer
     */
    private function _fix_unit($value)
    {
        if (PHP_INT_SIZE >= 8)
        {
            if ($value < 0)
            {
                $value += ( 1 << 32);
            }
            return $value;
        }
        else
        {
            return sprintf('%u', $value);
        }
    }

    /**
     * Send
     *
     * @access 	private
     * @param  	mixed	$handle
     * @param  	mixed	$data
     * @param  	integer	$length
     */
    private function _send($handle, $data, $length)
    {
        if (feof($handle) || fwrite($handle, $data, $length) !== $length)
        {
            $this->_error = 'connection unexpectedly closed (timed out?)';
            $this->_connection_error = TRUE;
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Enter mbstring workaround mode
     *
     * @access 	private
     */
    private function _mb_push()
    {
        $this->_mb_encode = '';
        if (ini_get('mbstring.func_overload') & 2)
        {
            $this->_mb_encode = mb_internal_encoding();
            mb_internal_encoding('latin1');
        }
    }

    /**
     * Leave mbstring workaround mode
     *
     * @access 	private
     */
    private function _mb_pop()
    {
        if ($this->_mb_encode)
        {
            mb_internal_encoding($this->_mb_encode);
        }
    }

    /**
     * Connect to searchd server
     *
     * @access 	private
     * @return 	mixed
     */
    private function _connect()
    {

        if ($this->_socket !== FALSE)
        {
            if (!@feof($this->_socket))
            {
                return $this->_socket;
            }
            $this->_socket = FALSE;
        }

        $errno = 0;
        $errstr = '';
        $this->_connection_error = FALSE;

        if ($this->_path)
        {
            $host = $this->_path;
            $port = 0;
        }
        else
        {
            $host = $this->_host;
            $port = $this->_port;
        }

        if ($this->_timeout <= 0)
        {
            $fp = fsockopen($host, $port, $errno, $errstr);
        }
        else
        {
            $fp = @fsockopen($host, $port, $errno, $errstr, $this->_timeout);
        }

        if (!$fp)
        {
            if ($this->_path)
            {
                $location = $this->_path;
            }
            else
            {
                $location = "{$this->_host}:{$this->_port}";
            }

            $errstr = trim($errstr);
            $this->_error = "connection to $location failed (errno={$errno}, msg={$errstr})";
            $this->_connection_error = TRUE;
            return FALSE;
        }

        if (!$this->_send($fp, pack('N', 1), 4))
        {
            fclose($fp);
            $this->_error = 'failed to send client protocol version';
            return FALSE;
        }

        list(, $v) = unpack('N*', fread($fp, 4));
        $v = (int) $v;
        if ($v < 1)
        {
            fclose($fp);
            $this->_error = "expected searchd protocol version 1+, got version '{$v}'";
            return FALSE;
        }

        return $fp;
    }

    /**
     * Get and check response packet from searchd server
     *
     * @access 	private
     * @param  	mixed	$fp
     * @param  	mixed	$client_ver
     * @return 	mixed
     */
    private function _get_response($fp, $client_ver)
    {
        $response = '';
        $len = 0;

        $header = fread($fp, 8);
        if (strlen($header) == 8)
        {
            list($status, $ver, $len) = array_values(unpack('n2a/Nb', $header));
            $left = $len;
            while ($left > 0 && !feof($fp))
            {
                $chunk = fread($fp, $left);
                if ($chunk)
                {
                    $response .= $chunk;
                    $left -= strlen($chunk);
                }
            }
        }
        if ($this->_socket === FALSE)
        {
            fclose($fp);
        }

        $read = strlen($response);
        if (!$response || $read != $len)
        {
            $this->_error = $len
                    ? "failed to read searchd response (status={$status}, ver={$ver}, len={$len}, read={$read})"
                    : 'received zero-sized searchd response';
            return FALSE;
        }
        if ($status == self::SEARCHD_WARNING)
        {
            list(, $wlen) = unpack('N*', substr($response, 0, 4));
            $this->_warning = substr($response, 4, $wlen);
            return substr($response, 4 + $wlen);
        }
        if ($status == self::SEARCHD_ERROR)
        {
            $this->_error = 'searchd error: ' . substr($response, 4);
            return FALSE;
        }
        if ($status == self::SEARCHD_RETRY)
        {
            $this->_error = 'temporary searchd error: ' . substr($response, 4);
            return FALSE;
        }
        if ($status != self::SEARCHD_OK)
        {
            $this->_error = "unknown status code '{$status}'";
            return FALSE;
        }
        if ($ver < $client_ver)
        {
            $this->_warning = sprintf('searchd command v.%d.%d older than client\'s v.%d.%d, some options might not work',
                            $ver >> 8, $ver & 0xff, $client_ver >> 8, $client_ver & 0xff);
        }

        return $response;
    }

    /**
     * Helper to pack floats in network byte order
     *
     * @access 	private
     * @param  	mixed	$f
     * @return 	mixed
     */
    private function _pack_float($f)
    {
        $t1 = pack('f', $f);
        list(, $t2) = unpack('L*', $t1);
        return pack('N', $t2);
    }

    /**
     * Parse and return search query (or queries) response
     *
     * @access 	private
     * @param  	string	$response
     * @param  	array	$nreqs
     * @return 	mixed
     */
    private function _parse_search_response($response, $nreqs)
    {
        $p = 0;
        $max = strlen($response);

        $results = array();
        for ($ires = 0; $ires < $nreqs && $p < $max; $ires++)
        {
            $results[] = array();
            $result = & $results[$ires];

            $result['error'] = '';
            $result['warning'] = '';

            list(, $status) = unpack('N*', substr($response, $p, 4));
            $p += 4;
            $result['status'] = $status;
            if ($status != self::SEARCHD_OK)
            {
                list(, $len) = unpack('N*', substr($response, $p, 4));
                $p += 4;
                $message = substr($response, $p, $len);
                $p += $len;

                if ($status == self::SEARCHD_WARNING)
                {
                    $result['warning'] = $message;
                }
                else
                {
                    $result['error'] = $message;
                    continue;
                }
            }

            $fields = array();
            $attrs = array();

            list(, $nfields) = unpack('N*', substr($response, $p, 4));
            $p += 4;

            while ($nfields-- > 0 && $p < $max)
            {
                list(, $len) = unpack('N*', substr($response, $p, 4));
                $p += 4;

                $fields[] = substr($response, $p, $len);
                $p += $len;
            }
            $result['fields'] = $fields;

            list(, $nattrs) = unpack('N*', substr($response, $p, 4));
            $p += 4;

            while ($nattrs-- > 0 && $p < $max)
            {
                list(, $len) = unpack('N*', substr($response, $p, 4));
                $p += 4;

                $attr = substr($response, $p, $len);
                $p += $len;

                list(, $type) = unpack('N*', substr($response, $p, 4));
                $p += 4;

                $attrs[$attr] = $type;
            }
            $result['attrs'] = $attrs;

            list(, $count) = unpack('N*', substr($response, $p, 4));
            $p += 4;

            list(, $id64) = unpack('N*', substr($response, $p, 4));
            $p += 4;

            $idx = -1;
            while ($count-- > 0 && $p < $max)
            {
                $idx++;

                if ($id64)
                {
                    $doc = $this->_unpack_unsigned_64bit(substr($response, $p, 8));
                    $p += 8;

                    list(, $weight) = unpack('N*', substr($response, $p, 4));
                    $p += 4;
                }
                else
                {
                    list($doc, $weight) = array_values(unpack('N*N*', substr($response, $p, 8)));
                    $p += 8;

                    $doc = $this->_fix_unit($doc);
                }
                $weight = sprintf('%u', $weight);

                if ($this->_array_result)
                {
                    $result['matches'][$idx] = array(
                        'id' => $doc,
                        'weight' => $weight
                    );
                }
                else
                {
                    $result['matches'][$doc]['weight'] = $weight;
                }

                $attrvals = array();
                foreach ($attrs as $attr => $type)
                {
                    if ($type == self::SPH_ATTR_BIGINT)
                    {
                        $attrvals[$attr] = $this->_unpack_signed_64bit(substr($response, $p, 8));
                        $p += 8;
                        continue;
                    }

                    if ($type == self::SPH_ATTR_FLOAT)
                    {
                        list(, $uval) = unpack('N*', substr($response, $p, 4));
                        $p += 4;

                        list(, $fval) = unpack('f*', pack('L', $uval));
                        $attrvals[$attr] = $fval;
                        continue;
                    }

                    list(, $val) = unpack('N*', substr($response, $p, 4));
                    $p += 4;

                    if ($type & self::SPH_ATTR_MULTI)
                    {
                        $attrvals[$attr] = array();
                        $nvalues = $val;
                        while ($nvalues-- > 0 && $p < $max)
                        {
                            list(, $val) = unpack('N*', substr($response, $p, 4));
                            $p += 4;

                            $attrvals[$attr][] = $this->_fix_unit($val);
                        }
                    }
                    else if ($type == self::SPH_ATTR_STRING)
                    {
                        $attrvals[$attr] = substr($response, $p, $val);
                        $p += $val;
                    }
                    else
                    {
                        $attrvals[$attr] = $this->_fix_unit($val);
                    }
                }

                if ($this->_array_result)
                {
                    $result['matches'][$idx]['attrs'] = $attrvals;
                }
                else
                {
                    $result['matches'][$doc]['attrs'] = $attrvals;
                }
            }

            list($total, $total_found, $msecs, $words) = array_values(unpack('N*N*N*N*', substr($response, $p, 16)));
            $result['total'] = sprintf('%u', $total);
            $result['total_found'] = sprintf('%u', $total_found);
            $result['time'] = sprintf('%.3f', $msecs / 1000);
            $p += 16;

            while ($words-- > 0 && $p < $max)
            {
                list(, $len) = unpack('N*', substr($response, $p, 4));
                $p += 4;

                $word = substr($response, $p, $len);
                $p += $len;
                list($docs, $hits) = array_values(unpack('N*N*', substr($response, $p, 8)));
                $p += 8;

                $result['words'][$word] = array(
                    'docs' => sprintf('%u', $docs),
                    'hits' => sprintf('%u', $hits)
                );
            }
        }

        $this->_mb_pop();
        return $results;
    }

    /**
     * Get last error message
     *
     * @access 	public
     * @return 	string
     */
    public function get_last_error()
    {
        return $this->_error;
    }

    /**
     * Get last warning message
     *
     * @access 	public
     * @return 	string
     */
    public function get_last_warning()
    {
        return $this->_warning;
    }

    /**
     * Get last error flag
     *
     * To tell network connection errors from searchd errors or broken responses
     *
     * @access 	public
     * @return 	string
     */
    public function is_connect_error()
    {
        return $this->_connection_error;
    }

    /**
     * Set searchd host name and port
     *
     * @access 	public
     * @param  	string	$host
     * @param  	integer	$port
     * @return 	mixed
     */
    public function set_server($host, $port = 0)
    {
        assert(is_string($host));
        if ($host[0] == '/')
        {
            $this->_path = 'unix://' . $host;
            return;
        }
        if (substr($host, 0, 7) == 'unix://')
        {
            $this->_path = $host;
            return;
        }

        assert(is_int($port));
        $this->_host = $host;
        $this->_port = $port;
        $this->_path = '';
    }

    /**
     * Set server connection timeout (0 to remove)
     *
     * @access 	public
     * @param  	integer	$timeout
     */
    public function set_connect_timeout($timeout)
    {
        assert(is_numeric($timeout));
        $this->_timeout = $timeout;
    }

    /**
     * Set offset and count into result set
     *
     * Optionally set max-matches and cutoff limits
     *
     * @access 	public
     * @param  	integer	$offset
     * @param  	integer	$limit
     * @param  	integer	$max
     * @param  	integer	$cutoff
     */
    public function set_limits($offset, $limit, $max=0, $cutoff=0)
    {
        assert(is_int($offset));
        assert(is_int($limit));
        assert($offset >= 0);
        assert($limit > 0);
        assert($max >= 0);
        $this->_offset = $offset;
        $this->_limit = $limit;
        if ($max > 0)
        {
            $this->_max_matches = $max;
        }
        if ($cutoff > 0)
        {
            $this->_cut_off = $cutoff;
        }
    }

    /**
     * Set maximum query time
     *
     * In milliseconds, per-index integer, 0 means 'do not limit'
     *
     * @access 	public
     * @param  	integer	$max
     */
    public function set_max_query_time($max)
    {
        assert(is_int($max));
        assert($max >= 0);
        $this->_max_query_time = $max;
    }

    /**
     * Set matching mode
     *
     * @access 	public
     * @param  	integer	$mode
     */
    public function set_match_mode($mode)
    {
        assert($mode == self::SPH_MATCH_ALL
                || $mode == self::SPH_MATCH_ANY
                || $mode == self::SPH_MATCH_PHRASE
                || $mode == self::SPH_MATCH_BOOLEAN
                || $mode == self::SPH_MATCH_EXTENDED
                || $mode == self::SPH_MATCH_FULLSCAN
                || $mode == self::SPH_MATCH_EXTENDED2);
        $this->_mode = $mode;
    }

    /**
     * Set ranking mode
     *
     * @access 	public
     * @param  	integer	$ranker
     */
    public function set_ranking_mode($ranker)
    {
        assert($ranker >= 0 && $ranker < self::SPH_RANK_TOTAL);
        $this->_ranker = $ranker;
    }

    /**
     * Set matches sorting mode
     *
     * @access 	public
     * @param  	integer	$mode
     * @param  	string	$sortby
     */
    public function set_sort_mode($mode, $sortby='')
    {
        assert($mode == self::SPH_SORT_RELEVANCE
                || $mode == self::SPH_SORT_ATTR_DESC
                || $mode == self::SPH_SORT_ATTR_ASC
                || $mode == self::SPH_SORT_TIME_SEGMENTS
                || $mode == self::SPH_SORT_EXTENDED
                || $mode == self::SPH_SORT_EXPR);
        assert(is_string($sortby));
        assert($mode == self::SPH_SORT_RELEVANCE || strlen($sortby) > 0);

        $this->_sort = $mode;
        $this->_sort_by = $sortby;
    }

    /**
     * Bind per-field weights by order
     *
     * @deprecated	use set_field_weights() instead
     * @access 	public
     * @param  	array	$weights
     */
    public function set_weights($weights)
    {
        assert(is_array($weights));
        foreach ($weights as $weight)
        {
            assert(is_int($weight));
        }
        $this->_weights = $weights;
    }

    /**
     * Bind per-field weights by name
     *
     * @access 	public
     * @param  	array	$weights
     */
    public function set_field_weights($weights)
    {
        assert(is_array($weights));
        foreach ($weights as $name => $weight)
        {
            assert(is_string($name));
            assert(is_int($weight));
        }
        $this->_field_weights = $weights;
    }

    /**
     * Bind per-index weights by name
     *
     * @access 	public
     * @param  	array	$weights
     */
    public function set_index_weights($weights)
    {
        assert(is_array($weights));
        foreach ($weights as $index => $weight)
        {
            assert(is_string($index));
            assert(is_int($weight));
        }
        $this->_index_weights = $weights;
    }

    /**
     * Set IDs range to match
     *
     * Only match records if document ID is beetwen $min and $max (inclusive)
     *
     * @access 	public
     * @param  	integer	$min
     * @param  	integer	$max
     */
    public function set_id_range($min, $max)
    {
        assert(is_numeric($min));
        assert(is_numeric($max));
        assert($min <= $max);
        $this->_min_id = $min;
        $this->_max_id = $max;
    }

    /**
     * Set values set filter
     *
     * Only match records where $attribute value is in given set
     *
     * @access 	public
     * @param  	string	$attribute
     * @param  	array	$values
     * @param  	bool	$exclude
     */
    public function set_filter($attribute, $values, $exclude=FALSE)
    {
        assert(is_string($attribute));
        assert(is_array($values));
        assert(count($values));

        if (is_array($values) && count($values))
        {
            foreach ($values as $value)
            {
                assert(is_numeric($value));
            }
            $this->_filters[] = array(
                'type' => self::SPH_FILTER_VALUES,
                'attr' => $attribute,
                'exclude' => $exclude,
                'values' => $values
            );
        }
    }

    /**
     * Set range filter
     *
     * Only match records if $attribute value is beetwen $min and $max (inclusive)
     *
     * @access 	public
     * @param  	string	$attribute
     * @param  	integer	$min
     * @param  	integer	$max
     * @param  	bool	$exclude
     */
    public function set_filter_range($attribute, $min, $max, $exclude=FALSE)
    {
        assert(is_string($attribute));
        assert(is_numeric($min));
        assert(is_numeric($max));
        assert($min <= $max);

        $this->_filters[] = array(
            'type' => self::SPH_FILTER_RANGE,
            'attr' => $attribute,
            'exclude' => $exclude,
            'min' => $min,
            'max' => $max
        );
    }

    /**
     * Set float range filter
     *
     * Only match records if $attribute value is beetwen $min and $max (inclusive)
     *
     * @access 	public
     * @param  	string	$attribute
     * @param  	integer	$min
     * @param  	integer	$max
     * @param  	bool	$exclude
     */
    public function set_filter_float_range($attribute, $min, $max, $exclude=FALSE)
    {
        assert(is_string($attribute));
        assert(is_float($min));
        assert(is_float($max));
        assert($min <= $max);

        $this->_filters[] = array(
            'type' => self::SPH_FILTER_FLOATRANGE,
            'attr' => $attribute,
            'exclude' => $exclude,
            'min' => $min,
            'max' => $max
        );
    }

    /**
     * Setup anchor point for geosphere distance calculations
     *
     * Required to use @geodist in filters and sorting latitude and longitude must be in radians
     *
     * @access 	public
     * @param  	string	$attrlat
     * @param  	string	$attrlong
     * @param  	integer	$lat
     * @param  	integer	$long
     */
    public function set_geo_anchor($attrlat, $attrlong, $lat, $long)
    {
        assert(is_string($attrlat));
        assert(is_string($attrlong));
        assert(is_float($lat));
        assert(is_float($long));

        $this->_anchor = array(
            'attrlat' => $attrlat,
            'attrlong' => $attrlong,
            'lat' => $lat,
            'long' => $long
        );
    }

    /**
     * Set grouping attribute and function
     *
     * @access 	public
     * @param  	string	$attribute
     * @param  	string	$func
     * @param  	string	$groupsort
     */
    public function set_group_by($attribute, $func, $groupsort='@group desc')
    {
        assert(is_string($attribute));
        assert(is_string($groupsort));
        assert($func == self::SPH_GROUPBY_DAY
                || $func == self::SPH_GROUPBY_WEEK
                || $func == self::SPH_GROUPBY_MONTH
                || $func == self::SPH_GROUPBY_YEAR
                || $func == self::SPH_GROUPBY_ATTR
                || $func == self::SPH_GROUPBY_ATTRPAIR);

        $this->_group_by = $attribute;
        $this->_group_func = $func;
        $this->_group_sort = $groupsort;
    }

    /**
     * Set count-distinct attribute for group-by queries
     *
     * @access 	public
     * @param  	string	$attribute
     */
    public function set_group_distinct($attribute)
    {
        assert(is_string($attribute));
        $this->_group_distinct = $attribute;
    }

    /**
     * Set distributed retries count and delay
     *
     * @access 	public
     * @param  	integer	$count
     * @param  	integer	$delay
     */
    public function set_retries($count, $delay=0)
    {
        assert(is_int($count) && $count >= 0);
        assert(is_int($delay) && $delay >= 0);
        $this->_retry_count = $count;
        $this->_retry_delay = $delay;
    }

    /**
     * Set result set format
     *
     * (hash or array; hash by default) PHP specific; needed for group-by-MVA result sets that may contain duplicate IDs
     *
     * @access 	public
     * @param  	bool	$arrayresult
     */
    public function set_array_result($arrayresult)
    {
        assert(is_bool($arrayresult));
        $this->_array_result = $arrayresult;
    }

    /**
     * Set attribute values override
     *
     * There can be only one override per attribute $values must be a hash that maps document IDs to attribute values
     *
     * @access 	public
     * @param  	string	$attrname
     * @param  	array	$attrtype
     * @param  	array	$values
     */
    public function set_override($attrname, $attrtype, $values)
    {
        assert(is_string($attrname));
        assert(in_array($attrtype, array(self::SPH_ATTR_INTEGER, self::SPH_ATTR_TIMESTAMP, self::SPH_ATTR_BOOL, self::SPH_ATTR_FLOAT, self::SPH_ATTR_BIGINT)));
        assert(is_array($values));

        $this->_overrides[$attrname] = array(
            'attr' => $attrname,
            'type' => $attrtype,
            'values' => $values
        );
    }

    /**
     * Set select-list
     *
     * (attributes or expressions) SQL-like syntax
     *
     * @access 	public
     * @param  	string	$select
     */
    public function set_select($select)
    {
        assert(is_string($select));
        $this->_select = $select;
    }

    /**
     * Clear all filters (for multi-queries)
     *
     * @access 	public
     */
    public function reset_filters()
    {
        $this->_filters = array();
        $this->_anchor = array();
    }

    /**
     * Clear groupby settings (for multi-queries)
     *
     * @access 	public
     */
    public function reset_group_by()
    {
        $this->_group_by = '';
        $this->_group_func = self::SPH_GROUPBY_DAY;
        $this->_group_sort = '@group desc';
        $this->_group_distinct = '';
    }

    /**
     * Clear all attribute value overrides (for multi-queries)
     *
     * @access 	public
     */
    public function reset_overrides()
    {
        $this->_overrides = array();
    }

    /**
     * Connect to searchd server
     *
     * Run given search query through given indexes, and return the search results
     *
     * @access 	public
     * @param  	string	$query
     * @param  	string	$index
     * @param  	string	$comment
     * @return 	mixed
     */
    public function query($query, $index='*', $comment='')
    {

        assert(empty($this->_requests));

        $this->add_query($query, $index, $comment);
        $results = $this->run_queries();
        $this->_requests = array();

        if (!is_array($results))
        {
            return FALSE;
        }
        $this->_error = $results[0]['error'];
        $this->_warning = $results[0]['warning'];
        if ($results[0]['status'] == self::SEARCHD_ERROR)
        {
            return FALSE;
        }
        else
        {
            return $results[0];
        }
    }

    /**
     * Add query to multi-query batch
     *
     * Returns index into results array from run_queries() call
     *
     * @access 	public
     * @param  	string	$query
     * @param  	string	$index
     * @param  	string	$comment
     * @return 	mixed
     */
    public function add_query($query, $index='*', $comment='')
    {
        $this->_mb_push();

        $req = pack('NNNNN', $this->_offset, $this->_limit, $this->_mode, $this->_ranker, $this->_sort)
                . pack('N', strlen($this->_sort_by))
                . $this->_sort_by
                . pack('N', strlen($query))
                . $query
                . pack('N', count($this->_weights));

        foreach ($this->_weights as $weight)
        {
            $req .= pack('N', (int) $weight);
        }
        $req .= pack('N', strlen($index))
                . $index
                . pack('N', 1)
                . $this->_pack_unsigned_64bit($this->_min_id)
                . $this->_pack_unsigned_64bit($this->_max_id)
                . pack('N', count($this->_filters));

        foreach ($this->_filters as $filter)
        {
            $req .= pack('N', strlen($filter['attr']))
                    . $filter['attr']
                    . pack('N', $filter['type']);

            switch ($filter['type'])
            {
                case self::SPH_FILTER_VALUES:
                    $req .= pack('N', count($filter['values']));
                    foreach ($filter['values'] as $value)
                    {
                        $req .= $this->_pack_signed_64bit($value);
                    }
                    break;

                case self::SPH_FILTER_RANGE:
                    $req .= $this->_pack_signed_64bit($filter['min'])
                            . $this->_pack_signed_64bit($filter['max']);
                    break;

                case self::SPH_FILTER_FLOATRANGE:
                    $req .= $this->_pack_float($filter['min'])
                            . $this->_pack_float($filter['max']);
                    break;

                default:
                    assert(0 && 'internal error: unhandled filter type');
            }
            $req .= pack('N', $filter['exclude']);
        }

        $req .= pack('NN', $this->_group_func, strlen($this->_group_by))
                . $this->_group_by
                . pack('N', $this->_max_matches)
                . pack('N', strlen($this->_group_sort))
                . $this->_group_sort
                . pack('NNN', $this->_cut_off, $this->_retry_count, $this->_retry_delay)
                . pack('N', strlen($this->_group_distinct))
                . $this->_group_distinct;

        if (empty($this->_anchor))
        {
            $req .= pack('N', 0);
        }
        else
        {
            $a = & $this->_anchor;
            $req .= pack('N', 1)
                    . pack('N', strlen($a['attrlat']))
                    . $a['attrlat']
                    . pack('N', strlen($a['attrlong']))
                    . $a['attrlong']
                    . $this->_pack_float($a['lat'])
                    . $this->_pack_float($a['long']);
        }

        $req .= pack('N', count($this->_index_weights));
        foreach ($this->_index_weights as $idx => $weight)
        {
            $req .= pack('N', strlen($idx)) . $idx . pack('N', $weight);
        }

        $req .= pack('N', $this->_max_query_time);
        $req .= pack('N', count($this->_field_weights));

        foreach ($this->_field_weights as $field => $weight)
        {
            $req .= pack('N', strlen($field)) . $field . pack('N', $weight);
        }

        $req .= pack('N', strlen($comment)) . $comment;
        $req .= pack('N', count($this->_overrides));

        foreach ($this->_overrides as $key => $entry)
        {
            $req .= pack('N', strlen($entry['attr'])) . $entry['attr'];
            $req .= pack('NN', $entry['type'], count($entry['values']));
            foreach ($entry['values'] as $id => $val)
            {
                assert(is_numeric($id));
                assert(is_numeric($val));

                $req .= $this->_pack_unsigned_64bit($id);
                switch ($entry['type'])
                {
                    case self::SPH_ATTR_FLOAT:
                        $req .= $this->_pack_float($val);
                        break;
                    case self::SPH_ATTR_BIGINT:
                        $req .= $this->_pack_signed_64bit($val);
                        break;
                    default:
                        $req .= pack('N', $val);
                        break;
                }
            }
        }

        $req .= pack('N', strlen($this->_select))
                . $this->_select;

        $this->_mb_pop();
        $this->_requests[] = $req;

        return count($this->_requests) - 1;
    }

    /**
     * Run queries batch
     *
     * Connect to searchd, and return an array of result sets
     *
     * @access 	public
     * @return 	mixed
     */
    public function run_queries()
    {
        if (empty($this->_requests))
        {
            $this->_error = 'no queries defined, issue add_query() first';
            return FALSE;
        }

        $this->_mb_push();

        if (!($fp = $this->_connect()))
        {
            $this->_mb_pop();
            return FALSE;
        }

        $nreqs = count($this->_requests);
        $req = join('', $this->_requests);
        $len = 4 + strlen($req);
        $req = pack('nnNN', self::SEARCHD_COMMAND_SEARCH, self::VER_COMMAND_SEARCH, $len, $nreqs) . $req;

        if (!($this->_send($fp, $req, $len + 8)) || !( $response = $this->_get_response($fp, self::VER_COMMAND_SEARCH)))
        {
            $this->_mb_pop();
            return FALSE;
        }

        $this->_requests = array();

        return $this->_parse_search_response($response, $nreqs);
    }

    /// connect to searchd server, and generate exceprts (snippets)
    /// of given documents for given query. returns false on failure,
    /// an array of snippets on success
    /**
     * Generate exceprts
     *
     * Connect to searchd server, and generate exceprts (snippets) of given documents for given query. returns false on failure, an array of snippets on success
     *
     * @access 	public
     * @param  	array	$docs
     * @param  	string	$index
     * @param  	string	$words
     * @param  	array	$opts
     * @return 	mixed
     */
    public function build_excerpts($docs, $index, $words, $opts=array())
    {
        assert(is_array($docs));
        assert(is_string($index));
        assert(is_string($words));
        assert(is_array($opts));

        $this->_mb_push();

        if (!( $fp = $this->_connect()))
        {
            $this->_mb_pop();
            return FALSE;
        }
        if (!isset($opts['before_match']))
        {
            $opts['before_match'] = '<b>';
        }
        if (!isset($opts['after_match']))
        {
            $opts['after_match'] = '</b>';
        }
        if (!isset($opts['chunk_separator']))
        {
            $opts['chunk_separator'] = ' ... ';
        }
        if (!isset($opts['limit']))
        {
            $opts['limit'] = 256;
        }
        if (!isset($opts['around']))
        {
            $opts['around'] = 5;
        }
        if (!isset($opts['exact_phrase']))
        {
            $opts['exact_phrase'] = FALSE;
        }
        if (!isset($opts['single_passage']))
        {
            $opts['single_passage'] = FALSE;
        }
        if (!isset($opts['use_boundaries']))
        {
            $opts['use_boundaries'] = FALSE;
        }
        if (!isset($opts['weight_order']))
        {
            $opts['weight_order'] = FALSE;
        }
        if (!isset($opts['query_mode']))
        {
            $opts['query_mode'] = FALSE;
        }
        if (!isset($opts['force_all_words']))
        {
            $opts['force_all_words'] = FALSE;
        }

        $flags = 1;
        if ($opts['exact_phrase'])
        {
            $flags |= 2;
        }
        if ($opts['single_passage'])
        {
            $flags |= 4;
        }
        if ($opts['use_boundaries'])
        {
            $flags |= 8;
        }
        if ($opts['weight_order'])
        {
            $flags |= 16;
        }
        if ($opts['query_mode'])
        {
            $flags |= 32;
        }
        if ($opts['force_all_words'])
        {
            $flags |= 64;
        }

        $req = pack('NN', 0, $flags)
                . pack('N', strlen($index))
                . $index
                . pack('N', strlen($words))
                . $words
                . pack('N', strlen($opts['before_match']))
                . $opts['before_match']
                . pack('N', strlen($opts['after_match']))
                . $opts['after_match']
                . pack('N', strlen($opts['chunk_separator']))
                . $opts['chunk_separator']
                . pack('N', (int) $opts['limit'])
                . pack('N', (int) $opts['around'])
                . pack('N', count($docs));

        foreach ($docs as $doc)
        {
            assert(is_string($doc));
            $req .= pack('N', strlen($doc))
                    . $doc;
        }

        $len = strlen($req);
        $req = pack('nnN', self::SEARCHD_COMMAND_EXCERPT, self::VER_COMMAND_EXCERPT, $len)
                . $req;

        if (!( $this->_send($fp, $req, $len + 8)) || !( $response = $this->_get_response($fp, self::VER_COMMAND_EXCERPT)))
        {
            $this->_mb_pop();
            return FALSE;
        }

        $pos = 0;
        $res = array();
        $rlen = strlen($response);
        for ($i = 0; $i < count($docs); $i++)
        {
            list(, $len) = unpack('N*', substr($response, $pos, 4));
            $pos += 4;

            if ($pos + $len > $rlen)
            {
                $this->_error = 'incomplete reply';
                $this->_mb_pop();
                return FALSE;
            }
            $res[] = $len
                    ? substr($response, $pos, $len)
                    : '';
            $pos += $len;
        }

        $this->_mb_pop();
        return $res;
    }

    /**
     * Build keywords
     *
     * Connect to searchd server, and generate keyword list for a given query returns false on failure, an array of words on success
     *
     * @access 	public
     * @param  	string	$query
     * @param  	string	$index
     * @param  	bool	$hits
     * @return 	mixed
     */
    public function build_keywords($query, $index, $hits)
    {
        assert(is_string($query));
        assert(is_string($index));
        assert(is_bool($hits));

        $this->_mb_push();

        if (!($fp = $this->_connect()))
        {
            $this->_mb_pop();
            return FALSE;
        }

        $req = pack('N', strlen($query))
                . $query
                . pack('N', strlen($index))
                . $index
                . pack('N', (int) $hits);

        $len = strlen($req);
        $req = pack('nnN', self::SEARCHD_COMMAND_KEYWORDS, self::VER_COMMAND_KEYWORDS, $len)
                . $req;

        if (!($this->_send($fp, $req, $len + 8)) || !( $response = $this->_get_response($fp, self::VER_COMMAND_KEYWORDS)))
        {
            $this->_mb_pop();
            return FALSE;
        }

        $pos = 0;
        $res = array();
        $rlen = strlen($response);

        list(, $nwords) = unpack('N*', substr($response, $pos, 4));
        $pos += 4;

        for ($i = 0; $i < $nwords; $i++)
        {
            list(, $len) = unpack('N*', substr($response, $pos, 4));
            $pos += 4;

            $tokenized = $len
                    ? substr($response, $pos, $len)
                    : '';
            $pos += $len;

            list(, $len) = unpack('N*', substr($response, $pos, 4));
            $pos += 4;

            $normalized = $len
                    ? substr($response, $pos, $len)
                    : '';
            $pos += $len;

            $res[] = array('tokenized' => $tokenized, 'normalized' => $normalized);

            if ($hits)
            {
                list($ndocs, $nhits) = array_values(unpack('N*N*', substr($response, $pos, 8)));
                $pos += 8;
                $res[$i]['docs'] = $ndocs;
                $res[$i]['hits'] = $nhits;
            }
            if ($pos > $rlen)
            {
                $this->_error = 'incomplete reply';
                $this->_mb_pop();
                return FALSE;
            }
        }

        $this->_mb_pop();
        return $res;
    }

    /**
     * Escape String
     *
     * @access 	public
     * @param  	string	$string
     * @return 	string
     */
    public function escape_string($string)
    {
        $from = array('\\', '(', ')', '|', '-', '!', '@', '~', '"', '&', '/', '^', '$', '=');
        $to = array('\\\\', '\(', '\)', '\|', '\-', '\!', '\@', '\~', '\"', '\&', '\/', '\^', '\$', '\=');

        return str_replace($from, $to, $string);
    }

    /**
     * Update Attributes
     *
     * Batch update given attributes in given rows in given indexes returns amount of updated documents (0 or more) on success, or -1 on failure
     *
     * @access 	public
     * @param  	string	$index
     * @param  	array	$attrs
     * @param  	array	$values
     * @param  	bool	$mva
     * @return 	integer
     */
    public function update_attributes($index, $attrs, $values, $mva=FALSE)
    {
        assert(is_string($index));
        assert(is_bool($mva));
        assert(is_array($attrs));

        foreach ($attrs as $attr)
        {
            assert(is_string($attr));
        }

        assert(is_array($values));

        foreach ($values as $id => $entry)
        {
            assert(is_numeric($id));
            assert(is_array($entry));
            assert(count($entry) == count($attrs));

            foreach ($entry as $v)
            {
                if ($mva)
                {
                    assert(is_array($v));
                    foreach ($v as $vv)
                    {
                        assert(is_int($vv));
                    }
                }
                else
                {
                    assert(is_int($v));
                }
            }
        }

        $this->_mb_push();
        $req = pack('N', strlen($index))
                . $index
                . pack('N', count($attrs));

        foreach ($attrs as $attr)
        {
            $req .= pack('N', strlen($attr))
                    . $attr
                    . pack('N', $mva
                                    ? 1
                                    : 0);
        }

        $req .= pack('N', count($values));

        foreach ($values as $id => $entry)
        {
            $req .= $this->_pack_unsigned_64bit($id);
            foreach ($entry as $v)
            {
                $req .= pack('N', $mva
                                        ? count($v)
                                        : $v);
                if ($mva)
                {
                    foreach ($v as $vv)
                    {
                        $req .= pack('N', $vv);
                    }
                }
            }
        }

        if (!($fp = $this->_connect()))
        {
            $this->_mb_pop();
            return -1;
        }

        $len = strlen($req);
        $req = pack('nnN', self::SEARCHD_COMMAND_UPDATE, self::VER_COMMAND_UPDATE, $len)
                . $req;

        if (!$this->_send($fp, $req, $len + 8))
        {
            $this->_mb_pop();
            return -1;
        }
        if (!($response = $this->_get_response($fp, self::VER_COMMAND_UPDATE)))
        {
            $this->_mb_pop();
            return -1;
        }

        list(, $updated) = unpack('N*', substr($response, 0, 4));
        $this->_mb_pop();
        return $updated;
    }

    /**
     * Open
     *
     * @access 	public
     * @return 	bool
     */
    public function open()
    {
        if ($this->_socket !== FALSE)
        {
            $this->_error = 'already connected';
            return FALSE;
        }
        if (!$fp = $this->_connect())
        {
            return FALSE;
        }

        $req = pack('nnNN', self::SEARCHD_COMMAND_PERSIST, 0, 4, 1);
        if (!$this->_send($fp, $req, 12))
        {
            return FALSE;
        }
        $this->_socket = $fp;
        return TRUE;
    }

    /**
     * Close
     *
     * @access 	public
     * @return 	bool
     */
    public function close()
    {
        if ($this->_socket === FALSE)
        {
            $this->_error = 'not connected';
            return FALSE;
        }

        fclose($this->_socket);
        $this->_socket = FALSE;

        return TRUE;
    }

    /**
     * Status
     *
     * @access 	public
     * @return 	mixed
     */
    public function status()
    {
        $this->_mb_push();
        if (!($fp = $this->_connect()))
        {
            $this->_mb_pop();
            return FALSE;
        }

        $req = pack('nnNN', self::SEARCHD_COMMAND_STATUS, self::VER_COMMAND_STATUS, 4, 1);
        if (!( $this->_send($fp, $req, 12)) || !( $response = $this->_get_response($fp, self::VER_COMMAND_STATUS)))
        {
            $this->_mb_pop();
            return FALSE;
        }

        $res = substr($response, 4);
        $p = 0;
        list($rows, $cols) = array_values(unpack('N*N*', substr($response, $p, 8)));
        $p += 8;

        $res = array();
        for ($i = 0; $i < $rows; $i++)
        {
            for ($j = 0; $j < $cols; $j++)
            {
                list(, $len) = unpack('N*', substr($response, $p, 4));
                $p += 4;
                $res[$i][] = substr($response, $p, $len);
                $p += $len;
            }
        }

        $this->_mb_pop();
        return $res;
    }

    /**
     * Flush Attributes
     *
     * @access 	public
     * @return 	mixed
     */
    public function flush_attributes()
    {
        $this->_mb_push();
        if (!($fp = $this->_connect()))
        {
            $this->_mb_pop();
            return -1;
        }

        $req = pack('nnN', self::SEARCHD_COMMAND_FLUSHATTRS, self::VER_COMMAND_FLUSHATTRS, 0);
        if (!( $this->_send($fp, $req, 8)) || !( $response = $this->_get_response($fp, self::VER_COMMAND_FLUSHATTRS)))
        {
            $this->_mb_pop();
            return -1;
        }

        $tag = -1;
        if (strlen($response) == 4)
        {
            list(, $tag) = unpack('N*', $response);
        }
        else
        {
            $this->_error = 'unexpected response length';
        }

        $this->_mb_pop();
        return $tag;
    }

}
/* End of file sphinx.php */
/* Location: ./application/libraries/sphinx.php */