<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class ClientNotFoundException extends Exception
{
    use RenderToJson;

    protected $message = 'Client not found!';
    protected $code = 404;
}
