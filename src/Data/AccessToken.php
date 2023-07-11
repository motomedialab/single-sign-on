<?php

namespace Motomedialab\SingleSignOn\Data;

use Illuminate\Support\Carbon;

class AccessToken
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
        public readonly Carbon $expiresAt,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_at' => $this->expiresAt->toIso8601String(),
        ];
    }
}
