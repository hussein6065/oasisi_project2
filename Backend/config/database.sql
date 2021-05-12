
DROP SCHEMA IF EXISTS OfficeHours;
CREATE SCHEMA OfficeHours;

USE OfficeHours;

CREATE TABLE Students(
	StudentID INT UNSIGNED PRIMARY KEY,
    FName VARCHAR(50) NOT NULL,
    LName VARCHAR(50) NOT NULL,
    DOB DATE,
    Gender enum('M','F','O'),
    Nationality VARCHAR(50),
    Major VARCHAR(50) NOT NULL,
    YearGroup YEAR NOT NULL,
    AshesiEmail VARCHAR(100)  UNIQUE NOT NULL,
    nonAshesiEmail VARCHAR(70) UNIQUE,
    PhoneNumber VARCHAR(20) NOT NULL,
    FacialData VARCHAR(100),
    PasswordC VARCHAR(50) DEFAULT NULL,
    Registered BOOLEAN DEFAULT FALSE
    );
CREATE TABLE Departments(
	DepartmentCode VARCHAR(10) PRIMARY KEY,
    DepartmentName VARCHAR(50) NOT NULL
    );
CREATE TABLE Faculty(
	FacultyID VARCHAR(10) PRIMARY KEY,
    SSN VARCHAR(20) UNIQUE,
    DepartmentCode VARCHAR(10) NOT NULL,
    FName VARCHAR(50) NOT NULL,
    LName VARCHAR(50) NOT NULL,
    DOB DATE,
    Gender enum('M','F','O'),
    Nationality VARCHAR(50),
    Level_of_Education VARCHAR(50),
    Speciality VARCHAR(70),
    Role enum('FULLTIME', 'ADJUNCT'),
    AshesiEmail VARCHAR(70) UNIQUE NOT NULL,
    nonAshesiEmail VARCHAR(70) UNIQUE,
    PhoneNumber VARCHAR(20),
    FacialData VARCHAR(100) UNIQUE,
    PasswordC VARCHAR(50) DEFAULT NULL,
    Registered BOOLEAN DEFAULT FALSE,
    FOREIGN KEY(DepartmentCode) REFERENCEs Departments(DepartmentCode)
    );
CREATE TABLE Courses(
	CourseID VARCHAR(10) PRIMARY KEY,
    DepartmentCode VARCHAR(10) NOT NULL,
    CourseName VARCHAR(50) NOT NULL,
    OfficeHourDay ENUM("M","T","W","TH","F"),
    OfficeHourTime TIME DEFAULT NOW(),
    Credit enum('Full', 'Half'),
    CreditHours DECIMAL(5,1),
    FOREIGN KEY(DepartmentCode) REFERENCES Departments(DepartmentCode)
    );

CREATE TABLE Registered_Courses(
	RegistrationID BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	StudentID INT UNSIGNED NOT NULL,
    CourseID VARCHAR(10) NOT NULL,
    FacultyID VARCHAR(10) NOT NULL,
	Registered_Date DATETIME DEFAULT NOW(), 
    FOREIGN KEY (StudentID) REFERENCES Students (StudentID),
    FOREIGN KEY (CourseID) REFERENCES Courses (CourseID),
    FOREIGN KEY (FacultyID) REFERENCES Faculty(FacultyID)
	);

CREATE TABLE Faculty_Arrival(
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FacultyID VARCHAR(10) NOT NULL,
    ArrivalTime Timestamp DEFAULT NOW(),
    Availability BOOLEAN DEFAULT True,
    FOREIGN KEY (FacultyID) REFERENCES Faculty(FacultyID)
);
CREATE TABLE Faculty_Course_Availability(
	Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Date DATETIME DEFAULT NOW(),
    CourseID VARCHAR(10)NOT NULL,
    Faculty_AvailabilityID BIGINT UNSIGNED NOT NULL,
    TimeStart TIME DEFAULT NULL,
    TimeEnd TIME DEFAULT NULL,
    Availability BOOLEAN DEFAULT False,
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID),
    FOREIGN KEY (Faculty_AvailabilityID) REFERENCES Faculty_Arrival(Id)
	);


CREATE TABLE Booking(
    BookingID BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    BookingDate DATETIME DEFAULT NOW(),
    Faculty_AvailabilityId BIGINT UNSIGNED,
    StudentID INT UNSIGNED,
    Outside_OfficeHours BOOLEAN NOT NULL,
    Booked_day ENUM ('M','T','W','TH','F'),
    BookingStatus  ENUM("P","T","C","E") DEFAULT "P", -- P => pending, T => Confirmed, C => Cancelled E => ended.
    Message TINYTEXT,
    FOREIGN KEY (Faculty_AvailabilityId) REFERENCES Faculty_Course_Availability (Id),
    FOREIGN KEY (StudentID) REFERENCES Students (StudentID)
);
-- create the faculty availability table
-- Create the booking table.
INSERT INTO Students (StudentID,FName,LName,DOB,Gender,Nationality,Major,YearGroup,AshesiEmail, nonAshesiEmail,PhoneNumber,FacialData)
					 VALUES ('60652022','Hussein','Fuseini','1998-09-06','M','Ghanaian','Computer Science',2022,
							'hussein.fuseini@ashesi.edu.gh','fuseinial@gmail.com','233508808098','images/60652022f.jpg'),

							('18992022','Akwesi','Kynx','2000-08-05','M','Ghanaian','Computer Engineering',2022,
							'akwesi.Kynx@ashesi.edu.gh','AKynx@gmail.com','233248808098','images/18992022f.jpg'),

                            ('17452022','James','Konney','1999-11-06','M','Nigerian','Business Administration',2022,
							'James.Konney@ashesi.edu.gh','Jamesk@gmail.com','233570008098','images/17452022f.jpg'),

                            ('98652022','Abena','Ofosu','1998-01-01','F','Ghanaian','Electrical Engineering',2022,
							'Abena.Ofosu@ashesi.edu.gh','AbenaO@gmail.com','233201108098','images/98652022f.jpg'),

                            ('14632022','Agyemang','Kweku','1999-12-06','M','Ghanaian','Management Information Systems',2022,
							'Agyemang.Kweku@ashesi.edu.gh','Agyemangk@gmail.com','233508523697','images/14632022f.jpg'),

                            ('89322022','Umar','Sanda','1996-06-06','M','Ghanaian','Computer Science',2022,
							'Umar.Sanda@ashesi.edu.gh','UmarSanda@gmail.com','233557891235','images/89322022f.jpg'),

                            ('96322022','Osman','Ali','1994-07-06','M','Ghanaian','Computer Science',2022,
							'Osman.Ali@ashesi.edu.gh','AliO@gmail.com','233247845121','images/96322022f.jpg'),
                            
                            ('78212022','Philip','James','1998-01-16','M','Ghanaian','Computer Science',2022,
							'Philip.James@ashesi.edu.gh','JPhilip@gmail.com','23357895621','images/78212022f.jpg'),

                            ('98742022','Kevin','Hynnes','2001-05-06','M','Ghanaian','Mechanical Engineering',2022,
							'Kevin.Hynnes@ashesi.edu.gh','KevinHy@gmail.com','233268462571','images/98742022f.jpg'),
                            
                            ('78942022','Kelvin','Akoffu','1998-11-11','M','Ghanaian','Computer Science',2022,
							'Kelvin.Akoffu@ashesi.edu.gh','AkKel@gmail.com','233509632741','images/78942022f.jpg');
    
INSERT INTO Departments VALUES ('HSS', 'Humanities and Social Sciences'),
								('BA','Business Administration'),
                                ('CSIS','Computer Science and Information System'),
                                ('ENG','Engineering');   
    
    
INSERT INTO Courses (CourseID, DepartmentCode,CourseName,Credit,CreditHours) VALUES 
                            ('SOAN 221','HSS','Leadership II','Half',1.5),
							('MATH 221','CSIS','Statistics with probability','Full',4),
                            ('CS 222','CSIS','Data Structures','Full',4),
                            ('BUSA 222','BA','Intro to Finance','Full',4),
                            ('ECON 102','BA','Microeconomics','Full',4),
                            ('CS 221','CSIS','Discrete Structures','Full',4),
                            ('ENG 221','ENG','Physics I: Mechanic','Full',4);
    
INSERT INTO Faculty (FacultyID,SSN,DepartmentCode,FName,LName,DOB,Gender,Nationality,Level_of_education,Speciality,Role,AshesiEmail,PhoneNumber,FacialData) 
				     VALUES ('ASF95631','GH78945612','ENG','Nathan','Amanquah','1974-06-13','M','Ghanaian','Doctorate','Electrical and Electronic Engineering',
							'FULLTIME','namanquah@ashesi.edu.gh','0248974563','images/ASF95631f.jpg'),
                            
                            ('ASF96351','GH78980212','CSIS','Ayorkor','Korsah','1978-02-23','F','Ghanaian','Doctorate','Robotics and Artificial Intelligence',
							'FULLTIME','akorsah@ashesi.edu.gh','0245698569','images/ASF96351f.jpg'),
                            
                            ('ASF88925','GH78889212','BA','Stephen','Armah','1970-12-13','M','Ghanaian','Doctorate',' Agricultural Economics',
							'FULLTIME','searmah@ashesi.edu.gh','0205689741','images/ASF88925f.jpg'),
                            
                            ('ASF80631','GH78995631','HSS','Pashington','Obeng','1965-05-23','M','Ghanaian','Doctorate','Anthropology of Religion & Cultural Communication',
							'FULLTIME','pobeng@ashesi.edu.gh','0578915985','images/ASF80631f.jpg'),
                            
                            ('ASF97003','GH78005612','CSIS','Stephane','Nwolley','1985-05-23','M','Togolese','Doctorate','ICT Management (Big Data)',
							'FULLTIME','snwolley@ashesi.edu.gh','0244074563','images/ASF97003f.jpg');

INSERT INTO Registered_Courses(StudentID,CourseID,FacultyID) 
							   VALUES('60652022','SOAN 221','ASF95631'),
									 ('60652022','CS 222','ASF95631'),
                                     ('60652022','ECON 102','ASF95631'),
									 ('18992022','ENG 221','ASF95631'),
                                     ('18992022','SOAN 221','ASF95631'),
                                     ('18992022','CS 222','ASF95631'),
                                     ('98742022','ENG 221','ASF95631'),
                                     ('98742022','SOAN 221','ASF95631'),
                                     ('98742022','CS 222','ASF95631'),
                                     ('78942022','SOAN 221','ASF95631'),
									 ('78942022','CS 222','ASF95631'),
                                     ('78942022','ECON 102','ASF95631'),
                                     ('14632022','SOAN 221','ASF95631'),
									 ('14632022','CS 222','ASF95631'),
                                     ('14632022','ECON 102','ASF95631'),
                                     ('98652022','ENG 221','ASF95631'),
                                     ('98652022','SOAN 221','ASF95631'),
                                     ('98652022','CS 222','ASF95631'),
                                     ('78212022','SOAN 221','ASF95631'),
									 ('78212022','CS 222','ASF95631'),
                                     ('78212022','ECON 102','ASF95631'),
                                     ('89322022','SOAN 221','ASF95631'),
									 ('89322022','CS 222','ASF95631'),
                                     ('89322022','ECON 102','ASF95631');
INSERT INTO Faculty_Arrival(FacultyID)
        VALUES ("ASF95631");

INSERT INTO Faculty_Course_Availability (CourseID,Faculty_AvailabilityID)
		VALUES ('CS 222','1'),
			   ('SOAN 221','1'),
               ('MATH 221','1'),
               ('ECON 102','1'),
               ('BUSA 222','1'),
			   ('CS 221','1'),
               ('ENG 221','1');

-- Getting the number of course for the lecturer

SELECT FA.Id, FA.CourseID, FA.Day1, FA.Day2, FA.TimeGMT1, FA.TimeGMT2, CO.CourseName FROM Faculty_Availability FA 
LEFT JOIN Courses CO ON FA.CourseID = CO.CourseID
LEFT JOIN Faculty F ON FA.FacultyID = F.FacultyID
WHERE F.FacultyID = "ASF95631"
;
-- Above working


-- Getting the number of the couser for the students
SELECT FA.Id, FA.CourseID, FA.Day1, FA.Day2, FA.TimeGMT1, FA.TimeGMT2,F.AshesiEmail, C.CourseName, concat(F.FName,' ',F.LName) as Faculty 
		FROM Registered_Courses R         
        INNER JOIN Courses C on R.CourseID = C.CourseID
        INNER JOIN Faculty F on R.FacultyID = F.FacultyID
        INNER JOIN Faculty_Availability FA ON FA.CourseID = R.CourseID
        WHERE R.StudentID ='60652022' GROUP BY C.CourseID ;
        -- Above working
-- Getting the data from the booking

SELECT B.BookingID,B.BookingStatus,C.CourseName, B.Faculty_AvailabilityId,B.Booked_day, B.Booked_time, CONCAT(S.FName," ",S.LName) as Student FROM Booking B 
INNER JOIN Faculty_Availability FA ON B.Faculty_AvailabilityId = FA.Id 
INNER JOIN Faculty F ON FA.FacultyID = F.FacultyID
INNER JOIN Courses C ON FA.CourseID = C.CourseID
INNER JOIN Students S ON B.StudentID = S.StudentID 
WHERE F.FacultyID = "ASF95631";


-- Getting the pending data from the booking
SELECT B.BookingID,B.BookingStatus,C.CourseName, B.Faculty_AvailabilityId,B.Booked_day, B.Booked_time, B.StudentID, CONCAT(F.FName," ",F.LName) as Faculty FROM Booking B 
INNER JOIN Faculty_Availability FA ON B.Faculty_AvailabilityId = FA.Id 
INNER JOIN Faculty F ON FA.FacultyID = F.FacultyID
INNER JOIN Courses C ON FA.CourseID = C.CourseID
INNER JOIN Students S ON B.StudentID = S.StudentID
WHERE B.StudentID = "60652022";


update Booking
   set BookingStatus=value
 where BookingID = id;

update Faculty_Availability
   set field=value
 where condition