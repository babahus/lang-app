<?php

namespace App\Http\Requests\Stages;

use App\Models\Stage;
use Illuminate\Foundation\Http\FormRequest;

class StageDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $stage = Stage::findOrFail($this->route('stage'));
        return $stage->course()->account_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
