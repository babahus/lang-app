<?php

namespace App\Http\Resources\Progress;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgressExerciseProgressResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'accounts_exercise_id' => $this->accounts_exercise_id,
            'account_id' => $this->account_id,
            'solved' => $this->solved,
            'solved_at' => $this->solved_at,
            'user' => new UserResource($this->user),
        ];
    }
}
