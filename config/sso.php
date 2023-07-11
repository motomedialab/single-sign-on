<?php

return [

    /*
    |--------------------------------------------------------------------------
    | oAuth2 Endpoints
    |--------------------------------------------------------------------------
    |
    | Define the endpoints that should be used when making requests to the
    | third party oAuth server. The authorize endpoint is used to redirect
    | the user to the third party login page, and the token endpoint is
    | used to exchange the authorization code for an access token.
    |
    */
    'endpoints' => [
        'token' => env('SSO_OAUTH_TOKEN_ENDPOINT', null),
        'authorize' => env('SSO_OAUTH_AUTHORIZE_ENDPOINT', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | oAuth2 Client Details
    |--------------------------------------------------------------------------
    |
    | Define the client details that should be used when making requests to
    | the third party oAuth server.
    |
    */
    'client' => [
        'id' => env('SSO_OAUTH_CLIENT_ID', null),
        'secret' => env('SSO_OAUTH_CLIENT_SECRET', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    | Define the scopes that should be requested from the oAuth server.
    |
    */
    'scopes' => env('SSO_OAUTH_SCOPES', 'openid'),

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    |
    | Define the actions that should be performed when a user is logged in.
    | The login action is responsible for creating the user in the local
    | database, and logging them in. You can define your own actions
    | by implementing the LogsInUser contract, and adding them to
    | this array.
    |
    */
    'actions' => [
        'login' => \App\Actions\LogInUser::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Route middleware
    |--------------------------------------------------------------------------
    |
    | Define the middleware that should be applied to the SSO login routes.
    | It's important that a session is started for the SSO routes, as this
    | is used to store the state parameter for the oAuth2 flow.
    |
    */
    'middleware' => [
        'web', // default web middleware.
        'guest', // user must be a guest to access sign-on routes.
    ],

    /*
    |--------------------------------------------------------------------------
    | Authorization query
    |--------------------------------------------------------------------------
    |
    | Define additional query parameters that can be sent to the
    | authorization endpoint. This can be used to request additional
    | scopes, or to pass additional information to the third party.
    |
    */
    'authorization_query' => [
        // 'prompt' => 'none',
    ],

];
