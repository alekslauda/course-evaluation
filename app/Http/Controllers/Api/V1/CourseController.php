<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Services\CourseService;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CourseController extends Controller 
{
  protected $service;

  public function __construct(CourseService $service)
  {
    $this->service = $service;
  }

  public function evaluate(Request $request) {
    
    $validator = Validator::make($request->all(), [
      'course_duration' => 'required|integer',
      'learning_process' => 'required|integer|between:0,100',
      'creation_date' => 'required|date',
      'due_date' => 'required|date'
    ]);
    
    if ($validator->fails()) {
      return response()->json([
        'error' => [
          'code' => 400,
          'message' => $validator->errors()
        ],
      ])->setStatusCode(400);
    }
    
    $course = new Course(
      null,
      $request->course_duration, 
      $request->learning_process, 
      (new \DateTime($request->creation_date)), 
      (new \DateTime($request->due_date))
    );

    $evaluation = $this->service->evaluate($course);
    
    return response()->json([
      'data' => [
        'progress_status' => $evaluation->get_progress_status(),
        'expected_progress' => $evaluation->get_expected_progress(),
        'needed_daily_learning_time' => $evaluation->get_needed_daily_learning_time()
      ]
    ]);
  }
}
