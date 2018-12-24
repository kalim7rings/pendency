<?php

namespace App\Http\Controllers;

use App\Jobs\SendOtp;

class HomeController extends Controller
{
    public function index()
    {
        $this->dispatchNow(SendOtp::fromRequest());

        //dd(session()->all());

        return view('home.index', [ 'user' => session()->all() ]);
    }
}
