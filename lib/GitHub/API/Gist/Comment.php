<?php

namespace GitHub\API\Gist;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Gists Comments
 *
 * @link      http://developer.github.com/v3/gists/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Comment extends Api
{
    /**
     * Constant for GitHub resource mime type identifier
     *
     * @link http://developer.github.com/v3/gists/comments/#custom-mime-types
     */
    const MIME_TYPE_RESOURCE = 'gistcomment';

    /**
     * List comments for gist
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/gists/comments/#list-comments-on-a-gist
     *
     * @param   int     $gistId           ID of gist
     * @param   string  $format           Response format of comment
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array                     List of comments for gist or FALSE if
     *                                    comments could not be retrieved for gist
     */
    public function all($gistId, $format = self::FORMAT_RAW)
    {
        $url = "gists/$gistId/comments";

        return $this->processResponse(
            $this->requestGet($url, array(), $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array()))
        );
    }

    /**
     * Get a gist comment
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/gists/comments/#get-a-single-comment
     *
     * @param   int     $id               ID of comment
     * @param   string  $format           Response format of comment
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array|bool                Details of the comment or FALSE if the request failed
     */
    public function get($id, $format = self::FORMAT_RAW)
    {
        return $this->processResponse(
            $this->requestGet("gists/comments/$id", array(), $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array()))
        );
    }

    /**
     * Add a gist comment
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/gists/comments/#create-a-comment
     *
     * @param   int     $gistId           ID of gist
     * @param   string  $body             The comment body
     * @param   string  $format           Response format of comment
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array                     Details of the created comment or FALSE if
     *                                    comment failed to create
     */
    public function create($gistId, $body, $format = self::FORMAT_RAW)
    {
        $details = array('body' => $body);

        return $this->processResponse(
            $this->requestPost("gists/$gistId/comments", $details, $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array()))
        );
    }

    /**
     * Edit a gist comment
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/gists/comments/#edit-a-comment
     *
     * @param   int     $id               ID of gist
     * @param   string  $body             The comment body
     * @param   string  $format           Response format of comment
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array                     Details of the updated comment or FALSE if
     *                                    comment failed to update
     */
    public function update($id, $body, $format = self::FORMAT_RAW)
    {
        $details = array('body' => $body);

        return $this->processResponse(
            $this->requestPatch("gists/comments/$id", $details, $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array()))
        );
    }

    /**
     * Delete a gist comment
     *
     * Authentication Required: true
     * 
     * @link http://developer.github.com/v3/gists/comments/#delete-a-comment
     *
     * @param   int     $id               ID of comment
     * @param   bool                      Returns TRUE if gist comment was deleted
     */
    public function delete($id)
    {
        return $this->processResponse($this->requestDelete("gists/comments/$id"));
    }
}
