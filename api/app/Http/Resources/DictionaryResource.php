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
        $arrValue = array_reduce(json_decode($this->dictionary, true), function ($carry, $item) {
            $carry[$item['word']] = $item['translation'];

            return $carry;
        }, []);

        $result =  array_map(function ($word, $translation) {
            return ['word' => $word, 'translation' => $translation];
        }, array_keys($arrValue), $arrValue);

        return [
          'id'         => $this->id,
          'dictionary' => $result,
          'updated_at' => $this->updated_at,
        ];
    }
}
