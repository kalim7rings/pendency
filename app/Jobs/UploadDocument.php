<?php

namespace App\Jobs;

use App\PasswordMessagesHander;
use App\Services\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Zttp\PendingZttpRequest;

class UploadDocument extends Service {

    /**
     * @var Request
     */
    protected $request;
    private $sender;

    protected $client;

    protected $responseMessage;

    protected $showPasswordFlag = false;

    static private $isPassword;

    /**
     * Create a new job instance.
     *
     *
     * @param Client $client
     * @param Request $request
     */
    public function __construct(Client $client, Request $request)
    {
        $this->client = $client;
        $this->request = $request;
        $this->showPasswordFlag = false;
        $this->sender = new PendingZttpRequest;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {

        try {
            $responseJson = $this->sender->asMultipart()->post(config('pendency.api_url') . 'UploadDigiDocuments',
                array_merge($this->handleFiles(), $this->handleExtraParameter())
            )->soap()['OBJECT'][0];

            $responseJson = $responseJson['UPLOAD_DOCUMENT_DET'] ?? $responseJson['UPLOADDOCUMENT'];
            $responseJson = $responseJson['0'];

            /*****    NOTE :
             *     $responseJson['UPLOAD_DOCUMENT_DET']['0']['RETURN_CODE']  == 0     //for success
             *     $responseJson['UPLOADDOCUMENT'][0]['RETURN_CODE'] == '15'    // for password protected files
             */

            if ($responseJson['RETURN_CODE'] == '0') {
                return response()->json([
                    'status'  => true,
                    'message' => 'Document Uploaded Successfully.',
                ]);
            }

            if ($responseJson['RETURN_CODE'] == '15') {

                $file = $this->request->file('file')[0];

                $passwordMessagesHander = new PasswordMessagesHander($file->getClientOriginalName());

                $passwordMessagesHander->setMessage();

                return response()->json([
                    'status'      => false,
                    'return_code' => $passwordMessagesHander->getStatusCode(),
                    'message'     => $passwordMessagesHander->getMessage(),
                ], 422);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Error While uploading Document',
            ], 417);

        } catch (ServerException | \Exception | \Throwable $e) {

            dd($e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], $e->getCode());

        }

    }

    private function handleFiles()
    {
        $files = collect($this->request->file('file'));

        $request = [];

        foreach ($files as $key => $file) {
            $request[] = [
                'name'     => 'files[]',
                'contents' => fopen($file->getLinkTarget(), 'r'),
                'filename' => basename($file->getClientOriginalName()),
            ];
        }

        return $request;
    }

    private function handleExtraParameter()
    {
        $requestParams = $this->request->except('file');

        $params = collect();

        foreach (array_keys($requestParams) as $val) {
            $params->push([
                'name'     => strtoupper($val),
                'contents' => $requestParams[$val],
            ]);
        }

        return $params->toArray();
    }


}
