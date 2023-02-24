<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class DictionaryResource extends JsonResource
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
            $carry[$item['word']] = $item['translate'];

            return $carry;
        }, []);

        return [
          'id'         => $this->id,
          'dictionary' => $result,
          'updated_at' => $this->updated_at,
        ];
    }
}
