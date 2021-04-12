
<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../config/Database.php';
include_once '../model/faculty.php';



$database = new Database();
$db = $database->connect();

$faculty = new Faculty($db);


$data = json_decode(file_get_contents("php://input"));
$faculty->id = $data->lectureId;
$faculty->day = $data->status;
$result = $faculty->changeStatus();
echo $result;




