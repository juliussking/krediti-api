<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class CompanyNotFoundException extends Exception
{
    use RenderToJson;

    protected $message = 'Company not found!';
    protected $code = 400;
    
}
