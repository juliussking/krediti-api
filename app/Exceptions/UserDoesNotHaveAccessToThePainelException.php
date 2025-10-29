<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class UserDoesNotHaveAccessToThePainelException extends Exception
{
    use RenderToJson;

    protected $message = 'User does not have access to the painel.';
    protected $code = 400;
}
