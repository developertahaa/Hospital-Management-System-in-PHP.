-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 12:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `AppointmentID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `DoctorID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Fee` double NOT NULL,
  `Status` enum('Missed','Cancelled','Completed','Pending') NOT NULL,
  `timeslot` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`AppointmentID`, `PatientID`, `DoctorID`, `Date`, `Fee`, `Status`, `timeslot`) VALUES
(1, 1, 1, '2024-11-28', 500, 'Pending', '09:30 - 10:00'),
(2, 2, 2, '2024-11-29', 1000, 'Pending', '09:30 - 10:00'),
(3, 3, 3, '2024-11-29', 500, 'Cancelled', '09:30 - 10:00'),
(13, 21, 1, '2024-12-01', 500, 'Pending', '09:30 - 10:00'),
(14, 22, 3, '2024-12-02', 12000, 'Pending', '11:30 - 12:00');

-- --------------------------------------------------------

--
-- Table structure for table `bed`
--

CREATE TABLE `bed` (
  `BedID` int(11) NOT NULL,
  `RoomID` int(11) NOT NULL,
  `Availability` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bed`
--

INSERT INTO `bed` (`BedID`, `RoomID`, `Availability`) VALUES
(1, 1, 0),
(2, 2, 0),
(3, 3, 0),
(4, 4, 0),
(5, 5, 0),
(6, 1, 0),
(7, 4, 1),
(8, 6, 1),
(9, 7, 1),
(10, 1, 1),
(11, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_labtest`
--

CREATE TABLE `book_labtest` (
  `LabTestID` int(11) NOT NULL,
  `TestID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `Fees` decimal(10,2) NOT NULL,
  `Result` varchar(255) DEFAULT 'Pending',
  `Date_Time` datetime NOT NULL,
  `BedID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_labtest`
--

INSERT INTO `book_labtest` (`LabTestID`, `TestID`, `PatientID`, `Fees`, `Result`, `Date_Time`, `BedID`) VALUES
(1, 2, 13, 200.00, 'Pending', '2024-11-30 16:20:00', '5'),
(2, 3, 14, 500.00, 'Pending', '2024-11-04 15:40:00', '5'),
(3, 3, 15, 500.00, 'Pending', '2024-11-30 16:45:00', '5');

-- --------------------------------------------------------

--
-- Table structure for table `dietaryneeds`
--

CREATE TABLE `dietaryneeds` (
  `DN_ID` int(11) NOT NULL,
  `PlanID` int(11) NOT NULL,
  `DietaryNeed` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dietaryneeds`
--

INSERT INTO `dietaryneeds` (`DN_ID`, `PlanID`, `DietaryNeed`) VALUES
(1, 1, 'No salt'),
(2, 2, 'No sugar'),
(3, 3, 'Controlled sugar'),
(4, 4, 'No wheat'),
(5, 5, 'Extra protein');

-- --------------------------------------------------------

--
-- Table structure for table `dietaryplan`
--

CREATE TABLE `dietaryplan` (
  `PlanID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `MealPlan` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dietaryplan`
--

INSERT INTO `dietaryplan` (`PlanID`, `PatientID`, `MealPlan`) VALUES
(1, 1, 'Low sodium'),
(2, 2, 'Low carb'),
(3, 3, 'Diabetic'),
(4, 4, 'Gluten-free'),
(5, 5, 'High protein');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `DoctorID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(25) NOT NULL,
  `LastName` varchar(25) NOT NULL,
  `Specialization` varchar(50) NOT NULL,
  `fees` int(11) NOT NULL,
  `timestart` time NOT NULL,
  `time_end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`DoctorID`, `UserID`, `FirstName`, `LastName`, `Specialization`, `fees`, `timestart`, `time_end`) VALUES
(1, 4, 'Alice', 'Smith', 'Cardiology', 500, '09:00:00', '12:00:00'),
(2, 5, 'Bob', 'Johnson', 'Orthopedics', 1000, '10:00:00', '12:00:00'),
(3, 27, 'Sami', 'Faisal', 'gynecologist', 12000, '11:00:00', '14:00:00'),
(4, 28, 'tahir', 'tahir', 'gynecologist', 10000, '10:00:00', '12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `ItemID` int(11) NOT NULL,
  `ItemName` varchar(50) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `ReOrderLevel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`ItemID`, `ItemName`, `Quantity`, `ReOrderLevel`) VALUES
(1, 'Syringes', 20, 10),
(2, 'Gloves', 100, 20),
(3, 'Masks', 200, 30),
(4, 'Diapers', 100, 40);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `InvoiceID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `AppointmentID` int(11) NOT NULL,
  `TreatmentID` int(11) NOT NULL,
  `RoomID` int(11) NOT NULL,
  `Amount` double NOT NULL,
  `Status` enum('Paid','Unpaid') NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`InvoiceID`, `PatientID`, `AppointmentID`, `TreatmentID`, `RoomID`, `Amount`, `Status`, `CreatedAt`) VALUES
(1, 1, 1, 1, 1, 1000, 'Paid', '2024-11-27 23:24:23'),
(2, 2, 2, 2, 2, 1500, 'Unpaid', '2024-11-27 23:24:23'),
(3, 3, 3, 3, 3, 2000, 'Paid', '2024-11-27 23:24:23');

-- --------------------------------------------------------

--
-- Table structure for table `invoicetest`
--

CREATE TABLE `invoicetest` (
  `InvoiceID` int(11) NOT NULL,
  `TestID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoicetest`
--

INSERT INTO `invoicetest` (`InvoiceID`, `TestID`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `labtest`
--

CREATE TABLE `labtest` (
  `TestID` int(11) NOT NULL,
  `TestName` varchar(25) NOT NULL,
  `Fee` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labtest`
--

INSERT INTO `labtest` (`TestID`, `TestName`, `Fee`) VALUES
(1, 'Blood Test', 100),
(2, 'X-Ray', 2000),
(3, 'MRI', 500),
(5, 'xyz', 12000);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `NotificationID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `NotificationType` varchar(25) NOT NULL,
  `Message` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`NotificationID`, `UserID`, `NotificationType`, `Message`) VALUES
(1, 6, 'Appointment', 'Your appointment is scheduled for tomorrow'),
(2, 7, 'Lab Test', 'Your lab results are ready'),
(3, 8, 'Treatment', 'Your treatment plan has been updated');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `PatientID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `BedID` int(11) NOT NULL,
  `FirstName` varchar(25) NOT NULL,
  `LastName` varchar(25) NOT NULL,
  `MedicalHistory` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`PatientID`, `UserID`, `BedID`, `FirstName`, `LastName`, `MedicalHistory`) VALUES
(1, 6, 1, 'Charlie', 'Brown', 'Hypertension'),
(2, 7, 2, 'Daisy', 'Miller', 'Asthma'),
(3, 8, 3, 'Edward', 'Taylor', 'Diabetes'),
(4, 9, 4, 'Fiona', 'Green', 'Allergy'),
(5, 10, 5, 'George', 'White', 'Arthritis'),
(10, 11, 4, 'Taha', 'Farooqui', 'N/a'),
(13, 11, 5, 'Taha', 'Farooqui', ''),
(14, 11, 5, 'Muhammad Taha', 'Farooqui', ''),
(15, 11, 5, 'Muhammad Taha', 'Farooqui', ''),
(21, 11, 5, 'Taha ', 'Farooqui', 'no'),
(22, 11, 6, 'Ahzam ', 'Waheed', 'bawaseer.');

-- --------------------------------------------------------

--
-- Table structure for table `patientallergy`
--

CREATE TABLE `patientallergy` (
  `PatientID` int(11) NOT NULL,
  `Allergy` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patientallergy`
--

INSERT INTO `patientallergy` (`PatientID`, `Allergy`) VALUES
(1, 'Peanuts'),
(2, 'Dust'),
(3, 'Pollen');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `RoomID` int(11) NOT NULL,
  `Capacity` int(11) NOT NULL,
  `Charges` double NOT NULL,
  `Status` enum('Occupied','Available','Under Maintainance') NOT NULL,
  `room_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`RoomID`, `Capacity`, `Charges`, `Status`, `room_type_id`) VALUES
(1, 2, 500, 'Available', 1),
(2, 2, 600, 'Occupied', 2),
(3, 1, 800, 'Available', 3),
(4, 1, 1000, 'Available', 4),
(5, 1, 1200, 'Occupied', 5),
(6, 5, 10000, 'Available', 4),
(7, 1, 12000, '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `roomtype`
--

CREATE TABLE `roomtype` (
  `RoomID` int(11) NOT NULL,
  `RoomType` enum('General','Semi-Private','Private','VIP','ICU','NICU','Maternity','Isolation') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomtype`
--

INSERT INTO `roomtype` (`RoomID`, `RoomType`) VALUES
(1, 'General'),
(2, 'Private'),
(3, 'ICU'),
(4, 'VIP'),
(5, 'Semi-Private');

-- --------------------------------------------------------

--
-- Table structure for table `treatment`
--

CREATE TABLE `treatment` (
  `TreatmentID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `DoctorID` int(11) NOT NULL,
  `Description` varchar(256) NOT NULL,
  `Fee` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treatment`
--

INSERT INTO `treatment` (`TreatmentID`, `PatientID`, `DoctorID`, `Description`, `Fee`) VALUES
(1, 1, 1, 'Blood pressure control', 200),
(2, 2, 2, 'Fracture treatment', 500),
(3, 3, 1, 'Diabetic treatment', 300);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(128) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Role` enum('Admin','Doctor','Patient') NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserName`, `Password`, `Email`, `Role`, `CreatedAt`) VALUES
(1, 'admin1', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'admin1@example.com', 'Admin', '2024-11-27 23:16:55'),
(2, 'admin2', '52bc47f80b6fe698e9a14327e1bb7ddfa39a21740c8c7017329f3ec555a3bcf6', 'admin2@example.com', 'Admin', '2024-11-27 23:16:55'),
(3, 'admin3', '0ca7539a8577dd196641e11315f8fc7d1dba9cc2741752642def9bcdb3599467', 'admin3@example.com', 'Admin', '2024-11-27 23:16:55'),
(4, 'doctor1', 'a68806ac4f26d29f4d2c07ddfeae94084d2b053bcdfc2b49b1a29407dc3025d4', 'doctor1@example.com', 'Doctor', '2024-11-27 23:16:55'),
(5, 'doctor2', '61816275a0b69bf6368c4606756ca041a7755ecb9fe6f7b653f6143f382f327f', 'doctor2@example.com', 'Doctor', '2024-11-27 23:16:55'),
(6, 'patient1', '3d84d51910b5948ed69028d6fb44e98211a6ffdeeddee0737ff9ec8fb160f4c0', 'patient1@example.com', 'Patient', '2024-11-27 23:16:55'),
(7, 'patient2', '89e90034ee7356ef69ad62f553417152a0215c371c3758d807fb923103b48cad', 'patient2@example.com', 'Patient', '2024-11-27 23:16:55'),
(8, 'patient3', '4254d0a295a8ccf2541a04901cba3e081f59dc9d6b4c99ef645695a1db8a5907', 'patient3@example.com', 'Patient', '2024-11-27 23:16:55'),
(9, 'patient4', '900d349333fd0fddbdb864e845ed00f7e56a6fc6bbc030b94b0b6b3ed9039ecc', 'patient4@example.com', 'Patient', '2024-11-27 23:16:55'),
(10, 'patient5', 'f90106a42bdbd29233e4599391ef53aceccc68148e19f04eacf619c6649849a9', 'patient5@example.com', 'Patient', '2024-11-27 23:16:55'),
(11, 'taha', '$2y$10$5ZjVZ5wc12PUp4mHDfvgTOjmwI3/OHdqFmm2vGziDXh2/Segb8imK', 'mohdtaha9901@gmail.com', 'Patient', '2024-11-29 10:36:25'),
(12, 'admin', '$2y$10$qjjQcGQ7PCivibm0pBRhluBg93LT/klZt3yxNEvO6gxargpQODjFa', 'admin@hms.com', 'Admin', '2024-11-29 16:10:14'),
(15, 'ahmed', '$2y$10$xIBOXLPZH8BG3C89Phyk7eXnZbhTcXlFD.reXNUhLd6KQCRBSEpYi', 'ahmed@gmail.com', 'Patient', '2024-11-30 08:34:09'),
(27, 'sami', '$2y$10$lhW75rdRdvepT4x93nQ7ue1kkYa2vDmQBXlN..0YCbIP2QJRlANnC', 'sami@gmail.com', 'Doctor', '2024-11-30 10:58:54'),
(28, 'tahir', '$2y$10$s.TLwc4B0q3AwVOOi23ceOhBISfLrbXbq7fHqefbcNCLIujwuzBOK', 'tahir@gmail.com', 'Doctor', '2024-11-30 12:14:25');

-- --------------------------------------------------------

--
-- Table structure for table `userphone`
--

CREATE TABLE `userphone` (
  `UserID` int(11) NOT NULL,
  `Phone_Number` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userphone`
--

INSERT INTO `userphone` (`UserID`, `Phone_Number`) VALUES
(1, '5551112222'),
(2, '5553334444'),
(3, '5555556666'),
(4, '5557778888'),
(5, '5559990000'),
(6, '1234567890'),
(7, '9876543210'),
(8, '1122334455'),
(9, '9988776655'),
(10, '8877665544');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`AppointmentID`),
  ADD KEY `appointment_FK_1` (`PatientID`),
  ADD KEY `appointment_FK_2` (`DoctorID`);

--
-- Indexes for table `bed`
--
ALTER TABLE `bed`
  ADD PRIMARY KEY (`BedID`),
  ADD KEY `bed_FK_1` (`RoomID`);

--
-- Indexes for table `book_labtest`
--
ALTER TABLE `book_labtest`
  ADD PRIMARY KEY (`LabTestID`),
  ADD KEY `TestID` (`TestID`),
  ADD KEY `book_labtest_ibfk_1` (`PatientID`);

--
-- Indexes for table `dietaryneeds`
--
ALTER TABLE `dietaryneeds`
  ADD PRIMARY KEY (`DN_ID`),
  ADD KEY `DietaryNeeds_FK_1` (`PlanID`);

--
-- Indexes for table `dietaryplan`
--
ALTER TABLE `dietaryplan`
  ADD PRIMARY KEY (`PlanID`),
  ADD KEY `dietaryplan_FK_1` (`PatientID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`DoctorID`),
  ADD KEY `doctor_FK_1` (`UserID`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`ItemID`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`InvoiceID`),
  ADD KEY `invoice_FK_1` (`PatientID`),
  ADD KEY `invoice_FK_2` (`AppointmentID`),
  ADD KEY `invoice_FK_3` (`TreatmentID`),
  ADD KEY `invoice_FK_4` (`RoomID`);

--
-- Indexes for table `invoicetest`
--
ALTER TABLE `invoicetest`
  ADD KEY `invoicetest_FK_1` (`InvoiceID`),
  ADD KEY `invoicetest_FK_2` (`TestID`);

--
-- Indexes for table `labtest`
--
ALTER TABLE `labtest`
  ADD PRIMARY KEY (`TestID`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`NotificationID`),
  ADD KEY `notification_FK_1` (`UserID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PatientID`),
  ADD KEY `patient_FK_1` (`UserID`),
  ADD KEY `patient_FK_2` (`BedID`);

--
-- Indexes for table `patientallergy`
--
ALTER TABLE `patientallergy`
  ADD KEY `patientallergy_FK_1` (`PatientID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`RoomID`);

--
-- Indexes for table `roomtype`
--
ALTER TABLE `roomtype`
  ADD KEY `RoomType_FK_1` (`RoomID`);

--
-- Indexes for table `treatment`
--
ALTER TABLE `treatment`
  ADD PRIMARY KEY (`TreatmentID`),
  ADD KEY `treatment_FK_1` (`PatientID`),
  ADD KEY `treatment_FK_2` (`DoctorID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `unique_username` (`UserName`),
  ADD UNIQUE KEY `unique_email` (`Email`);

--
-- Indexes for table `userphone`
--
ALTER TABLE `userphone`
  ADD UNIQUE KEY `unique_phonenumber` (`Phone_Number`),
  ADD KEY `userphone_FK_1` (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `AppointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `bed`
--
ALTER TABLE `bed`
  MODIFY `BedID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `book_labtest`
--
ALTER TABLE `book_labtest`
  MODIFY `LabTestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dietaryneeds`
--
ALTER TABLE `dietaryneeds`
  MODIFY `DN_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dietaryplan`
--
ALTER TABLE `dietaryplan`
  MODIFY `PlanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `DoctorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `InvoiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `labtest`
--
ALTER TABLE `labtest`
  MODIFY `TestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `NotificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `PatientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `RoomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `treatment`
--
ALTER TABLE `treatment`
  MODIFY `TreatmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_FK_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`),
  ADD CONSTRAINT `appointment_FK_2` FOREIGN KEY (`DoctorID`) REFERENCES `doctor` (`DoctorID`);

--
-- Constraints for table `bed`
--
ALTER TABLE `bed`
  ADD CONSTRAINT `bed_FK_1` FOREIGN KEY (`RoomID`) REFERENCES `room` (`RoomID`);

--
-- Constraints for table `book_labtest`
--
ALTER TABLE `book_labtest`
  ADD CONSTRAINT `book_labtest_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`),
  ADD CONSTRAINT `book_labtest_ibfk_2` FOREIGN KEY (`TestID`) REFERENCES `labtest` (`TestID`);

--
-- Constraints for table `dietaryneeds`
--
ALTER TABLE `dietaryneeds`
  ADD CONSTRAINT `DietaryNeeds_FK_1` FOREIGN KEY (`PlanID`) REFERENCES `dietaryplan` (`PlanID`);

--
-- Constraints for table `dietaryplan`
--
ALTER TABLE `dietaryplan`
  ADD CONSTRAINT `dietaryplan_FK_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`);

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_FK_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_FK_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`),
  ADD CONSTRAINT `invoice_FK_2` FOREIGN KEY (`AppointmentID`) REFERENCES `appointment` (`AppointmentID`),
  ADD CONSTRAINT `invoice_FK_3` FOREIGN KEY (`TreatmentID`) REFERENCES `treatment` (`TreatmentID`),
  ADD CONSTRAINT `invoice_FK_4` FOREIGN KEY (`RoomID`) REFERENCES `room` (`RoomID`);

--
-- Constraints for table `invoicetest`
--
ALTER TABLE `invoicetest`
  ADD CONSTRAINT `invoicetest_FK_1` FOREIGN KEY (`InvoiceID`) REFERENCES `invoice` (`InvoiceID`),
  ADD CONSTRAINT `invoicetest_FK_2` FOREIGN KEY (`TestID`) REFERENCES `labtest` (`TestID`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_FK_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_FK_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `patient_FK_2` FOREIGN KEY (`BedID`) REFERENCES `bed` (`BedID`);

--
-- Constraints for table `patientallergy`
--
ALTER TABLE `patientallergy`
  ADD CONSTRAINT `patientallergy_FK_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`);

--
-- Constraints for table `roomtype`
--
ALTER TABLE `roomtype`
  ADD CONSTRAINT `RoomType_FK_1` FOREIGN KEY (`RoomID`) REFERENCES `room` (`RoomID`);

--
-- Constraints for table `treatment`
--
ALTER TABLE `treatment`
  ADD CONSTRAINT `treatment_FK_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`),
  ADD CONSTRAINT `treatment_FK_2` FOREIGN KEY (`DoctorID`) REFERENCES `doctor` (`DoctorID`);

--
-- Constraints for table `userphone`
--
ALTER TABLE `userphone`
  ADD CONSTRAINT `userphone_FK_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
