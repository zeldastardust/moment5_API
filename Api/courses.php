<?php

//Headers
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once './config/database.php';
include_once './objects/Courses.php';

// variable for request method
$method = $_SERVER['REQUEST_METHOD'];

// check if id is set
if(isset($_GET['id'])) {
   $id = $_GET['id'] != '' ? $_GET['id'] : null;
}
// instantiate database and work object
$database = new Database();
$db = $database->getConnection();

// initialize object
$courses = new Courses($db);

//switch for requests
switch($method) {
   case 'GET':
      if(isset($id)) {
        $result = $courses->readOne($id);         
      } else {
        $result = $courses->read();
      }
      // check if more than 0 record found
      if(sizeof($result) > 0) {
         http_response_code(200); // set response code - 200 OK
      } else {
         http_response_code(404); // set response code - 404 Not found
         $result = array('message' => 'Could not find work');
      }
      break;
   case 'POST':
    // get posted data
      $data = json_decode(file_get_contents('php://input'));
 
       // set course property values
      $courses->code = $data->code;
      $courses->name = $data->name;
      $courses->progression = $data->progression;
      $courses->syllabus = $data->syllabus;

      // create the course
      if($courses->create()) {
         http_response_code(201); // set response code - 201 created
         $result = array('message' => 'course created');
      } else {
         http_response_code(503); // set response code - 503 server error
         $result = array('message' => 'Could not create course');
      }
      break;
   case 'PUT':
    //check if id is set
      if(!isset($id)) {
         http_response_code(510); 
         $result = array('message' => 'An id is needed');
      } else {
          // get id of course to be edited
         $data = json_decode(file_get_contents('php://input'));

          // set course property values
      $courses->code = $data->code;
      $courses->name = $data->name;
      $courses->progression = $data->progression;
      $courses->syllabus = $data->syllabus;
         // update course
         if($courses->update($id)) {
            http_response_code(200);  // set response code - 200 ok
            $result = array('message' => 'course updated');
         } else {
            http_response_code(503); // set response code - 503 server error
            $result = array('message' => 'Could not update course');
         }
      }
      break;
   case 'DELETE':

    //check if id is set
      if(!isset($id)) {
         http_response_code(510); 
         $result = array('message' => 'An id is needed');
      } else {
         if($courses->delete($id)) {
            http_response_code(200);  // set response code - 200 ok
            $result = array('message' => 'course deleted');
         } else {
            http_response_code(503); // set response code - 503 server error
            $result = array('message' => 'Unable to delete course');
         }
      }
      break;
}

// Return result as JSON
echo json_encode($result);

// Close DB connection
$db = $database->close();
?>