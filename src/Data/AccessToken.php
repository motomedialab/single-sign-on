<?php

namespace Motomedialab\SingleSignOn\Data;

use Illuminate\Support\Carbon;

class AccessToken
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
        public readonly Carbon $expiresAt,
    )
    {
        //
    }
}
