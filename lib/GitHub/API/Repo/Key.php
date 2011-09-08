<?php

namespace GitHub\API\Repo;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Repository Key
 *
 * @link      http://developer.github.com/v3/repos/keys/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Key extends \GitHub\API\Key\Key
{
    /**
     * List deploy keys for repo
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/keys/#list
     *
     * @param   string  $username         GitHub username of repo owner
     * @param   string  $repo             Repo name
     * @return  array                     List of key(s)
     */
    public function all($usename, $repo)
    {
        return $this->_all("repos/$username/$repo/keys");
    }
  
    /**
     * Get deploy key for repo
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/keys/#get
     *
     * @param   string  $username         GitHub username of repo owner
     * @param   string  $repo             Repo name
     * @param   string  $id               ID of deplo key to retrieve
     * @return  array|bool                The key or FALSE if key could not
     *                                    be found
     */
    public function get($usename, $repo, $id)
    {
        return $this->_get("repos/$username/$repo/keys/$id");
    }
  
    /**
     * Add a deploy key for repo
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/keys/#create
     *
     * @param   string  $username         GitHub username of repo owner
     * @param   string  $repo             Repo name
     * @param   string  $title            Title
     * @param   string  $key              Key
     * @return  array|bool                Returns the created key or FALSE if key
     *                                    failed to create
     */
    public function create($username, $repo, $title, $key)
    {
        return $this->_create("repos/$username/$repo/keys", $title, $key);
    }
  
    /**
     * Update a deploy key for repo
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/keys/#edit
     *
     * @param   string  $username         GitHub username of repo owner
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of key to update
     * @param   string  $title            Title
     * @param   string  $key              Key
     * @return  array|bool                Returns the update key or FALSE if key
     *                                    failed to update
     */
    public function update($usename, $repo, $id, $title = null, $key = null)
    {
        return $this->_update("repos/$username/$repo/keys/$id", $title, $key);
    }
  
    /**
     * Delete a deploy key for repo
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/keys/#delete
     *
     * @param   string  $username         GitHub username of repo owner
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of key to delete
     * @return  bool                      Returns TRUE is key was deleted.
     */
    public function delete($usename, $repo, $id)
    {
        return $this->_delete("repos/$username/$repo/keys/$id");
    }
}
