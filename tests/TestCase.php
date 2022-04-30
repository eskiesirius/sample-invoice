<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @return User             
     */
    protected function login()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user);

        return $user;
    }
}
