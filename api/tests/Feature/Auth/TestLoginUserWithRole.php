<?php

namespace Tests\Feature;

use App\DataTransfers\LoginDTO;
use App\Models\Role;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestLoginUserWithRole extends TestCase
{
    use RefreshDatabase;

    public function test_LoginWithValidCredentialsWithUserRole()
    {
        $this->seed();

        $user = User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('Password123'),
        ]);

        $loginDTO = new LoginDTO($user->email, 'Password123',
            'User'
        );
        $roleObj = Role::whereName($loginDTO->role)->first();
        $user->roles()->attach($roleObj->id);

        $authService = new AuthService();

        $userData = $authService->login($loginDTO);

        $this->assertNotNull($userData);
        $this->assertArrayHasKey('user', $userData);
        $this->assertArrayHasKey('token', $userData);
        $this->assertArrayHasKey('expired_at', $userData);
    }

    public function test_LoginWithValidCredentialsWithTeacherRole()
    {
        $this->seed();

        $user = User::factory()->create([
            'name'  => 'Test Teacher',
            'email' => 'test-teacher@example.com',
            'password' => bcrypt('Password123'),
        ]);

        $loginDTO = new LoginDTO($user->email, 'Password123',
            'Teacher'
        );
        $roleObj = Role::whereName($loginDTO->role)->first();
        $user->roles()->attach($roleObj->id);

        $authService = new AuthService();

        $userData = $authService->login($loginDTO);

        $this->assertNotNull($userData);
        $this->assertArrayHasKey('user', $userData);
        $this->assertArrayHasKey('token', $userData);
        $this->assertArrayHasKey('expired_at', $userData);
    }

}
