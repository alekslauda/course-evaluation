## Project description

Progress status evaluation and estimation API

## How to start

In the project directory, you can run:

### `composer install`

### php artisan serv

Runs the app in the development mode.\
Check the API at:
  - v1 (to be reviewed):
  
    `Navigate to the url below and provide the necessary params to perform an evaluation`
    `course_duration -> should be integer`
    `learning_process -> should be integer`
    `creation_date -> should be a valid date`
    `due_date -> should be a valid date`
    
    * [http://127.0.0.1:8000/api/v1/courses/evaluation]
  - v2:
    * [http://127.0.0.1:8000/api/v2/courses/{id}]
    * [http://127.0.0.1:8000/api/v2/courses/{id}/evaluation]
    * [http://127.0.0.1:8000/api/v2/courses]
