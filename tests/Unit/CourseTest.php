<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\LearningProcessInvalidPercentageException;
use DateTime;
use PHPUnit\Framework\TestCase;

class CourseTest extends TestCase
{
    public function test_it_can_instantiate_a_course()
    {
        $course = new Course(1, 1, 1, (new DateTime()), (new DateTime()));
        $this->assertInstanceOf(Course::class, $course);
    }

    public function test_it_raise_an_exception_if_invalid_percentage_is_passed()
    {
        $this->expectException(LearningProcessInvalidPercentageException::class);
        $course = new Course(1, 1, 101, (new DateTime()), (new DateTime()));
    }
}
