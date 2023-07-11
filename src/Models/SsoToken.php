<?php

namespace Motomedialab\SingleSignOn\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Motomedialab\SingleSignOn\Data\AccessToken;
use Motomedialab\SingleSignOn\Factories\TokenFactory;

class SsoToken extends Model
{
    protected $fillable = [
        'access_token',
        'refresh_token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function refresh(): static
    {
        return tap($this)->fillFromAccessToken(
            TokenFactory::refreshToken($this->refresh_token)
        )->save();
    }

    public function fillFromAccessToken(AccessToken $token): static
    {
        return $this->fill($token->toArray());
    }

}
