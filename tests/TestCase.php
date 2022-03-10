<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\UtilityTestCaseTrait;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, UtilityTestCaseTrait;
}
