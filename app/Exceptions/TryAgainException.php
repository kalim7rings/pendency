<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class TryAgainException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {

    }

    /**
     * @param $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render($request)
    {
       $baseUrl =  url(session()->get('random_key'));

       return view('errors.500',[ 'message' => "Something Went wrong, <a href='{$baseUrl}'>Try Again</a>"]);
    }
}
