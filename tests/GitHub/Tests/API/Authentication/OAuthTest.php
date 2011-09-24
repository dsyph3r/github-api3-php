<?php

namespace GitHub\Tests\Authentication;

use GitHub\API\Authentication\OAuth;
use Buzz\Message\Request;

class OAuthTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthenticate()
    {
        $basic = new OAuth('KFGDSYGSDHKKGFSFN');
        
        // Create request, authenticate it
        $request = new Request();
        $request->fromUrl('http://test.com?name=dsyph3r');
        $request = $basic->authenticate($request);
        
        // Check access_token param is set correctly
        $url = parse_url($request->getUrl());
        $params = explode('&', $url['query']);
        
        $this->assertContains('access_token=KFGDSYGSDHKKGFSFN', $params);        
    }
}
