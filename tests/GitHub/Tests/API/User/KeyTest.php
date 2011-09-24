<?php

namespace GitHub\Tests\API\User;

use GitHub\API\Api;
use GitHub\API\User\Key;
use GitHub\Tests\API\ApiTest;
use GitHub\API\Authentication;

class KeyTest extends ApiTest
{
    public function testAllAuthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultKeys()));

        $key = new Key($transportMock);

        // Get authenticated
        $key->setCredentials(new Authentication\Basic('username', 'password'));
        $key->login();
        $result = $key->all();
        $this->assertEquals('octocat@octomac', $result[0]['title']);
    }

    public function testAllUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
             ->method('send')
             ->will($this->returnValue($this->getResultUnauthorized()));

        $key = new Key($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $key->all();
    }

    public function testGetAuthenticated()
    {
        $transportMock = $this->getTransportMock();

        $expectedResult = $this->getResultKey();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($expectedResult));

        $key = new Key($transportMock);

        // Get authenticated
        $key->setCredentials(new Authentication\Basic('username', 'password'));
        $key->login();
        $result = $key->get(1);
        $this->assertEquals('octocat@octomac', $result['title']);
    }

    public function testGetUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultUnauthorized()));

        $key = new Key($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $key->get(1);
    }

    public function testCreateSuccess()
    {
        $transportMock = $this->getTransportMock();

        $create = array('title' => 'New Key Title', 'key' => 'ssh-rsa ABC');

        $expectedResults = $this->getResultKeys(array($create));
        $expectedResults->addHeader('HTTP/1.1 201 Created');

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($expectedResults));

        $key = new Key($transportMock);

        // Get authenticated
        $key->setCredentials(new Authentication\Basic('username', 'password'));
        $key->login();
        $result = $key->create($create['title'], $create['key']);
        $this->assertEquals('New Key Title', $result[2]['title']);
    }

    public function testCreateUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        // Should never try to access the API - expecting Exception
        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultUnauthorized()));

        $key = new Key($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $key->create('title', 'key');
    }

    public function testUpdateSuccess()
    {
        $transportMock = $this->getTransportMock();

        $update = array('title' => 'Update Key Title', 'key' => 'ssh-rsa ABCDEF');

        $expectedResults = $this->getResultKey($update);

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($expectedResults));

        $key = new Key($transportMock);

        // Get authenticated
        $key->setCredentials(new Authentication\Basic('username', 'password'));
        $key->login();
        $result = $key->update($update['title'], $update['key']);
        $this->assertEquals('Update Key Title', $result['title']);
    }

    public function testUpdateUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultUnauthorized()));

        $key = new Key($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $key->update('title', 'key');
    }

    public function testDeleteSuccess()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultNoContent()));

        $key = new Key($transportMock);

        // Get authenticated
        $key->setCredentials(new Authentication\Basic('username', 'password'));
        $key->login();
        $result = $key->delete(1);
        $this->assertTrue($result);
    }

    public function testDeleteFail()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultNotFound()));

        $key = new Key($transportMock);

        // Get authenticated
        $key->setCredentials(new Authentication\Basic('username', 'password'));
        $key->login();
        $result = $key->delete(1);
        $this->assertFalse($result);
    }

    public function testDeleteUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultUnauthorized()));

        $key = new Key($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $key->delete(1);
    }

    private function getResultKeys($additionalKeys = array())
    {
        $keys = array(
            array(
                'url'       =>'https://api.github.com/user/keys/1',
                'id'        => 1,
                'title'     => 'octocat@octomac',
                'key'       => 'ssh-rsa AAA...'
            ),
            array(
                'url'       =>'https://api.github.com/user/keys/2',
                'id'        => 2,
                'title'     => 'octocat@octobed',
                'key'       => 'ssh-rsa BBB...'
            )
        );
        
        $response = new \Buzz\Message\Response;
        $response->setContent(array_merge($keys, $additionalKeys));
        $response->addHeader('HTTP/1.1 200 OK');
        
        return $response;
    }

    private function getResultKey($details = array())
    {
        $key = array(
            'url'       =>'https://api.github.com/user/keys/1',
            'id'        => 1,
            'title'     => 'octocat@octomac',
            'key'       => 'ssh-rsa AAA...'
        );
        
        $response = new \Buzz\Message\Response;
        $response->setContent(array_merge($key, $details));
        $response->addHeader('HTTP/1.1 200 OK');
        
        return $response;
    }
}
