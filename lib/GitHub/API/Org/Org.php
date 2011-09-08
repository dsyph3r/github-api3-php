<?php

namespace GitHub\API\Org;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Organization
 *
 * @link      http://developer.github.com/v3/orgs/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Org extends Api
{
    /**
     * Org repos
     * 
     * @var Repo
     */
    protected $repo  = null;
  
    /**
     * Provides access to originzation repo operations
     *
     * @return  Repo
     */
    public function repos()
    {
        if (null === $this->repo)
          $this->repo = new Repo($this->getTransport());
    
        return $this->repo;
    }
}
