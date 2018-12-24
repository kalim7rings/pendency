<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    function it_finds_the_user_by_token_id()
    {
        $user = factory(User::class)->create();

        $this->assertTrue($user->is(User::findByToken($user->token)));
    }
}
