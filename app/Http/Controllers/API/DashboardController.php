<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UpdatePasswordRequest;
use App\Jobs\CheckUpdatePassword;
use App\Services\CheckPasswordService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function update(UpdatePasswordRequest $request)
    {
      return $this->dispatchNow(CheckUpdatePassword::fromRequest($request));

/*
        $data = (new CheckPasswordService([
            'DIGITAL_DTL_SRNO' => request('sr_no'),
            'PASSWORD'         => request('password'),
        ]))->updatePassword();


        return response()->json($data);*/



        //return request()->all();
        //$password = request('password');
        //$dtlSrno = request('sr_no');

        //CheckPasswordService->updatePassword();

        /*(new CheckPasswordService)->updatePassword([
            'DIGITAL_DTL_SRNO' => request('sr_no'),
            'PASSWORD'         => request('password'),
        ]);*/




        //{"password":"sdsdasd","sr_no":"182"}

        /*$password = $request->getParams()['password'];
        $dtlSrno = $request->getParams()['dtl_srno'];


            $result = $this->guzzle->request('POST', $this->settings['webservice_url'] . 'Read_Password_protected_file', [
                'form_params' => [
                    'DIGITAL_DTL_SRNO' => $dtlSrno,
                    'PASSWORD'         => $password,
                ],
                'verify'      => false,
            ]);

            $result = soap_response($result->getBody());

            $result = $result['OBJECT'][0]['GET_FILE_DATA'][0];

            return $response->withJson($result);*/

    }
}
