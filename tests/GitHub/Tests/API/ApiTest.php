<?php

namespace GitHub\Tests\API;

abstract class ApiTest extends \PHPUnit_Framework_TestCase
{
    protected function getTransportMock() {
        return $this->getMock('Network\Curl\Curl', array('get', 'post', 'put', 'patch', 'delete'));
    }
}
