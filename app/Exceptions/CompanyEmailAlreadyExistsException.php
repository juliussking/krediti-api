<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class CompanyEmailAlreadyExistsException extends Exception
{
    use RenderToJson;

    protected $message = 'Company email already registered!';
    protected $code = 400;
    
}
