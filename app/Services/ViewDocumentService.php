<?php

namespace App\Services;

use App\Http\Requests\ViewDocumentRequest;

class ViewDocumentService extends Service {


    public static function fromRequest(ViewDocumentRequest $request){
        return new static([
              'DIGITAL_REQ_SRNO' =>  $request->requestSerialNumber(),
              'DIGITAL_DTL_SRNO' =>  $request->detailsSerialNumber()
        ]);
    }

    public function get()
    {
        $responseJson =  $this->postJson('ViewLeadDocument')->soap();
        return $responseJson['OBJECT'][0]['MESSAGE'][0];
    }

}