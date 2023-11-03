<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class TestGetProviderLink extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_generate_provider_redirect_link()
    {
        $this->seed();

        Socialite::shouldReceive('with')
            ->once()
            ->with('facebook')
            ->andReturnSelf()
            ->shouldReceive('with')
            ->andReturnSelf()
            ->shouldReceive('stateless')
            ->andReturnSelf()
            ->shouldReceive('redirect')
            ->andReturnSelf()
            ->shouldReceive('getTargetUrl')
            ->andReturn('https://redirect-link-for-provider.com');

        $response = $this->getJson('api/login/facebook?role=User');

        $response->assertStatus(200)
            ->assertExactJson(['data' => 'https://redirect-link-for-provider.com', 'success' => true]);
    }
}
