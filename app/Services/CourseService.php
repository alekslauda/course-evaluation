<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Progress;


/**
 * EXAMPLES
 */
interface RepositoryInterface {
  public function all();
}

class MyHardCodedRepository implements RepositoryInterface {
  public function all()
  {
    $courses = [];
    foreach (range(1, 5) as $key => $value) {
      $d = rand(1, 20);
      $m = rand(1, 12);
      $y = rand(2021, 2022);
      
      $creation_date = new \DateTime($d . '-' . $m . '-' . $y);
      $due_date = clone $creation_date;
      $due_date = $due_date->add(new \DateInterval('P'.rand(1,5).'M'));
      $courses[] = new Course($value, 12000, rand(1, 100), $creation_date, $due_date);
    }

    return $courses;
  }
}

/**
 * Using the Service/Repository pattern
 * Basically the idea of this service in a production situation will be with some
 * data source(Mysql, Mongo etc.) dependency as a repository and it will be injected in the 
 * service class.
 * For the purpose of showing the whole picture in "v2" API where i assume we are having some DATA SOURCE
 * i've injected the hardcoded data source
 * 
 * As for "v1" and the Server-Side-Task the evaluate method if becoming more complex we can pull it out
 * in a different abstraction and inject it in the service and test it seperately
 * For the moment and because of the time i don't have :D i will put the logic here into seperate methods and test them
 */
class CourseService
{
  private $clock;

  protected $repository;

  public function __construct(
    \DateTime $clock= null,
    RepositoryInterface $repository = null
  )
  {
    $this->clock = !$clock ? new \DateTime() : $clock;
    $this->repository = !$repository ? new MyHardCodedRepository() : $repository;
  }

  public function set_clock(\DateTime $clock)
  {
    $this->clock = $clock;
  }

  public function get($id)
  {
    $courses = $this->repository->all();
    foreach($courses as $course) {
      if ($course->getId() === $id) {
        return $course;
      }
    }
    return null;
  }

  public function all()
  {
    return $this->repository->all();
  }
  
  public function evaluate(Course $course)
  {
    $ideal_percentage = $this->calculate_ideal_percentage($course->get_course_duration(), $course->get_creation_date());

    return (new Progress(
      $this->get_progress_status($course->get_learning_process(), $course->get_due_date(),$ideal_percentage), 
      $ideal_percentage,
      $this->calculate_daily_needed_learning_in_seconds($course->get_course_duration(), $course->get_learning_process()), 
    ));
  }

  protected function calculate_daily_needed_learning_in_seconds($course_duration, $learning_process_percentage)
  {
    $passed_seconds_based_on_process = intval(round($course_duration * ($learning_process_percentage / 100)));
    $passed_process_in_hours = intval(round($passed_seconds_based_on_process/3600));
    $daily_needed_hours = intval(round($passed_process_in_hours/24));
    return $daily_needed_hours * 3600;
  }

  protected function calculate_ideal_percentage($course_duration, $course_creation_date)
  {
    $difference_in_seconds = $this->clock->getTimestamp() - $course_creation_date->getTimestamp();
    
    return intval(
      round($difference_in_seconds * 100 / $course_duration)
    );
  }

  protected function get_progress_status($learning_process_percentage, $due_date, $ideal_percentage)
  {
    if ($this->clock > $due_date && $learning_process_percentage < 100) {
      return Progress::OVERDUE;
    }

    if ($learning_process_percentage >= $ideal_percentage) {
      return Progress::ON_TRACK;
    }

    return Progress::NOT_ON_TRACK;
  }

}
