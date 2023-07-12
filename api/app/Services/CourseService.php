<?php

namespace App\Services;

use App\Contracts\CourseContract;
use App\DataTransfers\Courses\CreateCourseDTO;
use App\Models\Course;
use App\Exceptions\Handler;

class CourseService implements CourseContract {

    public function create(CreateCourseDTO $createCourseDTO): Course {
        return Course::create([
           'title'       => $createCourseDTO->title,
           'description' => $createCourseDTO->description,
           'price'       => $createCourseDTO->price,
           'account_id'  => auth()->id()
        ]);
    }

    public function show(int $id): Course {

        return Course::findOrFail($id);
    }

    public function update(CreateCourseDTO $createCourseDTO, int $id): ?Course {
        Course::where('id', $id)->update([
            'title'       => $createCourseDTO->title,
            'description' => $createCourseDTO->description,
            'price'       => $createCourseDTO->price
        ]);

        return $this->show($id);
    }

    public function delete(int $id): bool {
        $course = $this->show($id);

        return $course->delete();
    }
}
