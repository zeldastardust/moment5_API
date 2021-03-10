<?php
class Courses{
  
    // database connection and table name
    private $conn;
    private $table_name = "courses";
  
    // object properties
    public $id;
    public $code;
    public $name;
    public $progression;
    public $syllabus;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read sites
 function read(){
  
    // select all query
    $query = "SELECT *
            FROM
                " . $this->table_name ;
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
    $num = $stmt->rowCount();
  
    // check if more than 0 record found
    if ($num > 0) {
        //array course objects
      $data = array();
       $data['courselist'] = array();
      // $data['itemCount'] = $num;

       // retrieve the table contents
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          // extract data from row to variables
          extract($row); 
          $coursearray = array(
             'id' => $id,
             'code' => $code,
             'name' => $name,
             'progression' => $progression,
             'syllabus'=> $syllabus
             
          );

          array_push($data['courselist'], $coursearray);
       }
    }

    return $data;
 }
// create sites
function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                code=:code, name=:name, progression=:progression, syllabus=:syllabus";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->code=htmlspecialchars(strip_tags($this->code));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->progression=htmlspecialchars(strip_tags($this->progression));
    $this->syllabus=htmlspecialchars(strip_tags($this->syllabus));
    // bind values
    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":progression", $this->progression);
    $stmt->bindParam(":syllabus", $this->syllabus);
    
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false;     
}

function readOne($id){  
    // query to read single record
    $query = "SELECT
                *
            FROM
                " . $this->table_name . "
            WHERE
                id = ". $id."
            LIMIT
                1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
  
    if(!$data) {
        $data = array();
     }

     return $data;

  }
// update site function
function update($id){
  
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                code = :code,
                name = :name,
                progression = :progression,
                syllabus=:syllabus
                
            WHERE
                id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->code=htmlspecialchars(strip_tags($this->code));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->progression=htmlspecialchars(strip_tags($this->progression));
    $this->syllabus=htmlspecialchars(strip_tags($this->syllabus));
    $this->id=htmlspecialchars(strip_tags($id));
  
    // bind values
    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":progression", $this->progression);
    $stmt->bindParam(":syllabus", $this->syllabus);
    $stmt->bindParam(':id', $this->id);
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}
// delete the site
function delete($id){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id =:id";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($id));
  
    // bind id of record to delete
    $stmt->bindParam(':id', $this->id);
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}
}
?>
