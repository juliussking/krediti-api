<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class LiberationNotFoundException extends Exception
{
    use RenderToJson;

    protected $message = 'Liberation not found!';
    protected $code = 400;
}
