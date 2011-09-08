<?php

namespace GitHub\API\Repo;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Repo issues
 *
 * @link      http://developer.github.com/v3/issues/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Issue extends \GitHub\API\Issue\Issue
{
    /**
     * List all issues for repo
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/issues/#list-issues-for-a-repository
     *
     * @param   string  $username         GitHub username
     * @param   string  $repo             Repo name
     * @param   array   $filters          Key/Value pairs for filters. See link for details
     * @param   arary   $sort             Key/Value pairs for sorting. See link for details
     * @param   int     $page             Paginated page to get
     * @param   int     $pageSize         Size of paginated page. Max 100
     * @param   string  $format           Response format
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array|bool                List of repo issues or FALSE if the request
     *                                    failed
     */
    public function all($username, $repo, $filters = array(), $sort = array(), $page = 1, $pageSize = self::DEFAULT_PAGE_SIZE, $format = self::FORMAT_RAW)
    {
        // Merge all params together
        $params   = array_merge($filters, $sort, $this->buildPageParams($page, $pageSize));
    
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/issues", $params)
        );
    }
  
    /**
     * Get an issue
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/issues/#get-a-single-issue
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of issue
     * @param   string  $format           Response format
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array|bool                Details of the issue or FALSE if the request failed
     */
    public function get($username, $repo, $id, $format = self::FORMAT_RAW)
    {
        $options = $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array());
        
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/milestones/$id", array(), $options)
        );
    }
  
    /**
     * Create an issue
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/issues/#create-an-issue
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   string  $title            The title
     * @param   string  $body             The issue body
     * @param   string  $assignee         The issue assignee
     * @param   int     $milestone        The issue milestone
     * @param   array   $labels           List of labels
     * @param   string  $format           Response format
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array                     Details of the created milestone or FALSE if
     *                                    milestone failed to create
     */
    public function create($username, $repo, $title, $body = null, $assignee = null, $milestone = null, $labels = array(), $format = self::FORMAT_RAW)
    {
        $details = array_merge(
            // Mandatory params
            array('title' => $title),
            // Optinal params
            $this->buildParams(array(
                'body'        => $body,
                'assignee'    => $assignee,
                'milestone'   => $milestone,
                'labels'      => $labels
            ))
        );
        
        $options = $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array());
        
        return $this->processResponse(
            $this->requestPost("repos/$username/$repo/issues", $details, $options)
        );
    }
  
    /**
     * Edit an issue
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/issues/#edit-an-issue
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of issue
     * @param   string  $title            The milestone title
     * @param   string  $body             The issue body
     * @param   string  $assignee         The issue assignee
     * @param   int     $milestone        The issue milestone
     * @param   array   $labels           List of labels
     * @param   string  $state            The issue state
     * @param   string  $format           Response format
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array                     Details of the updated milestone or FALSE if
     *                                    milestone failed to update
     */
    public function update($username, $repo, $id, $title, $body = null, $assignee = null, $milestone = null, $labels = array(), $state = self::STATE_OPEN, $format = self::FORMAT_RAW)
    {
        $details = array_merge(
            // Mandatory params
            array('title' => $title),
            // Optinal params
            $this->buildParams(array(
                'body'        => $body,
                'assignee'    => $assignee,
                'milestone'   => $milestone,
                'labels'      => $labels,
                'state'       => $state
            ))
        );
    
        $options = $this->setResponseFormatOptions($format, self::MIME_TYPE_RESOURCE, array());
        
        return $this->processResponse(
            $this->requestPatch("repos/$username/$repo/issues/$id", $details, $options)
        );
    }

}
