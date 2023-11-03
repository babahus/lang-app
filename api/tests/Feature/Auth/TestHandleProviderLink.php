<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Socialite;
use Tests\TestCase;

class TestHandleProviderLink extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_handles_provider_callback_and_creates_a_new_user_with_user_role()
    {
        $this->seed();

        $mockSocialiteUser = (new \Laravel\Socialite\Two\User)->setRaw([
            'id' => '123456',
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $mockSocialiteUser->id = $mockSocialiteUser->getRaw()['id'];
        $mockSocialiteUser->name = $mockSocialiteUser->getRaw()['name'];
        $mockSocialiteUser->email = $mockSocialiteUser->getRaw()['email'];

        Socialite::shouldReceive('driver')
            ->once()
            ->with('facebook')
            ->andReturnSelf()
            ->shouldReceive('stateless')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($mockSocialiteUser);

        $response = $this->postJson('api/login/facebook/callback?role=User');

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }
}
