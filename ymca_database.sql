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
    PRIMARY KEY (ClassID),
    FOREIGN KEY (PrerequisiteClassID) REFERENCES Classes(ClassID) ON DELETE SET NULL
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
ALTER TABLE Classes MODIFY COLUMN PrerequisiteClassID VARCHAR(100);
ALTER TABLE Classes ADD COLUMN ClassDescription VARCHAR(500) AFTER ClassName;
ALTER TABLE Classes ADD COLUMN PriceStaff DECIMAL(10, 2) AFTER MaxParticipants;
ALTER TABLE Classes ADD COLUMN CurrentParticipantCount INT AFTER MaxParticipants;
    -- allows storage of more than one day for classes as a csv. --

-- Alterations to User Permissions --
UPDATE people SET role = "supervisor", PermissionID = 2 where FirstName = "Eugene";
UPDATE people SET role = "staff", PermissionID = 3 where FirstName = "Squidward";

-- Add Future Classes to Database --
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassID)
VALUES ('Yoga Basics', '2025-01-10', '2025-03-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 101', 20, 15.00, 25.00, NULL);

INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassID)
VALUES ('Yoga Intermediate', '2025-04-10', '2025-06-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 103', 20, 20.00, 30.00, "Yoga Basics");


-- Add Past Classes to Database --
INSERT INTO Classes (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassID)
VALUES ('Yoga Basics', '2024-04-10', '2024-06-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 100', 10, 10.00, 15.00, NULL);

-- Add Current Classes to Database --