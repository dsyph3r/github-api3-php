<?php

namespace GitHub\API;

/**
 * Api
 *
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Api
{
    /**
     * GitHub API location
     */
    const API_URL            = 'https://api.github.com/';
  
    /**
     * Constants for available API response formats. Not all API methods
     * provide responses in all formats. Defaults to FORMAT_JSON
     *
     * @link http://developer.github.com/v3/mimes/
     */
    const FORMAT_JSON        = 'json';
    const FORMAT_RAW         = 'raw';
    const FORMAT_TEXT        = 'text';
    const FORMAT_HTML        = 'html';
    const FORMAT_FULL        = 'full';
  
    /**
     * Constants for HTTP status return codes
     */
    const HTTP_STATUS_OK                      = 200;
    const HTTP_STATUS_CREATED                 = 201;
    const HTTP_STATUS_NO_CONTENT              = 204;
    const HTTP_STATUS_BAD_REQUEST             = 400;
    const HTTP_STATUS_NOT_FOUND               = 404;
    const HTTP_STATUS_UNPROCESSABLE_ENTITY    = 422;
  
    /**
     * Constant for default pagination size on paginate requests. API will allow
     * max of 100 requests per page
     *
     * @link http://developer.github.com/v3/#pagination
     */
    const DEFAULT_PAGE_SIZE  = 30;

    /**
     * Transport layer
     * 
     * @var Transport
     */
    protected $transport      = null;
  
    /**
     * Constructor
     *
     * @param   Transport $transport   Transport layer. Allows mocking of transport layer in testing
     */
    public function __construct(Transport $transport = null)
    {
        if (null === $transport)
            $this->transport = new Transport();
        else
            $this->transport = $transport;
    }
  
    /**
     * Proxy method to transport layer authentication. Setting credentials does not
     * log the user in. A call to login() must be made aswell
     *
     * @param   string  $username       Username
     * @param   string  $password       Password
     */
    public function setCredentials($username, $password)
    {
        $this->transport->setCredentials($username, $password);
    }
  
    /**
     * Proxy method to transport layer authentication. Clearing credentials does not
     * logout the user. A call to logout() must be made first
     */
    public function clearCredentials()
    {
        $this->transport->clearCredentials();
    }
  
    /**
     * Proxy method to transport layer authentication. Applies authentication to all
     * subsequent API calls.
     */
    public function login()
    {
        $this->transport->login();
    }
  
    /**
     * Proxy method to transport layer authentication. Cancels authentication to all
     * subsequent API calls.
     *
     * @param   bool    $clearCredentials     Setting TRUE will also clear the set credentials
     */
    public function logout($clearCredentials = false)
    {
        $this->transport->logout();
    }
  
    /**
     * Proxy method to transport layer authentication.
     *
     * @return bool       Returns TRUE is authentication will be applied on subsequent API calls
     */
    public function isAuthenticated()
    {
        return $this->transport->isAuthenticated();
    }
  
    /**
     * Get transport layer
     *
     * @return  Transport       Returns Transport layer
     */
    public function getTransport()
    {
        return $this->transport;
    }
  
    /**
     * Make a HTTP GET request to API
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any GET params
     * @return  mixed                 API response
     */
    public function requestGet($url, $params = array(), $options = array())
    {
        return $this->transport->get(self::API_URL . $url, $params, $options);
    }
  
    /**
     * Make a HTTP POST request to API
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any POST params
     * @return  mixed                 API response
     */
    public function requestPost($url, $params = array(), $options = array())
    {
        $params = (count($params)) ? json_encode($params) : null;
    
        return $this->transport->post(self::API_URL . $url, $params, $options);
    }
  
    /**
     * Make a HTTP PUT request to API
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any PUT params
     * @return  mixed                 API response
     */
    public function requestPut($url, $params = array(), $options = array())
    {
        $params = (count($params)) ? json_encode($params) : null;
    
        return $this->transport->put(self::API_URL . $url, $params, $options);
    }
  
    /**
     * Make a HTTP PATCH request to API
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any PATCH params
     * @return  mixed                 API response
     */
    public function requestPatch($url, $params = array(), $options = array())
    {
        $params = (count($params)) ? json_encode($params) : null;
    
        return $this->transport->patch(self::API_URL . $url, $params, $options);
    }
  
    /**
     * Make a HTTP DELETE request to API
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any DELETE params
     * @return  mixed                 API response
     */
    public function requestDelete($url, $params = array(), $options = array())
    {
        $params = (count($params)) ? json_encode($params) : null;
    
        return $this->transport->delete(self::API_URL . $url, $params, $options);
    }
  
    /**
     * Generate pagintaion page parameters
     *
     * @param int   $page         The page to get
     * @param int   $pageSize     The size of the paginated page
     * @return                    Array params for pagination
     */
    protected function buildPageParams($page, $pageSize) {
        return array(
          'page'      => $page,
          'per_page'  => $pageSize
        );
    }
  
    /**
     * Generates parameters array from key/value pairs. Only values that are
     * not null are returned. This is useful for update functions where many of the fields
     * can be optional, but where we want to provide default arguments in our functions
     * and sending null to the API will cause errors.
     *
     * An example is ApiKey::update() method. Both $title and $key are optional, but
     * neither can be sent to API as null
     *
     * @param   array   $rawParams  Key/Value raw parameters
     * @return  array               Key/Value parameters
     */
    protected function buildParams($rawParams)
    {
        $params = array();
    
        foreach ($rawParams as $key=>$value) {
            if (false === is_null($value))
                $params[$key] = $value;
        }
    
        return $params;
    }
  
    /**
     * Sets the Mime type format options
     *
     * @param string    $format       The mime type format to use
     * @param string    $resourseKi   The GitHub resourse identifier
     * @param array     $options      Options array to update with mime type format
     * @return array                  Updated options with mime type format set
     */
    protected function setResponseFormatOptions($format, $resourceKi, $options)
    {
        if (false === isset($options['headers']))
            $options['headers'] = array();
    
        $options['headers'][] = 'Accept: application/vnd.github-' . $resourceKi . '.' . $format . '+json';
    
        return $options;
    }

}

/**
 * General API Exception
 */
class ApiException extends \Exception {
}

/**
 * API authentication error
 */
class AuthenticationException extends \Exception {

    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct("Authentication required for action [$message]", $code, $previous);
    }

}
