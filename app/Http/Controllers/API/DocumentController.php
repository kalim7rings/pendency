<?php

namespace App\Http\Controllers\API;

use App\Jobs\UploadDocument;
use App\Services\DocumentListsService;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DocumentController extends Controller {

    public function upload(Request $request)
    {
        return $this->dispatchNow(new UploadDocument(new Client, $request));

        /*print_r(request()->file('file'));


        print_r(request()->all());
        return $request;


        foreach ($request->getUploadedFiles()['files'] as $key => $value) {
            $params[] = [
                'name'     => $key,
                'contents' => @fopen($value->file, 'r'),
                'filename' => basename($value->getClientFilename()),
            ];
        }

        foreach ($request->getParsedBody() as $key => $value) {
            $params[] = [
                'name'     => strtoupper($key),
                'contents' => $value,
            ];
        }*/


        //return request()->file('file')->store('/');
        //return request()->all();

        //$imageName = time().'.'.$request->file->getClientOriginalExtension();
        //$request->file->move(public_path('images'), $imageName);

        //OR

        //return request()->file('file')->store('/');

        //return response()->json(['success'=>'You have successfully upload file.']);


        /*

        $result = $this->guzzle->request('POST', $this->settings['webservice_url'] . 'UploadDigiDocuments', ['multipart' => array_change_key_case($params, CASE_UPPER), 'verify' => false]);
            $result = soap_response($result->getBody());
            $result = $result['OBJECT'][0];

            if (!empty($result['UPLOADDOCUMENT'][0]['RETURN_CODE']) && $result['UPLOADDOCUMENT'][0]['RETURN_CODE'] == '15')
            {
                return $response->withJson($result, 422);
            }

            if ($result) {
                return $response->withJson($result);
            }
        */

    }

    public function docList()
    {
        return response()->json((DocumentListsService::fromSession())->toArray());
    }
}
