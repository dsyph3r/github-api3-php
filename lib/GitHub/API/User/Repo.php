<?php

namespace GitHub\API\User;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * User Repository
 *
 * @link      http://developer.github.com/v3/repos/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Repo extends \GitHub\API\Repo\Repo
{
    /**
     * Allowed repository types for users
     */
    protected $allowedRepoTypes = array(
        self::REPO_TYPE_ALL, self::REPO_TYPE_PUBLIC, self::REPO_TYPE_PRIVATE, self::REPO_TYPE_MEMBER
    );

    /**
     * List repositories for user
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/#list
     *
     * @param   string  $username         GitHub username. Omitting this option will
     *                                    return the repos of the authenticated user
     * @param   string  $repoType         The type of repository to get. Must be in $allowedRepoTypes
     * @param   int     $page             Paginated page to get
     * @param   int     $pageSize         Size of paginated page. Max 100
     * @return  array                     Users repositories or FALSE if request
     *                                    failed
     */
    public function all($username = null, $repoType = self::REPO_TYPE_ALL, $page = 1, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        if (false === in_array($repoType, $this->allowedRepoTypes))
            throw new ApiException("Unsupported $repoType option. Available types are " . join(", ", $this->allowedRepoTypes));

        $params   = array_merge(array('type' => $repoType), $this->buildPageParams($page, $pageSize));

        if (is_null($username))
            $url = 'user/repos';
        else
        {
            if (self::REPO_TYPE_PUBLIC !== $repoType)
                throw new ApiException("Unsupported $repoType option. Unathenticated user requests can only list public repositories [ApiRepo::REPO_TYPE_PUBLIC]");

            $url = "users/$username/repos";
        }

        return $this->processResponse($this->requestGet($url, $params));
    }

    /**
     * Create repo for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/#create
     *
     * @param   string        $repo       Name of repository
     * @param   array         $details    Optional details relating to repository. See link for
     *                                    full list of options
     * @return  array|bool                Return created repository or FALSE if operation
     *                                    to create repository failed.
     */
    public function create($repo, $details = array())
    {
        return $this->_create('user/repos', $repo, $details);
    }
}
