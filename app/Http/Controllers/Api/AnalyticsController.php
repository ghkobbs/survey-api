<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function show(Survey $survey)
    {
        $responses = $survey->responses;
        $analytics = [];

        foreach ($survey->questions as $question) {
            $questionData = [
                'id' => $question['id'],
                'text' => $question['text'],
                'type' => $question['type']
            ];

            switch ($question['type']) {
                case 'rating':
                    $questionData['data'] = collect(range(1, 5))
                        ->map(function ($rating) use ($responses, $question) {
                            return [
                                'rating' => (string)$rating,
                                'count' => $responses
                                    ->where("answers.{$question['id']}", $rating)
                                    ->count()
                            ];
                        });
                    break;

                case 'multiple_choice':
                case 'boolean':
                    $options = $question['type'] === 'boolean' 
                        ? ['Yes', 'No'] 
                        : $question['options'];

                    $questionData['data'] = collect($options)
                        ->map(function ($option) use ($responses, $question) {
                            return [
                                'option' => $option,
                                'count' => $responses
                                    ->where("answers.{$question['id']}", $option)
                                    ->count()
                            ];
                        });
                    break;

                case 'text':
                    $questionData['data'] = $responses
                        ->pluck("answers.{$question['id']}")
                        ->filter()
                        ->values();
                    break;
            }

            $analytics[] = $questionData;
        }

        return response()->json([
            'survey' => $survey,
            'analytics' => $analytics,
            'total_responses' => $responses->count()
        ]);
    }
}