<?php

namespace App\Models;


class LearningProcessInvalidPercentageException extends \Exception {}


class Course 
{
  protected $id;
  protected $course_duration;
  protected $learning_process;
  protected $creation_date;
  protected $due_date;

  public function __construct(int $id = null, int $course_duration,int $learning_process,\DateTime $creation_date,\DateTime $due_date)
  {
    if ($learning_process < 0 || $learning_process > 100) {
      throw new LearningProcessInvalidPercentageException('Invalid percentage');
    }
    $this->id = $id;
    $this->course_duration = $course_duration;
    $this->learning_process = $learning_process;
    $this->creation_date = $creation_date;
    $this->due_date = $due_date;
  }

  public function getId() {
    return $this->id;
  }

  public function set_course_duration($course_duration) {
    $this->course_duration = $course_duration;
  }

  public function get_course_duration() {
    return $this->course_duration;
  }

  public function set_learning_process($learning_process) {
    $this->learning_process = $learning_process;
  }

  public function get_learning_process() {
    return $this->learning_process;
  }

  public function set_creation_date($creation_date) {
    $this->creation_date = $creation_date;
  }

  public function get_creation_date() {
    return $this->creation_date;
  }

  public function set_due_date($due_date) {
    $this->due_date = $due_date;
  }

  public function get_due_date() {
    return $this->due_date;
  }

}
