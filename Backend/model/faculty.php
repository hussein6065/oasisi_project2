<?php
class Faculty
{
    private $conn;
    
    public $id;
    public $time;
    public $day;
    
    public function __construct($db)
    {
        $this->conn = $db;
    }

   public function getCourses(){
     //    $query = "SELECT FA.Id, FA.CourseID,  CO.CourseName FROM Faculty_Availability FA 
     //    LEFT JOIN Courses CO ON FA.CourseID = CO.CourseID
     //    LEFT JOIN Faculty F ON FA.FacultyID = F.FacultyID
     //    WHERE F.FacultyID =:id";

     
     $query = "SELECT DISTINCT R.CourseID, CO.CourseName FROM Registered_Courses R LEFT JOIN
          Courses CO ON R.CourseID = CO.CourseID
          LEFT JOIN Faculty F ON R.FacultyID  = F.FacultyID
          WHERE R.FacultyID =:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
   }
   
   public function getCourseAvailability($course){
     $date = date("Y-m-d");
     $query = "SELECT Availability, Id, TimeStart,TimeEnd from Faculty_Course_Availability where CAST(Date AS DATE) =:D and CourseID=:C";
     $stmt = $this->conn->prepare($query);
     
     $stmt->bindParam(':D', $date);
     $stmt->bindParam(':C', $course);
     // $stmt->bindParam(':Id', $this->id);

     $stmt->execute();
     return $stmt;

     // "SELECT FA.Availability as FAV, FA.TimeStart,FA.TimeEnd, FA.Id, FAA.Availability, FA.CourseID, C.CourseName,C.OfficeHourDay,C.OfficeHourTime, F.AshesiEmail,concat(F.FName,' ',F.LName) as Faculty,  FROM Registered_Courses R INNER JOIN Courses C on R.CourseID = C.CourseID INNER JOIN Faculty F on R.FacultyID = F.FacultyID INNER JOIN Faculty_Course_Availability FA ON FA.CourseID = R.CourseID
     //      INNER JOIN Faculty_Arrival FAA ON FAA.FacultyID = R.FacultyID  WHERE R.StudentID =:id GROUP BY C.CourseID ";
   }

   public function getNumBookingPerDay($course){
        $date = date("Y-m-d");
     $query = "SELECT COUNT(StudentID) as bookCount FROM Booking B
     LEFT JOIN Faculty_Course_Availability FA ON B.Faculty_AvailabilityId=FA.Id
     LEFT JOIN Faculty_Arrival F ON F.Id=FA.Faculty_AvailabilityID
     WHERE CAST(B.BookingDate AS DATE) =:D and FA.CourseId=:c and F.FacultyID=:Id";
     
     $stmt = $this->conn->prepare($query);
     $stmt->bindParam(':c', $course);
     $stmt->bindParam(':D', $date);
     $stmt->bindParam(':Id', $this->id);
     $stmt->execute();
     $results = $stmt->fetch(PDO::FETCH_ASSOC);
     return $results['bookCount'];
   }
   public function getAvailability(){
     $date = date("Y-m-d");
     $query = "SELECT Availability from Faculty_Arrival where CAST(ArrivalTime AS DATE) =:D and FacultyID=:Id";
     $stmt = $this->conn->prepare($query);
     
     $stmt->bindParam(':D', $date);
     $stmt->bindParam(':Id', $this->id);
     $stmt->execute();
     if($stmt->rowCount()>0){
          $results = $stmt->fetch(PDO::FETCH_ASSOC);
          return $this->available($results['Availability']);
     }
     return $this->available(false);
     
}
   public function available($status){
        switch ($status) {
             case 1:
                  return  ('<span style="
                  background-color: green;" class="badge ">Available</span>');
             
             default:
                    return  ('<span style="
                    background-color: brown;" class="badge ">Unavailable</span>');
        }
   }
   public function getCourse(){
     $query = "SELECT CourseID FROM Registered_Courses WHERE FacultyID =:id";
     $stmt = $this->conn->prepare($query);
     $stmt->bindParam(':id', $this->id);
     $stmt->execute();
     $info = array();
     while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          array_push($info,$row['CourseID']);
      }
     return $info;
     }
     public function faculty_availability(){

          $query = "INSERT INTO Faculty_Arrival(FacultyID) VALUES(:id)";
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':id', $this->id);
          $stmt->execute();
          $availability_id = $this->conn->lastInsertId();
          $courses = $this->getCourses();
          foreach ($courses as $course) {
               $this->insertCourseAvailability($availability_id,$course);
          }
          return true;
     }
     public function turnOffAvailability(){
          $query = 'UPDATE Faculty_Arrival SET Availability=0 where Id=:id ';
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':id', $this->id);
          return $stmt;
     }
    
     public function insertCourseAvailability($availability_id,$course){
          $query = "INSERT INTO Faculty_Availability(CourseID, Faculty_AvailabilityID) VALUES(:course,:availId)";
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':course', $course);
          $stmt->bindParam(':availId', $availability_id);
          $stmt->execute();
     }
   public function getName(){
     $query = "SELECT concat(FName,' ', LName) as FacultyName FROM Faculty where FacultyID=:id";
     $stmt = $this->conn->prepare($query);
     $stmt->bindParam(':id', $this->id);
     $stmt->execute();
     return $stmt;
   }
   

   public function getIds(){
        $query = 'SELECT FacultyID from Faculty';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
   }

   public function getPendingBooking(){
        $query = 'SELECT B.BookingID,B.BookingStatus,C.CourseName, B.Faculty_AvailabilityId,B.Booked_day, B.Booked_time, CONCAT(S.FName," ",S.LName) as Student FROM Booking B 
        INNER JOIN Faculty_Availability FA ON B.Faculty_AvailabilityId = FA.Id 
        INNER JOIN Faculty F ON FA.FacultyID = F.FacultyID
        INNER JOIN Courses C ON FA.CourseID = C.CourseID
        INNER JOIN Students S ON B.StudentID = S.StudentID 
        WHERE F.FacultyID =:id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
   }
   public function formatTime($time,$day){
     $timing = "";
     switch ($day) {
          case 'M':
               $timing = 'Mon ';
               break;
          case 'T':
               $timing = 'Tue ';
               break;
          case 'W':
               $timing = 'Wed ';
               break;
          case 'TH':
               $timing = 'Thurs ';
               break;
          case 'F':
               $timing = 'Fri ';
               break;
          default:
               $timing = '';
               break;
     }
     $tim = date("H:i",strtotime("+1 hour",strtotime($time)));
     return $timing.date("H:i",strtotime($time)).' - '.$tim;

   }
   public function changeTime(){
    
     $query = 'UPDATE Faculty_Availability SET Day1=:d, TimeGMT1=:t where Id=:i';
     $stmt = $this->conn->prepare($query);
     $stmt->bindParam(':d',$this->day);
     $stmt->bindParam(':t',$this->time);
     $stmt->bindParam(':i',$this->id);
     
     
     if($stmt->execute()){
          return true;
      }else{
          printf('Error: %s. \n',$stmt->error);
          return false;
      }
}
public function changeStatus(){
     $query = 'UPDATE Booking SET BookingStatus=:d where BookingID=:i';
     $stmt = $this->conn->prepare($query);
     $stmt->bindParam(':d',$this->day);
     $stmt->bindParam(':i',$this->id);
     
     
     if($stmt->execute()){
          return true;
      }else{
          printf('Error: %s. \n',$stmt->error);
          return false;
      }
}

public function delete(){
     $query = 'DELETE FROM Booking where BookingID=:i';
     $stmt = $this->conn->prepare($query);
     $stmt->bindParam(':i',$this->id);
     
     
     if($stmt->execute()){
          return true;
      }else{
          printf('Error: %s. \n',$stmt->error);
          return false;
      }
}
public function historybooking($info){
     switch ($info) {
          case 'P':
               return '<span class="label label-warning label-mini">Pending</span>';
               
          case 'T':
               return '<span class="label label-success label-mini">Approved</span>';
               break;
          case 'C':
               return '<span class="label label-danger label-mini">Disapproved</span>';
          case 'E':
               return '<span class="label label-secondary label-mini">Ended</span>';
          default:
               return '<span class="label label-warning label-mini">Pending</span>';
     }
   }
}