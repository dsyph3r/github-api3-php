<?php

namespace GitHub\API\Repo;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Repository commits
 *
 * @link      http://developer.github.com/v3/repos/commits/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Commit extends Api
{
    /**
     * Commit comments
     * @var CommitComment
     */
    protected $comments   = null;
 
    /**
     * List commits for repo
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/commits/#list-commits-on-a-repository
     *
     * @param   string  $username         GitHub username
     * @param   string  $repo             Repo name
     * @param   string  $sha              Sha or branch to start listing commits from
     * @param   string  $path             Only commits containing this file path will be returned
     * @return  array                     List of commit(s) or FALSE if the request
     *                                    failed
     */
    public function all($username, $repo, $sha = null, $path = null)
    {
        $params = array(
           'sha'   => $sha,
           'path'  => $path
        );
        
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/commits", $this->buildParams($params))
        );
    }
 
    /**
     * Get a commit for a repo
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/commits/#get-a-single-commit
     *
     * @param   string  $username         GitHub username
     * @param   string  $repo             Repo name
     * @param   string  $sha              Sha of commit
     * @return  array|bool                Details of the commit or FALSE if the request failed
     */
    public function get($username, $repo, $sha)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/commits/$sha")
        );
    }
 
    /**
     * Provides access to commit comment operations
     *
     * @return  CommitComment
     */
    public function comments()
    {
        if (null === $this->comments)
          $this->comments = new CommitComment($this->getTransport());
    
        return $this->comments;
    }
}
