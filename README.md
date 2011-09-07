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

### GitHub API Coverage

The following resources for the API are covered:

 * [Users API](http://developer.github.com/v3/users/)
 
### Requirements

 * PHP5+
 * Curl

### Dependancies

 * [curl-php](https://github.com/dsyph3r/curl-php)
 
### Installation

````bash
$ git clone git@github.com:dsyph3r/github-api3-php.git
$ cd github-api3-php
$ git submodule update -i
````

### Testing

The library is tested using phpunit. Run tests with

````bash
$ phpunit
````

### Documentation

Full documentation is provided in this README. There is also extensive source
code documentation and the actual [GitHub API](http://developer.github.com/)
documentation.

### Credits

Ideas from the excellent
(php-github-api)[https://github.com/ornicar/php-github-api] library by
[ornicar](https://github.com/ornicar) have been used in this library.

## Authentication

At present only basic authentication is available. OAuth with be available soon.

Authentication can be achieved as follows.

```php
    $user = new User();
    $user->setCredentials('username', 'password');
    $user->login();

    // Perform operations that require authentication ...

    $user->logout();
```

Most of the GitHub API requires authentication. Requests made to these methods
without authentication will cause a `GitHub\API\AuthenticationException()` to be
thrown.

Once credentials have been set via `setCredentials()` you can call `login()` and
`logout()` without setting the credentials again. Credentials can be cleared
with `clearCredentials()`.

```php
    $user->clearCredentials();
```

## Users

Perform operations on unauthenticated or authenticated user including retrieval, updating,
listing followers, etc.

GitHub API Documentation - [User API](http://developer.github.com/v3/users/).

### Get user by username

Returns user details. No authentication required.

```php
    $response = $user->get('dsyph3r');
```

Returns user details for authenticated user

```php
    $response = $user->get();
```

### Update user

Updates the authenticated user. See
[API](http://developer.github.com/v3/users/#update-the-authenticated-user) for
full list of available update fields. The updated user is returned.

```php
    $response = $user->update(array(
      'name'      => 'dsyph3r',
      'email'     => 'd.syph.3r@gmail.com',
      'location'  => 'Wales, UK'
    ));
```

### List followers

Lists user followers. No authentication required.

```php
    $response = $user->followers('dsyph3r');
```

Lists the followers for authenticated user

```php
    $response = $user->followers();
```

Both requests can return paginated results. The optional 2nd and 3rd parameter
are provided to access this functionality. The default pagination result size is
30. GitHub [limits](http://developer.github.com/v3/#pagination) the page result
size to 100.

```php
    $response = $user->followers('dsyph3r', 2, 50);   // Get page 2, 50 results per page
```

### List following users

Lists user a user is following. No authentication required.

```php
    $response = $user->following('dsyph3r');
```

Lists the user the authenticated user is following.

```php
    $response = $user->following();
```

Access paginated results.

```php
    $response = $user->following('dsyph3r', 2, 50);   // Get page 2, 50 results per page
```

### Check user is being followed

Check if authenticated user is following a user. Returns `TRUE` is user is being
followed.

```php
    $response = $user->isFollowing('octocat');
```

### Follow a user

Follow a user for the authenticated user. Returns `TRUE` if user was followed.

```php
    $response = $user->follow('octocat');
```

### Unfollow a user

Unfollow a user for the authenticated user. Returns `TRUE` if user was unfollowed.

```php
    $response = $user->unfollow('octocat');
```