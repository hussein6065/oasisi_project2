
<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../config/Database.php';
include_once '../model/student.php';



$database = new Database();
$db = $database->connect();

$student = new Students($db);


$data = json_decode(file_get_contents("php://input"));
$result = $student->booking($data->studentId,$data->msg,$data->lectureId);
echo $result;




