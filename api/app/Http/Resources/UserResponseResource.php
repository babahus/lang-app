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
          'id'    => $this->resource['user']->id,
          'name'  => $this->resource['user']->name,
          'email' => $this->resource['user']->email,
          'token' => $this->resource['token'],
        ];
    }
}
