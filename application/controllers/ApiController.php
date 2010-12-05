<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category ApiController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
require(APPPATH . '/controllers/RestController.php');

class ApiController extends RestController
{

    /**
     * Default GET Request API Function 
     *
     * Leave this function in place as it handles requests with not data.
     * 
     * Here is a sample on interacting with other models.  We should standardize this
     * with all models to interact with the API by having an apiGet function to interact
     * with different model data.
     * 
     * USING THE URL:
     * 
     * 	You can pass parameters in the URL after /api/function to send variables to the API.
     * 
     * 		Example: /api/sample/id/123
     * 
     * 	Will allow you to use: $this->get('id') so you can pass that to a model.
     * 
     * 		Example: $data = $this->model->apiGetFunction( $this->get('id') );
     * 		
     * SPECIFYING RETURNED DATA FORMAT:
     * 
     * 	This is relativley straight forward, just add /format/type to the end of the URL string.
     * 	
     * 		Example:  /api/sample/id/123/format/csv
     * 		
     * 	The available format types are as follows:
     * 	
     * 		* xml 			Almost any programming language can read XML. This is the default format.
     * 		* json			Useful for JavaScript.
     * 		* csv			Open with spreadsheet programs. NOTE: Requres response to be an array with 'key'=>'value' pairs.
     * 		* html			A simple HTML table. NOTE: Requres response to be an array with 'key'=>'value' pairs.
     * 		* php			Representation of PHP code that can be eval()’ed
     * 		* serialize		Serialized data that can be unserialized in PHP.
     * 		
     * SENDING THE RESPONSE
     * 
     * 	To send a response you have a few options.  For missing page errors you can send a blank 404.
     * 			
     * 		Example:  $this->response(NULL, 404);
     *
     * 	If you wish to send a message with the 404
     *
     * 		Example:  $this->response('The page you are looking for cannot be found.', 404);
     *
     * 	The send an array with the 200 OK Status Code you would do something like
     *
     * 		Example:  $this->response( array('name'=>'John Doe', 'email'=>'my@email.com' ));
     * 
     * 	Here are all the possible Status Codes you can use
     *
     * 		* 100 Continue
     * 		* 101 Switching Protocols
     * 		* 200 OK
     * 		* 201 Created
     * 		* 202 Accepted
     * 		* 203 Non-Authoritative Information
     * 		* 204 No Content
     * 		* 205 Reset Content
     * 		* 206 Partial Content
     * 		* 300 Multiple Choices
     * 		* 301 Moved Permanently
     * 		* 302 Found
     * 		* 303 See Other
     * 		* 304 Not Modified
     * 		* 305 Use Proxy
     * 		* 307 Temporary Redirect
     * 		* 400 Bad Request
     * 		* 401 Unauthorized
     * 		* 402 Payment Required
     * 		* 403 Forbidden
     * 		* 404 Not Found
     * 		* 405 Method Not Allowed
     * 		* 406 Not Acceptable
     * 		* 407 Proxy Authentication Required
     * 		* 408 Request Timeout
     * 		* 409 Conflict
     * 		* 410 Gone
     * 		* 411 Length Required
     * 		* 412 Precondition Failed
     * 		* 413 Request Entity Too Large
     * 		* 414 Request-URI Too Long
     * 		* 415 Unsupported Media Type
     * 		* 416 Requested Range Not Satisfiable
     * 		* 417 Expectation Failed
     * 		* 500 Internal Server Error
     * 		* 501 Not Implemented
     * 		* 502 Bad Gateway
     * 		* 503 Service Unavailable
     * 		* 504 Gateway Timeout
     * 		* 505 HTTP Version Not Supported
     *
     * @access public
     * @return array
     */
    function index_get()
    {
        $this->response(array('ERROR' => 'Invalid GET Request. Refer to API Documentation.'));
    }

    /**
     * Default POST Request API Function
     *
     * Leave this function in place as it handles requests with not data.
     *
     * Here is a sample on interacting with other models.  We should standardize this
     * with all models to interact with the API by having an apiPost function to interact
     * with different model data.
     *
     * USING THE URL:
     *
     * 	You can pass parameters in the URL after /api/function to send variables to the API.
     *
     * 		Example: /api/sample/id/123
     *
     * 	Will allow you to use: $this->post('id') so you can pass that to a model.
     *
     * 		Example: $data = $this->model->apiPostFunction( $this->post('id') );
     *
     * SPECIFYING RETURNED DATA FORMAT:
     *
     * 	NOTE: This is the same as GET
     * 	@see index_get()
     *
     * SENDING THE RESPONSE
     *
     * 	NOTE: This is the same as GET
     * 	@see index_get()
     *
     * @access public
     * @return array
     */
    function index_post()
    {
        $this->response(array('ERROR' => 'Invalid POST Request. Refer to API Documentation.'));
    }

    /**
     * Default PUT Request API Function
     *
     * Leave this function in place as it handles requests with not data.
     *
     * Here is a sample on interacting with other models.  We should standardize this
     * with all models to interact with the API by having an apiPut function to interact
     * with different model data.
     *
     * USING THE URL:
     *
     * 	You can pass parameters in the URL after /api/function to send variables to the API.
     *
     * 		Example: /api/sample/id/123
     *
     * 	Will allow you to use: $this->put('id') so you can pass that to a model.
     *
     * 		Example: $data = $this->model->apiPutFunction( $this->put('id') );
     *
     * SPECIFYING RETURNED DATA FORMAT:
     *
     * 	NOTE: This is the same as GET
     * 	@see index_get()
     *
     * SENDING THE RESPONSE
     *
     * 	NOTE: This is the same as GET
     * 	@see index_get()
     *
     * @access public
     * @return array
     */
    function index_put()
    {
        $this->response(array('ERROR' => 'Invalid PUT Request. Refer to API Documentation.'));
    }

    /**
     * Default DELETE Request API Function
     *
     * Leave this function in place as it handles requests with not data.
     *
     * Here is a sample on interacting with other models.  We should standardize this
     * with all models to interact with the API by having an apiDelete function to interact
     * with different model data.
     *
     * USING THE URL:
     *
     * 	You can pass parameters in the URL after /api/function to send variables to the API.
     *
     * 		Example: /api/sample/id/123
     *
     * 	Will allow you to use: $this->delete('id') so you can pass that to a model.
     *
     * 		Example: $data = $this->model->apiDeleteFunction( $this->delete('id') );
     *
     * SPECIFYING RETURNED DATA FORMAT:
     *
     * 	NOTE: This is the same as GET
     * 	@see index_get()
     *
     * SENDING THE RESPONSE
     *
     * 	NOTE: This is the same as GET
     * 	@see index_get()
     *
     * @access public
     * @return array
     */
    function index_delete()
    {
        $this->response(array('ERROR' => 'Invalid DELETE Request. Refer to API Documentation.'));
    }

}
/* End of file ApiController.php */
/* Location: ./application/controllers/ApiController.php */