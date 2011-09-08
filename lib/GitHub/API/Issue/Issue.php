<?php

namespace GitHub\API\Issue;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Issues
 *
 * @link      http://developer.github.com/v3/issues/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
abstract class Issue extends Api
{
    /**
     * Constant for GitHub resource mime type identifier
     *
     * @link http://developer.github.com/v3/issues/#custom-mime-types
     */
    const MIME_TYPE_RESOURCE = 'issue';

    /**
     * Constant for GitHub issue filters
     */
    const FILTER_ASSIGNED   = 'assigned';
    const FILTER_CREATED    = 'created';
    const FILTER_MENTIONED  = 'mentioned';
    const FILTER_SUBSCRIBED = 'subscribed';

    /**
     * Constant for GitHub issue states
     */
    const STATE_OPEN        = 'open';
    const STATE_CLOSED      = 'closed';

    /**
     * Constant for GitHub issue sort column
     */
    const SORT_CREATED      = 'created';
    const SORT_UPDATED      = 'updated';
    const SORT_COMMENTS     = 'comments';

    /**
     * Constant for GitHub issue sort direction
     */
    const SORT_DIR_ASC      = 'asc';
    const SORT_DIR_DESC     = 'desc';

    /**
     * Issue comments
     *
     * @var Commit
     */
    protected $comment   = null;

    /**
     * Issue events
     *
     * @var Commit
     */
    protected $event   = null;

    /**
     * Issue labels
     *
     * @var Commit
     */
    protected $label   = null;

    /**
     * Issue milestones
     *
     * @var Commit
     */
    protected $milestone   = null;

    /**
     * Provides access to issue comment operations
     *
     * @return  Comment
     */
    public function comments()
    {
        if (null === $this->comment)
            $this->comment = new Comment($this->getTransport());

        return $this->comment;
    }

    /**
     * Provides access to issue event operations
     *
     * @return  Event
     */
    public function events()
    {
        if (null === $this->event)
            $this->event = new Event($this->getTransport());

        return $this->event;
    }

    /**
     * Provides access to issue label operations
     *
     * @return  Label
     */
    public function labels()
    {
        if (null === $this->label)
            $this->label = new Label($this->getTransport());

        return $this->label;
    }

    /**
     * Provides access to issue milestone operations
     *
     * @return  Milestone
     */
    public function milestones()
    {
        if (null === $this->milestone)
            $this->milestone = new Milestone($this->getTransport());

        return $this->milestone;
    }
}
