<?php

namespace App\Jobs;

use App\Events\OtpSent;
use App\Exceptions\ApplicationRequestCompleted;
use App\Exceptions\OtpFailed;
use Zttp\PendingZttpRequest;

class SendOtp {

    /**
     * @var
     */
    private $randomKey;
    /**
     * @var
     */
    private $clientIp;
    /**
     * @var
     */
    private $browserInfo;
    /**
     * @var
     */
    private $browserVersion;

    /**
     * @var PendingZttpRequest
     */
    protected $sender = null;

    /**
     * Create a new job instance.
     *
     * @param $randomKey
     * @param $clientIp
     * @param $browserInfo
     * @param $browserVersion
     */

    public function __construct($randomKey, $clientIp, $browserInfo, $browserVersion = '')
    {
        $this->randomKey = $randomKey;
        $this->clientIp = $clientIp;
        $this->browserInfo = $browserInfo;
        $this->browserVersion = $browserVersion;

        $this->sender = new PendingZttpRequest();
    }



    public static function fromRequest()
    {
        return new static(
            request()->route('token'),
            request()->getClientIp(),
            request()->userAgent()
        );
    }

    /**
     * Execute the job.
     * @return void
     * @throws ApplicationRequestCompleted
     * @throws OtpFailed
     */
    public function handle()
    {
      /*  $responseJson = cache()->remember('Check_Digital_Doc_Otp', 60, function () {
            return $this->sender->asFormParams()->post(config('pendency.api_url') . 'Check_Digital_Doc_Otp', [
                'RANDOM_KEY'      => $this->randomKey,
                'CLIENT_IP'       => $this->clientIp,
                'BROWSER_INFO'    => $this->browserInfo,
                'BROWSER_VERSION' => $this->browserVersion,
            ])->soap();
        });*/


            $responseJson = $this->sender->asFormParams()->post(config('pendency.api_url') . 'Check_Digital_Doc_Otp', [
                'RANDOM_KEY'      => $this->randomKey,
                'CLIENT_IP'       => $this->clientIp,
                'BROWSER_INFO'    => $this->browserInfo,
                'BROWSER_VERSION' => $this->browserVersion,
            ])->soap();

        $responseJson = data_get($responseJson, 'OBJECT.0.CHECKDIGITALDOCOTP.0');

        switch ($responseJson['RETURN_CODE']) {
            case '0':
                event(new OtpSent(
                        $responseJson['EMAIL_ID'],
                        $responseJson['FILE_NO'],
                        $responseJson['MOBILE_NO'],
                        $responseJson['CUST_NAME'],
                        $this->randomKey)
                );
                break;
            case '-15':
                throw new OtpFailed($responseJson['RETURN_MESSAGE'] ?? null);
                break;
            case '-10':
                throw new ApplicationRequestCompleted;
                break;
            default:
        }
    }
}
