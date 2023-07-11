<?php

namespace Motomedialab\SingleSignOn\Contracts;

use Illuminate\Http\RedirectResponse;
use Motomedialab\SingleSignOn\Data\AccessToken;

interface LogsInUser
{
    public function __invoke(AccessToken $token): RedirectResponse;
}
