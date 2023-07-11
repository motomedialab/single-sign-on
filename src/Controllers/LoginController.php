<?php

namespace Motomedialab\SingleSignOn\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController
{
    public function __invoke(Request $request): RedirectResponse
    {
        // store our state.
        $request->session()->put('state', $state = Str::random(40));

        // generate our redirect URL.
        return redirect(config('sso.endpoints.authorize') . '?' . http_build_query([
            'client_id' => config('sso.client.id'),
            'redirect_uri' => route('login-sso-callback'),
            'response_type' => 'code',
            'scope' => config('sso.scopes'),
            'state' => $state,
            ...config('sso.authorization_query', []),
        ]));
    }
}
