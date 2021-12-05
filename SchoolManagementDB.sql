CREATE DATABASE school_management;
USE school_management;

CREATE TABLE Users(
    ID VARCHAR(8) NOT NULL, 
    Name VARCHAR (20) NOT NULL, 
    Password VARCHAR(40) NOT NULL,  
    LibPerm VARCHAR(5) NOT NULL DEFAULT "N", 
    HostelPerm VARCHAR(5) NOT NULL DEFAULT "N", 
    OnlineTutorialsPerm VARCHAR(5) NOT NULL DEFAULT "N",
    
    PRIMARY KEY (ID)  
);

INSERT INTO Users 
VALUES ("Admin", "Fname I Lname", "passwd123", "RE", "RE", "RE"),
("HUser", "AFname AI ALname", "passwd123", "N", "RE", "N"),
("LUser", "CFname CI CLname", "passwd123", "RE", "N", "R"),
("TUser", "BFname BI BLname", "passwd123", "N", "N", "T");

CREATE TABLE Students(
    StudentID VARCHAR(8) NOT NULL,  
    Name VARCHAR (20) NOT NULL, 
    DOB DATE,  
    Sex CHAR,  
    Address CHAR (50), 
    PhoneNo VARCHAR(9) NOT NULL, 
    Course VARCHAR (10) NOT NULL,   

    PRIMARY KEY (StudentID)  
);

INSERT INTO Students 
VALUES ("18ERME03", "ASD FGH", "2002-08-18", "F", "123 ABC Apartment, XYZ", "123456789", "QWE"),
("17WRMQ03", "QWE RTY", "1995-01-23", "F", "123, ASDF Apartment, QWE", "987654021", "XYZ");

CREATE TABLE Library( 
    BookID VARCHAR(6) NOT NULL, 
    BookName VARCHAR (50) NOT NULL, 
    Author VARCHAR (30) NOT NULL, 
    Genre VARCHAR (20) NOT NULL, 
    PRIMARY KEY (BookID)
);

INSERT INTO Library
VALUES ('EDU1', 'The Fundamentals of Database Systems 6th edition', 'Elmasri, Navathe', 'Educational'),
('SH2', 'Nonviolent Communication', 'Rosenberg', 'Self help'),
('SF3', 'Dune', 'Herbert', 'Science fiction');

CREATE TABLE Checkout_Logs(
    BookID VARCHAR(15) NULL,  
    LenderID VARCHAR(8) NOT NULL,   
    IssuedDate DATE NOT NULL,
    ReturnedDate DATE DEFAULT NULL
);

INSERT INTO Checkout_Logs VALUES ('SH2', '18ERME03', '2021-11-07' , NULL);

CREATE TABLE Hostel(
    RoomNumber INT NOT NULL,   
    BuildingNumber INT NOT NULL,   
    OccupancyLimit INT NOT NULL,
    NumOfOccupants INT NOT NULL DEFAULT 0,
    PRIMARY KEY (RoomNumber, BuildingNumber)
);

INSERT INTO Hostel
VALUES ('1011', '2', 2, 1),
('1012', '2', 2, 0),
('1001', '1', 1, 1);

CREATE TABLE Occupancy_Logs(
    RoomNumber INT NOT NULL,   
    BuildingNumber INT NOT NULL,   
    OccupantID VARCHAR(8) NOT NULL,
    MoveInDate DATE NOT NULL,
    MoveOutDate DATE DEFAULT NULL
);

INSERT INTO Occupancy_Logs
VALUES ('1011', '2', '18ERME03', '2021-11-07', NULL),
('1001', '1', '17WRMQ03', '2020-12-02', NULL);

CREATE TABLE Subject(
    SubjectID VARCHAR(5) NOT NULL,  
    SubjectName VARCHAR(150),
    InstructorID VARCHAR(8) NOT NULL, 
    PRIMARY KEY (SubjectID)   
);

CREATE TABLE demo_subject(  
    PostID INT NOT NULL,
    PostTitle VARCHAR(150) NOT NULL,
    PostDesc VARCHAR(500),
    PostEmbed VARCHAR(2083),
    PostUploadTime DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (PostID) 
);


INSERT INTO Subject
VALUES ('PPL1', 'Principles of Programming Languages', 'TUser');
CREATE TABLE PPL1 LIKE demo_subject;
INSERT INTO PPL1
VALUES ('1', 'Test title', 'Test desc', 'https://youtu.be/embed/-Pg819il8lY', '2021-03-12');