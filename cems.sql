-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2025 at 04:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cems`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(4, 'akhilesh Chaubey', 'akhileshchaubey.230411222@gehu.ac.in', 'kjdsghkjghkjdfg', '2025-05-02 16:37:24'),
(5, 'deepak mehta', 'akhileshchaubey.230411222@gehu.ac.in', 'hi name is Anuj Kumar', '2025-05-03 16:13:39');

-- --------------------------------------------------------

--
-- Table structure for table `cultural_events`
--

CREATE TABLE `cultural_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `event_name` varchar(100) DEFAULT NULL,
  `event_type` varchar(50) DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT 0,
  `amount` int(11) DEFAULT 0,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cultural_events`
--

INSERT INTO `cultural_events` (`id`, `title`, `category`, `event_date`, `image_path`, `event_name`, `event_type`, `is_paid`, `amount`, `description`) VALUES
(1, '', 'Holi', '2026-03-15', 'uploads/holi.jpeg', 'Holi', 'Cultural', 0, 0, 'In MPH \r\nMinimum 1 Maximum 4'),
(2, '', 'Diwali\r\n', '2025-11-09', 'uploads/diwali.jpeg', 'diwali', 'Cultural', 0, 0, 'In MPH \r\nMinimum 1 Maximum 4'),
(3, '', 'Freshers', '2025-08-08', 'uploads/Freshers(Dance).jpeg', 'Freshers(Dance)', 'Cultural', 0, 0, 'In MPH \r\nMinimum 1 Maximum 4'),
(4, '', 'Freshers', '2025-08-08', 'uploads/Freshers(Singing).jpeg', 'Freshers(Singing)', 'Cultural', 0, 0, 'In MPH \r\nMinimum 1 Maximum 4'),
(5, '', 'Freshers', '2025-08-08', 'uploads/Freshers(Act-play).jpeg', 'Freshers(Act-play)', 'Cultural', 0, 0, 'In MPH \r\nMinimum 1 Maximum 4'),
(7, '', 'Diwali', '2025-11-01', 'uploads/Diwali(Dance).jpeg', 'Diwali(Dance)', 'Cultural', 0, 0, 'In MPH \r\nMinimum 1 Maximum 4'),
(8, '', 'Diwali', '2025-11-09', 'uploads/Diwali(Singing).jpeg', 'Diwali(Singing)', 'Cultural', 0, 0, 'In MPH \r\nMinimum 1 Maximum 4'),
(9, '', 'Diwali', '2025-11-09', 'uploads/Diwali(Act-play).jpeg', 'Diwali(Act-play)', 'Cultural', 0, 0, 'In MPH \r\nMinimum 1 Maximum 4'),
(10, '', 'Holi', '2025-03-09', 'uploads/Holi(Dance).jpeg', 'Holi(Dance)', 'Cultural', 0, 0, 'In MPH \r\nMinimum 1 Maximum 4'),
(11, '', 'Holi', '2025-03-09', 'uploads/Holi(Singing).jpeg', 'Holi(Singing)', 'Cultural', 0, 0, 'In MPH \r\nMinimum 1 Maximum 4'),
(12, '', 'Holi', '2025-03-09', 'uploads/Holi(Act-play).jpeg', 'Holi(Act-play)', 'Cultural', 0, 0, 'In MPH \r\nMinimum 1 Maximum 4'),
(15, 'dancing', '', '2025-09-18', 'uploads/fest.jpeg', 'fest', 'Cultural', 0, 200, 'In MPH \r\nMinimum 1 Maximum 4');

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `event_type` enum('Tech','Cultural','Sport') NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `event_name` varchar(255) NOT NULL,
  `payment_screenshot` varchar(255) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT 'not_required',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_registrations`
--

INSERT INTO `event_registrations` (`id`, `event_id`, `event_type`, `name`, `email`, `phone`, `registered_at`, `event_name`, `payment_screenshot`, `payment_status`, `amount`) VALUES
(41, 8, 'Cultural', 'akhilesh Chaubey', 'ankursinghmehta23041567@gehu.ac.in', '9759790023', '2025-05-10 16:18:04', 'Diwali(Singing)', NULL, 'not_required', 0.00),
(42, 3, 'Sport', 'akhilesh Chaubey', 'ankursinghmehta23041567@gehu.ac.in', '9759790023', '2025-05-11 06:15:00', 'Volleyball', 'payment_screenshots/1746944113_fest.jpeg', 'Paid', 100.00),
(43, 1, 'Tech', 'akhilesh Chaubey', 'akhileshchaubey.230411222@gehu.ac.in', '9759790023', '2025-05-11 09:44:13', 'Takken', 'payment_screenshots/1746956668_fest.jpeg', 'Paid', 40.00),
(44, 4, 'Cultural', 'akhilesh Chaubey', 'ankursinghmehta23041567@gehu.ac.in', '9759790023', '2025-05-21 15:55:21', 'Freshers(Singing)', NULL, 'not_required', 0.00),
(45, 3, 'Cultural', 'akhilesh Chaubey', 'akhileshchaubey.230411222@gehu.ac.in', '9759790023', '2025-05-21 15:59:58', 'Freshers(Dance)', NULL, 'not_required', 0.00),
(46, 5, 'Tech', 'akhilesh Chaubey', 'akhileshchaubey.230411222@gehu.ac.in', '9759790023', '2025-06-20 02:10:51', 'Hackathon', 'payment_screenshots/1750385462_image.webp', 'Pending Approval', 40.00);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comments` text DEFAULT NULL,
  `feedback_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `event_id`, `rating`, `comments`, `feedback_date`) VALUES
(1, 2, 1, 5, 'Amazing hackathon event!', '2025-04-08 16:35:38'),
(2, 2, 2, 4, 'Great cultural night!', '2025-04-08 16:35:38'),
(17, 3, NULL, NULL, 'Akhilesh is the best in the world', '2025-04-25 17:56:14');

-- --------------------------------------------------------

--
-- Table structure for table `registration_members`
--

CREATE TABLE `registration_members` (
  `id` int(11) NOT NULL,
  `registration_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration_members`
--

INSERT INTO `registration_members` (`id`, `registration_id`, `name`, `email`, `phone`) VALUES
(9, 41, 'akhilesh Chaubey', 'ankursinghmehta23041567@gehu.ac.in', '9759790023'),
(10, 42, 'akhilesh Chaubey', 'ankursinghmehta23041567@gehu.ac.in', '9759790023'),
(11, 43, 'akhilesh Chaubey', 'akhileshchaubey.230411222@gehu.ac.in', '9759790023'),
(12, 44, 'akhilesh Chaubey', 'ankursinghmehta23041567@gehu.ac.in', '9759790023'),
(13, 45, 'akhilesh Chaubey', 'akhileshchaubey.230411222@gehu.ac.in', '9759790023'),
(14, 46, 'akhilesh Chaubey', 'akhileshchaubey.230411222@gehu.ac.in', '9759790023');

-- --------------------------------------------------------

--
-- Table structure for table `sport_events`
--

CREATE TABLE `sport_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `is_paid` tinyint(1) DEFAULT 0,
  `amount` int(11) DEFAULT 0,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sport_events`
--

INSERT INTO `sport_events` (`id`, `title`, `category`, `event_date`, `image_path`, `event_name`, `event_type`, `is_paid`, `amount`, `description`) VALUES
(1, '', '', '2025-07-30', 'uploads/cricket.jpg', 'Cricket', 'Sports', 1, 100, 'Players: 11/11\r\nVenue: cricket ground\r\nEntry Fee: Rs300\r\nTiming:11:00 to 2:30\r\n'),
(2, '', '\r\n\r\n', '2025-07-09', 'uploads/Basketball.webp', 'Basketball', 'Sports', 1, 100, 'Players: 10/10\r\nVenue: Basketball ground\r\nEntry Fee: Rs 300\r\nTiming: 11:00 to 12:30\r\n'),
(3, '', '\r\n\r\n', '2025-07-24', 'uploads/Volleyball.jpg', 'Volleyball', 'Sports', 1, 100, 'Players: 6/6\r\nVenue: volleyball ground\r\nEntry Fee: Rs 300\r\nTiming: 11:00 to 12:30'),
(4, '', '\r\n\r\n', '2025-07-20', 'uploads/Tressure Hunt.jpg', 'Tressure Hunt', 'Sports', 1, 100, 'Players: 4/4\r\nVenue: Poach area\r\nEntry Fee: Rs 100\r\nTiming: 11:00 to 12:30'),
(5, '', '\r\n\r\n', '2025-07-15', 'uploads/riddle.jpg', 'Riddle', 'Sports', 1, 100, 'Players: 4/4\r\nVenue: Lab\r\nEntry Fee: Rs 300\r\nTiming: 11:00 to 12:30'),
(6, '', '\r\n\r\n', '2025-07-19', 'uploads/badminton.jpg', 'Badminton', 'Sports', 1, 100, 'Players: 1/1\r\nVenue: Badminton coart\r\nEntry Fee: Rs 100\r\nTiming: 11:00 to 12:30');

-- --------------------------------------------------------

--
-- Table structure for table `tech_events`
--

CREATE TABLE `tech_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `is_paid` tinyint(1) DEFAULT 0,
  `amount` int(11) DEFAULT 0,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tech_events`
--

INSERT INTO `tech_events` (`id`, `title`, `category`, `event_date`, `image_path`, `event_name`, `event_type`, `is_paid`, `amount`, `description`) VALUES
(1, '\r\n', 'Takken', '2025-05-25', 'uploads/takken.png', 'Takken', 'Tech', 1, 40, 'Players: 1/1\r\nVenue: Lab\r\nEntry Fee: Rs40\r\nTiming: 9-10am'),
(2, '\r\n', 'BGMI', '2025-05-25', 'uploads/BGMI.png', 'BGMI', 'Tech', 1, 40, 'Players: 4/4\r\nVenue: Lab\r\nEntry Fee: Rs40\r\nTiming: 9-10am'),
(3, '\r\n', 'Free fire', '2025-05-25', 'uploads/freefire.jpg', 'Free Fire', 'Tech', 1, 40, 'Players: 4/4\r\nVenue: Lab\r\nEntry Fee: Rs40\r\nTiming: 9-10am'),
(4, '\r\n', 'Coding', '2025-05-25', 'uploads/coding.jpg', 'Coding', 'Tech', 1, 40, 'Players: 4/4\r\nVenue: Lab\r\nEntry Fee: Rs40\r\nTiming: 9-10am'),
(5, '\r\n', 'Hackathon', '2025-05-25', 'uploads/Hackathon.jpg', 'Hackathon', 'Tech', 1, 40, 'Players: 4/4\r\nVenue: Lab\r\nEntry Fee: Rs40\r\nTiming: 24x7\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `profile_image`, `user_id`) VALUES
(1, 'Akhilesh Chaubey', 'akhilesh@gehu.ac.in', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'admin', '2025-04-08 16:28:21', NULL, 0),
(2, 'John Doe', 'john@gehu.ac.in', 'Johngehu@123', 'student', '2025-04-08 16:28:21', NULL, 0),
(3, 'akhilesh Chaubey', 'akhileshchaubey.230411222@gehu.ac.in', '$2y$10$uEYVJECCR2DHvsKNXo65Z.ma3MLDRSRo4YHsJWR7yKugW9z2mRqUe', 'admin', '2025-04-08 16:53:26', '1745603922_IMG-20241113-WA0003.jpg', 0),
(4, '', 'pankajairi.22446688@gehu.ac.in', '$2y$10$GkbWtONlz98R336biOH4rOgU.CZPj1gO/fARdW4RixZsjBn4qpEay', 'student', '2025-04-18 06:14:38', 'image.webp', 0),
(6, 'manoj mehta', 'manojmehta22446688@gehu.ac.in', '$2y$10$n/kBvK.D2wNw6efFeyhTGu5vuXpTVFUqfcZHBdHKfCIUfmZ6Yz7QW', 'student', '2025-04-18 06:21:35', 'image.webp', 0),
(7, 'Ankur Singh Mehta', 'ankursinghmehta23041567@gehu.ac.in', '$2y$10$oaGU4tLQcT/4QgpXOmZ7G.bx6NydWiWpBytAUYfVH/uFkuOriW.UC', 'student', '2025-05-03 11:55:21', '1747843079_1746287451_profile_photo.jpg', 0),
(9, 'Harshit bhatt', 'harshitbhatt.230411360@gehu.ac.in', '$2y$10$nNBjLpA0FC9wUl.FlNhqt.NSggC94Py2CFRFSW/llDRHCWa.6s4yy', 'student', '2025-05-26 11:22:53', 'fest.jpeg', 0),
(10, 'deepak', 'deepak@gehu.ac.in', '$2y$10$Wa9dVS9v3d9ilQSe2TOFCuDxLZKjgPv.KOO7MVEWuET5hhlVvMQam', 'student', '2025-06-20 01:53:22', 'image.webp', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cultural_events`
--
ALTER TABLE `cultural_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `registration_members`
--
ALTER TABLE `registration_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registration_id` (`registration_id`);

--
-- Indexes for table `sport_events`
--
ALTER TABLE `sport_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tech_events`
--
ALTER TABLE `tech_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cultural_events`
--
ALTER TABLE `cultural_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `registration_members`
--
ALTER TABLE `registration_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sport_events`
--
ALTER TABLE `sport_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tech_events`
--
ALTER TABLE `tech_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `registration_members`
--
ALTER TABLE `registration_members`
  ADD CONSTRAINT `registration_members_ibfk_1` FOREIGN KEY (`registration_id`) REFERENCES `event_registrations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
