-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2025 at 12:23 PM
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
-- Database: `chat_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `groupadmin` int(11) NOT NULL,
  `groupic` text DEFAULT NULL,
  `description` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `groupadmin`, `groupic`, `description`, `timestamp`) VALUES
(14, 'katseye fanclub ❤️', 2, '../uploads/groups/682ad74562d83_katseye.jpg', 'let em know!!', '2025-05-20 08:50:38'),
(15, 'JYPPP⛓️🖤⛓️', 6, '../uploads/groups/682c4ca413e2e_jypp.jpg', 'WHO HATES HIM , well i dont', '2025-05-20 09:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `member_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`member_id`, `user_id`, `group_id`, `status`) VALUES
(5, 2, 14, 'Added'),
(8, 6, 14, 'Added'),
(9, 6, 15, 'Added');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `group_id`, `user_id`, `message`, `sent_at`) VALUES
(1, 14, 2, 'hey?', '2025-05-22 09:07:29'),
(2, 14, 2, 'in', '2025-05-22 09:19:27'),
(3, 14, 2, 'hey?', '2025-05-22 09:20:12'),
(4, 14, 6, 'oyy!!', '2025-05-22 10:04:46'),
(5, 14, 6, 'Hows Gnarly?????', '2025-05-22 10:07:09'),
(6, 14, 2, 'Its great!!', '2025-05-22 10:20:01'),
(7, 14, 2, 'no, its grnarly!!', '2025-05-22 10:20:54'),
(8, 14, 2, 'Gnarly!', '2025-05-22 10:22:22');

-- --------------------------------------------------------

--
-- Table structure for table `private_chat_messages`
--

CREATE TABLE `private_chat_messages` (
  `pr_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `content` text NOT NULL,
  `message_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `private_chat_messages`
--

INSERT INTO `private_chat_messages` (`pr_id`, `to_user_id`, `from_user_id`, `timestamp`, `content`, `message_status`) VALUES
(45, 2, 6, '2025-05-26 07:00:10', 'rhea??', 1),
(46, 6, 2, '2025-05-26 07:50:03', 'diarheaa....', 1),
(47, 6, 2, '2025-05-26 07:50:03', 'hehe', 1),
(48, 6, 2, '2025-05-26 07:50:13', 'im not rhea!!', 1),
(49, 2, 6, '2025-05-26 07:51:38', 'really??', 1),
(50, 6, 2, '2025-05-26 07:58:43', 'yea, and im not telling you...hehe', 0),
(51, 6, 2, '2025-05-26 08:04:41', 'is this micheal? from senior class?', 0),
(52, 6, 2, '2025-05-26 08:04:58', 'well, let me tell u, i know rhea tho', 0);

-- --------------------------------------------------------

--
-- Table structure for table `report_issue`
--

CREATE TABLE `report_issue` (
  `issue_id` int(11) NOT NULL,
  `issue_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_issue`
--

INSERT INTO `report_issue` (`issue_id`, `issue_name`) VALUES
(1, 'Inappropriate Language'),
(2, 'Sexual Abuse');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_pass` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `admin_name`, `admin_pass`) VALUES
(1, 'admin@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reports`
--

CREATE TABLE `tbl_reports` (
  `report_id` int(11) NOT NULL,
  `report_issue` int(11) NOT NULL,
  `report_reason` varchar(250) NOT NULL,
  `report_against` int(11) NOT NULL,
  `reported_by` int(11) NOT NULL,
  `proof` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_reports`
--

INSERT INTO `tbl_reports` (`report_id`, `report_issue`, `report_reason`, `report_against`, `reported_by`, `proof`, `status`) VALUES
(1, 1, 'stupid', 6, 2, 'report.png', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_requests`
--

CREATE TABLE `tbl_requests` (
  `req_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `reciever_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_requests`
--

INSERT INTO `tbl_requests` (`req_id`, `sender_id`, `reciever_id`, `status`) VALUES
(1, 2, 6, 'accepted'),
(2, 6, 5, 'accepted'),
(3, 6, 4, 'accepted'),
(6, 2, 5, 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(100) DEFAULT NULL,
  `user_status` varchar(10) NOT NULL DEFAULT 'Accepted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `profile_pic`, `user_status`, `created_at`) VALUES
(2, 'satya nadela', 'aparna21nr@gmail.com', 'satya', '../uploads/profile_pics/user_2_1747387663.jpg', 'Accepted', '2025-05-16 07:17:52'),
(3, 'cryingchingchong', 'paunokilo@gmail.com', 'Cry123', '../uploads/profile_pics/user_3_1747389499.jpg', 'Accepted', '2025-05-16 09:52:42'),
(4, 'Nomoregossip', 'gossipgirl@gmail.com', 'gossipgossip', '../uploads/profile_pics/user_4_1747389623.jpg', 'Accepted', '2025-05-16 09:59:41'),
(5, 'idrinkwineandim9', 'vanessapikachu@gmail.com', 'pikapikapii', '../uploads/profile_pics/user_5_1747389706.jpg', 'Accepted', '2025-05-16 10:01:17'),
(6, 'nerdnobookie', 'nerd@gmail.com', 'nerd', '../uploads/profile_pics/user_6_1747389785.jpg', 'Accepted', '2025-05-16 10:02:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `group_members_ibfk_1` (`user_id`),
  ADD KEY `group_members_ibfk_2` (`group_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `private_chat_messages`
--
ALTER TABLE `private_chat_messages`
  ADD PRIMARY KEY (`pr_id`);

--
-- Indexes for table `report_issue`
--
ALTER TABLE `report_issue`
  ADD PRIMARY KEY (`issue_id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_reports`
--
ALTER TABLE `tbl_reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `tbl_requests`
--
ALTER TABLE `tbl_requests`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `private_chat_messages`
--
ALTER TABLE `private_chat_messages`
  MODIFY `pr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `report_issue`
--
ALTER TABLE `report_issue`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_reports`
--
ALTER TABLE `tbl_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_requests`
--
ALTER TABLE `tbl_requests`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
