<?php

namespace GitHub\API\Repo;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Repository Fork
 *
 * @link      http://developer.github.com/v3/repos/forks/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Fork extends Api
{
    /**
     * List forks for repo
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/forks/#list-forks
     *
     * @param   string  $username         GitHub username of repo owner
     * @param   string  $repo             Repo name
     * @param   int     $page             Paginated page to get
     * @param   int     $pageSize         Size of paginated page. Max 100
     * @return  array                     List of fork(s) or FALSE if the request
     *                                    failed
     */
    public function all($username, $repo, $page = 1, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/forks", $this->buildPageParams($page, $pageSize))
        );;
    }
  
    /**
     * Create a repository fork for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/forks/#create-a-fork
     *
     * @param   string  $organization     If provided repository will be forked
     *                                    into this organization
     * @return  array|bool                Returns the forked repository or FALSE if repository
     *                                    failed to fork
     */
    protected function create($organization = null)
    {
        return $this->processResponse(
            $this->requestPost("repos/$username/$repo/forks", $this->buildParams(array('org' => $organization)))
        );
    }
}
