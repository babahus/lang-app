<?php

namespace Tests\Feature\Course;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class TestUpdateCourse extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
//    public function course_update()
//    {
//        $this->seed();
//        $user = User::factory()->create();
//        $course = Course::factory()->create(['account_id' => $user->id]);
//
//        $dataToUpdate = [
//            'title' => 'Updated Title',
//            'description' => 'Updated Description',
//            'price' => 999.99,
//        ];
//
//        $response = $this->actingAs($user)->putJson("api/course/{$course->id}", $dataToUpdate);
//
//        $response->assertOk();
//        $response->assertJson([
//            'data' => [
//                'id' => $course->id,
//                'title' => $dataToUpdate['title'],
//                'description' => $dataToUpdate['description'],
//            ]
//        ]);
//
//        $course->refresh();
//        $this->assertEquals($dataToUpdate['title'], $course->title);
//        $this->assertEquals($dataToUpdate['description'], $course->description);
//    }

    /** @test */
    public function course_update_unauthorized()
    {
        //$this->seed();
        \Log::info('Current User', ['user' => auth()->user()]);
        $course = Course::factory()->create(['account_id' => 1]);
        $dataToUpdate = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'price' => 999.99,
        ];

        $response = $this->putJson("api/course/{$course->id}", $dataToUpdate);

        $response->assertStatus(ResponseAlias::HTTP_UNAUTHORIZED); // 401
    }

    /** @test */
//    public function course_update_with_invalid_data()
//    {
//        $this->seed();
//        $user = User::factory()->create();
//        $course = Course::factory()->create(['account_id' => $user->id]);
//
//        $invalidDataToUpdate = [
//            'title' => '',
//            'description' => '',
//
//        ];
//
//        $response = $this->actingAs($user)->putJson("api/course/{$course->id}", $invalidDataToUpdate);
//
//        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY); // 422
//        $response->assertJsonValidationErrors(['title', 'description']);
//    }


}
