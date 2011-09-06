# GitHub API v3 - PHP Library

Currently under construction.

## Overview

Provides access to [GitHub API v3](http://developer.github.com/) via an Object
Oriented PHP library.

The goal of this library is to provide an intuitive Object Oriented wrapper
around the GitHub API v3. This is achieved using a number of methods:

 * Method chaining to request the resources you require
 * Consistent interface for [C]reate (create), [R]etrieve (all|get), [U]pdate (update), [D]elete (delete)
 * Abstracting the specific details of the GitHub API

```php
    // Setup the user, and authenticate
    $user = new User();
    $user->setCredentials('username', 'password');
    $user->login();

    // Get the user details
    $response = $user->get();

    // Now lets get user emails
    $emails = $user->emails()->all();

    // Add some new emails
    $emails = $user->emails()->create(array('email1@test.com', 'email2@test.com'));

    // Get my public keys
    $keys = $user->keys()->all();

    // Update a key
    $user->keys()->update(44, 'New key title', 'ssh-rsa ABCDEF');
```

### Requirements

 * PHP5+
 * Curl