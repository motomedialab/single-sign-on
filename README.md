# MotoMediaLab - Single Sign On

A package providing the basic premise for a single sign on system using oAuth2.
This package is perfectly compatible with `laravel/passport`.

## Installation

```bash
composer require motomedialab/single-sign-on
```

## Configuration

### Basic Configuration

Publish the configuration file using the below command:

```bash
php artisan vendor:publish --tag=sso-config
```

Configure the relevant properties, such as the oAuth2 endpoints, client id and client secret.
An example of how this can be represented in your environment file can be seen below:

```dotenv
# define the endpoints for our single sign on.
SSO_OAUTH_TOKEN_ENDPOINT=https://example-sso-website.com/oauth/token
SSO_OAUTH_AUTHORIZE_ENDPOINT=https://example-sso-website.com/oauth/authorize

# define the client details for our single sign on.
SSO_OAUTH_CLIENT_ID=5
SSO_OAUTH_CLIENT_SECRET=7oeMatFkmQVMgJOh0dcOuqfaGOxkjqbMC0J45mDk
SSO_OAUTH_SCOPES="profile"
```

### SSO Only App Configuration

If you're not authenticating users with anything but the single sign on, you can use the below command to publish the
packages migrations, which will remove the password field for users, and create a table for storing access tokens.

```bash
php artisan vendor:publish --tag=sso-migrations && php artisan migrate
```

Once migrations have been published, you can optionally apply the `Motomedialab\SingleSignOn\Traits\HasSsoToken` to your `User` model.
This will form a direct relationship between your users and their SSO keys. Remember, this is optional based on your configuration
and there are some considerations to make when storing SSO keys in your database.

## Creating a login action

To complete sign in, the next steps are up to you. **You need to define the action that should take place after authentication for this to work.**

This can be achieved by creating a new class that implements the
`MotoMediaLab\SingleSignOn\Contracts\LogsInUser` interface.

An example of how this method might look can be seen below:

```php
<?php

namespace App\Actions;

use Illuminate\Http\RedirectResponse;use Illuminate\Support\Facades\Http;
use MotoMediaLab\SingleSignOn\Contracts\LogsInUser;
use Motomedialab\SingleSignOn\Data\AccessToken;

class LogInUser implements LogsInUser
{
    public function __invoke(AccessToken $token): RedirectResponse
    {
        // get our user data from our third party.
        $userData = Http::acceptJson()
            ->withHeader('Authorization', 'Bearer ' . $token->accessToken)
            ->get('https://example-sso-website.com/api/me')
            ->throw()
            ->json();
            
        // find or create our user.
        $user = App\Models\User::firstOrCreate(
            ['email' => $userData['email']],
            ['name' => $userData['name']]
        );
        
        // optionally store the third party access token against the user.
        // the below requires the HasSsoToken trait.
        $user->setSsoToken($token);
        
        // authenticate our user with the current application
        auth()->login($user, true);
        
        // redirect the user to the home page.
        return redirect()->route('home');
    }
}
