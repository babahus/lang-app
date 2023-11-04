<?php

namespace Tests\Feature\Course;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestShowCourse extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function show_course()
    {
        $this->seed();
        $course = Course::factory(['account_id' => 1])->create();

        $response = $this->getJson("api/course/{$course->id}");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $course->id,
                'title' => $course->title,
            ]
        ]);

        $responseData = $response->json('data');
        $this->assertEquals($course->id, $responseData['id']);
        $this->assertEquals($course->title, $responseData['title']);
    }

}
