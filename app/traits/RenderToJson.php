<?php

namespace App\Traits;

trait RenderToJson
{
    public function render($request)
    {
        return response()->json([
            'message' => $this->message,
            'error' => class_basename($this),
        ], $this->getCode());
    }
}