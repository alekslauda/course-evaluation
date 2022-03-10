<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Services\CourseService;
use PHPUnit\Framework\TestCase;

class CourseService__GeneralTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    $this->service = new CourseService();
  }

  public function test_it_can_instantiate_the_course_service()
  {
    $this->assertInstanceOf(CourseService::class, $this->service);
  }

  public function test_it_can_return_all_of_the_courses()
  {
    $this->assertCount(5, $this->service->all());
  }

  public function test_it_returns_a_single_course()
  {
    $this->assertInstanceOf(Course::class, $this->service->get(2));
  }

  public function test_it_returns_null_if_no_course_is_found()
  {
    $this->assertEquals(null, $this->service->get(22));
  }
}
