<?php

namespace Motomedialab\SingleSignOn\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Responsable;

class OAuthFailedException extends Exception implements Responsable
{

    public function toResponse($request)
    {
        abort($this->code, $this->message, [
            'error' => $this->code,
            'error_description' => $this->message,
        ]);
    }
}
