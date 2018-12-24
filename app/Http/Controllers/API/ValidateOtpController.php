<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InvalidOtp;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;
use Zttp\PendingZttpRequest;

class ValidateOtpController extends Controller
{
    private $sender;

    public function __construct()
    {
        $this->sender = new PendingZttpRequest();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
       request()->validate([
           'otp' => 'required|numeric|digits_between:4,6',
       ]);

       try{
           $response = $this->sender->asFormParams()->post(config('pendency.api_url'). 'Validate_Digital_Doc_Otp' ,[
               'RANDOM_KEY'      => session('random_key'),
               'OTP'             => request('otp'),
               'CLIENT_IP'       => request()->getClientIp(),
               'BROWSER_INFO'    => request()->userAgent(),
               'BROWSER_VERSION' => '',
           ])->soap();

           $response = data_get($response, 'OBJECT.0.TABLE.0');

           if($response['RETURN_CODE'] == '0')
           {
               session()->put('doc_srno', $response['DIGITAL_DOC_SRNO']);
               session()->put('session_key', $response['SESSION_KEY']);

               return response()->json([
                   'status' => true,
                   'data' => $response,
               ], 200);
           }

           if($response['RETURN_CODE'] == '10'){
               throw new InvalidOtp($response['RETURN_MESSAGE']);
           }
       }
       catch(Exception $e)
       {
           return response()->json([
              'status' =>false,
              'message' => $e->getMessage(),
           ], $e->getCode());
       }

    }
}
