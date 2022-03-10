<?php

namespace Tests\Unit;

use App\Models\InvalidProgressStatusException;
use App\Models\Progress;
use DateTime;
use PHPUnit\Framework\TestCase;

class ProgressTest extends TestCase
{
    public function test_it_can_instantiate_a_progress()
    {
        $progress = new Progress(Progress::NOT_ON_TRACK, 1, 1);
        $this->assertInstanceOf(Progress::class, $progress);
    }

    public function test_progress_status_should_be_on_track_overdue_or_not_on_track()
    {
        $this->expectException(InvalidProgressStatusException::class);
        $progress = new Progress('', 1, 1);
    }
}
