<?php

namespace App\Events;

class OtpSent {

    /**
     * @var string
     */
    public $emailId;
    /**
     * @var int
     */
    public $fileNumber;
    /**
     * @var int
     */
    public $mobileNo;
    /**
     * @var string
     */
    public $customerName;
    /**
     * @var string
     */
    public $randomKey;

    /**
     * Create a new event instance.
     *
     * @param string $emailId
     * @param int $fileNumber
     * @param int $mobileNo
     * @param string $customerName
     * @param string $randomKey
     */
    public function __construct($emailId, $fileNumber, $mobileNo, $customerName, $randomKey)
    {
        $this->emailId = $emailId;
        $this->fileNumber = $fileNumber;
        $this->mobileNo = $mobileNo;
        $this->customerName = $customerName;
        $this->randomKey = $randomKey;
    }
}
