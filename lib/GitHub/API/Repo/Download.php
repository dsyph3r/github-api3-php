<?php

namespace GitHub\API\Repo;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Repository downloads
 *
 * @link      http://developer.github.com/v3/repos/downloads/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Download extends Api
{
    /**
     * List downloads for repo
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/downloads/#list-downloads-for-a-repository
     *
     * @param   string  $username         GitHub username
     * @param   string  $repo             Repo name
     * @param   string  $sha              Sha or branch to start listing downloads from
     * @param   string  $path             Only downloads containing this file path will be returned
     * @return  array                     List of downloads(s) or FALSE if the request
     *                                    failed
     */
    public function all($username, $repo, $sha = null, $path = null)
    {
        // Optional filters
        $params = array(
            'sha'   => $sha,
            'path'  => $path
        );
      
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/downloads", $this->buildParams($params))
        );
    }
  
    /**
     * Get a download for a repo
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/downloads/#get-a-single-download
     *
     * @param   string  $username         GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of download
     * @return  array|bool                Details of the download or FALSE if the request failed
     */
    public function get($username, $repo, $id)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/downloads/$id")
        );
    }
  
    /**
     * Create a new download for repo
     *
     * Authentication Required: true
     *
     * @todo Implement - see link for API implementation details
     *
     * @link http://developer.github.com/v3/repos/downloads/#create-a-new-download-part-1-create-the-resource
     */
    public function create() {
        throw new ApiException("[create] not yet implemented");
    }
}
