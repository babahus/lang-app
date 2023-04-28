<?php

namespace App\Contracts;

interface StageContract
{
    public function create();
    public function show();
    public function update();
    public function delete();
    public function attachToCourse();
    public function detachFromCourse();
}
