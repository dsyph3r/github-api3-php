<?php

namespace GitHub\API\Repo;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Repository commit comments
 *
 * @link      http://developer.github.com/v3/repos/commits/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class CommitComment extends Api
{
    /**
     * Constant for GitHub resource mime type identifier
     *
     * @link http://developer.github.com/v3/repos/commits/#custom-mime-types
     */
    const MIME_TYPE_RESOURCE = 'commitcomment';
  
    /**
     * List comments for commit
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/commits/#list-comments-for-a-single-commit
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   string  $sha              SHA hash of commit to limit comments to a single
     *                                    commit
     * @param   string  $format           Response format of comment
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array                     List of comments for commit or FALSE if
     *                                    comments could not be retrieved for commit
     */
    public function all($username, $repo, $sha = null, $format = self::FORMAT_RAW)
    {
        if (is_null($sha))
             $url = "repos/$username/$repo/comments";
        else
            $url = "repos/$username/$repo/commits/$sha/comments";
        
        $options = $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array());
        
        return $this->processResponse(
            $this->requestGet($url, array(), $options)
        );
    }
  
    /**
     * Get a commit comment
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/commits/#get-a-single-commit-comment
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   string  $id               ID of comment
     * @param   string  $format           Response format of comment
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array|bool                Details of the comment or FALSE if the request failed
     */
    public function get($username, $repo, $id, $format = self::FORMAT_RAW)
    {
        $options = $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array());
        
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/comments/$id", array(), $options)
        );
    }
  
    /**
     * Add a commit comment
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/commits/#create-a-commit-comment
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   string  $sha              SHA hash of commit to add comment for
     * @param   string  $body             The comment body
     * @param   string  $line             Line number in file to comment on
     * @param   string  $path             Relative path of file to comment on
     * @param   string  $position         Line index in diff to comment on
     * @param   string  $format           Response format of comment
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array                     Details of the created comment or FALSE if
     *                                    comment failed to create
     */
    public function create($username, $repo, $sha, $body, $line, $path, $position, $format = self::FORMAT_RAW)
    {
        // All are mandatory
        $details = array(
            'body'      => $body,
            'commit_id' => $sha,
            'line'      => $line,
            'path'      => $path,
            'position'  => $position
        );
        
        $options = $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array());
        
        return $this->processResponse(
            $this->requestPost("repos/$username/$repo/commits/$sha/comments", $details, $options)
        );
    }
  
    /**
     * Edit a commit comment
     *
     * Authentication Required: true
     * 
     * @link http://developer.github.com/v3/repos/commits/#update-a-commit-comment
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of comment
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
            $this->requestPatch("repos/$username/$repo/comments/$id", $details, $options)
        );
    }
  
    /**
     * Delete a commit comment
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/commits/#delete-a-comment
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of comment
     * @param   bool                      Returns TRUE if commit comment was deleted
     */
    public function delete($username, $repo, $id)
    {
        return $this->processResponse(
            $this->requestDelete("repos/$username/$repo/comments/$id")
        );
    }
}
