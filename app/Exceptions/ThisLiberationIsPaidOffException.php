<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class ThisLiberationIsPaidOffException extends Exception
{
   use RenderToJson;

   protected $message = 'This liberation is paid off';
   protected $code = 400;
}
