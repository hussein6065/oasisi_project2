<?php
class Students
{
    private $conn;
    
    public $id;
    public $bookingId;
    public $time;
    public $day;
    public $msg;
    
    public function __construct($db)
    {
        $this->conn = $db;
    }

   public function getCourses(){
        $query = "SELECT FA.Id, FA.CourseID, FA.Day1, FA.Day2, FA.TimeGMT1, FA.TimeGMT2,C.CourseName, F.AshesiEmail,concat(F.FName,' ',F.LName) as Faculty FROM Registered_Courses R INNER JOIN Courses C on R.CourseID = C.CourseID INNER JOIN Faculty F on R.FacultyID = F.FacultyID INNER JOIN Faculty_Availability FA ON FA.CourseID = R.CourseID WHERE R.StudentID =:id GROUP BY C.CourseID ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
   }

   public function getPendingBooking(){
        $query = 'SELECT B.BookingID,B.BookingStatus,C.CourseName, B.Faculty_AvailabilityId,B.Booked_day, B.Booked_time, B.StudentID, CONCAT(F.FName," ",F.LName) as Faculty FROM Booking B INNER JOIN Faculty_Availability FA ON B.Faculty_AvailabilityId = FA.Id INNER JOIN Faculty F ON FA.FacultyID = F.FacultyID INNER JOIN Courses C ON FA.CourseID = C.CourseID INNER JOIN Students S ON B.StudentID = S.StudentID WHERE B.StudentID = :id';
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
   public function booking($student,$msg,$FAid){
          $status = "T";
          $time = "8:00:00";
          $query = 'INSERT INTO Booking (Faculty_AvailabilityId,Booked_day,Booked_time,StudentID,Message)values(:FA,:Bd,:Bt,:SD,:msg)';
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':FA',$FAid);
          $stmt->bindParam(':Bd',$status);
          $stmt->bindParam(':Bt',$time);
          $stmt->bindParam(':SD',$student);
          $stmt->bindParam(':msg',$msg);
          
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