<?php

namespace GitHub\API\Event;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Github Events
 *
 * @link http://developer.github.com/v3/issues/events/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
abstract class Key extends Api
{
    /**
     * List events
     *
     * Authentication Required: false|true
     *
     * @param   string    $url            API resource URL
     * @return  array|bool                List of events or FALSE if the request failed
     */
    protected function _all($url)
    {
        return $this->processResponse(
            $this->requestGet($url)
        );
    }
  
    /**
     * Get event
     *
     * Authentication Required: false|true
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of event
     * @return  array|bool                The key or FALSE if key could not
     *                                    be found
     */
    protected function get($username, $repo, $id)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/issues/events/$id")
        );
    }
}
