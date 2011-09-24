<?php

require_once dirname(__FILE__) . '/../lib/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

/**
 * Configure the autoloader
 *
 * The Symfony ClassLoader Component is used, but could easy be substituted for
 * another autoloader.
 *
 * @link https://github.com/symfony/ClassLoader
 * @link http://symfony.com/doc/current/cookbook/tools/autoloader.html
 */
$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
// Register the location of the GitHub namespace
$loader->registerNamespaces(array(
    'Buzz'              => __DIR__.'/../lib/vendor/Buzz/lib',
    'GitHub'            => __DIR__.'/../lib'
));
$loader->register();


use GitHub\API\Authentication;
use GitHub\API\User\User;
use GitHub\API\AuthenticationException;

// Lets access the User API
$user = new User();

/**
 * Perform operations that require no authentication
 */

// Get details for user 'dsyph3r'
var_dump($user->get('dsyph3r'));

// Get users 'dsyph3r' is following
var_dump($user->following('dsyph3r'));

/**
 * Perform operations that require authentication
 */
// Set user credentials and login
$user->setCredentials(new Authentication\Basic('username', 'password'));
$user->login();

try
{
    // Check if your following user
    var_dump($user->isFollowing("octocat"));

    // Update some user details
    var_dump($user->update(array('location' => 'Wales, United Kingdom')));

    // Get all emails for user
    var_dump($user->emails()->all());

    // Add key for user
    var_dump($user->keys()->create("New Key", "ssh-rsa CCC"));
}
catch (AuthenticationException $exception)
{
    echo $exception->getMessage();
}

// Finally lets logout
$user->logout();