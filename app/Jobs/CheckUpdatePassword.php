<?php

namespace App\Jobs;

use App\Http\Requests\UpdatePasswordRequest;
use App\Services\Service;
use App\Exceptions\InvalidPasswordException;

class CheckUpdatePassword extends Service {

    public static function fromRequest(UpdatePasswordRequest $request)
    {
        return new static([
            'DIGITAL_DTL_SRNO' => $request->serialNumber(),
            'PASSWORD'         => $request->password(),
        ]);
    }

    /**
     * Execute the job.
     *
     * @return true
     * @throws InvalidPasswordException
     */
    public function handle()
    {
        $responseJson = $this->postJson('Read_Password_protected_file')->soap();

        $responseJson = $responseJson['OBJECT']['0']['GET_FILE_DATA']['0'];

        switch ($responseJson['RETURN_CODE']) {
            case '-12':
                throw new InvalidPasswordException($responseJson['MESSAGE']);
            case '0':
                return true;
        }
    }
}
