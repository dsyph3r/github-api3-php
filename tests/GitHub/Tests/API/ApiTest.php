<?php

namespace GitHub\Tests\API;

use GitHub\API\Api;

abstract class ApiTest extends \PHPUnit_Framework_TestCase
{
    protected function getTransportMock()
    {
        return $this->getMock('Network\Curl\Curl', array('get', 'post', 'put', 'patch', 'delete'));
    }

    protected function getResultUnauthorized()
    {
        return array(
            'status'    => Api::HTTP_STATUS_UNAUTHORIZED
        );
    }
}
