<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class CompanyDontHasSubscriptionException extends Exception
{
    use RenderToJson;

    protected $message = 'Company dont has subscription!';
    protected $code = 400;
    
}
