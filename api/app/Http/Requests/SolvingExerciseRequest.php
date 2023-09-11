<?php

namespace App\Http\Requests;

use App\DataTransfers\SolvingExerciseDTO;
use App\Enums\ExercisesTypes;
use App\Rules\Exercise\Solving\AuditRule;
use App\Rules\Exercise\Solving\CompilePhraseRule;
use App\Rules\Exercise\Solving\DictionaryRule;
use App\Rules\Exercise\Solving\ExerciseIdExistsRule;
use App\Rules\Exercise\Solving\PairExerciseRule;
use App\Rules\Exercise\Solving\PictureExerciseRule;
use App\Rules\Exercise\Solving\SentenceRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class SolvingExerciseRequest extends BaseRequest
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
            'id' => match (ExercisesTypes::inEnum($this->input('type'))) {
                ExercisesTypes::COMPILE_PHRASE => ['required', 'numeric', Rule::exists('compile_phrases', 'id')],
                ExercisesTypes::DICTIONARY => ['required', 'numeric', Rule::exists('dictionaries', 'id')],
                ExercisesTypes::AUDIT => ['required', 'numeric', Rule::exists('audits', 'id')],
                ExercisesTypes::PAIR_EXERCISE => ['required', 'numeric', Rule::exists('pair_exercises', 'id')],
                ExercisesTypes::PICTURE_EXERCISE => ['required', 'numeric', Rule::exists('picture_exercises', 'id')],
                ExercisesTypes::SENTENCE => ['required', 'numeric', Rule::exists('sentence', 'id')],
                default => 'nullable'
            },
            'data' => match (ExercisesTypes::inEnum($this->input('type'))) {
                ExercisesTypes::COMPILE_PHRASE => ['required', new CompilePhraseRule()],
                ExercisesTypes::AUDIT => ['required', new AuditRule()],
                ExercisesTypes::PICTURE_EXERCISE => ['required', new PictureExerciseRule()],
                ExercisesTypes::DICTIONARY => ['required', new DictionaryRule()],
                ExercisesTypes::SENTENCE => ['required', new SentenceRule()],
                ExercisesTypes::PAIR_EXERCISE => ['required', new PairExerciseRule()],

                default => 'nullable',
            },
            'type' => ['required', 'string', new Enum(ExercisesTypes::class)],
            'exercise_id' => ['required', 'numeric', new ExerciseIdExistsRule()]
        ];
    }

    public function getDTO(): SolvingExerciseDTO
    {
        return new SolvingExerciseDTO(
            $this->input('id'),
            $this->input('type'),
            $this->input('data'),
            $this->input('exercise_id'),
        );
    }
}
