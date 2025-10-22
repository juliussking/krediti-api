<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class PaymentNotFoundException extends Exception
{
    use RenderToJson;

    protected $message = "Payment not found";

    protected $code = 404;
}
