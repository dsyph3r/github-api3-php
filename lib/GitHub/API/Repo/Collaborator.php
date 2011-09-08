<?php

namespace GitHub\API\Repo;

use GitHub\API\Api;
use GitHub\API\ApiException;
use GitHub\API\AuthenticationException;

/**
 * Repository Collaborators
 *
 * @link      http://developer.github.com/v3/repos/collaborators/
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Collaborator extends Api
{
    /**
     * List collaborators for repository
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/collaborators/#list
     *
     * @param   string  $username         GitHub username of repo owner
     * @param   string  $repo             Repo name
     * @return  array|bool                Returns list of repo collaborators or
     *                                    FALSE if request failed
     */
    public function all($username, $repo)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/collaborators")
        );
    }
  
    /**
     * Check if a user is a collaborator for a repository
     *
     * Authentication Required: false|true
     *
     * @link http://developer.github.com/v3/repos/collaborators/#get
     *
     * @param   string  $username         GitHub username of repo owner
     * @param   string  $repo             Repo name
     * @param   string  $checkUsername    Username of user to check if they are a repo
     *                                    collaborator
     * @return  bool                      Return TRUE is user is a repo collaborator
     */
    public function isCollaborator($username, $repo, $checkUsername)
    {
        return $this->processResponse(
            $this->requestGet("repos/$username/$repo/collaborators/$checkUsername")
        );
    }
  
    /**
     * Adds user as a collaborator for a repository
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/collaborators/#add-collaborator
     *
     * @param   string  $username         GitHub username of repo owner
     * @param   string  $repo             Repo name
     * @param   string  $addUsername      Username of user to add as a repo collaborator
     * @return  bool                      Return TRUE is user was added as a repo collaborator
     */
    public function create($username, $repo, $addUsername)
    {
        if (false === is_array($email))
          $email = array($email);
    
        return $this->processResponse(
            $this->requestPut("repos/$username/$repo/collaborators/$addUsername", $email)
        );
    }
  
    /**
     * Removes user as a collaborator for a repository
     *
     * Authentication Required: true
     *
     * @link http://developer.github.com/v3/repos/collaborators/#remove-collaborator
     *
     * @param   string  $username         GitHub username of repo owner
     * @param   string  $repo             Repo name
     * @param   string  $deleteUser       Username of user to remove as a repo collaborator
     * @return  bool                      Return TRUE is user was removed as a repo collaborator
     */
    public function delete($username, $repo, $deleteUser)
    {
        return $this->processResponse(
            $this->requestDelete("repos/$username/$repo/collaborators/$deleteUser")
        );
    }
}
