<?php

namespace App\Services;

use App\Exceptions\TryAgainException;

class DocumentListsService extends Service implements ServiceContract {

    public static function fromSession()
    {
        return new static([
            'RANDOM_KEY'       => session('random_key'),
            'SESSION_KEY'      => session('session_key'),
            'DIGITAL_DOC_SRNO' => session('doc_srno'),
        ]);
    }

    public function toArray()
    {
        $responseJson = $this
                ->postJson('Get_Digital_Doc_List')
                ->soap()['OBJECT'] ?? [];

          //dd($responseJson);
        //$responseJson = [];

        if(empty($responseJson)){
            throw new TryAgainException;
        }

        $groupedDocuments = $this->sortAllDocs(data_get($responseJson, '1.DIGITAL_DOC_REQ_DTLS', []));

        return [
            'DATA' => [
                'customerDetails' => data_get($responseJson, '0.DIGITAL_DOC_REQUEST.0'),
                'passwordDocList' => $groupedDocuments['PASSWORD'] ?? [],
                'pendingDocList'  => $groupedDocuments['PENDING'] ?? [],
                'receivedDocList' => $groupedDocuments['RECEIVED'] ?? [],
            ],
        ];
    }

    private function sortAllDocs(array $docs)
    {
        $docArray = [];

        foreach ($docs as $doc) {

            if ($doc['STATUS'] == 'PENDING') {
                $docArray['PENDING'][$doc['APPL_CUST_NUMBER']][] = $doc;
            }

            if ($doc['PASSWORD_FLAG'] == 'Y') {
                $docArray['PASSWORD'][] = $doc;
            }

            if (($doc['STATUS'] == 'RECEIVED' || $doc['STATUS'] == 'UPLOADED') && $doc['PASSWORD_FLAG'] != 'Y') {
                $docArray['RECEIVED'][] = $doc;
            }
        }

        return $docArray;
    }
}