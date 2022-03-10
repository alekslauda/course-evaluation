<?php

namespace Tests\Feature\Api\V2;

use App\Models\Course;
use Tests\TestCase;

class Show__CourseTest extends TestCase
{
    public function test_it_returns_a_course_if_founded()
    {
        $response = $this->get('/api/v2/courses/2');

        $response->assertStatus(200);

        $json = $this->decode_response($response, 'data');
        $r = new \ReflectionClass(Course::class);
        
        foreach ($r->getProperties() as $prop) {
            $this->assertArrayHasKey($prop->getName(), $json);
        }
    }

    public function test_it_fails_to_get_a_none_existing_course()
    {
        $response = $this->get('/api/v2/courses/22');
        
        $json = $this->decode_response($response, 'error');
        
        $response->assertStatus($json['code']);
        $this->assertEquals(
            "There are 5 courses at the moment and they are hardcoded for the purpose of the demo.Please use an id from 1 to 5! :)Thank you!", 
            $json['message']
        );
    }
}
