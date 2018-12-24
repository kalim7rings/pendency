<?php

namespace App\Exceptions;

use Exception;

class InvalidPasswordException extends Exception {

    public function render()
    {
        return response()->json([
            'status'  => false,
            'message' => $this->message,
        ], 403);
    }
}
