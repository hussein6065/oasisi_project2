<?php
    include_once './Backend/config/Database.php';
    include_once './Backend/model/student.php';
   
   
    $database = new Database();
    $db = $database->connect();
    $student = new Students($db);
    $student->id = '60652022';
    $result = $student->getCourses();
    while ( $row = $result->fetch(PDO::FETCH_ASSOC)){
       echo $row['Faculty'];
    }

?>