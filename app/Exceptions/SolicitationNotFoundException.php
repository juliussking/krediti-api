<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class SolicitationNotFoundException extends Exception
{
    use RenderToJson;

    protected $message = 'Solicitation not found!';
    protected $code = 400;
}
