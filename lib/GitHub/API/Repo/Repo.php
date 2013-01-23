<?php

namespace GitHub\API\Repo;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Repository
 *
 * @link      http://developer.github.com/v3/repos/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
abstract class Repo extends Api
{
    const REPO_TYPE_ALL     = 'all';
    const REPO_TYPE_PUBLIC  = 'public';
    const REPO_TYPE_PRIVATE = 'private';
    const REPO_TYPE_MEMBER  = 'member';

    /**
     * Allowed repository types. Should be configure in parent class
     */
    protected $allowedRepoTypes = array();

    /**
    * Repo commits
    *
    * @var Commit
    */
    protected $commit   = null;

    /**
     * Repo collaborators
     *
     * @var Collaborator
     */
    protected $collaborator   = null;

    /**
     * Repo downloads
     *
     * @var Download
     */
    protected $download   = null;

    /**
     * Repo forks
     *
     * @var Fork
     */
    protected $fork   = null;

    /**
     * Repo keys
     *
     * @var Key
     */
    protected $key   = null;

    /**
     * Repo events
     * 
     * @var Event
     */
    protected $event = null;

    /**
     * Get information about a repository. Access to private repositories
     * require authentication by repository owner.
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/#get
     *
     * @param   string  $username         User GitHub username of repo owner
     * @param   string  $repo             Repo name
     * @return  array|bool                Details of the repo or FALSE if the request
     *                                    failed
     */
    public function get($username, $repo)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo")
        );
    }

    /**
     * Create repo for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/#create
     *
     * @param   string        $url        API resource URL
     * @param   string        $repo       Name of repository
     * @param   array         $details    Optional details relating to repository. See link for
     *                                    full list of options
     * @return  array|bool                Return created repository or FALSE if operation
     *                                    to create repository failed.
     */
    protected function _create($url, $repo, $details = array())
    {
        // Add repo name into details
        $details['name'] = $repo;
        
        return $this->processResponse(
            $this->requestPost($url, $this->buildParams($details))
        );
    }

    /**
     * Edit repo for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/#edit
     *
     * @param   string        $username   GitHub username of repo owner
     * @param   string        $repo       Name of repository
     * @param   array         $details    Optional details relating to repository. See link for
     *                                    full list of options
     * @return  array|bool                Return updated repository or FALSE if operation
     *                                    to udpated repository failed.
     */
    public function edit($username, $repo, $details = array())
    {
        // Add repo name into details
        $details['name'] = $repo;
        
        return $this->processResponse(
            $this->requestPatch("repos/$username/$repo", $this->buildParams($details))
        );
    }

    /**
     * List repository contributors
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/#list-contributors
     *
     * @param   string        $username           GitHub username of repo owner
     * @param   string        $repo               Name of repository
     * @param   array         $includeAnonymous   Set TRUE to include annonymous contributors
     * @return  array|bool                        Returns the list of contributors or FALSE
     *                                            if the request failed
     */
    public function contributors($username, $repo, $includeAnonymous = true)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/contributors", array('anon' => $includeAnonymous))
        );
    }

    /**
     * List repository languanges
     *
     * Authentication Required: false|true
     * 
     * @link http://developer.github.com/v3/repos/#list-languages
     *
     * @param   string        $username           GitHub username of repo owner
     * @param   string        $repo               Name of repository
     * @return  array|bool                        Returns the list of languages or FALSE
     *                                            if the request failed
     */
    public function languages($username, $repo)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/languages")
        );
    }

    /**
     * List repository teams
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/#list-languages
     *
     * @param   string        $username           GitHub username of repo owner
     * @param   string        $repo               Name of repository
     * @return  array|bool                        Returns the list of teams or FALSE
     *                                            if the request failed
     */
    public function teams($username, $repo)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/teams")
        );
    }

    /**
     * List repository tags
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/#list-languages
     *
     * @param   string        $username           GitHub username of repo owner
     * @param   string        $repo               Name of repository
     * @return  array|bool                        Returns the list of tags or FALSE
     *                                            if the request failed
     */
    public function tags($username, $repo)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/tags")
        );
    }

    /**
     * List repository branches
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/#list-languages
     *
     * @param   string        $username           GitHub username of repo owner
     * @param   string        $repo               Name of repository
     * @return  array|bool                        Returns the list of branches or FALSE
     *                                            if the request failed
     */
    public function branches($username, $repo)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/branches")
        );
    }

    /**
     * List repository watches
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/watching/#list-watchers
     *
     * @param   string        $username           GitHub username of repo owner
     * @param   string        $repo               Name of repository
     * @param   int           $page               Paginated page to get
     * @param   int           $pageSize           Size of paginated page. Max 100
     * @return  array|bool                        Returns the list of watchers or FALSE
     *                                            if the request failed
     */
    public function watchers($username, $repo, $page = 1, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/watchers", $this->buildPageParams($page, $pageSize))
        );
    }

    /**
     * List repository being watched
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/watching/#list-repos-being-watched
     *
     * @param   string        $username           GitHub username
     * @return  array|bool                        Returns the list of watched repos or FALSE
     *                                            if the request failed
     */
    public function watched($username = null)
    {
        if (is_null($username))
            $url = 'user/watched';
        else
            $url = "users/$username/watched";

        return $this->processResponse(
            $this->requestGet($url)
        );
    }

    /**
     * Check if a repository is being watched
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/watching/#check-if-you-are-watching-a-repo
     *
     * @param   string        $username           GitHub username
     * @param   string        $repo               Name of repository
     * @return  array|bool                        Returns TRUE if the repo is being watched
     */
    public function isWatched($username, $repo)
    {
        if (is_null($username))
            $url = 'user/watched';
        else
            $url = "users/$username/watched";

        return $this->processResponse(
            $this->requestGet($url)
        );
    }

    /**
     * Watch a repository
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/watching/#watch-a-repo
     *
     * @param   string        $username           GitHub username
     * @param   string        $repo               Name of repository
     * @return  array|bool                        Returns TRUE if the repo is being watched
     */
    public function watch($username, $repo)
    {
        return $this->processResponse(
            $this->requestPut("user/watched/$username/$repo")
        );
    }

    /**
     * Stop watching a repository
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/watching/#stop-watching-a-repo
     *
     * @param   string        $username           GitHub username
     * @param   string        $repo               Name of repository
     * @return  array|bool                        Returns TRUE if the repo is being unwatched
     */
    public function unwatch($username, $repo)
    {
        return $this->processResponse(
            $this->requestDelete("user/watched/$username/$repo")
        );
    }

    /**
     * Provides access to repo commit operations
     *
     * @return  Commit
     */
    public function commits()
    {
        if (null === $this->commit)
            $this->commit = new Commit($this->getTransport());

        return $this->commit;
    }

    /**
     * Provides access to repo collaborator operations
     *
     * @return  Collaborator
     */
    public function collaborators()
    {
        if (null === $this->collaborator)
            $this->collaborator = new Collaborator($this->getTransport());

        return $this->collaborator;
    }

    /**
     * Provides access to repo download operations
     *
     * @return  Download
     */
    public function downloads()
    {
        if (null === $this->download)
            $this->download = new Download($this->getTransport());

        return $this->download;
    }

    /**
     * Provides access to repo fork operations
     *
     * @return  Fork
     */
    public function forks()
    {
        if (null === $this->fork)
            $this->fork = new Fork($this->getTransport());

        return $this->fork;
    }

    /**
     * Provides access to repo key operations
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
     * Provides access to repo event operations
     *
     * @return  Event
     */
    public function events()
    {
        if (null === $this->event)
            $this->event = new Event($this->getTransport());

        return $this->event;
    }

    /**
     * List repository stargazers.
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/activity/starring/#list-stargazers
     *
     * @param   string        $username           GitHub username of repo owner
     * @param   string        $repo               Name of repository
     * @param   int           $page               Paginated page to get
     * @param   int           $pageSize           Size of paginated page. Max 100
     * @return  array|bool                        Returns the list of stargazers or FALSE
     *                                            if the request failed
     */
    public function stargazers($username, $repo, $page = 1, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/stargazers", $this->buildPageParams($page, $pageSize))
        );
    }

    /**
     * List repositories being starred by user.
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/activity/starring/#list-repositories-being-starred
     *
     * @param   string        $username           GitHub username
     * @return  array|bool                        Returns the list of starred repos or FALSE
     *                                            if the request failed
     */
    public function starred($username = null)
    {
        if (is_null($username))
            $url = 'user/starred';
        else
            $url = "users/$username/starred";

        return $this->processResponse(
            $this->requestGet($url)
        );
    }

    /**
     * Check if a repository is being starred by authenticated user.
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/activity/starring/#check-if-you-are-starring-a-repository
     *
     * @param   string        $username           GitHub username
     * @param   string        $repo               Name of repository
     * @return  array|bool                        Returns TRUE if the repo is being starred
     */
    public function isStarred($username, $repo)
    {
        $url = "users/starred/$username/$repo";

        return $this->processResponse(
            $this->requestGet($url)
        );
    }

    /**
     * Star a repository.
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/activity/starring/#star-a-repository
     *
     * @param   string        $username           GitHub username
     * @param   string        $repo               Name of repository
     * @return  array|bool                        Returns TRUE if the repo is being starred
     */
    public function star($username, $repo)
    {
        return $this->processResponse(
            $this->requestPut("user/starred/$username/$repo")
        );
    }

    /**
     * Unstar a repository.
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/activity/starring/#unstar-a-repository
     *
     * @param   string        $username           GitHub username
     * @param   string        $repo               Name of repository
     * @return  array|bool                        Returns TRUE if the repo is being unstarred
     */
    public function unstar($username, $repo)
    {
        return $this->processResponse(
            $this->requestDelete("user/starred/$username/$repo")
        );
    }
}
