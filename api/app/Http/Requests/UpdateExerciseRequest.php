<?php

namespace App\Http\Requests;

use App\DataTransfers\UpdateExerciseDTO;
use App\Enums\ExercisesTypes;
use App\Rules\Exercise\AdditionalDataValidationRule;
use App\Rules\Exercise\DataValidationRule;
use Illuminate\Validation\Rules\Enum;

final class UpdateExerciseRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => ['required', new DataValidationRule()],
            'additional_data' => ['nullable', new AdditionalDataValidationRule()],
            'type' => ['required', 'string', new Enum(ExercisesTypes::class)]
        ];
    }

    public function getDTO(): UpdateExerciseDTO
    {
        return new UpdateExerciseDTO(
            match (ExercisesTypes::inEnum($this->input('type'))){
                ExercisesTypes::AUDIT,ExercisesTypes::PICTURE_EXERCISE  => $this->file('data'),
                default               => $this->input('data'),
            },
            $this->input('type'),
            $this->input('additional_data') ?? null,
        );
    }

    //match (ExercisesTypes::inEnum($this->input('type'))){
    //    ExercisesTypes::COMPILE_PHRASE => ['required', 'numeric', Rule::exists('compile_phrase', 'id')],
    //    ExercisesTypes::DICTIONARY     => ['required', 'numeric', Rule::exists('contracts_drafts', 'id')]
    //}
}
