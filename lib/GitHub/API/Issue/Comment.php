<?php

namespace GitHub\API\Issue;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Issue comment
 *
 * @link      http://developer.github.com/v3/issues/comments/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Comment extends Api
{
    /**
     * Constant for GitHub resource mime type identifier
     *
     * @link http://developer.github.com/v3/issues/comments/#custom-mime-types
     */
    const MIME_TYPE_RESOURCE = 'issuecomment';
  
    /**
     * List comments for issue
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/issues/#list-comments-on-an-issue
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $issueId          Id of issue
     * @param   string  $format           Response format of comment
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array                     List of comments for issue or FALSE if
     *                                    comments could not be retrieved for issue
     */
    public function all($username, $repo, $issueId = null, $format = self::FORMAT_RAW)
    {
        $url = "repos/$username/$repo/issues/$issueId/comments";
    
        return $this->processResponse(
            $this->requestGet($url, array(), $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array()))
        );
    }
  
    /**
     * Get an issue comment
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/issues/#get-a-single-comment
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of comment
     * @param   string  $format           Response format of comment
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array|bool                Details of the comment or FALSE if the request failed
     */
    public function get($username, $repo, $id, $format = self::FORMAT_RAW)
    {
        $options = $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array());
        
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/issues/comments/$id", array(), $options)
        );
    }
  
    /**
     * Create an issue comment
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/issues/#create-a-comment
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $issueId          Id of issue
     * @param   string  $body             The comment body
     * @param   string  $format           Response format of comment
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array                     Details of the created comment or FALSE if
     *                                    comment failed to create
     */
    public function create($username, $repo, $issueId, $body, $format = self::FORMAT_RAW)
    {
        $details = array('body' => $body);
        
        $options = $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array());
        
        return $this->processResponse(
            $this->requestPost("repos/$username/$repo/issues/$issueId/comments", $details, $options)
        );
    }
  
    /**
     * Edit an issue comment
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/issues/#edit-a-comment
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of issue
     * @param   string  $body             The comment body
     * @param   string  $format           Response format of comment
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array                     Details of the updated comment or FALSE if
     *                                    comment failed to update
     */
    public function update($username, $repo, $id, $body, $format = self::FORMAT_RAW)
    {
        $details = array('body' => $body);
        
        $options = $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array());
        
        return $this->processResponse(
            $this->requestPatch("repos/$username/$repo/issues/comments/$id", $details, $options)
        );
    }
  
    /**
     * Delete an issue comment
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/issues/#delete-a-comment
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of comment
     * @param   bool                      Returns TRUE if issue comment was deleted
     */
    public function delete($username, $repo, $id)
    {
        return $this->processResponse(
            $this->requestDelete("repos/$username/$repo/issues/comments/$id")
        );
    }
}
