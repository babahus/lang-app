<?php

namespace Tests\Feature\Auth;

use App\Mail\EmailMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TestSendResetLinkEmail extends TestCase
{
    use RefreshDatabase;

    public function test_sends_a_reset_password_email()
    {
        $user = User::factory()->create([
            'name'  => 'Test Reacher',
            'email' => 'test-reacher@example.com',
            'password' => bcrypt('Password123'),
        ]);

        Mail::fake();

        $response = $this->postJson('api/forgot-password', ['email' => 'test-reacher@example.com']);

        Mail::assertSent(EmailMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $response->assertExactJson(['data' => 'Password reset link sent', "success" => true]);
    }
}
