<?php

namespace GitHub\API\Authentication;

/**
 * OAuth Authentication
 *
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class OAuth implements AuthenticationInterface
{
    /**
     * OAuth access token
     *
     * @var string
     */
    private $accessToken;
    
    /**
     * Create with OAuth access token
     *
     * @param   string      $accessToken        OAuth access token
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }
    
    public function getToken()
    {
        return $this->accessToken;
    }
    
    public function setToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }
    
    /**
     * Authenticate the request object. Apply the access_token to the URL
     *
     * TODO: implement
     * 
     * @param Request       $request        Request object to authenticate
     */
    public function authenticate(\Buzz\Message\Request $request)
    {
        $url = $request->getUrl();
        $prefix = (strpos($url, '?') > 0) ? '&' : '?';
        
        $request->fromUrl($url . $prefix . 'access_token=' . $this->accessToken);
        
        return $request;
    }
}