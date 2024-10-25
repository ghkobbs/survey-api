<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResponseController extends Controller
{
    public function store(Request $request, Survey $survey)
    {
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validate required questions
        $requiredQuestions = collect($survey->questions)
            ->where('required', true)
            ->pluck('id');

        $answeredQuestions = collect($request->answers)->keys();
        $unansweredRequired = $requiredQuestions->diff($answeredQuestions);

        if ($unansweredRequired->isNotEmpty()) {
            return response()->json([
                'errors' => ['answers' => ['Some required questions are not answered.']]
            ], 422);
        }

        $response = $survey->responses()->create([
            'answers' => $request->answers
        ]);

        return response()->json($response, 201);
    }
}
