<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

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
          'role'  => Role::whereId(Cache::get('users_role_' . $this->resource['user']->id))->value('name') ?? 'User'
        ];
    }
}
