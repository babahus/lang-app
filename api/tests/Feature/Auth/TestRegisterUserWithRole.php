<?php

namespace Tests\Feature;

use App\Mail\EmailMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TestRegisterUserWithRole extends TestCase
{
    use RefreshDatabase;

    public function test_UserRegistrationWithUserRole()
    {
        $this->seed();
        Mail::fake();
        Event::fake();

        $password = 'Securssword123';

        $requestData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => $password,
            'password_confirmation' => $password,
            'role' => 'User',
        ];

        $response = $this->json('POST', 'api/register', $requestData);
        $this->assertTrue($response->getStatusCode() != 422);
        $user = User::where('email', $requestData['email'])->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check($password, $user->password));
        $this->assertTrue($user->roles->where('name', 'User')->first()->id == 1);

        Mail::assertSent(EmailMail::class, function ($mail) use ($user) {
            $verificationUrl = $mail->data['dataUrl'];
            return $mail->hasTo($user->email);
        });

        $response->assertStatus(200);
    }

    public function test_UserRegistrationWithTeacherRole()
    {
        $this->seed();
        Mail::fake();
        Event::fake();

        $password = 'Securssword123';

        $requestData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => $password,
            'password_confirmation' => $password,
            'role' => 'Teacher',
        ];

        $response = $this->json('POST', 'api/register', $requestData);
        $this->assertTrue($response->getStatusCode() != 422);
        $user = User::where('email', $requestData['email'])->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check($password, $user->password));
        $this->assertTrue($user->roles->where('name', 'Teacher')->first()->id == 2);

        Mail::assertSent(EmailMail::class, function ($mail) use ($user) {
            $verificationUrl = $mail->data['dataUrl'];
            return $mail->hasTo($user->email);
        });

        $response->assertStatus(200);
    }
}
