<?php

namespace App\Http\Controllers;

use App\Http\Requests\ViewDocumentRequest;
use App\PasswordMessagesHander;
use App\Services\DocumentListsService;
use App\Services\ViewDocumentService;
use Illuminate\Support\Facades\Request;

class DashboardController extends Controller {

    public function index()
    {
        PasswordMessagesHander::flushMessages();
        //return session()->all();

        $data = (DocumentListsService::fromSession())->toArray();

        //dd($data);

        //session()->put('doc_srno'
        $user = session()->all();
        //dd($user);

        return view('dashboard.index', compact('data', 'user'));
    }

    public function viewDocument(ViewDocumentRequest $request)
    {
        $responseData = ViewDocumentService::fromRequest($request)->get();

        //return $responseData;

        $data = $responseData['FILE_DATA'];

        $data = base64_decode($data);

        $ext = strtolower(pathinfo($responseData['FILE_NAME'], PATHINFO_EXTENSION));

        $mimes = new \Mimey\MimeTypes;

        $contentType = $mimes->getMimeType($ext);   //get content type

        $disposition = 'attachment';

        if (str_contains(strtolower($contentType), 'image')) {
            $disposition = 'inline';
        }

        if (str_contains(strtolower($contentType), 'pdf')) {
            $disposition = 'inline';
        }

        //dd($disposition,'hii', $contentType);

        return response($data)
            ->header('Content-type', $contentType)
            ->header('Content-Length', strlen($data))
            ->header('Content-Disposition', $disposition . "; filename=" . $responseData['FILE_NAME']);

        //->header('Content-Disposition', "'.$disposition.'; filename=" . $responseData['FILE_NAME']);
        //->withHeader('Content-Disposition', $disposition.'; filename="' . $result['FILE_NAME'] . '"')

        /* header("Content-Length:" . strlen($data));
         //header("Content-Disposition: inline; filename=".request('doc_path'));
         header("Content-Disposition: inline; filename=" . $responseData['FILE_NAME']);
         print $data;*/


    }

    public function logout()
    {
        request()->session()->flush();
        return view('logout.logout');
    }

}
