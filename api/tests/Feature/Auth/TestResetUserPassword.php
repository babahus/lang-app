<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class TestResetUserPassword extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_can_reset_their_password()
    {
        $user = User::factory()->create([
            'name'  => 'Test Reacher',
            'email' => 'test-reacher@example.com',
            'password' => bcrypt('Password123'),
        ]);

        $token = Password::createToken($user);

        $response = $this->postJson(route('password.reset'), [
            'token' => $token,
            'email' => 'test-reacher@example.com',
            'password' => 'newPassword123',
            'password_confirmation' => 'newPassword123',
        ]);

        $response->assertStatus(200)
            ->assertExactJson(['data' => 'Password reset successfully', 'success' => true]);

        $this->assertTrue(Hash::check('newPassword123', $user->fresh()->password));
    }
}
