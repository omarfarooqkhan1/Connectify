-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2020 at 01:49 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `connectify`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment` varchar(140) NOT NULL,
  `commentOn` int(11) NOT NULL,
  `commentBy` int(11) NOT NULL,
  `commentAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `follow_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `followOn` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `follow`
--

INSERT INTO `follow` (`follow_id`, `sender`, `receiver`, `followOn`) VALUES
(288, 1, 23, '0000-00-00 00:00:00'),
(289, 1, 32, '0000-00-00 00:00:00'),
(290, 1, 13, '0000-00-00 00:00:00'),
(291, 1, 22, '0000-00-00 00:00:00'),
(292, 1, 33, '0000-00-00 00:00:00'),
(293, 31, 21, '0000-00-00 00:00:00'),
(294, 31, 21, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `likeBy` int(11) NOT NULL,
  `likeOn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `likeBy`, `likeOn`) VALUES
(205, 22, 67),
(206, 22, 66),
(207, 31, 66),
(208, 34, 69);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `messageID` int(11) NOT NULL,
  `message` text NOT NULL,
  `messageTo` int(11) NOT NULL,
  `messageFrom` int(11) NOT NULL,
  `messageOn` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`messageID`, `message`, `messageTo`, `messageFrom`, `messageOn`, `status`) VALUES
(1, 'hi,this is my new message', 14, 21, '0000-00-00 00:00:00', 0),
(2, 'hi hamza', 21, 22, '0000-00-00 00:00:00', 1),
(3, 'i am muslim', 21, 22, '0000-00-00 00:00:00', 1),
(4, 'mmmmm', 22, 21, '2020-07-28 02:32:24', 1),
(5, 'hi how are u', 22, 21, '2020-07-28 02:33:35', 1),
(6, 'hi how are u', 22, 21, '2020-07-28 02:33:43', 1),
(7, 'Aslamoalikum', 22, 21, '2020-07-28 02:35:13', 1),
(8, 'Aslamoalikum', 22, 21, '2020-07-28 02:35:16', 1),
(9, 'Aslamoalikum', 22, 21, '2020-07-28 02:35:24', 1),
(10, 'Aslamoalikum', 22, 21, '2020-07-28 02:35:24', 1),
(11, 'Aslamoalikum', 22, 21, '2020-07-28 02:35:48', 1),
(12, 'ccc', 22, 21, '2020-07-28 02:36:24', 1),
(13, 'mmmmm', 22, 21, '2020-07-28 02:37:35', 1),
(14, 'mmmmmm', 22, 21, '2020-07-28 02:38:49', 1),
(15, 'aslamoalikum', 22, 21, '2020-07-28 02:39:03', 1),
(16, 'how are u', 22, 21, '2020-07-28 02:39:18', 1),
(17, 'MY]\n\nmy  nam', 22, 21, '2020-07-28 05:37:19', 1),
(19, 'hello Aslamoalikum', 21, 22, '2020-07-29 07:01:49', 1),
(20, 'hi', 21, 22, '2020-07-30 09:31:59', 1),
(21, 'how are u', 21, 22, '2020-07-30 09:32:15', 1),
(22, 'gffgfgfgfgfgfg', 31, 21, '0000-00-00 00:00:00', 1),
(23, 'SSSSSSSSSS', 21, 31, '2020-07-30 11:13:01', 1),
(24, 'm', 21, 31, '2020-07-31 12:00:53', 1),
(25, 'hi\nhi', 21, 34, '2020-08-06 01:26:33', 1),
(26, 'how are u', 21, 34, '2020-08-06 01:27:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `ID` int(11) NOT NULL,
  `notificationFor` int(11) NOT NULL,
  `notificationForm` int(11) NOT NULL,
  `target` int(11) NOT NULL,
  `type` enum('follow','retweet','like','mention') NOT NULL,
  `time` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`ID`, `notificationFor`, `notificationForm`, `target`, `type`, `time`, `status`) VALUES
(39, 1, 21, 1, 'follow', '0000-00-00 00:00:00', 0),
(40, 21, 22, 21, 'follow', '0000-00-00 00:00:00', 0),
(41, 21, 22, 67, 'like', '0000-00-00 00:00:00', 0),
(42, 21, 22, 66, 'like', '0000-00-00 00:00:00', 0),
(43, 21, 22, 21, 'follow', '0000-00-00 00:00:00', 1),
(44, 21, 22, 21, 'follow', '0000-00-00 00:00:00', 1),
(45, 21, 31, 66, 'like', '0000-00-00 00:00:00', 1),
(46, 21, 31, 21, 'follow', '0000-00-00 00:00:00', 1),
(47, 1, 31, 1, 'follow', '0000-00-00 00:00:00', 0),
(48, 31, 21, 31, 'follow', '0000-00-00 00:00:00', 1),
(49, 31, 32, 31, 'follow', '0000-00-00 00:00:00', 1),
(50, 21, 34, 21, 'follow', '0000-00-00 00:00:00', 1),
(51, 31, 1, 31, 'follow', '0000-00-00 00:00:00', 1),
(52, 31, 1, 31, 'follow', '0000-00-00 00:00:00', 1),
(53, 23, 1, 23, 'follow', '0000-00-00 00:00:00', 0),
(54, 32, 1, 32, 'follow', '0000-00-00 00:00:00', 0),
(55, 13, 1, 13, 'follow', '0000-00-00 00:00:00', 0),
(56, 22, 1, 22, 'follow', '0000-00-00 00:00:00', 0),
(57, 33, 1, 33, 'follow', '0000-00-00 00:00:00', 0),
(58, 21, 31, 21, 'follow', '0000-00-00 00:00:00', 0),
(59, 21, 31, 21, 'follow', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `trends`
--

CREATE TABLE `trends` (
  `trendID` int(11) NOT NULL,
  `hashtag` varchar(140) NOT NULL,
  `createdOn` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trends`
--

INSERT INTO `trends` (`trendID`, `hashtag`, `createdOn`) VALUES
(16, 'comsatsReduceFee', '2020-07-30 23:20:14'),
(17, 'web', '2020-07-31 00:18:37');

-- --------------------------------------------------------

--
-- Table structure for table `tweets`
--

CREATE TABLE `tweets` (
  `tweet_id` int(11) NOT NULL,
  `status` varchar(140) NOT NULL,
  `tweetBy` int(11) NOT NULL,
  `retweet_id` int(11) NOT NULL,
  `retweetBy` int(11) NOT NULL,
  `tweetImage` varchar(255) NOT NULL,
  `likesCount` int(11) NOT NULL,
  `retweetCount` int(11) NOT NULL,
  `postedOn` datetime NOT NULL,
  `retweetMsg` varchar(140) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tweets`
--

INSERT INTO `tweets` (`tweet_id`, `status`, `tweetBy`, `retweet_id`, `retweetBy`, `tweetImage`, `likesCount`, `retweetCount`, `postedOn`, `retweetMsg`) VALUES
(66, '#comsatsReduceFee', 21, 0, 0, '', 2, 0, '2020-07-30 20:20:14', ''),
(67, 'U lose only, when u give up', 21, 0, 0, '', 1, 0, '2020-07-30 20:21:42', ''),
(68, '#web I love webDesigning', 31, 0, 0, '', 0, 0, '2020-07-30 21:18:37', ''),
(69, '@saqlain hi, what\'s up', 34, 0, 0, '', 1, 0, '2020-08-06 13:23:22', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `screenName` varchar(40) NOT NULL,
  `profileImage` varchar(255) NOT NULL,
  `profileCover` varchar(255) NOT NULL,
  `following` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `bio` varchar(140) NOT NULL,
  `country` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `screenName`, `profileImage`, `profileCover`, `following`, `followers`, `bio`, `country`, `website`) VALUES
(1, 'omarfarooqkhan', 'omarfarooqkhan@outlook.com', '53261b193ce5766fe3f6a12f152ddfe1', 'Omar Farooq Khan', 'users/download.jpg', 'users/structure-play-color-community-colorful-together-818679-pxhere.com.jpg', 5, 0, 'I love PHP', '', ''),
(13, 'slimrazasim', 'slimrazasim@gmail.com', 'a0786ca6f25c102cf8be5e1e68a48a0d', 'Saleem Akhtar', 'assets/images/defaultProfileImage.png', 'assets/images/defaultCoverImage.png', 0, 1, '', '', ''),
(21, 'hurera_abbasi', 'hu123@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Hurera Tallat Abbasi', 'users/hurera.jpg', 'users/new-facebook-cover-photos.jpg', 0, 2, 'CS Student', 'G-9 Islamabad', 'localhost/connectify'),
(22, 'ham', 'hamza@gmail.com', 'bcb759b5b8ab63b06295c7434345d7a5', 'hamza', 'assets/images/defaultProfileImage.png', 'users/about-us.jpg', 0, 1, 'I am hamza', 'jhelum', 'n/a'),
(23, 'farooq', 'farooq@gmail.com', '7a1c7943385e8cb8dc0b74ff4d3c9844', 'farooq', 'assets/images/defaultProfileImage.png', 'assets/images/defaultCoverImage.png', 0, 1, '', '', ''),
(31, 'ha123', 'ha123@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Haseeb Akhter', 'users/joker_dp.jpg', 'users/cover.jpg', 2, 0, 'we fear rejection,wants Attention crave affection and have dream of perfexction', 'Islamabad, Pakistan', 'localhost/Connectify'),
(32, 'h', 'malikhamza@gmail.com', '25f9e794323b453885f5181f1b624d0b', 'mota', 'assets/images/defaultProfileImage.png', 'assets/images/defaultCoverImage.png', 0, 1, '', '', ''),
(33, '', 'maliksaqlain@gmail.com', 'fcea920f7412b5da7be0cf42b8c93759', 'maliksaqlain', 'assets/images/defaultProfileImage.png', 'assets/images/defaultCoverImage.png', 0, 1, '', '', ''),
(34, 'malikSaqlain', 'sp18-bcs-082@isbstudent.comsats.edu.pk', '8556420166ecc35954e6032bdd38d36d', 'sp18-bcs-082', 'assets/images/defaultProfileImage.png', 'assets/images/defaultCoverImage.png', 0, 0, '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`follow_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messageID`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `trends`
--
ALTER TABLE `trends`
  ADD PRIMARY KEY (`trendID`),
  ADD UNIQUE KEY `hashtag` (`hashtag`);

--
-- Indexes for table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`tweet_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `follow`
--
ALTER TABLE `follow`
  MODIFY `follow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `messageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `trends`
--
ALTER TABLE `trends`
  MODIFY `trendID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tweets`
--
ALTER TABLE `tweets`
  MODIFY `tweet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
