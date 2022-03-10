<?php

namespace App\Models;

class InvalidProgressStatusException extends \Exception {}


class Progress {

  const ON_TRACK = 'on track';
  const NOT_ON_TRACK = 'not on track';
  const OVERDUE = 'overdue';

  protected $progress_status;
  protected $expected_progress;
  protected $needed_daily_learning_time;

  public function __construct(
    string $progress_status,
    int $expected_progress,
    int $needed_daily_learning_time
  )
  {
    if (!in_array($progress_status, [
      Progress::NOT_ON_TRACK, Progress::ON_TRACK, Progress::OVERDUE
    ])) {
      throw new InvalidProgressStatusException('Invalid progress status');
    }

    $this->progress_status = $progress_status;
    $this->expected_progress = $expected_progress;
    $this->needed_daily_learning_time  = $needed_daily_learning_time ;
  }

  public function get_progress_status()
  {
    return $this->progress_status;
  }

  public function set_progress_status($progress_status)
  {
    $this->progress_status = $progress_status;
  }

  public function get_expected_progress()
  {
    return $this->expected_progress;
  }

  public function set_expected_progress($expected_progress)
  {
    $this->expected_progress = $expected_progress;
  }

  public function get_needed_daily_learning_time()
  {
    return $this->needed_daily_learning_time;
  }

  public function set_needed_daily_learning_time($needed_daily_learning_time)
  {
    $this->needed_daily_learning_time = $needed_daily_learning_time;
  }

  public function equals(Progress $other): bool
  {
    return $this == $other;
  }
}
