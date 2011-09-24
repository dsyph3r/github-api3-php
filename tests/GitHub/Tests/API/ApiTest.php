<?php

namespace GitHub\Tests\API;

use GitHub\API\Api;

abstract class ApiTest extends \PHPUnit_Framework_TestCase
{
    protected function getTransportMock()
    {
        return $this->getMock('Buzz\Browser', array('send'));
    }

    protected function getResultUnauthorized()
    {
        $response = new \Buzz\Message\Response;
        $response->addHeader('HTTP/1.1 401 Unauthorized');
        
        return $response;
    }
    
    protected function getResultNoContent()
    {
        $response = new \Buzz\Message\Response;
        $response->addHeader('HTTP/1.1 204 No Content');
        
        return $response;
    }
    
    protected function getResultNotFound()
    {
        $response = new \Buzz\Message\Response;
        $response->addHeader('HTTP/1.1 404 Not Found');
        
        return $response;
    }
}
