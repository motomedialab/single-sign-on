<?php

namespace Motomedialab\SingleSignOn\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Motomedialab\SingleSignOn\Data\AccessToken;
use Motomedialab\SingleSignOn\Models\SsoToken;

/**
 * @mixin Model
 */
trait HasSsoToken
{
    public function ssoToken(): HasOne
    {
        return $this->hasOne(SsoToken::class);
    }

    public function setSsoToken(AccessToken $token)
    {
        $token = $this->ssoToken()->firstOrNew()
            ->fillFromAccessToken($token);

        return tap($token)->save();
    }
}
