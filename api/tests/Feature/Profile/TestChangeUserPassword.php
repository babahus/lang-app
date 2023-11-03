<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseStatusCodeSame;
use Tests\TestCase;

class TestChangeUserPassword extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_change_user_password()
    {
        $user = User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('Password123'),
        ]);
        $this->actingAs($user);

        $data = [
            'current_password' => 'Password123',
            'new_password' => 'newSecurePassword123',
            'password_confirmation' => 'newSecurePassword123',
        ];

        $response = $this->json('POST', route('password.change'), $data);

        $user->refresh();
        $this->assertTrue(Hash::check($data['new_password'], $user->password), 'The password has not been changed.');

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertJson([
            'data' => 'Password changed successfully', 'success' => true
        ]);
    }
}
