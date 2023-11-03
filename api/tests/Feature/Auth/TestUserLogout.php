<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TestUserLogout extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->accessToken;

        $this->actingAs($user);

        $dataUserRole = [
            'role_id' => 1,
            'user_id' => $user->id
        ];
        $dataUserToken = [
            'token' => $token,
            'user_id' => $user->id
        ];

        Cache::put("users_role_" . $user->id, $dataUserRole, now()->addHours(1));
        Cache::put("users_token_" . $user->id, $dataUserToken, now()->addHours(1));

        $this->assertTrue(Cache::has("users_role_" . $user->id));
        $this->assertTrue(Cache::has("users_token_" . $user->id));

        $response = $this->get('/api/logout');

        $response->assertOk();
        $response->assertJson(['data' => 'Successfully logged out', 'success' => true]);

        $this->assertFalse(Cache::has("users_role_" . $user->id));
        $this->assertFalse(Cache::has("users_token_" . $user->id));

        $this->assertEmpty($user->tokens);
    }
}
