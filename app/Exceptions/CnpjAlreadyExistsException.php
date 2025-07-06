<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class CnpjAlreadyExistsException extends Exception
{
    use RenderToJson;

    protected $message = 'Cnpj already registered!';
    protected $code = 400;
    
}