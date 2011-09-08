<?php

namespace GitHub\API\issue;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Issue milestone
 *
 * @link      http://developer.github.com/v3/issues/milestones/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Milestone extends Api
{
    /**
     * Constant for GitHub milestone states
     */
    const STATE_OPEN        = 'open';
    const STATE_CLOSED      = 'closed';
  
    /**
     * Constant for GitHub milestone sort column
     */
    const SORT_CREATED      = 'due_date';
    const SORT_UPDATED      = 'completeness';
  
    /**
     * Constant for GitHub milestone sort direction
     */
    const SORT_DIR_ASC      = 'asc';
    const SORT_DIR_DESC     = 'desc';
  
    /**
     * List milestones for issue
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/issues/milestones/#list-milestones-for-a-repository
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   array   $filters          Key/Value pairs for filters. See link for details
     * @param   arary   $sort             Key/Value pairs for sorting. See link for details
     * @param   int     $page             Paginated page to get
     * @param   int     $pageSize         Size of paginated page. Max 100
     * @return  array                     List of milestones for issue or FALSE if
     *                                    milestones could not be retrieved for issue
     */
    public function all($username, $repo, $filters = array(), $sort = array(), $page = 1, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        $url = "repos/$username/$repo/milestones";
    
        // Merge all params together
        $params   = array_merge($filters, $sort, $this->buildPageParams($page, $pageSize));
    
        return $this->processResponse(
            $this->requestGet($url, $params)
        );
    }
  
    /**
     * Get an issue milestone
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/issues/milestones/#get-a-single-milestone
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of milestone
     * @return  array|bool                Details of the milestone or FALSE if the request failed
     */
    public function get($username, $repo, $id)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/milestones/$id")
        );
    }
  
    /**
     * Create an issue milestone
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/issues/milestones/#create-a-milestone
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   string  $title            The milestone title
     * @param   string  $state            The milestone state
     * @param   string  $description      The milestone description
     * @param   string  $dueOn            The milestone due on date
     * @return  array                     Details of the created milestone or FALSE if
     *                                    milestone failed to create
     */
    public function create($username, $repo, $title, $state = self::STATE_OPEN, $description = null, $dueOn = null)
    {
        $details = array_merge(
            // Mandatory params
            array('body' => $body),
            // Optinal params
            $this->buildParams(array(
                'state'       => $state,
                'description' => $description,
                'due_on'      => $dueOn
            ))
        );
    
        return $this->processResponse(
            $this->requestPost("repos/$username/$repo/milestones", $details)
        );
    }
  
    /**
     * Edit an issue milestone
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/issues/milestones/#update-a-milestone
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of milestone
     * @param   string  $title            The milestone title
     * @param   string  $state            The milestone state
     * @param   string  $description      The milestone description
     * @param   string  $dueOn            The milestone due on date
     * @return  array                     Details of the updated milestone or FALSE if
     *                                    milestone failed to update
     */
    public function update($username, $repo, $id, $title, $state = self::STATE_OPEN, $description = null, $dueOn = null)
    {
        $details = array_merge(
            // Mandatory params
            array('body' => $body),
            // Optinal params
            $this->buildParams(array(
                'state'       => $state,
                'description' => $description,
                'due_on'      => $dueOn
            ))
        );
    
        return $this->processResponse(
            $this->requestPatch("repos/$username/$repo/milestones/$id", $details)
        );
    }
  
    /**
     * Delete an issue milestone
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/issues/milestones/#delete-a-milestone
     *
     * @param   string  $username         User GitHub username
     * @param   string  $repo             Repo name
     * @param   int     $id               ID of milestone
     * @param   bool                      Returns TRUE if issue milestone was deleted
     */
    public function delete($username, $repo, $id)
    {
        return $this->processResponse(
            $this->requestDelete("repos/$username/$repo/milestones/$id")
        );
    }

}
