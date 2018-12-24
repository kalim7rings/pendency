<?php

namespace App\Jobs;

use GuzzleHttp\Exception\ServerException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mockery\Exception;
use Zttp\PendingZttpRequest;

class GetDocumentList implements ShouldQueue
{
    private $sender = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sender = new PendingZttpRequest();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $response =  $this->sender->asFormParams()->post(config('pendency.api_url'). 'Get_Digital_Doc_List',[
                'RANDOM_KEY'       => session('random_key'),
                'SESSION_KEY'      => session('session_key'),
                'DIGITAL_DOC_SRNO' => session('doc_srno'),
            ])->soap();

            $data['customerDetails'] = $response['OBJECT'][0]['DIGITAL_DOC_REQUEST'][0] ?? $response['OBJECT'][0]['DIGITAL_DOC_REQUEST'];

            $docs = $this->sortAllDocs($response['OBJECT'][1]['DIGITAL_DOC_REQ_DTLS']);
            $data['pendingDocList'] = $docs['PENDING'] ?? [];
            $data['receivedDocList'] = $docs['RECEIVED'] ?? [];
            $data['passwordDocList'] = $docs['PASSWORD'] ?? [];

            return $data;

           /* return response()->json([
                'status' => true, $data,
            ], 200);*/

        }
        catch(ServerException | Exception $e){
            return response()->json([
               'status' => false,
               'message' => $e->getMessage(),
            ], $e->getCode());
        }

    }


    public function sortAllDocs(array $docs)
    {
        $docArray = [];

        foreach ($docs as $doc){

            if($doc['STATUS'] == 'PENDING') {
                $docArray['PENDING'][$doc['APPL_CUST_NUMBER']][] = $doc;
            }

            if($doc['PASSWORD_FLAG'] == 'Y') {
                $docArray['PASSWORD'][] = $doc;
            }

            if( ($doc['STATUS'] == 'RECEIVED' || $doc['STATUS'] == 'UPLOADED')  && $doc['PASSWORD_FLAG'] != 'Y'){
                $docArray['RECEIVED'][] = $doc;
            }
        }

        return $docArray;
    }


}
