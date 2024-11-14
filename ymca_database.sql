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

-- Last Updated: [11/12/2024]

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
    IsActive BOOLEAN DEFAULT TRUE NOT NULL,
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
VALUES 
('Yoga Basics', '2025-01-10', '2025-03-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 101', 20, 20, 15.00, 25.00, NULL),
('Yoga Intermediate', '2025-04-10', '2025-06-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 103', 20, 20, 20.00, 30.00, 'Yoga Basics'),
('Pilates Beginner', '2025-02-15', '2025-05-01', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 15, 20, 12.00, 18.00, NULL),
('Pilates Advanced', '2025-05-15', '2025-07-01', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 10, 20, 18.00, 25.00, 'Pilates Beginner'),
('HIIT Workout', '2025-03-01', '2025-05-15', 'Saturday', '08:00:00', '09:00:00', 'Gym', 30, 20, 10.00, 20.00, NULL),
('Swimming Basics', '2025-06-01', '2025-08-01', 'Tuesday,Thursday', '13:00:00', '14:00:00', 'Pool', 25, 20, 15.00, 25.00, NULL),
('Advanced Swimming', '2025-09-01', '2025-11-01', 'Tuesday,Thursday', '14:00:00', '15:00:00', 'Pool', 20, 20, 20.00, 30.00, 'Swimming Basics');

-- Add Current Classes to Database --
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES 
('Yoga Basics', '2024-10-10', '2025-01-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 204', 20, 20, 15.00, 25.00, NULL),
('Yoga Intermediate', '2024-09-10', '2024-12-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 103', 20, 20, 20.00, 30.00, 'Yoga Basics'),
('Pilates Beginner', '2024-11-01', '2025-01-01', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 15, 20, 12.00, 18.00, NULL),
('Pilates Advanced', '2024-10-15', '2024-12-14', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 10, 20, 18.00, 25.00, 'Pilates Beginner'),
('HIIT Workout', '2024-08-01', '2024-12-15', 'Saturday', '08:00:00', '09:00:00', 'Gym', 30, 20, 10.00, 20.00, NULL),
('Swimming Basics', '2024-06-01', '2024-12-01', 'Tuesday,Thursday', '13:00:00', '14:00:00', 'Pool', 25, 20, 15.00, 25.00, NULL),
('Advanced Swimming', '2024-09-01', '2025-02-01', 'Tuesday,Thursday', '14:00:00', '15:00:00', 'Pool', 20, 20, 20.00, 30.00, 'Swimming Basics');

-- Add Past Classes to Database --
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES 
('Yoga Basics', '2024-04-10', '2024-06-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 100', 10, 20, 10.00, 15.00, NULL),
('Kickboxing Intro', '2024-01-10', '2024-03-20', 'Monday,Wednesday', '17:00:00', '18:00:00', 'Room 105', 20, 20, 10.00, 15.00, NULL),
('Kickboxing Advanced', '2024-04-10', '2024-06-20', 'Monday,Wednesday', '17:00:00', '18:00:00', 'Room 105', 15, 20, 15.00, 25.00, 'Kickboxing Intro'),
('Senior Yoga', '2023-09-10', '2023-12-20', 'Thursday', '10:00:00', '11:00:00', 'Room 101', 12, 20, 8.00, 12.00, NULL),
('Youth Swimming', '2024-03-01', '2024-06-01', 'Saturday', '09:00:00', '10:00:00', 'Pool', 20, 20, 5.00, 10.00, NULL);

-- Add Shark Program on Sundays
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES 
('Shark', '2024-11-17', '2024-12-22', 'Sunday', '17:00:00', '17:40:00', 'YMCA Onalaska Pool', 8, 24.00, 48.00, 96.00, 'Pike Level');

-- Add Shark Program on Mondays and Wednesdays
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES 
('Shark', '2024-11-17', '2024-12-22', 'Monday,Wednesday', '18:00:00', '18:40:00', 'YMCA Onalaska Pool', 8, 33.00, 65.00, 130.00, 'Pike Level');

-- Add Log Rolling Program on Sundays
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES 
('Log Rolling', '2024-11-17', '2024-12-22', 'Sunday', '17:00:00', '17:40:00', 'YMCA Onalaska Pool', 1, 50.00, 100.00, 200.00, NULL);


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
UPDATE Classes SET MaxParticipants = FLOOR(10 + RAND() * 25);
UPDATE Classes SET PriceStaff = CEIL(PriceMember / 2);


-- Populate Registrations for various classes ensuring prerequisites are met
-- Assume PersonIDs 1 to 25 are the test users created

INSERT INTO Registrations (PersonID, ClassID, RegistrationDate, PaymentAmount, PaymentStatus)
VALUES 
(1, 2, '2024-06-15', 20.00, 'Paid'),
(2, 3, '2024-12-05', 12.00, 'Paid'),
(3, 4, '2025-03-15', 18.00, 'Paid'),
(4, 5, '2025-04-01', 10.00, 'Paid'),
(5, 6, '2024-12-01', 15.00, 'Paid'),
(6, 7, '2024-07-10', 20.00, 'Paid'),
(7, 8, '2024-11-20', 10.00, 'Due'),
(8, 9, '2024-03-05', 10.00, 'Paid'),
(9, 10, '2024-08-01', 15.00, 'Paid'),
(10, 11, '2023-12-01', 8.00, 'Paid'),
(11, 12, '2024-02-10', 5.00, 'Paid'),
(12, 13, '2024-09-10', 12.00, 'Due'),
(13, 14, '2024-11-05', 10.00, 'Paid'),
(14, 15, '2024-08-15', 20.00, 'Paid'),
(15, 16, '2024-09-01', 12.00, 'Paid'),
(16, 17, '2025-05-20', 18.00, 'Due'),
(17, 18, '2024-10-05', 20.00, 'Paid'),
(18, 19, '2024-11-01', 30.00, 'Paid'),
(19, 20, '2024-12-01', 18.00, 'Due'),
(20, 21, '2024-11-15', 48.00, 'Paid'),
(21, 22, '2024-11-22', 96.00, 'Due'),
(22, 1, '2024-09-15', 15.00, 'Paid'),
(23, 3, '2024-11-25', 12.00, 'Paid'),
(24, 5, '2024-10-30', 10.00, 'Paid'),
(1, 7, '2024-09-20', 20.00, 'Paid'),
(2, 2, '2024-07-01', 20.00, 'Paid'),
(3, 6, '2024-05-10', 15.00, 'Paid'),
(4, 10, '2024-09-01', 18.00, 'Paid'),
(5, 8, '2024-08-01', 10.00, 'Paid'),
(6, 13, '2024-12-05', 12.00, 'Paid'),
(7, 9, '2024-10-05', 15.00, 'Paid'),
(8, 4, '2024-11-01', 18.00, 'Paid'),
(9, 16, '2024-06-10', 12.00, 'Paid'),
(10, 14, '2024-09-15', 20.00, 'Paid'),
(11, 12, '2024-07-10', 5.00, 'Paid'),
(12, 15, '2024-08-10', 18.00, 'Paid'),
(13, 17, '2024-07-20', 18.00, 'Paid'),
(14, 18, '2025-03-10', 30.00, 'Paid'),
(15, 19, '2024-10-01', 20.00, 'Paid'),
(16, 20, '2024-08-25', 30.00, 'Paid'),
(17, 21, '2024-12-10', 48.00, 'Due'),
(18, 22, '2024-09-01', 96.00, 'Paid'),
(19, 5, '2024-06-20', 25.00, 'Paid'),
(20, 9, '2024-05-05', 15.00, 'Paid'),
(21, 8, '2024-09-15', 25.00, 'Paid'),
(22, 10, '2024-07-05', 15.00, 'Paid'),
(23, 11, '2024-12-01', 18.00, 'Paid'),
(24, 12, '2024-06-01', 5.00, 'Paid'),



UPDATE Classes 
SET CurrentParticipantCount = (
    SELECT COUNT(*) 
    FROM Registrations 
    WHERE Registrations.ClassID = Classes.ClassID
);
