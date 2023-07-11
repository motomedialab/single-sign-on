<?php

namespace Motomedialab\SingleSignOn\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Motomedialab\SingleSignOn\Contracts\LogsInUser;
use Motomedialab\SingleSignOn\Data\AccessToken;
use Motomedialab\SingleSignOn\Exceptions\OAuthFailedException;
use Motomedialab\SingleSignOn\Factories\TokenFactory;
use Throwable;

class CallbackController
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request)
    {
        // check we have the expected parameters.
        throw_if(
            !$request->has(['code', 'state']),
            new OAuthFailedException('Missing code or state parameter', 404)
        );

        // check if we had any callback errors.
        throw_if(
            $request->has('error'),
            new OAuthFailedException($request->get('error_description', $request->get('error')), 400)
        );

        // verify our state parameter.
        throw_if(
            $request->session()->pull('state') !== $request->get('state'),
            new OAuthFailedException('Invalid state parameter', 400)
        );

        // attempt to get our access token.
        $token = TokenFactory::getAccessToken($request->get('code'));

        // now perform our login action.
        return app()->make(LogsInUser::class)($token);
    }
}
