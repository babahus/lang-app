<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class TestGetProfileInfo extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_profile_info_for_user_with_User_role()
    {
        $this->seed();

        $user = User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Password123'),
        ]);
        $role = Role::whereName('User')->first();
        $user->roles()->attach($role->id);

        Cache::shouldReceive('get')
            ->with('users_role_' . $user->id)
            ->andReturn(['role_id' => $role->id]);

        $response = $this->actingAs($user)->getJson(route('profile.info'));

        $response->assertOk();
    }
}
