<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::with('responses')->get();
        return response()->json($surveys);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:rating,multiple_choice,text,boolean',
            'questions.*.required' => 'required|boolean',
            'questions.*.options' => 'array|required_if:questions.*.type,multiple_choice'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $survey = Survey::create([
            'title' => $request->title,
            'questions' => $request->questions,
            // 'user_id' => auth()->id()
        ]);

        return response()->json($survey, 201);
    }

    public function show(Survey $survey)
    {
        $survey->load('responses');
        return response()->json($survey);
    }

    public function update(Request $request, Survey $survey)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:rating,multiple_choice,text,boolean',
            'questions.*.required' => 'required|boolean',
            'questions.*.options' => 'array|required_if:questions.*.type,multiple_choice'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $survey->update($request->all());
        return response()->json($survey);
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();
        return response()->json(null, 204);
    }
}
