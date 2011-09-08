<?php

namespace GitHub\API\Key;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Github Keys
 *
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
abstract class Key extends Api
{
    /**
     * List public keys
     *
     * Authentication Required: true
     *
     * @param   string        $url        API resource URL
     * @return  array|bool                List of key(s) or FALSE if the request failed
     */
    protected function _all($url)
    {
        return $this->processResponse($this->requestGet($url));
    }

    /**
     * Get public key
     *
     * Authentication Required: true
     *
     * @param   string    $url            API resource URL
     * @return  array|bool                The key or FALSE if key could not
     *                                    be found
     */
    protected function _get($url)
    {
        return $this->processResponse($this->requestGet($url));
    }

    /**
     * Add a public key
     *
     * Authentication Required: true
     *
     * @param   string    $url            API resource URL
     * @param   string    $title          Title
     * @param   string    $key            Key
     * @return  array|bool                Returns the created key or FALSE if key
     *                                    failed to create
     */
    protected function _create($url, $title, $key)
    {
        return $this->processResponse(
            $this->requestPost($url, array('title' => $title, 'key' => $key))
        );
    }

    /**
     * Update a public key
     *
     * Authentication Required: true
     *
     * @param   string  $url              API resource URL
     * @param   string  $title            Title
     * @param   string  $key              Key
     * @return  array|bool                Returns the update key or FALSE if key
     *                                    failed to update
     */
    protected function _update($url, $title = null, $key = null)
    {
        return $this->processResponse(
            $this->requestPatch($url, $this->buildParams(array('title' => $title, 'key' => $key)))
        );
    }

    /**
     * Delete a public key
     *
     * Authentication Required: true
     *
     * @param   string    $url            API resource URL
     * @return  bool                      Returns TRUE is key was deleted.
     */
    protected function _delete($url)
    {
        return $this->processResponse($this->requestDelete($url));
    }
}
