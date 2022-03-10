<?php

namespace Tests\Feature\Api\V2;

use App\Models\Course;
use Tests\TestCase;

class Index__CourseTest extends TestCase
{
    public function test_it_returns_a_list_of_courses()
    {
        $response = $this->get('/api/v2/courses');
        $response->assertStatus(200);
        $json = $this->decode_response($response, 'data');
        $this->assertEquals(5, count($json));

        $r = new \ReflectionClass(Course::class);

        foreach($json as $course) {
            foreach ($r->getProperties() as $prop) {
                $this->assertArrayHasKey($prop->getName(), $course);
            }
        }
        
    }
}
