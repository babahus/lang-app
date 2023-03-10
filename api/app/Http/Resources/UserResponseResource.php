<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class UserResponseResource extends JsonResource
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
          'name'  => $this->name,
          'email' => $this->email,
          'token' => $this->createToken('authToken')->plainTextToken,
        ];
    }
}
