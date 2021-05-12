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
<!-- <button  onclick='getId(event)'  id="<?php echo $row["Id"]?>,  <?php echo $data['Id'] ?>" class="btn btn-theme" type="button" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"><i class="fa fa-pencil"></i> Edit Office Hours </button> -->