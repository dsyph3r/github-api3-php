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
    use GitHub\API\User\User;
    
    // Setup the user, and authenticate (using basic HTTP auth)
    $user = new User();
    $user->setCredentials(new Authentication\Basic('username', 'password'));
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
   * [Email API](http://developer.github.com/v3/users/emails/)
   * [Followers API](http://developer.github.com/v3/users/followers/)
   * [Keys API](http://developer.github.com/v3/users/keys/)
 * [Gists API](http://developer.github.com/v3/gists/)
   * [Comments API](http://developer.github.com/v3/gists/comments/)

### Requirements

 * PHP 5.3+ 

### Dependancies

 * [Buzz](https://github.com/dsyph3r/Buzz)

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
[php-github-api](https://github.com/ornicar/php-github-api) library by
[ornicar](https://github.com/ornicar) have been used in this library.

## Authentication

Authentication is supported for both basic HTTP and OAuth. OAuth is the recommened
way to authenticate. (Note: This library does not actually deal with how you
do the OAuth process to retrieve the access token. You must implement this your
self. See [oauth2-php](https://github.com/dsyph3r/oauth2-php) lib for an
implementation of this).

Authentication can be achieved as follows.

### OAuth

```php
    use GitHub\API\User\User;
    
    $user = new User();
    $user->setCredentials(new Authentication\OAuth('access_token'));
    $user->login();

    // Perform operations that require authentication ...

    $user->logout();
```

### Basic

```php
    use GitHub\API\User\User;
    
    $user = new User();
    $user->setCredentials(new Authentication\Basic('username', 'password'));
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

### Access user related API's

Each API can be used independent of other API's. However, proxy methods can be used
to access related API's such as `Emails` for a `User`. The `User` API provides the following
proxies.

```php
    // Access email API
    $user->emails();

    // Access keys API
    $user->keys();

    // Access repos API
    $user->repos();

    // Access gists API
    $user->gists();
```

## User Emails

Perform operations on authenticated users emails.

GitHub API Documentation - [User Email API](http://developer.github.com/v3/users/emails/).

The email API can be used independently of the other API's as follows:

```php
    use GitHub\API\User\Email;
    
    $email = new Email();
    $response = $email->all();
```

However, as emails are related to the user you can also access the email API via the
user as follows:

```php
    use GitHub\API\User\User;
    
    $user = new User();
    $response = $user->emails()->all();
```

There are a number of benefits to this approach including increased readability, and
authentication being carried across from each independent API, i.e. you don't
have to authenticated the user and the email API.

### List emails

List all email addresses for authenticated user.

```php
    $response = $user->emails()->all();
```

### Add email address(es)

Adds single or multiple address(es) for the authenticated user. On success returns
list of all email address for user. On failure returns `FALSE`. This operation
can fail if email addresses already exist for the user.

```php
    // Add single
    $response = $user->emails()->create('dsyph3r@gmail.com');
    // Add multiple
    $response = $user->emails()->create(array('dsyph3r@gmail.com', 'octocat@github.com'));
```

### Delete email address(es)

Deletes single or multiple address(es) for the authenticated user. On success returns
`TRUE`.

```php
    // Delete single
    $response = $user->emails()->delete('dsyph3r@gmail.com');
    // Delete multiple
    $response = $user->emails()->delete(array('dsyph3r@gmail.com', 'octocat@github.com'));
```

## User Keys

Perform operations on authenticated users keys.

GitHub API Documentation - [User Keys API](http://developer.github.com/v3/users/keys/).

The keys API can be used independently of the other API's as follows:

```php
    use GitHub\API\User\Key;
    
    $key = new Key();
    $response = $key->all();
```

However, as keys are related to the user you can also access the keys API via the
user as follows:

```php
    use GitHub\API\User\User;
    
    $user = new User();
    $response = $user->keys()->all();
```

### List keys

List all keys for authenticated user.

```php
    $response = $user->keys()->all();
```

### Get key

Get a key for authenticated user by key ID.

```php
    $response = $user->keys()->get(1);
```

### Add key

Adds a key for authenticated user. On success returns the created key.
On failure returns `FALSE`. This operation can fail if the key is invalid.

```php
    $response = $user->keys()->create('desktop@dsyph3r', 'ssh-rsa ABCDEF');
```

### Update key

Update a key for authenticated user by key ID. On success returns the updated key.
On failure returns `FALSE`. This operation can fail if the key is invalid.

```php
    $response = $user->keys()->update(1, 'desktop@dsyph3r', 'ssh-rsa FEDCBA');
```

### Delete key

Delete a key for authenticated user by ID. On success returns `TRUE`.

```php
    $response = $user->keys()->delete(1);
```

## Gists

Perform operations on gists.

GitHub API Documentation - [Gists API](http://developer.github.com/v3/gists/).

The gists API can be used independently of the other API's as follows:

```php
    use GitHub\API\Gist\Gist;
    
    $gist = new Gist();
    $response = $gist->all();
```

### List gists

List all public gists. No authentication required.

```php
    $response = $gist->all();
```

List all public gists for a user

```php
    $response = $gist->all('dsph3r');
```

List all public gists for authenticated user.

```php
    $gist->login();
    $response = $gist->all();
```

List all starred gists for authenticated user.

```php
    $gist->login();
    $response = $gist->all(null, Api::GIST_TYPE_STARRED);
```

All above operations return paginated results. Accessing paginated results is as follows.

```php
    $response = $gist->all('dsyph3r', Api::GIST_TYPE_PUBLIC, 2, 50);   // Get page 2, 50 results per page
```

### Get gist

Get a gist. Authentication maybe required depending on the gist permissions, ie. Private gist

```php
    $response = $gist->get(1);
```

### Create gist

Creates a gist for authenticated user. On success returns the created gist.
On failure returns `FALSE`.

Gists contain files and should be passed in as an array.

```php
    $files = array(
      'file1.txt' => array(
        'content' => 'File 1 contents',
      ),
      'file2.txt' => array(
        'content' => 'File 2 contents',
      ),
    );
    $response = $gist->create($files, true, "Gist description");
```

### Update gist

Updates a gist for authenticated user. On success returns the updated gist.
On failure returns `FALSE`.

Gists contain files and should be passed in as an array. Setting a filename
key value to null will removed the file from the gist. Leaving filename keys
out will cause no update to the gist file.

```php
    $files = array(
      'file1.txt' => array(
        // Update the contents of this file
        'content' => 'File 1 contents update',
      ),
      // This file will be removed from gist
      'file2.txt' => null,
    );
    $response = $gist->update(1, $files, "Gist description update");
```

### Star gist

Star a gist for the authenticated user. Returns `TRUE` if gist was starred.

```php
    $response = $gist->star(1);
```

### Unstar gist

Unstar a gist for the authenticated user. Returns `TRUE` if gist was unstarred.

```php
    $response = $gist->unstar(1);
```

### Check gist is starred

Check if gist is starred for the authenticated user. Returns `TRUE` if gist is starred.

```php
    $response = $gist->isStarred(1);
```

### Fork gist

Fork a gist for the authenticated user. Returns `TRUE` if gist was forked.

```php
    $response = $gist->fork(1);
```

### Delete gist

Delete a gist for the authenticated user. Returns `TRUE` if gist was deleted.

```php
    $response = $gist->delete(1);
```

### Access gists related API's

Each API can be used independent of other API's. However, proxy methods can be used
to access related API's such as `Comments` for a `Gist`. The `Gist` API provides the following
proxies.

```php
    // Access gist comments API
    $gist->comments();
```

## Gist Comments

Perform operations on gist comments.

GitHub API Documentation - [Gists Comments API](http://developer.github.com/v3/gists/comments/).

The gists comments API can be used independently of the other API's as follows:

```php
    use GitHub\API\Gist\Comment;
    
    $comment = new Comment();
    $response = $comment->all();
```

However, as comments are related to the gists you can also access the comments API via the
gist as follows:

```php
    use GitHub\API\Gist\Gist;
    
    $gist = new Gist();
    $response = $gist->comments()->all();
```

### Custom Mime Types

Gist comments use [custom mime types](http://developer.github.com/v3/gists/comments/#custom-mime-types)
to return formatted results. Custom mime types can be passed in as an argument to most functions.
Available formats are:

```php
    Api::FORMAT_RAW;      // Default
    Api::FORMAT_TEXT;
    Api::FORMAT_HTML;
    Api::FORMAT_FULL;
```

### List comments

List all comments for gist. Authentication maybe required depending on the gist permissions, ie. Private gist

```php
    $response = $gist->comments()->all(1);
```

Get the results in `HTML` format

```php
    $response = $gist->comments()->all(1, Api::FORMAT_HTML);
```

### Get comment

Get a gist comment. Authentication maybe required depending on the gist permissions, ie. Private gist

```php
    $response = $gist->comments()->get(1);
```

### Create comment

Creates a gist comment for authenticated user. On success returns the created comment.
On failure returns `FALSE`.

```php
    $response = $gist->comments()->create(1, "Comment Body");
```

### Update comment

Updates a gist comment for authenticated user. On success returns the updated comment.
On failure returns `FALSE`.

```php
    $response = $gist->comments()->update(1, "Comment body update");
```

### Delete comment

Delete a gist comment for the authenticated user. Returns `TRUE` if gist comment was deleted.

```php
    $response = $gist->comments()->delete(1);
```
