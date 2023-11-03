<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class TestChangeUserEmail extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_change_email()
    {
        $user = User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Password123'),
        ]);
        $this->actingAs($user);

        $data = [
            'email' => 'test1@example.com',
        ];

        $response = $this->json('POST', route('email.change'), $data);

        $user->refresh();
        $this->assertTrue($data['email'] == $user->email, 'The email has not been changed.');

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertJson([
            'data' => 'Email changed successfully', 'success' => true
        ]);
    }
}
