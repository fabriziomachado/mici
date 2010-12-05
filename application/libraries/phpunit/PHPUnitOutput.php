<?PHP if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category PHPUnit
 */

/**
 * Begin Document
 */

class PHPUnitOutput extends CI_Output
{
    function PHPUnitOutput()
	{
        parent::CI_Output();
        $this->final_output = '';
        $this->_ci_ob_level  = ob_get_level();
        $this->cookies = array();
    }

    /**
    * store cookie headers
    */
    function set_cookie($arr)
	{
        if(!is_array($arr))
		{
            $arr = func_get_args();
        }
        $this->cookies[]=$arr;
    }

    /**
    * Add to instead of replace final output
    */
    function add_output($str)
	{
        $this->final_output.=$str;
    }

    /**
    * Pop Output
    *
    * The final output the output class has stringed together is returned and truncated
    *
    */
    function pop_output()
	{
        $output = $this->final_output;
        $this->final_output = "";
        return $output;
    }

	/**
	* set_no_cache_headers
	* called as a post controller construction hook
	* should count therefore as controller duty
	*/
	function set_no_cache_headers()
	{
		$CI = &get_instance();
		$CI->output->soft_set_header('Content-type: text/html; charset=utf-8');
		$CI->output->soft_set_header('Cache-Control: no-cache');
		log_message('debug', 'no cache headers set in output class');
	}

	/**
	 * sets headers if not already set
	 */
	function soft_set_header($header)
	{
        $key = strtolower(array_shift(split(':', $header)));
        $add = TRUE;
        foreach($this->headers as $hdr)
		{
            $h = split(':', $hdr);
            if(strtolower(array_shift($h)) == $key)
			{
                $add = FALSE;
            }
        }
        $add?($this->headers[] = $header):'';
	}

	/**
	* say
	* like normal echo but puts it in the output_buffer first, so we still can set headers
	* and post process it
	*/
	function say($str)
	{
		ob_start();
		echo $str;
		$this->ob_flush_clean();
	}
	
	/**
	* ob_flush_clean
	* flushes or cleans the buffer depending on if we are finished outputting or still on a nested level
	*/
	function ob_flush_clean(){
		$CI = &get_instance();
		if (ob_get_level() > $this->_ci_ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
			$this->add_output(ob_get_contents());
			@ob_end_clean();
		}
	}

  	/**
	 * Display Output
	 *
	 * All "view" data is automatically put into this variable by the controller class:
	 *
	 * $this->final_output
	 *
	 * This function sends the finalized output data to the browser along
	 * with any server headers and profile data.  It also stops the
	 * benchmark timer so the page rendering speed and memory usage can be shown.
	 *
	 * @access	public
	 * @return	mixed
	 */
	function _display($output = '')
	{
		global $BM, $CFG;

		if ($output == '')
		{
			$output =& $this->final_output;
		}

		if ($this->cache_expiration > 0)
		{
			$this->_write_cache($output);
		}

		$elapsed = $BM->elapsed_time('total_execution_time_start', 'total_execution_time_end');
		$output = str_replace('{elapsed_time}', $elapsed, $output);

		$memory	 = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
		$output = str_replace('{memory_usage}', $memory, $output);

		if ($CFG->item('compress_output') === TRUE)
		{
			if (extension_loaded('zlib'))
			{
				if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE)
				{
					ob_start('ob_gzhandler');
				}
			}
		}
		if (count($this->headers) > 0)
		{
			foreach ($this->headers as $header)
			{
				@header($header);
                log_message('debug', "header '$header' set.");
			}
		}
		if (count($this->cookies) > 0)
		{
			foreach ($this->cookies as $cookie)
			{
                call_user_func_array ( 'setcookie' , $cookie );
                log_message('debug', "cookie '".join(', ', $cookie)."' set.");
			}
		}
		if ( ! function_exists('get_instance'))
		{
			echo $output;
			log_message('debug', "Final output sent to browser");
			log_message('debug', "Total execution time: ".$elapsed);
			return TRUE;
		}

		$CI =& get_instance();
		if ($this->enable_profiler == TRUE)
		{
			$CI->load->library('profiler');
			if (preg_match("|</body>.*?</html>|is", $output))
			{
				$output  = preg_replace("|</body>.*?</html>|is", '', $output);
				$output .= $CI->profiler->run();
				$output .= '</body></html>';
			}
			else
			{
				$output .= $CI->profiler->run();
			}
		}

		if (method_exists($CI, '_output'))
		{
			$CI->_output($output);
		}
		else
		{
			echo $output;
		}

		log_message('debug', "Final output sent to browser");
		log_message('debug', "Total execution time: ".$elapsed);
	}
}

/* End of file PHPUnitOutput.php */
/* Location: ./application/libraries/phpunit/PHPUnitOutput.php */