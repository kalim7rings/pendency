<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    /** @test */
    function it_should_take_token_from_url()
    {
        $this->get(route('/', '123456'));

    }

    /** @test */
    function it_should_call_api_and_return_few_data_on_page_load()
    {
        $this->post('/', [

        ])->assertRedirect('/');
    }
}
