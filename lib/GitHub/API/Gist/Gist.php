<?php

namespace GitHub\API\Gist;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Gists
 *
 * @link      http://developer.github.com/v3/gists/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Gist extends Api
{
    /**
     * Constants for available Gist types
     */
    const GIST_TYPE_PRIVATE     = 'private';
    const GIST_TYPE_PUBLIC      = 'public';
    const GIST_TYPE_STARRED     = 'starred';

    /**
     * Gist comments
     *
     * @var Comment
     */
    protected $comment  = null;

    /**
     * List gists
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/gists/#list-gists
     *
     * @param   string  $username         Users GitHub username
     * @param   string  $gistType         Gist type GIST_TYPE_PUBLIC|GIST_TYPE_STARRED
     * @param   int     $page             Paginated page to get
     * @param   int     $pageSize         Size of paginated page. Max 100
     * @return  array|bool                List of gists or FALSE if request failed
     */
    public function all($username = null, $gistType = self::GIST_TYPE_PUBLIC, $page = 1, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        if (is_null($username))
        {
            if (false === $this->isAuthenticated())
                $url = 'gists';    // Get all public gists
            else
            {
                if (self::GIST_TYPE_STARRED === $gistType)
                    $url = 'gists/starred';
                else
                    $url = "gists";
            }
        }
        else
        {
            if (self::GIST_TYPE_PUBLIC !== $gistType)
                throw new ApiException("Unsupported [$gistType] gistType option. Unathenticated user requests can only list public gists [ApiGist::GIST_TYPE_PUBLIC]");

            $url ="users/$username/gists";
        }

        return $this->processResponse(
            $this->requestGet($url, $this->buildPageParams($page, $pageSize))
        );
    }

    /**
     * Get a gist
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/gists/#get-a-single-gist
     *
     * @param   int     $id               ID of gist
     * @return  array|bool                Returns the gist or FALSE if the gist
     *                                    was not found
     */
    public function get($id)
    {
        return $this->processResponse($this->requestGet("gists/$id"));
    }

    /**
     * Add gist
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/gists/#create-a-gist
     *
     * @param   array  $files             List of files that make up the gist
     * @param   bool   $public            TRUE indicates gist is public
     * @param   string $description       Description of the gist
     * @return  array|bool                Details of the created gist or FALSE if
     *                                    gist failed to created
     */
    public function create($files, $public, $description = '')
    {
        $details = array(
          'description' => $description,
          'public'      => $public,
          'files'       => $files
        );

        return $this->processResponse($this->requestPost('gists', $details));
    }

    /**
     * Update a gist
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/gists/#edit-a-gist
     *
     * @param   int    $id                ID of gist
     * @param   array  $files             List of files that make up the gist
     * @param   string $description       Description of the gist
     * @return  array                     Details of the updated gist or FALSE
     *                                    if the gist failed to update
     */
    public function update($id, $files = array(), $description = '')
    {
        $details = array(
          'description' => $description,
          'files'       => $files
        );

        return $this->processResponse(
            $this->requestPatch("gists/$id", $this->buildParams($details))
        );
    }

    /**
     * Star a gist
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/gists/#star-a-gist
     *
     * @param   int    $id                ID of gist
     * @return  bool                      Returns TRUE is gist was starred
     */
    public function star($id)
    {
        return $this->processResponse($this->requestPut("gists/$id/star"));
    }

    /**
     * Unstar a gist
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/gists/#unstar-a-gist
     *
     * @param   int    $id                ID of gist
     * @return  bool                      Returns TRUE is gist was unstarred
     */
    public function unstar($id)
    {
        return $this->processResponse($this->requestDelete("gists/$id/star"));
    }

    /**
     * Check if a gist is starred
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/gists/#check-if-a-gist-is-starred
     *
     * @param   int    $id                ID of gist
     * @param   bool                      Returns TRUE is gist is starred
     */
    public function isStarred($id)
    {
        return $this->processResponse($this->requestGet("gists/$id/star"));
    }

    /**
     * Fork a gist
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/gists/#fork-a-gist
     *
     * @param   int    $id                ID of gist
     * @param   bool                      Details of the forked gist
     */
    public function fork($id)
    {
        return $this->processResponse($this->requestPost("gists/$id/fork"));
    }

    /**
     * Delete a gist
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/gists/#delete-a-gist
     *
     * @param   int    $id                ID of gist
     * @param   bool                      Returns TRUE if gist was deleted
     */
    public function delete($id)
    {
        return $this->processResponse($this->requestDelete("gists/$id"));
    }

    /**
     * Provides access to gist comment operations
     *
     * @return  Comment
     */
    public function comments()
    {
        if (null === $this->comment)
            $this->comment = new Comment($this->getTransport());

        return $this->comment;
    }
}
