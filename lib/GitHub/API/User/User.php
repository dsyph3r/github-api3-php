<?php

namespace GitHub\API\User;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;
use GitHub\API\Gist\Gist;

/**
 * User
 *
 * @link      http://developer.github.com/v3/users/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class User extends Api
{
    /**
     * User emails
     *
     * @var Email
     */
    protected $email  = null;

    /**
     * User keys
     *
     * @var Key
     */
    protected $key    = null;

    /**
     * User repos
     *
     * @var Repo
     */
    protected $repo   = null;

    /**
     * User gists
     *
     * @var \GitHub\API\Gist\Gist
     */
    protected $gist   = null;

    /**
     * User issues
     *
     * @var Issue
     */
    protected $issue   = null;

    /**
     * Get information about a user by username
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/users/#get-a-single-user
     *
     * @param   string  $username         Users GitHub username. Omitting this option will
     *                                    return the authenticated user
     * @return  array|bool                Details of the user or FALSE if the user
     *                                    could not be found
     */
    public function get($username = null)
    {
        if (is_null($username))
            $url = 'user';
        else
            $url = "users/$username";

        return $this->processResponse($this->requestGet($url));
    }

    /**
     * Update the authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/#update-the-authenticated-user
     *
     * @param   array   $details          Array of key/value pairs of details to update
     * @return  array                     Updated details of the user
     */
    public function update(array $details)
    {
        return $this->processResponse($this->requestPatch('user', $details));
    }

    /**
     * List followers for a user. If $username is not provided the followers are
     * retrieved for the authenticated user
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/users/followers/#user-followers-api
     *
     * @param   string  $username         Username of user to get followers.
     *                                    Omitting this option will return results
     *                                    for authenticated user
     * @param   int     $page             Paginated page to get
     * @param   int     $pageSize         Size of paginated page. Max 100
     * @return  array|bool                List of users or FALSE if $username could
     *                                    not be found
     */
    public function followers($username = null, $page = 1, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        if (is_null($username))
            $url = 'user/followers';
        else
            $url = "users/$username/followers";

        return $this->processResponse(
            $this->requestGet($url, $this->buildPageParams($page, $pageSize))
        );
    }

    /**
     * List users a user is following. If $username is not provided the users are
     * retrieved for the authenticated user
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/users/followers/#list-users-following-another-user
     *
     * @param   string  $username         Username of user. Omitting this option will return results
     *                                    for authenticated user
     * @param   int     $page             Paginated page to get
     * @param   int     $pageSize         Size of paginated page. Max 100
     * @return  array|bool                List of users or FALSE if $username could
     *                                    not be found
     */
    public function following($username = null, $page = 1, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        if (is_null($username))
            $url = 'user/following';
        else
            $url = "users/$username/following";

        return $this->processResponse(
            $this->requestGet($url, $this->buildPageParams($page, $pageSize))
        );
    }

    /**
     * Check if authenticated user is following a user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/followers/#check-if-you-are-following-a-user
     *
     * @param   string  $username         Username of user
     * @return  bool                      Returns TRUE is user is following
     */
    public function isFollowing($username)
    {
        return $this->processResponse($this->requestGet("user/following/$username"));
    }

    /**
     * Follow a user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/followers/#follow-a-user
     *
     * @param   string  $username         Username of user
     * @return  bool                      Returns TRUE is user was followed
     */
    public function follow($username)
    {
        return $this->processResponse($this->requestPut("user/following/$username"));
    }

    /**
     * Unfollow a user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/followers/#unfollow-a-user
     *
     * @param   string  $username         Username of user
     * @return  bool                      Returns TRUE is user was unfollowed
     */
    public function unfollow($username)
    {
        return $this->processResponse($this->requestDelete("user/following/$username"));
    }

    /**
     * Provides access to user emails operations
     *
     * @return  Email
     */
    public function emails()
    {
        if (null === $this->email)
            $this->email = new Email($this->getTransport());

        return $this->email;
    }

    /**
     * Provides access to user keys operations
     *
     * @return  Key
     */
    public function keys()
    {
        if (null === $this->key)
            $this->key = new Key($this->getTransport());

        return $this->key;
    }

    /**
     * Provides access to user repository operations
     *
     * @return  Repo
     */
    public function repos()
    {
        if (null === $this->repo)
            $this->repo = new Repo($this->getTransport());

        return $this->repo;
    }

    /**
     * Provides access to user gist operations
     *
     * @return  \GitHub\API\Gist\Gist
     */
    public function gists()
    {
        if (null === $this->gist)
            $this->gist = new Gist($this->getTransport());

        return $this->gist;
    }

    /**
     * Provides access to user gist operations
     *
     * @return  Issues
     */
    public function issues()
    {
        if (null === $this->issue)
            $this->issue = new Issue($this->getTransport());

        return $this->issue;
    }
}
