<?php

namespace GitHub\Tests\Authentication;

use GitHub\API\Authentication\Basic;
use Buzz\Message\Request;

class BasicTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthenticate()
    {
        $basic = new Basic('username', 'password');
        
        // Create request, authenticate it
        $request = new Request();
        $request = $basic->authenticate($request);
        
        // Check headers are set correctly
        $header = $request->getHeader('Authorization');
        $this->assertNotNull($header);
        
        list(, $credentials)        = explode(' ', $header);
        list($username, $password)  = explode(':', base64_decode($credentials));
        
        $this->assertEquals('username', $username);
        $this->assertEquals('password', $password);
    }
}
