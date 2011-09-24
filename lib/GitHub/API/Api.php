<?php

namespace GitHub\API;

use Buzz\Browser;

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
    const HTTP_STATUS_UNAUTHORIZED            = 401;
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
     * Authenticatin flag. TRUE indicates subsequent API request will be made
     * with authentication
     *
     * @var bool
     */
    protected $authenticated = false;

    /**
     * Authenticator to use (Basic|OAuth)
     *
     * @var AuthenticationInterface
     */
    protected $authenticator  = null;

    /**
     * Constructor
     *
     * @param   Browser $transport   Transport layer. Allows mocking of transport layer in testing
     */
    public function __construct(Browser $transport = null)
    {
        if (null === $transport)
            $this->transport = new Browser();
        else
            $this->transport = $transport;
    }
    
    /**
    * Sets user credentials via an AuthenticationInterface. Setting credentials
    * does not log the user in. A call to login() must be made aswell
    *
    * @param   AuthenticationInterface  $authenticator       Authenticator
    */
    public function setCredentials(Authentication\AuthenticationInterface $authenticator)
    {
        $this->authenticator = $authenticator;
    }
    
    /**
     * Clears credentials. Clearing credentials does not logout the user. A call
     * to logout() must be made first
     */
    public function clearCredentials()
    {
        if (false === $this->isAuthenticated())
        {
            $this->authenticator = null;
        }
        else
            throw new ApiException('You must logout before clearing credentials. Use logout() first');
    }

    /**
     * Login the user. Applies authentication to all subsequent API calls.
     * Credentials must be set first with setCredentials()
     */
    public function login()
    {
        if (null === $this->authenticator)
            throw new ApiException('Cannot login. You must specify the credentials first. Use setCredentials()');

        $this->authenticated = true;
    }

    /**
     * Logout the user. Cancels authentication to all subsequent API calls.
     *
     * @param   bool    $clearCredentials     Setting TRUE will also clear the set credentials
     */
    public function logout($clearCredentials = false)
    {
        $this->authenticated = false;
        if (false === $clearCredentials)
            $this->clearCredentials();
    }

    /**
     * Check if authentication will be applied
     *
     * @return bool       Returns TRUE is authentication will be applied on subsequent API calls
     */
    public function isAuthenticated()
    {
        return $this->authenticated;
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
    public function requestGet($url, $params = array(), $headers = array())
    {
        return $this->doRequest('GET', self::API_URL . $url, $params, $headers);
    }

    /**
     * Make a HTTP POST request to API
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any POST params
     * @return  mixed                 API response
     */
    public function requestPost($url, $params = array(), $headers = array())
    {
        $params = (count($params)) ? json_encode($params) : null;

        return $this->doRequest('POST', self::API_URL . $url, $params, $headers);
    }

    /**
     * Make a HTTP PUT request to API
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any PUT params
     * @return  mixed                 API response
     */
    public function requestPut($url, $params = array(), $headers = array())
    {
        $params = (count($params)) ? json_encode($params) : null;

        return $this->doRequest('PUT', self::API_URL . $url, $params, $headers);
    }

    /**
     * Make a HTTP PATCH request to API
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any PATCH params
     * @return  mixed                 API response
     */
    public function requestPatch($url, $params = array(), $headers = array())
    {
        $params = (count($params)) ? json_encode($params) : null;

        return $this->doRequest('PATCH', self::API_URL . $url, $params, $headers);
    }

    /**
     * Make a HTTP DELETE request to API
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any DELETE params
     * @return  mixed                 API response
     */
    public function requestDelete($url, $params = array(), $headers = array())
    {
        $params = (count($params)) ? json_encode($params) : null;

        return $this->doRequest('DELETE', self::API_URL . $url, $params, $headers);
    }

    /**
     * Perform HTTP request
     *
     * @param   string  $method       HTTP method
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any params
     * @return  mixed                 API response
     */
    protected function doRequest($method, $url, $params, $headers)
    {
        $request = $this->transport->createRequest();

        $request->setMethod($method);
        $request->fromUrl($url);
        $request->addHeaders($headers);
        $request->setContent($params);

        if ($this->isAuthenticated() && null !== $this->authenticator)
        {
            $request = $this->authenticator->authenticate($request);
        }
        
        return $this->transport->send($request);        
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

        foreach ($rawParams as $key=>$value)
        {
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

    /**
     * Process the repsonse from the API
     *
     * @param   array   $response       Raw repsonse from Transport layer
     * @return  array|bool              Processed response
     */
    protected function processResponse($response)
    {
        switch ($response->getStatusCode())
        {
            // Return the data
            case self::HTTP_STATUS_OK:
            case self::HTTP_STATUS_CREATED:
                return $response->getContent();
                break;

            case self::HTTP_STATUS_NO_CONTENT:
                return true;
                break;

            case self::HTTP_STATUS_NOT_FOUND:
                return false;
                break;

            // Errors have happened, return entire response
            case self::HTTP_STATUS_BAD_REQUEST:
            case self::HTTP_STATUS_UNPROCESSABLE_ENTITY:
                return $response;

            // Authenitication required, or credentials invalid
            case self::HTTP_STATUS_UNAUTHORIZED:
                throw new AuthenticationException("Unauthorized: Authentication required");
                break;

            default:
                return $response;
        }
    }
}

/**
 * General API Exception
 */
class ApiException extends \Exception {}

/**
 * API authentication error
 */
class AuthenticationException extends \Exception {}
