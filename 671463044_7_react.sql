-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2026 at 01:05 AM
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
-- Database: `671463044_7_react`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoriesID` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoriesID`, `name`) VALUES
(4, 'ทั่วไป'),
(3, 'ภาพยนตร์'),
(2, 'เกม'),
(1, 'เทคโนโลยี');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `commentID` bigint(20) UNSIGNED NOT NULL,
  `postID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `commentDetail` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`commentID`, `postID`, `userID`, `commentDetail`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'ขอบคุณมาก อ่านแล้วเข้าใจขึ้นเยอะ', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(2, 1, 3, 'งงตรง props นิดนึง แต่รวมๆดี', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(3, 2, 2, 'เอออันนี้แหละที่กูหาอยู่', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(4, 2, 4, 'ขอไฟล์ตัวอย่างได้ไหม', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(5, 3, 2, 'เกมนี้โหดจริง แต่เพลิน', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(6, 4, 1, 'เห็นด้วย เรื่องนี้ภาพสวยมาก', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(7, 5, 5, 'กูด้วย 5555', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(8, 6, 1, 'useEffect = ทำงานตอน render/ค่าเปลี่ยน', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(9, 7, 3, 'แก้ CORS ที่ headers.php เลย', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(10, 8, 5, 'ลอง The Long Dark ถ้าชอบเอาตัวรอด', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(11, 1, 1, 'props คือข้อมูลที่ส่งจาก component แม่ไปลูก', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(12, 2, 1, 'ไฟล์ตัวอย่างเดี๋ยวค่อยแนบใน repo ได้', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(13, 7, 1, 'ถ้าใช้ vite ต้องให้ backend ตอบ OPTIONS ด้วย', '2026-02-24 21:41:43', '2026-02-24 21:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `comment_images`
--

CREATE TABLE `comment_images` (
  `commentImageID` bigint(20) UNSIGNED NOT NULL,
  `commentID` bigint(20) UNSIGNED NOT NULL,
  `imageUrl` varchar(1024) NOT NULL,
  `sortOrder` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comment_images`
--

INSERT INTO `comment_images` (`commentImageID`, `commentID`, `imageUrl`, `sortOrder`, `created_at`) VALUES
(1, 1, 'img/comment/c1_1.jpg', 1, '2026-02-24 21:41:43'),
(2, 3, 'img/comment/c3_1.jpg', 1, '2026-02-24 21:41:43'),
(3, 6, 'img/comment/c6_1.jpg', 1, '2026-02-24 21:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `comment_reply`
--

CREATE TABLE `comment_reply` (
  `replyID` bigint(20) UNSIGNED NOT NULL,
  `parentCommentID` bigint(20) UNSIGNED NOT NULL,
  `childCommentID` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `comment_reply`
--

INSERT INTO `comment_reply` (`replyID`, `parentCommentID`, `childCommentID`, `created_at`) VALUES
(1, 2, 11, '2026-02-24 21:41:43'),
(2, 4, 12, '2026-02-24 21:41:43'),
(3, 9, 13, '2026-02-24 21:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favoritesID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `postID` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`favoritesID`, `userID`, `postID`, `created_at`) VALUES
(1, 2, 1, '2026-02-24 21:41:43'),
(2, 2, 2, '2026-02-24 21:41:43'),
(3, 3, 2, '2026-02-24 21:41:43'),
(4, 4, 4, '2026-02-24 21:41:43'),
(5, 5, 3, '2026-02-24 21:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `like_post`
--

CREATE TABLE `like_post` (
  `likepostID` bigint(20) UNSIGNED NOT NULL,
  `postID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `value` tinyint(4) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `like_post`
--

INSERT INTO `like_post` (`likepostID`, `postID`, `userID`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(2, 1, 3, 1, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(3, 2, 2, 1, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(4, 2, 4, 1, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(5, 3, 5, 1, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(6, 4, 1, 1, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(7, 5, 5, 1, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(8, 7, 3, 1, '2026-02-24 21:41:43', '2026-02-24 21:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `postID` bigint(20) UNSIGNED NOT NULL,
  `topicID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `postDetail` longtext NOT NULL,
  `postImage` varchar(1024) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`postID`, `topicID`, `userID`, `postDetail`, `postImage`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'สรุป React เบื้องต้น: component / state / props', 'img/post/p1.jpg', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(2, 2, 1, 'ตัวอย่าง REST API ด้วย PHP แบบง่ายๆ', 'img/post/p2.jpg', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(3, 3, 5, 'เกม survival ที่เล่นแล้วหัวร้อน แต่สนุก', 'img/post/p3.jpg', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(4, 4, 2, 'หนังไซไฟเรื่องนี้พล็อตดี ภาพสวย แต่จบแปลก', 'img/post/p4.jpg', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(5, 5, 3, 'วันนี้มีใครเรียนไม่รู้เรื่องเหมือนกันบ้าง', NULL, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(6, 1, 3, 'useEffect ใช้ทำอะไร สรุปให้หน่อย', NULL, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(7, 2, 2, 'CORS คืออะไร ทำไมยิงจาก React แล้วติด', NULL, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(8, 3, 4, 'เล่นคนเดียวเกมไหนคุ้มๆ แนะนำหน่อย', NULL, '2026-02-24 21:41:43', '2026-02-24 21:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `postImageID` bigint(20) UNSIGNED NOT NULL,
  `postID` bigint(20) UNSIGNED NOT NULL,
  `imageUrl` varchar(1024) NOT NULL,
  `sortOrder` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_images`
--

INSERT INTO `post_images` (`postImageID`, `postID`, `imageUrl`, `sortOrder`, `created_at`) VALUES
(1, 1, 'img/post/1_1.jpg', 1, '2026-02-24 21:41:43'),
(2, 1, 'img/post/1_2.jpg', 2, '2026-02-24 21:41:43'),
(3, 2, 'img/post/2_1.jpg', 1, '2026-02-24 21:41:43'),
(4, 3, 'img/post/3_1.jpg', 1, '2026-02-24 21:41:43'),
(5, 4, 'img/post/4_1.jpg', 1, '2026-02-24 21:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE `topic` (
  `topicID` bigint(20) UNSIGNED NOT NULL,
  `topicName` varchar(255) NOT NULL,
  `categoriesID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topic`
--

INSERT INTO `topic` (`topicID`, `topicName`, `categoriesID`, `userID`, `created_at`, `updated_at`) VALUES
(1, 'React / Frontend', 1, 1, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(2, 'PHP / API', 1, 1, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(3, 'เกมแนว Survival', 2, 5, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(4, 'รีวิวหนังไซไฟ', 3, 2, '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(5, 'คุยเล่นทั่วไป', 4, 3, '2026-02-24 21:41:43', '2026-02-24 21:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` bigint(20) UNSIGNED NOT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `userName` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('guest','user','admin') NOT NULL DEFAULT 'user',
  `gender` enum('male','female','other','unknown') DEFAULT 'unknown',
  `bio` text DEFAULT NULL,
  `userImage` varchar(1024) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `firstName`, `lastName`, `userName`, `email`, `password`, `role`, `gender`, `bio`, `userImage`, `created_at`, `updated_at`) VALUES
(1, 'Somchai', 'Jaidee', 'chai', 'chai@gmail.com', '1234', 'admin', 'male', 'แอดมินของระบบ', 'img/profile/u1.png', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(2, 'Suda', 'Wong', 'suda', 'suda@gmail.com', '1234', 'user', 'female', 'ชอบอ่าน ชอบเม้นท์', 'img/profile/u2.png', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(3, 'Anan', 'Krit', 'anan', 'anan@gmail.com', '1234', 'user', 'male', 'มือใหม่หัดโพสต์', 'img/profile/u3.png', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(4, 'Mali', 'Siri', 'mali', 'mali@gmail.com', '1234', 'user', 'female', 'สายส่องเฉยๆ', 'img/profile/u4.png', '2026-02-24 21:41:43', '2026-02-24 21:41:43'),
(5, 'Boss', 'Tan', 'boss', 'boss@gmail.com', '1234', 'user', 'male', 'เล่นเกมเยอะ', 'img/profile/u5.png', '2026-02-24 21:41:43', '2026-02-24 21:41:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoriesID`),
  ADD UNIQUE KEY `uq_categories_name` (`name`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentID`),
  ADD KEY `idx_comment_postID` (`postID`),
  ADD KEY `idx_comment_userID` (`userID`),
  ADD KEY `idx_comment_created_at` (`created_at`);

--
-- Indexes for table `comment_images`
--
ALTER TABLE `comment_images`
  ADD PRIMARY KEY (`commentImageID`),
  ADD KEY `idx_comment_images_commentID` (`commentID`),
  ADD KEY `idx_comment_images_sortOrder` (`commentID`,`sortOrder`);

--
-- Indexes for table `comment_reply`
--
ALTER TABLE `comment_reply`
  ADD PRIMARY KEY (`replyID`),
  ADD UNIQUE KEY `uq_comment_reply_child` (`childCommentID`),
  ADD KEY `idx_comment_reply_parent` (`parentCommentID`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favoritesID`),
  ADD UNIQUE KEY `uq_favorites_user_post` (`userID`,`postID`),
  ADD KEY `idx_favorites_postID` (`postID`);

--
-- Indexes for table `like_post`
--
ALTER TABLE `like_post`
  ADD PRIMARY KEY (`likepostID`),
  ADD UNIQUE KEY `uq_reaction` (`postID`,`userID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`postID`),
  ADD KEY `idx_post_topicID` (`topicID`),
  ADD KEY `idx_post_userID` (`userID`),
  ADD KEY `idx_post_created_at` (`created_at`);

--
-- Indexes for table `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`postImageID`),
  ADD KEY `idx_post_images_postID` (`postID`),
  ADD KEY `idx_post_images_sortOrder` (`postID`,`sortOrder`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`topicID`),
  ADD KEY `idx_topic_categoriesID` (`categoriesID`),
  ADD KEY `idx_topic_userID` (`userID`),
  ADD KEY `idx_topic_created_at` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `idx_users_nickName` (`userName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoriesID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `commentID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `comment_images`
--
ALTER TABLE `comment_images`
  MODIFY `commentImageID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comment_reply`
--
ALTER TABLE `comment_reply`
  MODIFY `replyID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favoritesID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `like_post`
--
ALTER TABLE `like_post`
  MODIFY `likepostID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `postID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `post_images`
--
ALTER TABLE `post_images`
  MODIFY `postImageID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `topicID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_post` FOREIGN KEY (`postID`) REFERENCES `post` (`postID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON UPDATE CASCADE;

--
-- Constraints for table `comment_images`
--
ALTER TABLE `comment_images`
  ADD CONSTRAINT `fk_comment_images_comment` FOREIGN KEY (`commentID`) REFERENCES `comment` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment_reply`
--
ALTER TABLE `comment_reply`
  ADD CONSTRAINT `fk_reply_child` FOREIGN KEY (`childCommentID`) REFERENCES `comment` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reply_parent` FOREIGN KEY (`parentCommentID`) REFERENCES `comment` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_post` FOREIGN KEY (`postID`) REFERENCES `post` (`postID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `like_post`
--
ALTER TABLE `like_post`
  ADD CONSTRAINT `like_post_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `post` (`postID`) ON DELETE CASCADE,
  ADD CONSTRAINT `like_post_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_topic` FOREIGN KEY (`topicID`) REFERENCES `topic` (`topicID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON UPDATE CASCADE;

--
-- Constraints for table `post_images`
--
ALTER TABLE `post_images`
  ADD CONSTRAINT `fk_post_images_post` FOREIGN KEY (`postID`) REFERENCES `post` (`postID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `topic`
--
ALTER TABLE `topic`
  ADD CONSTRAINT `fk_topic_category` FOREIGN KEY (`categoriesID`) REFERENCES `categories` (`categoriesID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_topic_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
