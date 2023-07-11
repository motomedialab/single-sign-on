<?php

namespace Motomedialab\SingleSignOn\Factories;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Motomedialab\SingleSignOn\Data\AccessToken;
use Motomedialab\SingleSignOn\Exceptions\OAuthFailedException;

class TokenFactory
{
    /**
     * Retrieve an access token from an authorization code.
     *
     * @param string $code
     * @return AccessToken
     * @throws \Throwable
     */
    public static function getAccessToken(string $code): AccessToken
    {
        $response = self::makeRequest([
            'redirect_uri' => route('login-sso-callback'),
            'code' => $code,
        ]);

        throw_if(
            $response->failed(),
            new OAuthFailedException($response->json('error_description', 'Failed to exchange token'), 400)
        );

        return new AccessToken(
            accessToken: $response->json('access_token'),
            refreshToken: $response->json('refresh_token'),
            expiresAt: now()->addSeconds($response->json('expires_in')),
        );
    }

    /**
     * Retrieve a fresh AccessToken instance.
     *
     * @throws \Throwable
     */
    public static function refreshToken(string $refreshToken): AccessToken
    {
        $response = self::makeRequest([
            'refresh_token' => $refreshToken,
        ], true);

        throw_if(
            $response->failed(),
            new OAuthFailedException($response->json('error_description', 'Failed to get refresh token'), 400)
        );

        return new AccessToken(
            accessToken: $response->json('access_token'),
            refreshToken: $response->json('refresh_token'),
            expiresAt: now()->addSeconds($response->json('expires_in')),
        );
    }


    /**
     * Make a request to the token exchange endpoint.
     *
     * @param array $params
     * @param bool $refresh
     * @return Response
     */
    private static function makeRequest(array $params = [], bool $refresh = false): Response
    {
        return Http::asForm()
            ->post(config('sso.endpoints.token'), array_filter([
                'grant_type' => $refresh ? 'refresh_token' : 'authorization_code',
                'client_id' => config('sso.client.id'),
                'client_secret' => config('sso.client.secret'),
                ...$params,
            ]));
    }

}
