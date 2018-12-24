<?php

namespace Tests\Unit;

use App\PasswordMessagesHander;
use Tests\TestCase;

class PasswordMessagesHanderTest extends TestCase {

    private $fileName;

    protected function setUp()
    {
        parent::setUp();

        $this->fileName = 'a.pdf';
    }

    /** @test */
    function it_should_set_message_correctly()
    {
        $passwordMessagesHander = new PasswordMessagesHander($this->fileName);

        $passwordMessagesHander->setMessage();

        $this->assertEquals(
            'The file is password protected.',
            $passwordMessagesHander->getMessage()
        );

        $passwordMessagesHander->setMessage();

        $this->assertEquals(
            'Please enter valid password.',
            $passwordMessagesHander->getMessage()
        );
    }

    /** @test */
    function it_should_return_the_correct_status_code()
    {
        $passwordMessagesHander = new PasswordMessagesHander($this->fileName);

        $passwordMessagesHander->setMessage();

        $this->assertEquals(
            15,
            $passwordMessagesHander->getStatusCode()
        );

        $passwordMessagesHander->setMessage();

        $this->assertEquals(
            16,
            $passwordMessagesHander->getStatusCode()
        );
    }

    /** @test */
    function it_should_get_all_the_messages()
    {
        $passwordMessagesHander = new PasswordMessagesHander($this->fileName);

        $passwordMessagesHander->setMessage('a.pdf');

        $this->assertCount(1, $passwordMessagesHander->getAllMessages());
    }

    /** @test */
    function it_should_flush_the_session()
    {
        $passwordMessagesHander = new PasswordMessagesHander($this->fileName);

        $passwordMessagesHander->setMessage();

        $passwordMessagesHander->flushMessages();

        $this->assertCount(0, $passwordMessagesHander->getAllMessages());
    }
}
