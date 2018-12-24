<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadDocumentController extends Controller
{

    public function uplaod()
    {
        print_r(request()->all());
        return request()->all();
    }
}
