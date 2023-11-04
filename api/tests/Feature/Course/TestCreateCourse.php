<?php

namespace Tests\Feature\Course;

use App\Models\User;
use Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class TestCreateCourse extends TestCase
{

    use RefreshDatabase, WithFaker;

    /** @test */
    public function authorized_user_can_store_course()
    {
        $this->seed();
        $user = User::factory()->create();
        Cache::put("users_role_" . $user->id, ['role_id' => 2]); // Assuming 2 is an authorized role

        $response = $this->actingAs($user)->postJson('api/course', [
            'title'       => 'Sample Course',
            'description' => 'This is a sample course description.',
            'price'       => 50.00
        ]);

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'title',
                'description',
                'price'
            ],
        ]);

        $this->assertDatabaseHas('accounts_courses', [
            'title'       => 'Sample Course',
            'description' => 'This is a sample course description.'
        ]);
    }

    /** @test */
    public function unauthorized_user_cannot_store_course()
    {
        $this->seed();
        $user = User::factory()->create();
        Cache::put("users_role_" . $user->id, ['role_id' => 1]);

        $response = $this->actingAs($user)->postJson('api/course', [
            'title'       => 'Unauthorized Course',
            'description' => 'This should not be created.',
            'price'       => 100.00
        ]);

        $response->assertStatus(ResponseAlias::HTTP_FORBIDDEN); // Forbidden
    }

    /** @test */
    public function store_course_requires_validation()
    {
        $this->seed();
        $user = User::factory()->create();
        Cache::put("users_role_" . $user->id, ['role_id' => 2]);

        $response = $this->actingAs($user)->postJson('api/course', [
            'title'       => '', // Title is required
            // No description
            'price'       => 150000 // Price is above the maximum
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY); // Unprocessable Entity 422
        $response->assertJsonValidationErrors(['title', 'description', 'price']);
    }
}
