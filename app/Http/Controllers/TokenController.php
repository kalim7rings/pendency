<?php

namespace App\Http\Controllers;


use App\User;

class TokenController extends Controller {

    public function login($token)
    {
        $user = User::findByToken($token);

        if ($user) {
            auth()->loginUsingId($user->getKey());

            return redirect('/');
        }

        return redirect('/');
    }
}
