<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class TaskNotFoundException extends Exception
{
    use RenderToJson;

    protected $message = "Task not found!";
    protected $code = 404;
}
