<?php

namespace GitHub\API\Authentication;

interface AuthenticationInterface
{
    public function authenticate(\Buzz\Message\Request $request);
}