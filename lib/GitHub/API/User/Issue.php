<?php

namespace GitHub\API\User;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * User issues
 *
 * @link      http://developer.github.com/v3/issues/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Issue extends \GitHub\API\Issue\Issue
{
    /**
     * List all issues for user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/issues/#list-your-issues
     *
     * @param   array   $filters          Key/Value pairs for filters. See link for details
     * @param   arary   $sort             Key/Value pairs for sorting. See link for details
     * @param   int     $page             Paginated page to get
     * @param   int     $pageSize         Size of paginated page. Max 100
     * @param   string  $format           Response format
     *                                    FORMAT_RAW|FORMAT_TEXT|FORMAT_HTML|FORMAT_FULL
     * @return  array|bool                List of user issues or FALSE if the request
     *                                    failed
     */
    public function all($filters = array(), $sort = array(), $page = 1, $pageSize = self::DEFAULT_PAGE_SIZE, $format = self::FORMAT_RAW)
    {
        // Merge all params together
        $params   = array_merge($filters, $sort, $this->buildPageParams($page, $pageSize));

        return $this->processResponse(
            $this->requestGet("issues", $params)
        );
    }
}
