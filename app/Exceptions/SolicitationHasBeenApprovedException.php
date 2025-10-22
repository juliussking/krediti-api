<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class SolicitationHasBeenApprovedException extends Exception
{
    use RenderToJson;

    protected $message = "Solicitation has been approved";
    protected $code = 400;
}
