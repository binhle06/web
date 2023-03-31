Create Table User_info(
    Id int(6) DEFAULT AUTO_INCREMENT,
    UserId Varchar(10) Unique,
    UserFullname Char(50),
    pass Varchar(20),
    User_role ENUM('Student', 'Teacher','Admin'),
    Date_Created datetime DEFAULT CURRENT_TIMESTAMP,
    Primary Key(UserId),
    Key(Id)
)
-- Add sample user
insert into User_info (UserId, UserFullname, pass, User_role) Values ('B1910631','Pham Huu Duc', '1234', 'Admin');
insert into User_info (UserId, UserFullname, pass, User_role) Values ('B1910665','Tran Phuc Loc', '1234', 'Student');
insert into User_info (UserId, UserFullname, pass, User_role) Values ('B1901951','Tran Phuc Thinh', '1234', 'Teacher');

--Check Login
--select * from User_info where UserId =

-- Drop Table User_info

Create Table Test_list(
    TestId Varchar(10) Unique NOT NULL,
    TestName Varchar(200) NOT NULL,  
    Question_total int(2) NOT NULL,
    Time_limit_minute int(3) NOT NULL,
    UserFullname Char(50) NOT NULL,
    UserId Varchar(10),
    exam_status enum('Activated','Deactivated') NOT NULL,
    Primary Key(TestId),
    CONSTRAINT FK_UserName FOREIGN KEY (UserId)
     REFERENCES User_info(UserId)
)

insert into Test_list Values ('CT203H','Quan Ly du an phan mem','30','30','Pham Huu Duc', 'B1910631','Pending');
insert into Test_list Values ('CT205H','Nien Luan','30','30','Tran Phuc Thinh', 'B1901951','Created');
INSERT INTO Test_list (TestId, TestName, Question_total, Time_limit_minute, UserFullname, UserId, exam_status) 
VALUES ('CT200', 'English', 50,60, 'Pham Huu Duc', 'B1910631', 'Pending');
-- Drop Table Test_list



Create table Question_List(
  `question_id` int(11) NOT NULL auto_increment,
  TestId 	varchar(10) NOT NULL,
  `question_detail` text NOT NULL,
  `answer_option` enum('1','2','3','4') NOT NULL,
  Primary Key(`question_id`)
)
Drop table Question_List;

CREATE TABLE `option_table` (
  `option_id` int(11) NOT NULL auto_increment,
  `question_id` int(11) NOT NULL,
  `option_number` int(2) NOT NULL,
  `option_detail` varchar(250) NOT NULL,
  Primary Key(`option_id`)
) 


Create Table Result_list(
    UserId Varchar(10) ,
    UserFullname Char(50),
    attempt int(3) DEFAULT 0,
    score int(3),
    'Time' Timestamp,
    FOREIGN KEY (UserId) REFERENCES User_info(UserId)
)


