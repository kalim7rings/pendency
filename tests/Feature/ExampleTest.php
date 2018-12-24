<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    function it_sss_user_based_via_token()
    {
        dd(route('token.login','1212132'));
        $this->get(route('token.login','1212132'))->assertSee('Hello World');

    }
}
