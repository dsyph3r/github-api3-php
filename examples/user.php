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
    'Network'           => __DIR__.'/../lib/vendor/curl-php/lib',
    'GitHub'            => __DIR__.'/../lib'
));
$loader->register();


use GitHub\API\User\User;

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
$user->setCredentials('username', 'password');
$user->login();

// Update some user details
var_dump($user->update(array('location' => 'Wales, United Kingdom')));

// Finally lets logout
$user->logout();