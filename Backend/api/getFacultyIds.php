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
$result = $faculty->getIds();
$info = array();
while($row = $result->fetch(PDO::FETCH_ASSOC)){
    array_push($info,$row['FacultyID']);
}
echo json_encode(array(
    'data' => $info
));





