<?php

namespace App\Listeners;

use App\Events\OtpSent;
use Illuminate\Session\Store as Session;

class BindOtpResponseToSession {

    /**
     * @var Session
     */
    private $session;

    /**
     * Create the event listener.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Handle the event.
     *
     * @param  OtpSent $event
     *
     * @return void
     */
    public function handle(OtpSent $event)
    {
        $this->session->put('email_id', $event->emailId);
        $this->session->put('file_no', $event->fileNumber);
        $this->session->put('mobile_no', $event->mobileNo);
        $this->session->put('cust_name', $event->customerName);
        $this->session->put('random_key', $event->randomKey);
    }
}
