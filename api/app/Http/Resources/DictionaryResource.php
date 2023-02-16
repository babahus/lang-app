<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DictionaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $result = array_reduce(json_decode($this->dictionary, true), function ($carry, $item) {
            $item = str_replace(['{', '}', "'", " "], '', $item);
            [$key, $value] = explode(',', $item);
            [$translate, $word] = explode(':', $value);
            $carry[trim(explode(':', $key)[1])] = $word;
            return $carry;
        }, []);
        return [
          'dictionary' => $result,
          'updated_at' => $this->updated_at,
        ];
    }
}
