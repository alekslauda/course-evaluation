<?php

namespace Tests;
use \Illuminate\Testing\TestResponse;

trait UtilityTestCaseTrait
{
  protected function decode_response(TestResponse $response, $key)
  {
    return $response->decodeResponseJson()->json($key);
  }
}
