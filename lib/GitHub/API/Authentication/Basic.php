<?php

namespace GitHub\API\Authentication;

/**
 * Basic HTTP Authentication
 *
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Basic implements AuthenticationInterface
{
    /**
     * Username
     *
     * @var string
     */
    private $username;
    
    /**
     * Password
     *
     * @var string
     */
    private $password;
    
    /**
     * Create with basic HTTP credentials set
     *
     * @param   string      $username        Username
     * @param   string      $password        Password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    /**
     * Authenticate the request object. Apply basic HTTP headers
     * 
     * @param Request       $request        Request object to authenticate
     */
    public function authenticate(\Buzz\Message\Request $request)
    {
        $encoded = base64_encode($this->username . ':' . $this->password);
        $request->addHeader('Authorization: Basic ' . $encoded);
        
        return $request;
    }
}