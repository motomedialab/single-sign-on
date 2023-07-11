<?php

namespace Motomedialab\SingleSignOn\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Motomedialab\SingleSignOn\Contracts\LogsInUser;
use Motomedialab\SingleSignOn\Data\AccessToken;
use Motomedialab\SingleSignOn\Exceptions\OAuthFailedException;
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

        // make our request to the token endpoint.
        $response = Http::asForm()
            ->post(config('sso.endpoints.token'), [
                'grant_type' => 'authorization_code',
                'client_id' => config('sso.client.id'),
                'client_secret' => config('sso.client.secret'),
                'redirect_uri' => route('login-sso-callback'),
                'code' => $request->get('code'),
            ]);

        throw_if(
            $response->failed(),
            new OAuthFailedException($response->json('error_description', 'Failed to exchange token'), 400)
        );

        return app()->make(LogsInUser::class)(
            new AccessToken(
                accessToken: $response->json('access_token'),
                refreshToken: $response->json('refresh_token'),
                expiresAt: now()->addSeconds($response->json('expires_in')),
            )
        );
    }
}
