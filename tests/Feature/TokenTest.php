<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TokenTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    function it_authenticates_user_based_via_token()
    {
        $user = factory(User::class)->create();

        $this->get(route('token.login', $user->token));

        $this->assertAuthenticated();
    }

    /** @test */
    function if_the_token_is_invalid_it_redirects_user_to_home()
    {
        $this->get(route('token.login', 'INVALID_TOKEN'))
            ->assertRedirect('/');

        $this->assertFalse($this->isAuthenticated());

    }
}
