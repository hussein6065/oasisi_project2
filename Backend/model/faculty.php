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
        $query = "SELECT FA.Id, FA.CourseID, FA.Day1, FA.Day2, FA.TimeGMT1, FA.TimeGMT2, CO.CourseName FROM Faculty_Availability FA 
        LEFT JOIN Courses CO ON FA.CourseID = CO.CourseID
        LEFT JOIN Faculty F ON FA.FacultyID = F.FacultyID
        WHERE F.FacultyID =:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
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