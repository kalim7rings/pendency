<?php

namespace App;

class PasswordMessagesHander {

    private $session;
    private $prefix = 'PasswordMessagesHander';
    private $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        $this->session = session();
    }

    public function setMessage($fileName = null)
    {
        $fileName = $fileName ?? $this->fileName;

        $messages = $this->session->get($this->prefix);

        if ($this->isInvalidPassword($fileName, $messages)) {
            data_set($messages, "$fileName.message", 'Invalid password.');
            data_set($messages, "$fileName.statusCode", 16);
        } else {
            data_set($messages, "$fileName.message", 'The file is password protected, please enter the password in the space provided.');
            data_set($messages, "$fileName.statusCode", 15);
        }

        $this->session->put($this->prefix, $messages);
    }

    public function getMessage($fileName = null)
    {
        $fileName = $fileName ?? $this->fileName;

        return $this->session->get("$this->prefix.$fileName.message");
    }

    public function getAllMessages()
    {
        return $this->session->get("$this->prefix", []);
    }

    private function isInvalidPassword($fileName, $messages)
    {
        return data_get($messages, $fileName) != null;
    }

    public static function flushMessages()
    {
        session()->forget('PasswordMessagesHander');
    }

    public function getStatusCode()
    {
        return $this->session->get("{$this->prefix}.{$this->fileName}.statusCode");
    }
}