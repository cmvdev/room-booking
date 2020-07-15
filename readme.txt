ROOM BOOKING TOOL
-------------------

Developer: Marvin Vounkeng
Program languages : PHP, HTML5, JavaScript, Bootstrap, Jquerry, MySQL
----------------------

Quick start guide
-------------------

To Do : 
- Create Database
CREATE TABLE `decix_meeting`.`Name` ( `Buchung_Id` INT NOT NULL AUTO_INCREMENT , `Name` VARCHAR(30) NOT NULL , `Email` VARCHAR(30) NOT NULL , `TimeFrom` DATETIME NOT NULL , `TimeTo` DATETIME NOT NULL , `Places` INT NOT NULL , `Catering` BOOLEAN NOT NULL,  PRIMARY KEY (`Booking_Id`)) ENGINE = InnoDB;
- Config Database with username and Password
- Rename BASE_URL for redirect in 'Js/functions'