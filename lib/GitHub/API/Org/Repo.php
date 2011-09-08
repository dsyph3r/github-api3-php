<?php

namespace GitHub\API\Org;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Organizations Repository
 *
 * @link      http://developer.github.com/v3/repos/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Repo extends \GitHub\API\Repo\Repo
{
    /**
     * Allowed repository types for Organizations
     */
    protected $allowedRepoTypes = array(
        self::REPO_TYPE_ALL, self::REPO_TYPE_PUBLIC, self::REPO_TYPE_PRIVATE
    );
  
    /**
     * List repositories for an organization
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/#list
     *
     * @param   string    $organization     Organization
     * @param   string    $repoType         The type of repository to get. Must be in $allowedRepoTypes
     * @param   int       $page             Paginated page to get
     * @param   int       $pageSize         Size of paginated page. Max 100
     * @return  array|bool                  Organization repositories or FALSE if
     *                                      request failed
     */
    public function all($organization, $repoType = self::REPO_TYPE_ALL, $page = 1, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        if (false === in_array($repoType, $this->allowedRepoTypes))
          throw new Exception("Unsupported $repoType option. Available types are " . join(", ", $this->allowedRepoTypes));
    
        if (false === $this->isAuthenticated() && self::REPO_TYPE_PUBLIC !== $repoType)
          throw new Exception("Unsupported $repoType option. Unathenticated user requests can only list public organization repositories [ApiRepo::REPO_TYPE_PUBLIC]");
    
        return $this->processResponse(
            $this->requestGet("orgs/$organization/repos", $params)
        );
    }
  
    /**
     * Create repo for authenticated user in origization
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/#create
     *
     * @param   string        $organization   Organization
     * @param   string        $repo           Name of repository
     * @param   array         $details        Optional details relating to repository. See link for
     *                                        full list of options
     * @return  array|bool                    Return created repository or FALSE if operation
     *                                        to create repository failed.
     */
    public function create($organization, $repo, $details = array())
    {
        return $this->_create("orgs/$organization/repos", $repo, $details);
    }
}
