<?php

namespace App\Http\Controllers\Api\V2;


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
    $this->mapper = new class {
      protected function show_date(\DateTime $date) {
        return $date->format(\DateTimeInterface::RFC3339);
      }
      public function to_api(Course $course = null)
      {
        if (!$course) {
          return null;
        }
        return [
          'id' => $course->getId(),
          'course_duration' => $course->get_course_duration(),
          'learning_process' => $course->get_learning_process(),
          'creation_date' => $this->show_date($course->get_creation_date()),
          'due_date' => $this->show_date($course->get_due_date()),
        ];
      }
    };
  }

  public function index()
  {
    return response()->json([
      'data' => array_map(fn (Course $course) => $this->mapper->to_api($course), $this->service->all())
    ]);
  }

  public function show($id)
  {
    $course = $this->mapper->to_api($this->service->get((int) $id));

    if (!$course) {
      return response()->json([
        'error' => [
          'code' => 404,
          'message' => 'There are 5 courses at the moment and they are hardcoded for the purpose of the demo.Please use an id from 1 to 5! :)Thank you!'
        ],
      ])->setStatusCode(404);
    }

    return response()->json([
      'data' => $course
    ]);
  }

  public function evaluate($id, Request $request) {
    return response()->json([
      'data' => 'To be implemented!'
    ]);
  }
}
