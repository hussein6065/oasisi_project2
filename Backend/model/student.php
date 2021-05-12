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
     //    $query = "SELECT FA.Id, FA.Availability, FA.CourseID, C.CourseName,C.OfficeHourDay,C.OfficeHourTime, F.AshesiEmail,concat(F.FName,' ',F.LName) as Faculty FROM Registered_Courses R INNER JOIN Courses C on R.CourseID = C.CourseID INNER JOIN Faculty F on R.FacultyID = F.FacultyID INNER JOIN Faculty_Course_Availability FA ON FA.CourseID = R.CourseID WHERE R.StudentID =:id GROUP BY C.CourseID ";

        $query = "SELECT FA.Availability as FAV, FA.TimeStart,FA.TimeEnd, FA.Id, FAA.Availability, FA.CourseID, C.CourseName,C.OfficeHourDay,C.OfficeHourTime, F.AshesiEmail,concat(F.FName,' ',F.LName) as Faculty FROM Registered_Courses R INNER JOIN Courses C on R.CourseID = C.CourseID INNER JOIN Faculty F on R.FacultyID = F.FacultyID INNER JOIN Faculty_Course_Availability FA ON FA.CourseID = R.CourseID
          INNER JOIN Faculty_Arrival FAA ON FAA.FacultyID = R.FacultyID  WHERE R.StudentID =:id GROUP BY C.CourseID ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
   }
   public function getAvailabilityCourse(){
        $query = "SELECT Availability, TimeStart,TimeEnd from ";
   }
   public function available($status){
     switch ($status) {
          case 1:
               return  ('<span style="
               background-color: green;height:50px" class="badge ">Available</span>');
          
          default:
                 return  ('<span style="
                 background-color: brown;" class="badge ">Available</span>');
     }
}
   public function getPendingBooking(){
        $query = 'SELECT B.BookingID,B.BookingStatus,C.CourseName, B.Faculty_AvailabilityId,B.BookingDate, B.StudentID, CONCAT(F.FName," ",F.LName) as Faculty FROM Booking B INNER JOIN Faculty_Course_Availability FA ON B.Faculty_AvailabilityId = FA.Id INNER JOIN Courses C ON FA.CourseID = C.CourseID  INNER JOIN  Faculty_Arrival  FAA ON FAA.Id=FA.Faculty_AvailabilityID INNER JOIN Faculty F ON FAA.FacultyID = F.FacultyID WHERE B.StudentID = :id';

     //    INNER JOIN Faculty F ON FA.FacultyID = F.FacultyID 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
   }
  
   public function formatDay($day){
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
               $timing = 'Tue';
               break;
     }
     // $tim = date("H:i",strtotime("+1 hour",strtotime($time)));
     // return $timing.date("H:i",strtotime($time)).' - '.$tim;
     return $timing;

   }
   public function formatTime($time){
     $timing = "";
     // switch ("") {
     //      case $time:
     //           return date("h:i",$time);
          
     //      default:
     //           return date("h:i",time());
     // }
     $tim = time();
     
     $tim = date("H:i",strtotime("+1 hour",strtotime($time)));
     return $timing.date("H:i",strtotime($time)).' - '.$tim;
     // return $timing;

   }
   public function booking($student,$type,$msg,$FAid,$day){
          
          if($type == 'outside' ){
               $type=1;
          }else{
               $type = 0;
          }
          

          $query = 'INSERT INTO Booking (Faculty_AvailabilityId,Booked_day,StudentID,Message,Outside_OfficeHours)values(:FA,:Bd,:SD,:msg,:t)';
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':FA',$FAid);
          $stmt->bindParam(':t',$type);
          $stmt->bindParam(':Bd',$day);
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
     public function getNumBookingPerDay($course){
          $date = date("Y-m-d");
     $query = "SELECT COUNT(StudentID) as bookCount FROM Booking B
     LEFT JOIN Faculty_Course_Availability FA ON B.Faculty_AvailabilityId=FA.Id
     LEFT JOIN Faculty_Arrival F ON F.Id=FA.Faculty_AvailabilityID
     WHERE CAST(B.BookingDate AS DATE) =:D and FA.CourseId=:c";
     
     $stmt = $this->conn->prepare($query);
     $stmt->bindParam(':c', $course);
     $stmt->bindParam(':D', $date);
     
     $stmt->execute();
     $results = $stmt->fetch(PDO::FETCH_ASSOC);
     return $results['bookCount'];
     
     }
}