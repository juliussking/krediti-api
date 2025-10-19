<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class UserAndClientCompanyDontMatchException extends Exception
{
    use RenderToJson;

    protected $message = 'The user and client company dont match';
    protected $code = 400;
}
