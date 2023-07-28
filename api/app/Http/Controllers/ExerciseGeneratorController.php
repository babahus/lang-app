<?php

namespace App\Http\Controllers;

use App\Enums\ExercisesTypes;
use App\Enums\Generated\ExerciseGeneratedTypes;
use App\Services\OpenAiService;
use Illuminate\Http\Request;

class ExerciseGeneratorController extends Controller
{

    private OpenAiService $openAiService;

    public function __construct(OpenAiService $openAiService)
    {
        $this->openAiService = $openAiService;
    }

    public function test(Request $request)
    {
        if (ExercisesTypes::inEnum($request->input('type'))){
            $exerciseType = ExercisesTypes::inEnum($request->input('type'));
            $exercisePrompt = sprintf(ExerciseGeneratedTypes::getMessageTemplate($exerciseType->value), '10', 'JSON');

            return $this->openAiService->generateText($exercisePrompt);
        }

        //return $this->openAiService->generateText();
    }
}
