<?php

namespace Tests\Feature\Profile;

use App\Mail\EmailMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TestSendVerificationNotification extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_verification_notification()
    {
        Mail::fake();
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->postJson(route('verification.send'));

        Mail::assertSent(EmailMail::class, function ($mail) use ($user) {
            // return $mail->viewData['user']->id === $user->id;

            return $mail->hasTo($user->email);
        });

        $response->assertOk();
        $response->assertJson(['data' => 'Verification link sent']);
    }
}
