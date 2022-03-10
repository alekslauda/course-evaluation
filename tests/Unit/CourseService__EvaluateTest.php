<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Progress;
use App\Services\CourseService;
use PHPUnit\Framework\TestCase;

class CourseService__EvaluateTest extends TestCase
{
  protected static function getMethod($name)
  {
    $class = new \ReflectionClass(CourseService::class);
    $method = $class->getMethod($name);
    $method->setAccessible(true);
    return $method;
  }

  protected function setUp(): void
  {
    parent::setUp();
    $this->service = new CourseService();
  }

  public function test_evaluation_returns_a_progress_model_class()
  {
    $d1 = (new \DateTime('2021-05-01'));
    $d2 = (new \DateTime('2021-06-01'));

    $month_in_seconds = 2629746; #course duration 1month
    $learning_process = 20; # percentage
    # integer max is 2147483647
    $course = new Course(null, $month_in_seconds, $learning_process, $d1, $d2);

    $this->service->set_clock(new \DateTime('2021-05-05'));

    $r = $this->service->evaluate($course);
    $expected = new Progress(Progress::ON_TRACK, 13, 21600);

    $this->assertInstanceOf(Progress::class, $r);
    $this->assertObjectEquals($expected, $r);
  }

  public function test_it_calculates_the_daily_needed_learning_in_seconds()
  {
    $tested_method = self::getMethod('calculate_daily_needed_learning_in_seconds');
    $result = $tested_method->invokeArgs($this->service, [
      'course_duration' => 2629746, # 1 month
      'learning_process_percentage' => 20
    ]);

    $this->assertEquals(21600, $result);
  }

  public function test_it_calculates_the_ideal_percentage()
  {
    $this->service->set_clock(new \DateTime('2021-05-05'));

    $tested_method = self::getMethod('calculate_ideal_percentage');
    $result = $tested_method->invokeArgs($this->service, [
      'course_duration' => 2629746, # 1 month
      'course_creation_date' => (new \DateTime('2021-05-01'))
    ]);

    $this->assertEquals(13, $result);
  }

  public function test_it_gets_the_progress_status()
  {
    $this->mock_and_assert_progress_status(Progress::OVERDUE, new \DateTime('2021-05-15'), [
      'learning_process_percentage' => 33,
      'due_date' => (new \DateTime('2021-05-06')),
      'ideal_percentage' => 22,
    ]);

    $this->mock_and_assert_progress_status(Progress::ON_TRACK, new \DateTime('2021-04-15'), [
      'learning_process_percentage' => 33,
      'due_date' => (new \DateTime('2021-05-06')),
      'ideal_percentage' => 22,
    ]);

    $this->mock_and_assert_progress_status(Progress::NOT_ON_TRACK, new \DateTime('2021-04-15'), [
      'learning_process_percentage' => 33,
      'due_date' => (new \DateTime('2021-05-06')),
      'ideal_percentage' => 42,
    ]);
  }

  protected function mock_and_assert_progress_status(
    $expected,
    \DateTime $mock_today,
    array $mock_args
  ) {
    $this->service->set_clock($mock_today);
    $tested_method = self::getMethod('get_progress_status');
    $actual = $tested_method->invokeArgs($this->service, $mock_args);

    $this->assertEquals($expected, $actual);
  }
}
