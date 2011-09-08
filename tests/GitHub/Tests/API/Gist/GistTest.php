<?php

namespace GitHub\Tests\API\Gist;

use GitHub\API\Api;
use GitHub\API\Gist\Gist;
use GitHub\Tests\API\ApiTest;

class GistTest extends ApiTest
{
    public function testAllAuthenticated()
    {
        $transportMock = $this->getTransportMock();

        $expectedResults = $this->getResultGists();
        $expectedResults['status'] = Api::HTTP_STATUS_OK;

        $transportMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($expectedResults));

        $gist = new Gist($transportMock);

        // Get authenticated
        $gist->setCredentials('username', 'password');
        $gist->login();
        $result = $gist->all();
        $this->assertEquals('https://api.github.com/gists/1', $result[0]['url']);
    }

    public function testAllUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $expectedResults = $this->getResultGists();
        $expectedResults['status'] = Api::HTTP_STATUS_OK;

        $transportMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($expectedResults));

        $gist = new Gist($transportMock);
        $result = $gist->all('dsyph3r');
        $this->assertEquals('https://api.github.com/gists/1', $result[0]['url']);
    }

    public function testAllUnauthenticatedPrivate()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->never())
            ->method('get')
            ->will($this->returnValue(array()));

        $gist = new Gist($transportMock);
        $this->setExpectedException('GitHub\API\ApiException');
        // Try starred gists without authentication
        $result = $gist->all('dsyph3r', Gist::GIST_TYPE_PRIVATE);
    }

    public function testGet()
    {
        $transportMock = $this->getTransportMock();

        $expectedResults = $this->getResultGist();
        $expectedResults['status'] = Api::HTTP_STATUS_OK;

        $transportMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($expectedResults));

        $gist = new Gist($transportMock);

        // Get authenticated
        $gist->setCredentials('username', 'password');
        $gist->login();
        $result = $gist->get(1);
        $this->assertEquals('https://api.github.com/gists/1', $result['url']);
    }

    public function testCreateSuccess()
    {
        $transportMock = $this->getTransportMock();

        $files        = array('file1.txt' => array('content' => 'File 1 contents'));
        $gistResult   = array(
          'url'   => 'https://api.github.com/gists/200',
          'files' => $files
        );

        // Setup exepected result - Need to set HTTP status also
        $expectedResults = $this->getResultGist($gistResult);
        $expectedResults['status'] = Api::HTTP_STATUS_CREATED;

        $transportMock->expects($this->once())
            ->method('post')
            ->will($this->returnValue($expectedResults));

        $gist = new Gist($transportMock);

        // Get authenticated
        $gist->setCredentials('username', 'password');
        $gist->login();
        $result = $gist->create($files, true, "description");
        $this->assertEquals('File 1 contents', $result['files']['file1.txt']['content']);
    }

    public function testCreateUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $files = array('file1.txt' => array('content' => 'File 1 contents'));

        $transportMock->expects($this->once())
            ->method('post')
            ->will($this->returnValue($this->getResultUnauthorized()));

        $gist = new Gist($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $gist->create($files, true, "description");
    }

    public function testUpdateSuccess()
    {
        $transportMock = $this->getTransportMock();

        $files        = array('file1.txt' => array('content' => 'File 1 contents updated'));
        $gistResult   = array(
          'description'   => 'description update',
          'files'         => $files
        );

        // Setup exepected result - Need to set HTTP status also
        $expectedResults = $this->getResultGist($gistResult);
        $expectedResults['status'] = Api::HTTP_STATUS_OK;

        $transportMock->expects($this->once())
            ->method('patch')
            ->will($this->returnValue($expectedResults));

        $gist = new Gist($transportMock);

        // Get authenticated
        $gist->setCredentials('username', 'password');
        $gist->login();
        $result = $gist->update(1, $files, "description update");
        $this->assertEquals('description update', $result['description']);
    }

    public function testUpdateUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $files = array('file1.txt' => array('content' => 'File 1 contents'));

        $transportMock->expects($this->once())
            ->method('patch')
            ->will($this->returnValue($this->getResultUnauthorized()));

        $gist = new Gist($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $gist->update(1, $files, "description update");
    }

    public function testStarAuthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
             ->method('put')
             ->will($this->returnValue(array('status' => Api::HTTP_STATUS_NO_CONTENT)));

        $gist = new Gist($transportMock);
        // Get authenticated
        $gist->setCredentials('username', 'password');
        $gist->login();
        $this->assertTrue($gist->star(1));
    }

    public function testStarUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
             ->method('put')
             ->will($this->returnValue($this->getResultUnauthorized()));

        $gist = new Gist($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $gist->star(1);
    }

    public function testUnstarAuthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
             ->method('delete')
             ->will($this->returnValue(array('status' => Api::HTTP_STATUS_NO_CONTENT)));

        $gist = new Gist($transportMock);
        // Get authenticated
        $gist->setCredentials('username', 'password');
        $gist->login();
        $this->assertTrue($gist->unstar(1));
    }

    public function testUnstarUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
             ->method('delete')
             ->will($this->returnValue($this->getResultUnauthorized()));

        $gist = new Gist($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $gist->unstar(1);
    }

    public function testIsStarredAuthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
             ->method('get')
             ->will($this->returnValue(array('status' => Api::HTTP_STATUS_NO_CONTENT)));

        $gist = new Gist($transportMock);
        // Get authenticated
        $gist->setCredentials('username', 'password');
        $gist->login();
        $this->assertTrue($gist->isStarred(1));
    }

    public function testIsStarredUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
             ->method('get')
             ->will($this->returnValue($this->getResultUnauthorized()));

        $gist = new Gist($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $gist->isStarred(1);
    }

    public function testForkAuthenticated()
    {
        $transportMock = $this->getTransportMock();

        // Setup exepected result - Need to set HTTP status also
        $expectedResults = $this->getResultGist();
        $expectedResults['status'] = Api::HTTP_STATUS_CREATED;

        $transportMock->expects($this->once())
            ->method('post')
            ->will($this->returnValue($expectedResults));

        $gist = new Gist($transportMock);

        // Get authenticated
        $gist->setCredentials('username', 'password');
        $gist->login();
        $result = $gist->fork(1);
        $this->assertEquals('ring.erl', $result['files']['ring.erl']['filename']);
    }

    public function testForkUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('post')
            ->will($this->returnValue($this->getResultUnauthorized()));

        $gist = new Gist($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $gist->fork(1);
    }

    public function testDeleteAuthenticated()
    {
        $transportMock = $this->getTransportMock();

        // Setup exepected result - Need to set HTTP status also
        $expectedResults = array();
        $expectedResults['status'] = Api::HTTP_STATUS_NO_CONTENT;

        $transportMock->expects($this->once())
            ->method('delete')
            ->will($this->returnValue($expectedResults));

        $gist = new Gist($transportMock);

        // Get authenticated
        $gist->setCredentials('username', 'password');
        $gist->login();
        $this->assertTrue($gist->delete(1));
    }

    public function testDeleteUnauthenticated()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->expects($this->once())
            ->method('delete')
            ->will($this->returnValue($this->getResultUnauthorized()));

        $gist = new Gist($transportMock);
        $this->setExpectedException('GitHub\API\AuthenticationException');
        // Try without authentication
        $result = $gist->delete(1);
    }

    public function testComments()
    {
        $gist = new Gist();
        $this->assertInstanceOf('GitHub\API\Gist\Comment', $gist->comments());
    }

    private function getResultGists($additionalGists = array())
    {
        $gists = array(
            array(
                "url"           => "https://api.github.com/gists/1",
                "id"            => "1",
                "description"   => "description of gist",
                "public"        => true,
                "user"          => array(
                    "login"         => "octocat",
                    "id"            => 1,
                    "gravatar_url"  => "https://github.com/images/error/octocat_happy.gif",
                    "url"           => "https://api.github.com/users/octocat"
                ),
                "files"         => array(
                    "ring.erl"      => array(
                        "size"          => 932,
                        "filename"      => "ring.erl",
                        "raw_url"       => "https://gist.github.com/raw/365370/8c4d2d43d178df44f4c03a7f2ac0ff512853564e/ring.erl",
                        "content"       => "contents of gist"
                    )
                ),
                "comments"      => 0,
                "git_pull_url"  => "git://gist.github.com/1.git",
                "git_push_url"  => "git@gist.github.com:1.git",
                "created_at"    => "2010-04-14T02:15:15Z"
            ),
        );

        return array('data' => array_merge($gists, $additionalGists));
    }

    private function getResultGist($details = array())
    {
        $gists = $this->getResultGists();
        $gist  = $gists['data'][0];

        return array('data' => array_merge($gist, $details));
    }
}
