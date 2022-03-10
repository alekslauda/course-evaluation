<?php

namespace Tests\Feature\Api\V1;

use App\Models\Course;
use App\Models\Progress;
use Tests\TestCase;

class Evaluation__CourseTest extends TestCase
{

    const EVALUATION_ENDPOINT_URL = '/api/v1/courses/evaluation';

    private function build_evaluation_url($params)
    {
      return self::EVALUATION_ENDPOINT_URL . '?'.http_build_query($params);
    }

    public function test_it_fails_if_neither_of_the_required_params_are_passed()
    {
        $response = $this->get(self::EVALUATION_ENDPOINT_URL);

        $json = $this->decode_response($response, 'error');
        
        $required_fields = [
          'course_duration',
          'learning_process',
          'creation_date',
          'due_date',
        ];
        
        foreach ($required_fields as $required_field) {
          $field_splitted = explode('_', $required_field);
          
          $this->assertEquals(
            'The ' . $field_splitted[0] . ' ' . $field_splitted[1] . ' ' . 'field is required.',
            $json['message'][$required_field][0]
          );
        }

        $response->assertStatus($json['code']);
    }

    public function test_it_fails_if_course_duration_and_learning_process_params_are_not_an_integer()
    {
      $response = $this->get($this->build_evaluation_url(['course_duration' => 'test']));

      $json = $this->decode_response($response, 'error');
      $this->assertEquals(
        'The course duration must be an integer.',
        $json['message']['course_duration'][0]
      );
      $response->assertStatus($json['code']);

      $response = $this->get($this->build_evaluation_url([
        'course_duration' => 2,
        'learning_process' => 'test'
      ]));

      $json = $this->decode_response($response, 'error');
      $this->assertEquals(
        'The learning process must be an integer.',
        $json['message']['learning_process'][0]
      );
      $response->assertStatus($json['code']);

      $response = $this->get($this->build_evaluation_url([
        'course_duration' => 22212,
        'learning_process' => 2.13
      ]));

      $json = $this->decode_response($response, 'error');
      $this->assertEquals(
        'The learning process must be an integer.',
        $json['message']['learning_process'][0]
      );
      $response->assertStatus($json['code']);
      
    }

    public function test_it_fails_if_learning_process_is_an_invalid_percentage()
    {
      $response = $this->get($this->build_evaluation_url(['course_duration' => 2, 'learning_process' => 200]));
      $json = $this->decode_response($response, 'error');
      $this->assertEquals(
        'The learning process must be between 0 and 100.',
        $json['message']['learning_process'][0]
      );
      $response->assertStatus($json['code']);
    }

    public function test_it_fails_if_creation_date_and_due_date_params_are_not_in_RFC3339_format()
    {
      $test_date = (new \DateTime())->format(\DateTimeInterface::RFC3339);
      $response = $this->get($this->build_evaluation_url([
        'course_duration' => 2,
        'learning_process' => 22,
        'creation_date' => 'asdad',
        'due_date' => $test_date
      ]));
      

      $json = $this->decode_response($response, 'error');

      $this->assertEquals(
        'The creation date is not a valid date.',
        $json['message']['creation_date'][0]
      );

      $test_date = (new \DateTime())->format(\DateTimeInterface::RFC3339);
      $response = $this->get($this->build_evaluation_url([
        'course_duration' => 2,
        'learning_process' => 22,
        'creation_date' => $test_date,
        'due_date' => 'asdasd'
      ]));
      

      $json = $this->decode_response($response, 'error');
      $this->assertEquals(
        'The due date is not a valid date.',
        $json['message']['due_date'][0]
      );
    }

    public function test_it_returns_the_correct_response_if_it_evaluates_correctly_the_request()
    {
      $test_date = (new \DateTime())->format(\DateTimeInterface::RFC3339);
      $test_due_date = (new \DateTime());
      $test_due_date->modify('+ ' . rand(1,5) . 'month');
      
      $response = $this->get($this->build_evaluation_url([
        'course_duration' => 2221312,
        'learning_process' => 12,
        'creation_date' => $test_date,
        'due_date' => $test_due_date->format(\DateTimeInterface::RFC3339)
      ]));
      
      $json = $this->decode_response($response, 'data');
      
      $this->assertArrayHasKey('progress_status', $json);
      $this->assertArrayHasKey('expected_progress', $json);
      $this->assertArrayHasKey('needed_daily_learning_time', $json);

      $valid_statuses = [
        Progress::NOT_ON_TRACK,
        Progress::ON_TRACK,
        Progress::OVERDUE,
      ];

      $this->assertTrue(in_array($json['progress_status'], $valid_statuses));
      $this->assertIsNumeric($json['expected_progress']);
      $this->assertIsNumeric($json['needed_daily_learning_time']);
    }
}
