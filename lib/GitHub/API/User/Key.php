<?php

namespace GitHub\API\User;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * User Keys
 *
 * @link      http://developer.github.com/v3/users/keys/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Key extends \GitHub\API\Key\Key
{
    /**
     * List public keys for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/keys/#list-public-keys-for-a-user
     *
     * @return  array|bool                 List of public key(s) or FALSE if the request
     *                                     failed
     */
    public function all()
    {
        return $this->_all('user/keys');
    }

    /**
     * Get public key for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/keys/#get-a-single-public-key
     *
     * @param   int     $id               ID of public key to retrieve
     * @return  array|bool                The public key or FALSE if key could not
     *                                    be found
     */
    public function get($id)
    {
        return $this->_get("user/keys/$id");
    }

    /**
     * Add a public key for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/keys/#create-a-public-key
     *
     * @param   string  $title            Title
     * @param   string  $key              Key
     * @return  array|bool                Returns the created key or FALSE if key
     *                                    failed to create
     */
    public function create($title, $key)
    {
        return $this->_create('user/keys', $title, $key);
    }

    /**
     * Update a public key for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/keys/#update-a-public-key
     *
     * @param   int     $id               ID of key to update
     * @param   string  $title            Title
     * @param   string  $key              Key
     * @return  array|bool                Returns the update key or FALSE if key
     *                                    failed to update
     */
    public function update($id, $title = null, $key = null)
    {
        return $this->_update("user/keys/$id", $title, $key);
    }

    /**
     * Delete a public key for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/keys/#delete-a-public-key
     *
     * @param   int     $id               ID of key to delete
     * @return  bool                      Returns TRUE is key was deleted.
     */
    public function delete($id)
    {
        return $this->_delete("user/keys/$id");
    }
}
