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
    MaxParticipants INT DEFAULT 0 NOT NULL,
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
    isActive BOOLEAN DEFAULT TRUE,
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
    IsActive BOOLEAN DEFAULT TRUE,
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

/*
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
*/
-- Add Future Classes to Database --
INSERT INTO Classes (ClassName, Description, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES 
('Yoga Basics', 'A beginner-level yoga class focusing on foundational poses.', '2025-01-10', '2025-03-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 101', 20, 20, 15.00, 25.00, NULL),
('Yoga Intermediate', 'An intermediate yoga class for improving skills.', '2025-04-10', '2025-06-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 103', 20, 20, 20.00, 30.00, 'Yoga Basics'),
('Pilates Beginner', 'An introduction to Pilates focusing on core strength.', '2025-02-15', '2025-05-01', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 15, 20, 12.00, 18.00, NULL),
('Pilates Advanced', 'An advanced Pilates class with challenging exercises.', '2025-05-15', '2025-07-01', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 10, 20, 18.00, 25.00, 'Pilates Beginner'),
('HIIT Workout', 'A high-intensity interval training session.', '2025-03-01', '2025-05-15', 'Saturday', '08:00:00', '09:00:00', 'Gym', 30, 20, 10.00, 20.00, NULL),
('Swimming Basics', 'Learn basic swimming techniques and water safety.', '2025-06-01', '2025-08-01', 'Tuesday,Thursday', '13:00:00', '14:00:00', 'Pool', 25, 20, 15.00, 25.00, NULL),
('Advanced Swimming', 'Advanced swimming techniques for experienced swimmers.', '2025-09-01', '2025-11-01', 'Tuesday,Thursday', '14:00:00', '15:00:00', 'Pool', 20, 20, 20.00, 30.00, 'Swimming Basics');

-- Add Current Classes to Database --
INSERT INTO Classes (ClassName, Description, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES 
('Yoga Basics', 'A beginner-level yoga class focusing on foundational poses.', '2024-10-10', '2025-01-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 204', 20, 20, 15.00, 25.00, NULL),
('Yoga Intermediate', 'An intermediate yoga class for improving skills.', '2024-09-10', '2024-12-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 103', 20, 20, 20.00, 30.00, 'Yoga Basics'),
('Pilates Beginner', 'An introduction to Pilates focusing on core strength.', '2024-11-01', '2025-01-01', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 15, 20, 12.00, 18.00, NULL),
('Pilates Advanced', 'An advanced Pilates class with challenging exercises.', '2024-10-15', '2024-12-14', 'Monday,Wednesday,Friday', '10:00:00', '11:00:00', 'Room 202', 10, 20, 18.00, 25.00, 'Pilates Beginner'),
('HIIT Workout', 'A high-intensity interval training session.', '2024-08-01', '2024-12-15', 'Saturday', '08:00:00', '09:00:00', 'Gym', 30, 20, 10.00, 20.00, NULL),
('Swimming Basics', 'Learn basic swimming techniques and water safety.', '2024-06-01', '2024-12-01', 'Tuesday,Thursday', '13:00:00', '14:00:00', 'Pool', 25, 20, 15.00, 25.00, NULL),
('Advanced Swimming', 'Advanced swimming techniques for experienced swimmers.', '2024-09-01', '2025-02-01', 'Tuesday,Thursday', '14:00:00', '15:00:00', 'Pool', 20, 20, 20.00, 30.00, 'Swimming Basics');

-- Add Past Classes to Database --
INSERT INTO Classes (ClassName, Description, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES 
('Yoga Basics', 'A beginner-level yoga class focusing on foundational poses.', '2024-04-10', '2024-06-20', 'Tuesday,Thursday', '09:00:00', '10:00:00', 'Room 100', 10, 20, 10.00, 15.00, NULL),
('Kickboxing Intro', 'An introductory kickboxing class for all levels.', '2024-01-10', '2024-03-20', 'Monday,Wednesday', '17:00:00', '18:00:00', 'Room 105', 20, 20, 10.00, 15.00, NULL),
('Kickboxing Advanced', 'An advanced kickboxing class for skill enhancement.', '2024-04-10', '2024-06-20', 'Monday,Wednesday', '17:00:00', '18:00:00', 'Room 105', 15, 20, 15.00, 25.00, 'Kickboxing Intro'),
('Senior Yoga', 'A yoga class tailored for seniors.', '2023-09-10', '2023-12-20', 'Thursday', '10:00:00', '11:00:00', 'Room 101', 12, 20, 8.00, 12.00, NULL),
('Youth Swimming', 'A swimming class designed for younger participants.', '2024-03-01', '2024-06-01', 'Saturday', '09:00:00', '10:00:00', 'Pool', 20, 20, 5.00, 10.00, NULL);


-- Add Shark Program on Sundays
INSERT INTO Classes (ClassName, ClassDescription, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES 
('Shark', 'Shark swimming class 1 for demo 2','2024-11-17', '2024-12-22', 'Sunday', '17:00:00', '17:40:00', 'YMCA Onalaska Pool', 8, 24.00, 48.00, 96.00, 'Pike');
-- Add Shark Program on Mondays and Wednesdays
INSERT INTO Classes (ClassName, ClassDescription, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES 
('Shark', 'Shark swimming class 2 for demo 2', '2024-11-17', '2024-12-22', 'Monday,Wednesday', '18:00:00', '18:40:00', 'YMCA Onalaska Pool', 8, 33.00, 65.00, 130.00, 'Pike');
-- Add Log Rolling Program on Sundays
INSERT INTO Classes (ClassName, ClassDescription, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES 
('Log Rolling', 'log rolling class for Demo 2', '2024-11-17', '2024-12-22', 'Sunday', '17:00:00', '17:40:00', 'YMCA Onalaska Pool', 1, 50.00, 100.00, 200.00, NULL);

-- Demo 3 Preloaded classes
INSERT INTO Classes (ClassName, ClassDescription, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES
('Shark', 'Shark swimming class 1 for Demo 3', '2025-01-05', '2025-02-09', 'Sunday', '17:00:00', '17:40:00', 'YMCA Onalaska Pool', 8, 24, 48, 96, 'Pike'),
('Shark', 'Shark swimming class 2 for Demo 3', '2025-01-05', '2025-02-09', 'Monday,Wednesday', '18:00:00', '18:40:00', 'YMCA Onalaska Pool', 8, 33, 65, 130, 'Pike'),
('Log Rolling', 'log rolling class 1 for Demo 3', '2025-01-05', '2025-02-09', 'Sunday', '17:00:00', '17:40:00', 'YMCA Onalaska Pool', 1, 50, 100, 200, NULL),
('Log Rolling', 'log rolling class 2 for Demo 3', '2025-01-05', '2025-02-09', 'Monday', '18:00:00', '18:40:00', 'YMCA Onalaska Pool', 2, 50, 100, 200, NULL);


INSERT INTO Classes (ClassName, ClassDescription, StartDate, EndDate, DayOfWeek, StartTime, EndTime, ClassLocation, MaxParticipants, PriceStaff, PriceMember, PriceNonMember, PrerequisiteClassName)
VALUES
('Pike', 'Pike swimming class 1', '2024-01-05', '2024-02-09', 'Sunday', '17:00:00', '17:40:00', 'YMCA Onalaska Pool', 8, 24, 48, 96, NULL),
('Pike', 'Pike swimming class 2', '2023-01-05', '2023-02-09', 'Monday', '14:00:00', '14:40:00', 'YMCA Onalaska Pool', 2, 50, 100, 200, NULL);


/*
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
*/

INSERT INTO People (FirstName, LastName, Email, PhoneNumber, Over18, IsParent, IsChild, PasswordHash, Role, PermissionID, MembershipPaid, HasMessage, MessageText) VALUES
('Mickey', 'Mouse', 'mm@email.com', '123-338-5530', TRUE, FALSE, FALSE, '$2b$12$wOrNWBXdM0queLjkRFBd.uhHb1kp9OwnXLMHG4oWe/t1WvZlcJawi', 'NonMember', 5, TRUE, FALSE, NULL),
('Donald', 'Duck', 'dd@email.com', '123-896-1023', TRUE, FALSE, FALSE, '$2b$12$CE7hDN6PjsRJS58kYUYHMey5i91JiC1VGb/VHDEiaZ6uL6ImzyAGO', 'Member', 4, TRUE, TRUE, 'Welcome to YMCA!'),
('Goofy', 'Goof', 'gg@email.com', '123-664-6256', TRUE, FALSE, FALSE, '$2b$12$.AUNcG0UE.tEyM3QC1hI9.UOEtppqBojheZ1KxeV6A9S5WDCLg9eK', 'Member', 4, TRUE, FALSE, NULL),
('Daisy', 'Duck', 'ddu@email.com', '123-669-2911', TRUE, FALSE, FALSE, '$2b$12$71zQEArPerK/PKHpsEXCCeDQ/ufV7wQz3HFcc3dcCpGfFQPHNjRVi', 'Member', 4, TRUE, FALSE, NULL),
('Pluto', 'Dog', 'pd@email.com', '123-626-9824', TRUE, FALSE, FALSE, '$2b$12$DdFA.T0Yd2Rko/nmonRzRO1UidrXp3dPKfFV8mCeL7aYl7Rc6Sn72', 'Member', 4, TRUE, FALSE, NULL),
('Simba', 'Lion', 'sl@email.com', '123-224-6301', TRUE, FALSE, FALSE, '$2b$12$6AF..hE0gIBKkajQF00o..HGJpgePYi1I96oPyitZhtiWezzvxgTe', 'Member', 4, TRUE, TRUE, 'Check out our family plans!'),
('Ariel', 'Mermaid', 'am@email.com', '123-913-2397', TRUE, FALSE, FALSE, '$2b$12$hZhzf91qcy.HWpFj7VipZO00AIe0.BcdOORoc7VSQ5x4ZYUWsW/Pa', 'Member', 4, TRUE, FALSE, NULL),
('Elsa', 'Queen', 'eq@email.com', '123-537-5920', TRUE, FALSE, FALSE, '$2b$12$P1dsDQTBxh0/jINVZ3NgxOZqrWFA0QiopI2ijPHfir/w6r0nTstly', 'Member', 4, TRUE, FALSE, NULL),
('Anna', 'Princess', 'ap@email.com', '123-816-4930', TRUE, FALSE, FALSE, '$2b$12$.OPdJrTs3tAgG83Ij0ABOuE9C9UlYcZcdVqVqexMgIUZnxaW6/Ese', 'Member', 4, TRUE, TRUE, 'See our upcoming events!'),
('Woody', 'Cowboy', 'wc@email.com', '123-483-2946', TRUE, FALSE, FALSE, '$2b$12$INQKaoluAsEeg/fAPC2Iq.1cJKXc9/eBZE67hOzC.CthpFAYfu6UW', 'Member', 4, TRUE, FALSE, NULL),
('Buzz', 'Lightyear', 'bl@email.com', '123-862-7085', TRUE, FALSE, FALSE, '$2b$12$GtL8WQxF/DeV8OkZPlcpE.2Ytw95hGWWVOjTbvr/9Mwigfrr14PQG', 'Member', 4, TRUE, FALSE, NULL),
('Lisa', 'Simpson', 'ls@email.com', '123-244-9612', TRUE, FALSE, FALSE, '$2b$12$uVwo5RY5osXn/o.ff6nK5.5Yjuc5k/6MHTSjM.M8fGVRhLGLCwIr.', 'NonMember', 5, FALSE, FALSE, NULL),
('Peter', 'Pan', 'pp@email.com', '123-840-4510', TRUE, FALSE, FALSE, '$2b$12$GMmmSpJ7U9qVE7vabcBLAuHQFg.nkgHysJqTiJTU4MOROpuBRzIbK', 'NonMember', 5, FALSE, TRUE, 'Welcome to YMCA!'),
('Tiana', 'Princess', 'tp@email.com', '123-194-8696', TRUE, FALSE, FALSE, '$2b$12$9r9HW2k6SC5pH9AlBCluFOKhh//XckvuNSdqv0MEhXB173wlXXlPq', 'NonMember', 5, FALSE, TRUE, 'Special offers for members!'),
('Aladdin', 'Prince', 'apr@email.com', '123-336-1502', TRUE, FALSE, FALSE, '$2b$12$PUN6FRI/cBYFRYAJLOTfeOqgLJDNHv0/.crXIyYGbNYLjMYEFU6Xq', 'Member', 4, TRUE, FALSE, NULL),
('Belle', 'Beauty', 'bb@email.com', '123-694-6024', TRUE, FALSE, FALSE, '$2b$12$EiE.e5JE1DNOoMIv49heJ.T9YAzYgDI/gNMdJcWmijlUbsz/POBRC', 'NonMember', 5, FALSE, FALSE, NULL),
('Jasmine', 'Princess', 'jp@email.com', '123-894-4903', TRUE, FALSE, FALSE, '$2b$12$1I.bud5kvyQ1Y9Uh5E9Vw.tgrbppamwdIBP1rgrX65EYR7Br5M9/u', 'NonMember', 5, FALSE, TRUE, 'Special offers for members!'),
('Mulan', 'Warrior', 'mw@email.com', '123-204-1455', TRUE, FALSE, FALSE, '$2b$12$8u2/tl1KTxaKfV6lqL9pteC9tSt5iQ./4VxzmdMTLiIn8sKZCocHi', 'NonMember', 5, FALSE, FALSE, NULL),
('Shrek', 'Ogre', 'so@email.com', '123-812-6217', TRUE, FALSE, FALSE, '$2b$12$RRftbkXHu67YzhaMEvGOvO9W6gsF1Cn1oCPEVwL.RPwjMiDmk2TES', 'NonMember', 5, FALSE, FALSE, NULL),
('Fiona', 'Princess', 'fp@email.com', '123-789-8763', TRUE, FALSE, FALSE, '$2b$12$Xd/Frb188LbVdHAGwBezm.3pBEUr7b7hiAURyX4lmArRjQZV8wqsC', 'NonMember', 5, FALSE, FALSE, NULL),
('Pooh', 'Bear', 'pb@email.com', '123-670-6826', TRUE, FALSE, FALSE, '$2b$12$yArpLoWU5DVtwHYJoomNK.vr4qr7tznFi6xTBjK5dvyuujYdo4iqe', 'NonMember', 5, FALSE, TRUE, 'Welcome to YMCA!');



-- Fix up the max participants and staff prices.
UPDATE Classes SET MaxParticipants = FLOOR(2 + RAND() * 10);
UPDATE Classes SET PriceStaff = CEIL(PriceMember / 2);


-- Populate Registrations for various classes ensuring prerequisites are met
-- Assume PersonIDs 1 to 25 are the test users created

INSERT INTO Registrations (PersonID, ClassID, RegistrationDate, PaymentAmount, PaymentStatus, isActive)
VALUES 
(1, 2, '2024-06-15', 20.00, 'Paid', TRUE),
(2, 3, '2024-12-05', 12.00, 'Paid', TRUE),
(3, 4, '2025-03-15', 18.00, 'Paid', TRUE),
(4, 5, '2025-04-01', 10.00, 'Paid', TRUE),
(5, 6, '2024-12-01', 15.00, 'Paid', TRUE),
(6, 7, '2024-07-10', 20.00, 'Paid', TRUE),
(7, 8, '2024-11-20', 10.00, 'Due', TRUE),
(8, 9, '2024-03-05', 10.00, 'Paid', TRUE),
(9, 10, '2024-08-01', 15.00, 'Paid', TRUE),
(10, 11, '2023-12-01', 8.00, 'Paid', TRUE),
(11, 12, '2024-02-10', 5.00, 'Paid', TRUE),
(12, 13, '2024-09-10', 12.00, 'Due', TRUE),
(13, 14, '2024-11-05', 10.00, 'Paid', TRUE),
(14, 15, '2024-08-15', 20.00, 'Paid', TRUE),
(15, 16, '2024-09-01', 12.00, 'Paid', TRUE),
(16, 17, '2025-05-20', 18.00, 'Due', TRUE),
(17, 18, '2024-10-05', 20.00, 'Paid', TRUE),
(18, 19, '2024-11-01', 30.00, 'Paid', TRUE),
(19, 20, '2024-12-01', 18.00, 'Due', TRUE),
(20, 21, '2024-11-15', 48.00, 'Paid', TRUE),
(21, 22, '2024-11-22', 96.00, 'Due', TRUE),
(2, 1, '2024-09-15', 15.00, 'Paid', TRUE),
(3, 3, '2024-11-25', 12.00, 'Paid', TRUE),
(4, 5, '2024-10-30', 10.00, 'Paid', TRUE),
(1, 7, '2024-09-20', 20.00, 'Paid', TRUE),
(2, 2, '2024-07-01', 20.00, 'Paid', TRUE),
(3, 6, '2024-05-10', 15.00, 'Paid', TRUE),
(4, 10, '2024-09-01', 18.00, 'Paid', TRUE),
(5, 8, '2024-08-01', 10.00, 'Paid', TRUE),
(6, 13, '2024-12-05', 12.00, 'Paid', TRUE),
(7, 9, '2024-10-05', 15.00, 'Paid', TRUE),
(8, 4, '2024-11-01', 18.00, 'Paid', TRUE),
(9, 16, '2024-06-10', 12.00, 'Paid', TRUE),
(10, 14, '2024-09-15', 20.00, 'Paid', TRUE),
(11, 12, '2024-07-10', 5.00, 'Paid', TRUE),
(12, 15, '2024-08-10', 18.00, 'Paid', TRUE),
(13, 17, '2024-07-20', 18.00, 'Paid', TRUE),
(14, 18, '2025-03-10', 30.00, 'Paid', TRUE),
(15, 19, '2024-10-01', 20.00, 'Paid', TRUE),
(16, 20, '2024-08-25', 30.00, 'Paid', TRUE),
(17, 21, '2024-12-10', 48.00, 'Due', TRUE),
(19, 5, '2024-06-20', 25.00, 'Paid', TRUE),
(20, 9, '2024-05-05', 15.00, 'Paid', TRUE),
(1, 8, '2024-09-15', 25.00, 'Paid', TRUE),
(2, 10, '2024-07-05', 15.00, 'Paid', TRUE),
(3, 11, '2024-12-01', 18.00, 'Paid', TRUE),
(2, 12, '2024-06-01', 5.00, 'Paid', TRUE);



UPDATE Classes 
SET CurrentParticipantCount = (
    SELECT COUNT(*) 
    FROM Registrations 
    WHERE Registrations.ClassID = Classes.ClassID
);



ALTER TABLE people ADD COLUMN isActive BOOLEAN DEFAULT TRUE;
UPDATE People SET IsActive = TRUE;

ALTER TABLE registrations ADD COLUMN isActive BOOLEAN DEFAULT TRUE;
UPDATE registrations SET IsActive = TRUE;


insert into registrations (PersonID, ClassID, RegistrationDate, PaymentAmount, PaymentStatus, IsActive)
values (22, 32, '2024-10-11', 48, 'Paid', TRUE);
insert into registrations (PersonID, ClassID, RegistrationDate, PaymentAmount, PaymentStatus, IsActive)
values (24, 32, '2024-10-11', 48, 'Paid', TRUE);