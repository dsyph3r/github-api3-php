<?php

namespace GitHub\Tests\API\User;

use GitHub\API\Api;
use GitHub\API\User\Email;
use GitHub\Tests\API\ApiTest;
use GitHub\API\Authentication;

class EmailTest extends ApiTest
{
    public function testAllAuthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultEmails()));

        $email = new Email($transportMock);

        // Get authenticated
        $email->setCredentials(new Authentication\Basic('username', 'password'));
        $email->login();
        $result = $email->all();
        $this->assertEquals('d.syph.3r@gmail.com', $result[0]);
    }

    public function testAllUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
             ->method('send')
             ->will($this->returnValue($this->getResultUnauthorized()));

        $email = new Email($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $email->all();
    }

    public function testCreateSuccess()
    {
        $transportMock = $this->getTransportMock();

        // Setup exepected result - Need to set HTTP status also
        $expectedResults = $this->getResultEmails(array('addme@test.com'));
        $expectedResults->addHeader('HTTP/1.1 201 Created');

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($expectedResults));

        $email = new Email($transportMock);

        // Get authenticated
        $email->setCredentials(new Authentication\Basic('username', 'password'));
        $email->login();
        $result = $email->create('addme@test.com');
        $this->assertTrue(in_array('addme@test.com',  $result));
    }

    public function testCreateUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultUnauthorized()));

        $email = new Email($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $email->create('adme@test.com');
    }

    public function testDeleteSuccess()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultNoContent()));

        $email = new Email($transportMock);

        // Get authenticated
        $email->setCredentials(new Authentication\Basic('username', 'password'));
        $email->login();
        $result = $email->delete('deleteme@test.com');
        $this->assertTrue($result);
    }

    public function testDeleteFail()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultNotFound()));

        $email = new Email($transportMock);

        // Get authenticated
        $email->setCredentials(new Authentication\Basic('username', 'password'));
        $email->login();
        $result = $email->delete('deleteme@test.com');
        $this->assertFalse($result);
    }

    public function testDeleteUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResultUnauthorized()));

        $email = new Email($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $email->delete('deleteme@test.com');
    }

    private function getResultEmails($additionalEmails = array())
    {
        $emails = array(
            'd.syph.3r@gmail.com',
            'octocat@github.com'
        );
        
        $response = new \Buzz\Message\Response;
        $response->setContent(array_merge($emails, $additionalEmails));
        $response->addHeader('HTTP/1.1 200 OK');
        
        return $response;
    }
}
