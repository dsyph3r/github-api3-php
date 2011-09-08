<?php

namespace GitHub\API\User;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Email
 *
 * @link      http://developer.github.com/v3/users/emails/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Email extends Api
{
    /**
     * List email addresses for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/emails/#list-email-addresses-for-a-user
     *
     * @return  array                     Email addresses
     */
    public function all()
    {
        return $this->processResponse($this->requestGet('user/emails'));
    }

    /**
     * Add email(s) for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/emails/#add-email-addresses
     *
     * @param   string|array  $email      Single email, or array of email addresses to add
     * @return  array|bool                Return updated email addresses or FALSE if operation
     *                                    to create email(s) failed. Email address must not already
     *                                    exist for user
     */
    public function create($email)
    {
        if (false === is_array($email))
            $email = array($email);

        return $this->processResponse($this->requestPost('user/emails', $email));
    }

    /**
     * Delete email(s) for authenticated user
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/users/emails/#delete-email-addresses
     *
     * @param   string|array  $email      Single email, or array of email addresses to delete
     * @return  bool                      Returns TRUE is email(s) were deleted.
     */
    public function delete($email)
    {
        if (false === is_array($email))
            $email = array($email);

        return $this->processResponse($this->requestDelete('user/emails', $email));
    }
}
