-- ========================================================
--                  YMCA Database Schema
-- ========================================================
-- Tables:
-- 1. People: Stores information about staff, members,
--    and non-members.
-- 2. Classes: Contains details about classes offered,
--    including prerequisites.
-- 3. Registrations: Tracks the registrations of
--    individuals for classes.
-- 4. Permissions: Manages role-based permissions for
--    users within the YMCA.

-- Last Updated: [10/3/2024]

-- Permissions table --
CREATE TABLE IF NOT EXISTS Permissions (
    PermissionID INT NOT NULL AUTO_INCREMENT,
    Role ENUM('Admin', 'Supervisor', 'Staff', 'Member', 'NonMember') NOT NULL,
    CanCreateMember BOOLEAN DEFAULT FALSE,
    CanEditMember BOOLEAN DEFAULT FALSE,
    CanCreateClass BOOLEAN DEFAULT FALSE,
    CanRegisterClass BOOLEAN DEFAULT TRUE,
    CanViewRegistrations BOOLEAN DEFAULT FALSE,
    CanRemoveRegistrations BOOLEAN DEFAULT TRUE,
    CanCreateEmployee BOOLEAN DEFAULT FALSE,
    CanEditEmployee BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (PermissionID)
) ENGINE=InnoDB;

-- Classes table --
CREATE TABLE IF NOT EXISTS Classes (
    ClassID INT NOT NULL AUTO_INCREMENT,
    ClassName VARCHAR(100) NOT NULL,
    ClassDescription VARCHAR(500),
    StartDate DATE NOT NULL,
    EndDate DATE NOT NULL,
    DayOfWeek VARCHAR(50),
    StartTime TIME,
    EndTime TIME,
    Location VARCHAR(100),
    MaxParticipants INT,
    CurrentParticipantCount INT,
    PriceStaff DECIMAL(10, 2),
    PriceMember DECIMAL(10, 2),
    PriceNonMember DECIMAL(10, 2),
    PrerequisiteClassName VARCHAR(100),
    PRIMARY KEY (ClassID)
) ENGINE=InnoDB;

-- People table --
CREATE TABLE IF NOT EXISTS People (
    PersonID INT NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    PhoneNumber VARCHAR(15) NOT NULL,
    Over18 BOOLEAN DEFAULT FALSE,
    IsParent BOOLEAN DEFAULT FALSE,
    IsChild BOOLEAN DEFAULT FALSE,
    PasswordHash VARCHAR(100) NOT NULL,
    Role ENUM('Admin', 'Supervisor', 'Staff', 'Member', 'NonMember') NOT NULL,
    PermissionID INT NOT NULL,
    PRIMARY KEY (PersonID),
    FOREIGN KEY (PermissionID) REFERENCES Permissions(PermissionID) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Registrations table --
CREATE TABLE IF NOT EXISTS Registrations (
    RegistrationID INT NOT NULL AUTO_INCREMENT,
    PersonID INT NOT NULL,
    ClassID INT NOT NULL,
    RegistrationDate DATE NOT NULL,
    PaymentAmount DECIMAL(10, 2),
    PaymentStatus ENUM('Paid', 'Due', 'Waived'),
    PRIMARY KEY (RegistrationID),
    FOREIGN KEY (PersonID) REFERENCES People(PersonID),
    FOREIGN KEY (ClassID) REFERENCES Classes(ClassID)
) ENGINE=InnoDB;

-- Admin role --
INSERT IGNORE INTO Permissions (Role, CanCreateMember, CanEditMember, CanCreateClass, CanRegisterClass, CanViewRegistrations, CanRemoveRegistrations, CanCreateEmployee, CanEditEmployee)
VALUES ('Admin', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE);

-- Supervisor role --
INSERT IGNORE INTO Permissions (Role, CanCreateMember, CanEditMember, CanCreateClass, CanRegisterClass, CanViewRegistrations, CanRemoveRegistrations, CanCreateEmployee, CanEditEmployee)
VALUES ('Supervisor', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE);

-- Staff role --
INSERT IGNORE INTO Permissions (Role, CanCreateMember, CanEditMember, CanCreateClass, CanRegisterClass, CanViewRegistrations, CanRemoveRegistrations, CanCreateEmployee, CanEditEmployee)
VALUES ('Staff', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, FALSE, FALSE);

-- Member role --
INSERT IGNORE INTO Permissions (Role, CanCreateMember, CanEditMember, CanCreateClass, CanRegisterClass, CanViewRegistrations, CanRemoveRegistrations, CanCreateEmployee, CanEditEmployee)
VALUES ('Member', TRUE, FALSE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE);

-- NonMember role --
INSERT IGNORE INTO Permissions (Role, CanCreateMember, CanEditMember, CanCreateClass, CanRegisterClass, CanViewRegistrations, CanRemoveRegistrations, CanCreateEmployee, CanEditEmployee)
VALUES ('NonMember', TRUE, FALSE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE);

-- Test Members --
INSERT INTO People (FirstName, LastName, Email, Over18, IsParent, IsChild, PasswordHash, Role, PermissionID)
VALUES ('Patrick', 'Star', 'pstar@gmail.com', 1, 0, 0, '123qwe', 'Member', 4);


-- Alterations to the Database --
ALTER TABLE Classes MODIFY COLUMN DayOfWeek VARCHAR(50);
-- ALTER TABLE Classes MODIFY COLUMN PrerequisiteClassID VARCHAR(100);
ALTER TABLE Classes ADD COLUMN ClassDescription VARCHAR(500) AFTER ClassName;
ALTER TABLE Classes ADD COLUMN PriceStaff DECIMAL(10, 2) AFTER MaxParticipants;
ALTER TABLE Classes ADD COLUMN CurrentParticipantCount INT AFTER MaxParticipants;
    -- allows storage of more than one day for classes as a csv. --

-- Alterations to User Permissions --
UPDATE people SET role = "supervisor", PermissionID = 2 where FirstName = "Eugene";
UPDATE people SET role = "staff", PermissionID = 3 where FirstName = "Squidward";

-- Add Future Classes to Database --
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Yoga Basics', '2025-01-10', '2025-03-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 101', 20, 15.00, 25.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Yoga Intermediate', '2025-04-10', '2025-06-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 103', 20, 20.00, 30.00, "Yoga Basics");

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Pilates Beginner', '2025-02-15', '2025-05-01', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 15, 12.00, 18.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Pilates Advanced', '2025-05-15', '2025-07-01', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 10, 18.00, 25.00, 'Pilates Beginner');

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('HIIT Workout', '2025-03-01', '2025-05-15', 'Saturday', '08:00:00', '09:00:00', 'Gym', 30, 10.00, 20.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Swimming Basics', '2025-06-01', '2025-08-01', 'Tuesday,Thursday', '13:00:00', '14:00:00', 'Pool', 25, 15.00, 25.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Advanced Swimming', '2025-09-01', '2025-11-01', 'Tuesday,Thursday', '14:00:00', '15:00:00', 'Pool', 20, 20.00, 30.00, 'Swimming Basics');

-- Add Past Classes to Database --
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Yoga Basics', '2024-04-10', '2024-06-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 100', 10, 10.00, 15.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Kickboxing Intro', '2024-01-10', '2024-03-20', 'Monday,Wednesday', '17:00:00', '18:00:00', 'Room 105', 20, 10.00, 15.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Kickboxing Advanced', '2024-04-10', '2024-06-20', 'Monday,Wednesday', '17:00:00', '18:00:00', 'Room 105', 15, 15.00, 25.00, 'Kickboxing Intro');

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Senior Yoga', '2023-09-10', '2023-12-20', 'Thursday', '10:00:00', '11:00:00', 'Room 101', 12, 8.00, 12.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Youth Swimming', '2024-03-01', '2024-06-01', 'Saturday', '09:00:00', '10:00:00', 'Pool', 20, 5.00, 10.00, NULL);


-- Add Shark Program on Sundays
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Shark', '2024-11-17', '2024-12-22', 'Sunday', '17:00:00', '17:40:00', 'YMCA Onalaska Pool', 8, 48.00, 96.00, 'Pike Level');

-- Add Shark Program on Mondays and Wednesdays
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Shark', '2024-11-17', '2024-12-22', 'Monday,Wednesday', '18:00:00', '18:40:00', 'YMCA Onalaska Pool', 8, 65.00, 130.00, 'Pike Level');

-- Add Log Rolling Program on Sundays
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Log Rolling', '2024-11-17', '2024-12-22', 'Sunday', '17:00:00', '17:40:00', 'YMCA Onalaska Pool', 1, 100.00, 200.00, NULL);

