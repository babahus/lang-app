<?php

namespace App\Http\Controllers\Api;

use App\DataTransfers\GenerateExerciseFromAiDTO;
use App\Enums\ExercisesTypes;
use App\Enums\Generated\ExerciseGeneratedTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateExerciseFromAiRequest;
use App\Http\Response\ApiResponse;
use App\Services\ExerciseService;
use App\Services\OpenAiService;

class ExerciseGeneratorController extends Controller
{

    private OpenAiService $openAiService;
    private ExerciseService $exerciseService;

    public function __construct(
        OpenAiService $openAiService,
        ExerciseService $exerciseService
    )
    {
        $this->openAiService = $openAiService;
        $this->exerciseService = $exerciseService;
    }

    public function generate(GenerateExerciseFromAiRequest $request)
    {
        $dto = $request->getDTO();

        if (ExercisesTypes::inEnum($dto->type)){
            $exerciseType = ExercisesTypes::inEnum($dto->type);
            $exercisePrompt = sprintf(ExerciseGeneratedTypes::getMessageTemplate($exerciseType->value), '10', 'JSON');
            $exercises = json_decode($this->openAiService->generateText($exercisePrompt), true);

            if ($exerciseType->value == 'compile_phrase'){
                foreach ($exercises as $exercise){
                    $request->setDTO($dto, $exercise['phrase']);

                    $this->exerciseService->create($dto);
                }
            }
        }

        return new ApiResponse('Successful generated exercises');
    }
}
