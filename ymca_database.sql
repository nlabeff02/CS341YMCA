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
    CanCreateNonMember BOOLEAN DEFAULT FALSE,
    CanEditNonMember BOOLEAN DEFAULT FALSE,
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
    ClassLocation VARCHAR(100),
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
    MembershipPaid BOOLEAN DEFAULT FALSE,
    HasMessage BOOLEAN DEFAULT FALSE,
    MessageText VARCHAR(500),
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
INSERT IGNORE INTO Permissions (Role, CanCreateNonMember, CanEditNonMember, CanCreateMember, CanEditMember, CanCreateClass, CanRegisterClass, CanViewRegistrations, CanRemoveRegistrations, CanCreateEmployee, CanEditEmployee)
VALUES ('Admin', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE);
-- Supervisor role --
INSERT IGNORE INTO Permissions (Role, CanCreateNonMember, CanEditNonMember, CanCreateMember, CanEditMember, CanCreateClass, CanRegisterClass, CanViewRegistrations, CanRemoveRegistrations, CanCreateEmployee, CanEditEmployee)
VALUES ('Supervisor', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE);
-- Staff role --
INSERT IGNORE INTO Permissions (Role, CanCreateNonMember, CanEditNonMember, CanCreateMember, CanEditMember, CanCreateClass, CanRegisterClass, CanViewRegistrations, CanRemoveRegistrations, CanCreateEmployee, CanEditEmployee)
VALUES ('Staff', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, FALSE, FALSE);
-- Member role --
INSERT IGNORE INTO Permissions (Role, CanCreateNonMember, CanEditNonMember, CanCreateMember, CanEditMember, CanCreateClass, CanRegisterClass, CanViewRegistrations, CanRemoveRegistrations, CanCreateEmployee, CanEditEmployee)
VALUES ('Member', TRUE, FALSE, TRUE, FALSE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE);
-- NonMember role --
INSERT IGNORE INTO Permissions (Role, CanCreateNonMember, CanEditNonMember, CanCreateMember, CanEditMember, CanCreateClass, CanRegisterClass, CanViewRegistrations, CanRemoveRegistrations, CanCreateEmployee, CanEditEmployee)
VALUES ('NonMember', TRUE, FALSE, FALSE, FALSE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE);


-- Add Future Classes to Database --
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Yoga Basics', '2025-01-10', '2025-03-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 101', 20, 20, 15.00, 25.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Yoga Intermediate', '2025-04-10', '2025-06-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 103', 20, 20, 20.00, 30.00, "Yoga Basics");

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Pilates Beginner', '2025-02-15', '2025-05-01', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 15, 20, 12.00, 18.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Pilates Advanced', '2025-05-15', '2025-07-01', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 10, 20, 18.00, 25.00, 'Pilates Beginner');

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('HIIT Workout', '2025-03-01', '2025-05-15', 'Saturday', '08:00:00', '09:00:00', 'Gym', 30, 20, 10.00, 20.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Swimming Basics', '2025-06-01', '2025-08-01', 'Tuesday,Thursday', '13:00:00', '14:00:00', 'Pool', 25, 20, 15.00, 25.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Advanced Swimming', '2025-09-01', '2025-11-01', 'Tuesday,Thursday', '14:00:00', '15:00:00', 'Pool', 20, 20, 20.00, 30.00, 'Swimming Basics');


-- Add Current Classes to Database --
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Yoga Basics', '2024-10-10', '2025-01-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 204', 20, 20, 15.00, 25.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Yoga Intermediate', '2024-09-10', '2024-12-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 103', 20, 20, 20.00, 30.00, "Yoga Basics");

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Pilates Beginner', '2024-11-01', '2025-01-01', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 15, 20, 12.00, 18.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Pilates Advanced', '2024-10-15', '2024-12-14', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 10, 20, 18.00, 25.00, 'Pilates Beginner');

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('HIIT Workout', '2024-08-01', '2024-12-15', 'Saturday', '08:00:00', '09:00:00', 'Gym', 30, 20, 10.00, 20.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Swimming Basics', '2024-06-01', '2024-12-01', 'Tuesday,Thursday', '13:00:00', '14:00:00', 'Pool', 25, 20, 15.00, 25.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Advanced Swimming', '2024-09-01', '2025-02-01', 'Tuesday,Thursday', '14:00:00', '15:00:00', 'Pool', 20, 20, 20.00, 30.00, 'Swimming Basics');


-- Add Past Classes to Database --
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Yoga Basics', '2024-04-10', '2024-06-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 100', 10, 20, 10.00, 15.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Kickboxing Intro', '2024-01-10', '2024-03-20', 'Monday,Wednesday', '17:00:00', '18:00:00', 'Room 105', 20, 20, 10.00, 15.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Kickboxing Advanced', '2024-04-10', '2024-06-20', 'Monday,Wednesday', '17:00:00', '18:00:00', 'Room 105', 15, 20, 15.00, 25.00, 'Kickboxing Intro');

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Senior Yoga', '2023-09-10', '2023-12-20', 'Thursday', '10:00:00', '11:00:00', 'Room 101', 12, 20, 8.00, 12.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Youth Swimming', '2024-03-01', '2024-06-01', 'Saturday', '09:00:00', '10:00:00', 'Pool', 20, 20, 5.00, 10.00, NULL);


-- Add Shark Program on Sundays
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Shark', '2024-11-17', '2024-12-22', 'Sunday', '17:00:00', '17:40:00', 'YMCA Onalaska Pool', 8, 24.00, 48.00, 96.00, 'Pike Level');

-- Add Shark Program on Mondays and Wednesdays
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Shark', '2024-11-17', '2024-12-22', 'Monday,Wednesday', '18:00:00', '18:40:00', 'YMCA Onalaska Pool', 8, 33.00, 65.00, 130.00, 'Pike Level');

-- Add Log Rolling Program on Sundays
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES ('Log Rolling', '2024-11-17', '2024-12-22', 'Sunday', '17:00:00', '17:40:00', 'YMCA Onalaska Pool', 1, 50.00, 100.00, 200.00, NULL);


-- Alterations to the Database --
ALTER TABLE Classes MODIFY COLUMN DayOfWeek VARCHAR(50);
ALTER TABLE Classes DROP FOREIGN KEY PrerequisiteClassID;
ALTER TABLE Classes DROP COLUMN PrerequisiteClassID;
ALTER TABLE Classes ADD COLUMN PrerequisiteClassName VARCHAR(100);
ALTER TABLE Classes ADD COLUMN ClassDescription VARCHAR(500) AFTER ClassName;
ALTER TABLE Classes ADD COLUMN PriceStaff DECIMAL(10, 2) AFTER MaxParticipants;
ALTER TABLE Classes ADD COLUMN CurrentParticipantCount INT AFTER MaxParticipants;

-- Alterations to User Permissions --
--UPDATE people SET role = "supervisor", PermissionID = 2 where FirstName = "Eugene";
--UPDATE people SET role = "staff", PermissionID = 3 where FirstName = "Squidward";

INSERT INTO People (FirstName, LastName, Email, PhoneNumber, Over18, IsParent, IsChild, PasswordHash, Role, PermissionID, MembershipPaid, HasMessage, MessageText) VALUES
('Mickey', 'Mouse', 'mm@email.com', '123-456-7890', TRUE, FALSE, FALSE, '123qwe', 'Member', 4, TRUE, FALSE, NULL),
('Donald', 'Duck', 'dd@email.com', '234-567-8901', TRUE, TRUE, FALSE, '123qwe', 'NonMember', 5, FALSE, TRUE, 'Welcome to YMCA!'),
('Goofy', 'Goof', 'gg@email.com', '345-678-9012', TRUE, FALSE, FALSE, '123qwe', 'Member', 4, TRUE, FALSE, NULL),
('Daisy', 'Duck', 'dd@email.com', '456-789-0123', TRUE, TRUE, FALSE, '123qwe', 'Member', 4, TRUE, TRUE, 'We hope you enjoy your classes!'),
('Pluto', 'Dog', 'pd@email.com', '567-890-1234', FALSE, FALSE, TRUE, '123qwe', 'NonMember', 5, FALSE, FALSE, NULL),
('Simba', 'Lion', 'sl@email.com', '678-901-2345', TRUE, FALSE, FALSE, '123qwe', 'Member', 4, TRUE, FALSE, NULL),
('Ariel', 'Mermaid', 'am@email.com', '789-012-3456', TRUE, FALSE, FALSE, '123qwe', 'NonMember', 5, FALSE, TRUE, 'See our upcoming events!'),
('Elsa', 'Queen', 'eq@email.com', '890-123-4567', TRUE, TRUE, FALSE, '123qwe', 'Member', 4, TRUE, FALSE, NULL),
('Anna', 'Princess', 'ap@email.com', '901-234-5678', TRUE, TRUE, FALSE, '123qwe', 'NonMember', 5, FALSE, TRUE, 'Special offers for members!'),
('Woody', 'Cowboy', 'wc@email.com', '123-345-6789', TRUE, FALSE, FALSE, '123qwe', 'Member', 4, TRUE, FALSE, NULL),
('Buzz', 'Lightyear', 'bl@email.com', '234-456-7890', TRUE, FALSE, FALSE, '123qwe', 'NonMember', 5, FALSE, FALSE, NULL),
('Homer', 'Simpson', 'hs@email.com', '345-567-8901', TRUE, TRUE, FALSE, '123qwe', 'Member', 4, TRUE, TRUE, 'Welcome to YMCA!'),
('Bart', 'Simpson', 'bs@email.com', '456-678-9012', FALSE, FALSE, TRUE, '123qwe', 'NonMember', 5, FALSE, FALSE, NULL),
('Marge', 'Simpson', 'ms@email.com', '567-789-0123', TRUE, TRUE, FALSE, '123qwe', 'Member', 4, TRUE, FALSE, NULL),
('Lisa', 'Simpson', 'ls@email.com', '678-890-1234', FALSE, FALSE, TRUE, '123qwe', 'NonMember', 5, FALSE, TRUE, 'Check out our family plans!'),
('Peter', 'Pan', 'pp@email.com', '789-901-2345', TRUE, FALSE, FALSE, '123qwe', 'Member', 4, TRUE, FALSE, NULL),
('Tiana', 'Princess', 'tp@email.com', '890-012-3456', TRUE, FALSE, FALSE, '123qwe', 'NonMember', 5, FALSE, FALSE, NULL),
('Aladdin', 'Prince', 'ap@email.com', '901-123-4567', TRUE, FALSE, FALSE, '123qwe', 'Member', 4, TRUE, TRUE, 'Welcome to YMCA!'),
('Belle', 'Beauty', 'bb@email.com', '123-234-5678', TRUE, FALSE, FALSE, '123qwe', 'NonMember', 5, FALSE, FALSE, NULL),
('Jasmine', 'Princess', 'jp@email.com', '234-345-6789', TRUE, TRUE, FALSE, '123qwe', 'Member', 4, TRUE, FALSE, NULL),
('Rapunzel', 'Tower', 'rt@email.com', '345-456-7890', TRUE, FALSE, FALSE, '123qwe', 'NonMember', 5, FALSE, TRUE, 'Join our special classes!'),
('Mulan', 'Warrior', 'mw@email.com', '456-567-8901', TRUE, TRUE, FALSE, '123qwe', 'Member', 4, TRUE, FALSE, NULL),
('Shrek', 'Ogre', 'so@email.com', '567-678-9012', TRUE, TRUE, FALSE, '123qwe', 'NonMember', 5, FALSE, FALSE, NULL),
('Fiona', 'Princess', 'fp@email.com', '678-789-0123', TRUE, TRUE, FALSE, '123qwe', 'Member', 4, TRUE, TRUE, 'Welcome to YMCA!'),
('Pooh', 'Bear', 'pb@email.com', '789-890-1234', TRUE, TRUE, FALSE, '123qwe', 'NonMember', 5, FALSE, FALSE, NULL);


-- Fix up the max participants and staff prices.
UPDATE Classes SET MaxParticipants = FLOOR(2 + RAND() * 11);
UPDATE Classes SET PriceStaff = CEIL(PriceMember / 2);


-- Populate Registrations for various classes ensuring prerequisites are met
-- Assume PersonIDs 1 to 25 are the test users created

-- Register users for past classes
INSERT INTO Registrations (PersonID, ClassID, RegistrationDate, PaymentAmount, PaymentStatus)
VALUES 
(1, (SELECT ClassID FROM Classes WHERE ClassName = 'Yoga Basics' AND StartDate = '2024-04-10'), '2024-05-01', 10.00, 'Paid'),
(2, (SELECT ClassID FROM Classes WHERE ClassName = 'Kickboxing Intro'), '2024-02-10', 10.00, 'Paid'),
(3, (SELECT ClassID FROM Classes WHERE ClassName = 'Senior Yoga'), '2023-10-01', 8.00, 'Paid'),
(4, (SELECT ClassID FROM Classes WHERE ClassName = 'Youth Swimming'), '2024-04-15', 5.00, 'Paid'),
(5, (SELECT ClassID FROM Classes WHERE ClassName = 'Kickboxing Advanced' AND StartDate = '2024-04-10'), '2024-05-01', 15.00, 'Paid');

-- Register users for current classes
INSERT INTO Registrations (PersonID, ClassID, RegistrationDate, PaymentAmount, PaymentStatus)
VALUES 
(6, (SELECT ClassID FROM Classes WHERE ClassName = 'Pilates Beginner' AND StartDate = '2024-11-01'), '2024-11-10', 12.00, 'Paid'),
(7, (SELECT ClassID FROM Classes WHERE ClassName = 'HIIT Workout' AND StartDate = '2024-08-01'), '2024-10-10', 10.00, 'Paid'),
(8, (SELECT ClassID FROM Classes WHERE ClassName = 'Swimming Basics' AND StartDate = '2024-06-01'), '2024-07-01', 15.00, 'Paid');

-- Register users for future classes, ensuring prerequisites are met
INSERT INTO Registrations (PersonID, ClassID, RegistrationDate, PaymentAmount, PaymentStatus)
VALUES 
(9, (SELECT ClassID FROM Classes WHERE ClassName = 'Yoga Basics' AND StartDate = '2025-01-10'), '2025-01-05', 15.00, 'Due'),
(10, (SELECT ClassID FROM Classes WHERE ClassName = 'Yoga Intermediate' AND StartDate = '2025-04-10'), '2025-03-01', 20.00, 'Waived'),
(11, (SELECT ClassID FROM Classes WHERE ClassName = 'Pilates Beginner' AND StartDate = '2025-02-15'), '2025-02-01', 12.00, 'Paid'),
(12, (SELECT ClassID FROM Classes WHERE ClassName = 'Pilates Advanced' AND StartDate = '2025-05-15'), '2025-04-15', 18.00, 'Paid');

-- Additional registrations to ensure all users are registered for at least 2 classes
INSERT INTO Registrations (PersonID, ClassID, RegistrationDate, PaymentAmount, PaymentStatus)
VALUES 
(13, (SELECT ClassID FROM Classes WHERE ClassName = 'HIIT Workout' AND StartDate = '2025-03-01'), '2025-03-05', 10.00, 'Paid'),
(14, (SELECT ClassID FROM Classes WHERE ClassName = 'Swimming Basics' AND StartDate = '2025-06-01'), '2025-06-01', 15.00, 'Paid'),
(15, (SELECT ClassID FROM Classes WHERE ClassName = 'Advanced Swimming' AND StartDate = '2025-09-01'), '2025-09-05', 20.00, 'Due'),
(16, (SELECT ClassID FROM Classes WHERE ClassName = 'Yoga Basics' AND StartDate = '2024-10-10'), '2024-10-15', 15.00, 'Paid'),
(17, (SELECT ClassID FROM Classes WHERE ClassName = 'Pilates Beginner' AND StartDate = '2025-02-15'), '2025-02-20', 12.00, 'Paid'),
(18, (SELECT ClassID FROM Classes WHERE ClassName = 'Yoga Intermediate' AND StartDate = '2025-04-10'), '2025-03-10', 20.00, 'Waived'),
(19, (SELECT ClassID FROM Classes WHERE ClassName = 'Senior Yoga'), '2023-09-15', 8.00, 'Paid'),
(20, (SELECT ClassID FROM Classes WHERE ClassName = 'Youth Swimming' AND StartDate = '2024-03-01'), '2024-03-10', 5.00, 'Paid'),
(21, (SELECT ClassID FROM Classes WHERE ClassName = 'Kickboxing Advanced' AND StartDate = '2024-04-10'), '2024-05-10', 15.00, 'Due'),
(22, (SELECT ClassID FROM Classes WHERE ClassName = 'Yoga Basics' AND StartDate = '2025-01-10'), '2025-01-15', 15.00, 'Paid'),
(23, (SELECT ClassID FROM Classes WHERE ClassName = 'Swimming Basics' AND StartDate = '2025-06-01'), '2025-06-15', 15.00, 'Paid'),
(24, (SELECT ClassID FROM Classes WHERE ClassName = 'Advanced Swimming' AND StartDate = '2025-09-01'), '2025-09-10', 20.00, 'Waived'),
(25, (SELECT ClassID FROM Classes WHERE ClassName = 'Pilates Advanced' AND StartDate = '2025-05-15'), '2025-05-20', 18.00, 'Paid');

UPDATE Classes
SET CurrentParticipantCount = (
    SELECT COUNT(*)
    FROM Registrations
    WHERE Registrations.ClassID = Classes.ClassID
);