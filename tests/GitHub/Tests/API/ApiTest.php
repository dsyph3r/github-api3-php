<?php

namespace GitHub\Tests\API;

abstract class ApiTest extends \PHPUnit_Framework_TestCase
{
    protected function getTransportMock() {
        return $this->getMock('GitHub\API\Transport', array('get', 'post', 'put', 'patch', 'delete'));
    }
}
