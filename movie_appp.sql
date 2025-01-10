-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2025 at 03:23 PM
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
-- Database: `movie_appp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_movies`
--

CREATE TABLE `admin_movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `release_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` float NOT NULL,
  `ticket_price` decimal(10,2) NOT NULL,
  `country` varchar(100) NOT NULL,
  `genre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_movies`
--

INSERT INTO `admin_movies` (`id`, `title`, `release_date`, `description`, `poster_url`, `created_at`, `rating`, `ticket_price`, `country`, `genre`) VALUES
(1, 'Black History', '2025-02-12', 'A powerful and poignant exploration of the African American experience, from the transatlantic slave trade to the present day, told through the eyes of one family\'s struggle for dusk,survival, freedom, and identity.\r\n', 'uploads/black-history-month-cover-concept.jpg', '2025-01-09 11:57:16', 4.5, 9000.00, 'Nigeria', 'Comedy, Historical'),
(2, 'The Boy', '1999-12-04', 'In the depths of a rural town, a young boy goes missing, only to return with an unspeakable evil that threatens to destroy everything in its path. As the boy\'s family and friends try to uncover the truth behind his disappearance, they realize that the boy is no longer the same. He\'s been consumed by a malevolent force that will stop at nothing to claim its next victim.', 'uploads/The boy.jpg', '2025-01-10 09:27:05', 4.9, 1599.99, 'Ghana', 'Comedy, Sci Fi, Romance'),
(3, 'Kurosawa', '2024-01-10', 'Set in feudal Japan, \'Kurosawa\' is a historical fiction film that follows the journey of a young samurai named Shinzaemon, who becomes embroiled in a bitter conflict between rival clans. As Shinzaemon navigates the treacherous landscape of 16th-century Japan, he must confront his own demons and make difficult choices that will determine the fate of his family and his honor. Directed by a protégé of Akira Kurosawa, this film is a sweeping epic that pays homage to the master\'s legacy while forging its own unique path.', 'uploads/Kurosawa poster.jpg', '2025-01-10 10:21:51', 3.9, 6000.00, 'Japan', 'Historical, War'),
(4, 'Chukky', '1878-02-03', 'In the sleepy town of Ravenswood, a legendary killer known as Chukky stalks and murders his victims with a rusty, old doll that seems to come to life in his hands. As a group of teenagers tries to uncover the truth behind Chukky\'s identity and motivations, they become his next targets. But Chukky\'s true horror may not be his brutal killings, but the dark secrets he uncovers about the town\'s twisted past.', 'uploads/Chukky.jpg', '2025-01-10 13:24:38', 2.5, 2999.99, 'Italy', 'Horror');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `created_at`, `role`) VALUES
(1, 'Lola', 'lola@gmail.com', '$2y$10$lay2Wrp7aRHpLorw1ZlKXe.TCMiKuZwrIR3Yn106S3NKVLC.xCZQi', '2025-01-06 09:50:49', 'user'),
(2, 'Ola', 'samuelolaoluwa181@gmail.com', '$2y$10$aM4wjh/yFkpdLXeEmD9TH.oMDxbiEUzk/PcxVr7Vd0B3iO9tndzwS', '2025-01-06 10:47:28', 'admin'),
(3, 'Emma', 'emma@gmail.com', '$2y$10$X/aOozRETs8RfWvwI0J6mOknwT8PVkZQQy6EXTSdcPja3i.e0PYRC', '2025-01-06 11:06:37', 'admin'),
(5, 'Olaoluwa', 'ola@gmail.com', '$2y$10$1IaVjlUwM8zWpZoZy5dIg.Y98y2wWhbtJOqKVtdHWlppuzLEmr18K', '2025-01-10 01:08:00', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_movies`
--
ALTER TABLE `admin_movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_movies`
--
ALTER TABLE `admin_movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
