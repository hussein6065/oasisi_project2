<?php
    include_once './Backend/config/Database.php';
    include_once './Backend/model/student.php';
   
   
    $database = new Database();
    $db = $database->connect();
    $student = new Students($db);
    $data = '60652022';
    $result = $student->getPendingBooking();
    while ( $row = $result->fetch(PDO::FETCH_ASSOC)){
        $row['Booked_day'];
    }

?>