-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 14, 2015 at 06:35 PM
-- Server version: 5.5.40-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `olgasmir_wonderland`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comments`
--

CREATE TABLE IF NOT EXISTS `tbl_comments` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username_id` int(3) NOT NULL,
  `post_id` int(4) NOT NULL,
  `comment` text CHARACTER SET utf8mb4 NOT NULL,
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hide` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `username_id` (`username_id`,`post_id`,`date_entered`,`hide`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `tbl_comments`
--

INSERT INTO `tbl_comments` (`id`, `username_id`, `post_id`, `comment`, `date_entered`, `hide`) VALUES
(2, 70, 3, 'I know! It''s a SECRET.', '2014-12-10 00:48:08', 2),
(6, 60, 3, 'Right..)', '2014-12-10 01:39:05', 2),
(7, 71, 63, 'two!', '2014-12-12 02:28:30', 2),
(8, 72, 63, 'three', '2014-12-12 02:57:50', 2),
(9, 73, 63, 'four!', '2014-12-12 03:14:39', 2),
(10, 62, 3, 'Tell me the secret!', '2014-12-12 03:54:14', 2),
(11, 1, 63, 'Guys, you''re not trying..', '2014-12-12 03:56:10', 2),
(13, 62, 63, 'I can see thirteen of them.', '2014-12-15 05:33:54', 2),
(15, 1, 63, 'Well done, Queen_of_Hearts!', '2014-12-15 20:10:54', 2),
(31, 60, 69, 'Pick the fruit from "APPLES AND ORANGES" box.', '2014-12-17 20:20:23', 2),
(30, 70, 4, 'Haa, it was Johnny)', '2014-12-17 19:17:16', 2),
(32, 71, 69, 'Cheshire_cat, why?', '2014-12-17 20:21:35', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pages`
--

CREATE TABLE IF NOT EXISTS `tbl_pages` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(20) NOT NULL,
  `page_title` varchar(30) NOT NULL,
  `page_description` varchar(40) NOT NULL,
  `page_headline` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `page_subline` varchar(300) CHARACTER SET utf8 NOT NULL,
  `menu_name` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `hide` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hide` (`hide`),
  KEY `page_title` (`page_title`),
  KEY `description` (`page_description`),
  KEY `page_name` (`page_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tbl_pages`
--

INSERT INTO `tbl_pages` (`id`, `page_name`, `page_title`, `page_description`, `page_headline`, `page_subline`, `menu_name`, `hide`) VALUES
(1, 'index.php', 'Welcome to Wonderland', 'logical riddles blog', 'Welcome to Wonderland', '... improve your mind by solving logical riddles', '', 2),
(2, 'signin.php', 'Sign in into Wonderland', 'logical riddles blog', 'Welcome to Wonderland', '... who are you? ah, that''s the great puzzle', '', 2),
(3, 'profile.php', 'My profile in Wonderland', 'write logical riddles', 'Welcome to Wonderland', '... curiouser and curiouser', 'My profile', 3),
(4, 'riddles.php', 'Riddles of Wonderland', 'solve logical riddles', 'Welcome to Wonderland', '... at any rate, there''s no harm in trying', 'Riddles', 4),
(5, 'friends.php', 'My friends'' feed in Wonderland', 'solve logical riddles', 'Welcome to Wonderland', '... we all are mad here', 'Friends', 4),
(6, 'leavecomment.php', 'Leave a comment', 'leave a comment', 'Welcome to Wonderland', '... you can always take more than nothing', '', 2),
(7, 'Post_riddle.php', 'Admin', 'secret', 'Welcome to Wonderland', '... sentence first - verdict afterwards', 'Admin', 1),
(8, 'thankyou.php', 'Thank you', 'thank you', 'Welcome to Wonderland', '... down, down, down', '', 2),
(10, 'goodbye.php', 'Goodbye', 'delete an account', 'Farewell to Wonderland', '... oh, dear', '', 2),
(11, 'deleted.php', 'Deleted profile', 'profile was deleted', 'Back to Wonderland', '... oh, dear', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posts`
--

CREATE TABLE IF NOT EXISTS `tbl_posts` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username_id` int(3) NOT NULL,
  `post_title` varchar(60) CHARACTER SET utf8mb4 NOT NULL,
  `post` text CHARACTER SET utf8mb4 NOT NULL,
  `img_name` varchar(100) DEFAULT NULL,
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dead_line` varchar(200) NOT NULL,
  `riddle_answer` mediumtext NOT NULL,
  `comment_num` tinyint(3) NOT NULL DEFAULT '0',
  `hide` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `username_id` (`username_id`,`date_entered`,`hide`),
  KEY `comment_num` (`comment_num`),
  KEY `img_name` (`img_name`),
  KEY `dead_line` (`dead_line`),
  FULLTEXT KEY `riddle_answer` (`riddle_answer`),
  FULLTEXT KEY `post` (`post`),
  FULLTEXT KEY `post_2` (`post`),
  FULLTEXT KEY `post_3` (`post`),
  FULLTEXT KEY `post_4` (`post`),
  FULLTEXT KEY `post_5` (`post`),
  FULLTEXT KEY `post_6` (`post`),
  FULLTEXT KEY `post_7` (`post`),
  FULLTEXT KEY `post_8` (`post`),
  FULLTEXT KEY `post_9` (`post`),
  FULLTEXT KEY `post_10` (`post`),
  FULLTEXT KEY `post_11` (`post`),
  FULLTEXT KEY `post_12` (`post`),
  FULLTEXT KEY `post_title` (`post_title`),
  FULLTEXT KEY `post_13` (`post`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=77 ;

--
-- Dumping data for table `tbl_posts`
--

INSERT INTO `tbl_posts` (`id`, `username_id`, `post_title`, `post`, `img_name`, `date_entered`, `dead_line`, `riddle_answer`, `comment_num`, `hide`) VALUES
(1, 1, 'Hello,', 'Congratulations! You''ve created an account successfully. Now you can read the feed or write your very first post. Please, be polite and remember that if you don''t think, than you shouldn''t talk.', NULL, '2014-11-26 20:43:02', '', '', 0, 3),
(3, 60, 'What am I?', 'If you have me, you want to share me. If you share me, you haven''t got me. What am I?', NULL, '2014-12-08 05:28:31', '', '', 3, 2),
(4, 60, 'Simple one', 'Johnny''s mother had three children. The first child was named April. The second child was named May. What was the third child''s name?', NULL, '2014-12-08 05:44:13', '', '', 1, 2),
(38, 1, 'Riddle#1: Who stole the jam?', 'The Queen of Hearts, she made some tarts\r\n All on a summer''s day;\r\n  The Knave of Hearts, he stole the tarts\r\n And took them quite away!\r\n\r\n"How about making us some nice tarts?" the King of Hearts asked the Queen of Hearts.\r\n"What''s the sense of making tarts without jam?" said the Queen furiously. "My jam has been stolen!"\r\n\r\nWell, the King had his soldiers scout around for the missing jam, and it was found in the house of the March Hare, the Mad Hatter, and the Dormouse. All three were promptly arrested and tried.\r\n\r\n"Did you by any chance steal the jam?" the King asked the March Hare.\r\n"I never stole the jam!" pleaded the March Hare.\r\n"What about you?" the King roared to the Hatter.\r\n"No, no!" pleaded the Hatter. "One of us stole it, but it wasn''t me!"\r\n"Make a note of that!" said the King to the jury. "What do you have to say about all this?" continued the King to the Dormouse. "Did the March Hare and the Hatter tell the truth?"\r\n"At least one of them did," replied the Dormouse, who then fell asleep for the rest of the trial.\r\nAs subsequent investigation revealed, the March Hare and the Dormouse were not both speaking the truth.\r\n\r\nWho stole the jam?', NULL, '2014-12-09 07:10:23', '12:00, 19 December 2014', 'The Hatter said, in effect, that either the March Hare or the Dormouse stole it. If the Hatter lied, then neither the March Hare nor the Dormouse stole it, which means that the March Hare didn''t steal it, hence was speaking the truth.\r\n\r\nTherefore, if the Hatter lied, then the March Hare didn''t lie, so it is impossible that the Hatter and the March Hare both lied. Therefore the Dormouse spoke the truth when he said that the Hatter and March Hare didn''t both lie. So we know that the\r\nDormouse spoke the truth.\r\n\r\nBut we are given that the Dormouse and the March Hare didn''t both speak the truth. Then, since the Dormouse did, the March Hare didn''t. This means that the March Hare lied, so his statement was false, which means that the March Hare stole the jam.', 0, 2),
(63, 1, 'Riddle#2: Triangles', 'Count the triangles in picture above.', 'Screen Shot 2014-12-11 at 10.34.22 PM.png', '2014-12-11 09:35:42', '20:00, 15 December 2014', '13', 6, 2),
(68, 1, 'RIDDLE#3: The Waiter', 'Three men in a cafe order a meal the total cost of which is $15. They each contribute $5. The waiter takes the money to the chef who recognizes the three as friends and asks the waiter to return $5 to the men.\r\n\r\nThe waiter is not only poor at mathematics but dishonest and instead of going to the trouble of splitting the $5 between the three he simply gives them $1 each and pockets the remaining $2 for himself.\r\n\r\nNow, each of the men effectively paid $4, the total paid is therefore $12. Add the $2 in the waiters pocket and this comes to $14.....where has the other $1 gone from the original $15?', NULL, '2014-12-17 20:04:11', '12:00, 20 December 2014', 'The payments should equal the receipts. It does not make sense to add what was paid by the men ($12) to what was received from that payment by the waiter ($2)\r\n\r\nAlthough the initial bill was $15 dollars, one of the five dollar notes gets changed into five ones. The total the three men ultimately paid is $12, as they get three ones back. So from the $12 the men paid, the owner receives $10 and the waiter receives the $2 difference. $15 - $3 = $10 + $2.', 0, 2),
(69, 1, 'RIDDLE#4: The Boxes', 'There are three boxes. One is labeled "APPLES" another is labeled "ORANGES". The last one is labeled "APPLES AND ORANGES". You know that each is labeled incorrectly. You may ask me to pick one fruit from one box which you choose.\r\n\r\nHow can you label the boxes correctly?', NULL, '2014-12-17 20:07:49', '10:00, 19 December 2014', 'Pick from the one labeled "Apples & Oranges". This box must contain either only apples or only oranges.\r\n\r\nE.g. if you find an Orange, label the box Orange, then change the Oranges box to Apples, and the Apples box to "Apples & Oranges."', 2, 2),
(72, 1, 'RIDDLE#5: For PHP fans', 'You have database of flowers. You need to color each of flower. Every 4th flower has to be yellow, every 3rd one - pink, every second - dark pink, and every first one in those quads - crimson (as shown on the picture above).\r\n\r\nWhat''s the query for coloring flowers?', 'Screen Shot 2014-12-18 at 10.09.50 AM.png', '2014-12-17 21:13:03', '12:00, 20 February 2015', 'WHERE NOT (id-1)%4 => affects 1, 5, 9, 13 etc... flower.\r\nWHERE NOT (id-2)%4 => affects 2, 6, 10, 14 etc... flower.\r\nWHERE NOT (id-3)%4 => affects 3, 7, 11, 15 etc... flower.\r\nWHERE NOT id%4 => affects 4, 8, 12, 16 etc... flower.', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reasons_of_leaving`
--

CREATE TABLE IF NOT EXISTS `tbl_reasons_of_leaving` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `like_btn` int(3) NOT NULL,
  `too_simple` smallint(3) NOT NULL,
  `too_difficult` smallint(3) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `sum` (`id`,`like_btn`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_reasons_of_leaving`
--

INSERT INTO `tbl_reasons_of_leaving` (`id`, `like_btn`, `too_simple`, `too_difficult`) VALUES
(1, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(30) NOT NULL,
  `statement` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `logic_attitude` varchar(3) NOT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT '2',
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`avatar`),
  UNIQUE KEY `email` (`email`),
  KEY `hide` (`hide`,`date_entered`),
  KEY `logic_attitude` (`logic_attitude`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=87 ;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `password`, `email`, `statement`, `avatar`, `logic_attitude`, `hide`, `date_entered`) VALUES
(1, 'white_rabbit', '$2y$10$RAXLvS9mxCfpf6oYYfd5iOREBvZMjcW/ZPljJydALy2xOtjCqQ5Sa', 'ladiez.os@gmail.com', 'oh, dear, I shall be late', 'rabbit.jpg', 'yey', 2, '2014-11-26 20:12:28'),
(73, 'four_of_hearts', '$2y$10$SEXFVqBJGIGndc.8cN6y0.6pZmtrXAgGPm/ljq6of9KkHMDrjerEa', 'four@mail.com', 'i''m funny', 'four.jpg', 'nay', 2, '2014-12-12 03:13:50'),
(71, 'two_of_hearts', '$2y$10$TFdT9xAeX5oSCbiicoG/Bew5ACkaYX.Pabo.B78vKOFoH3yHHfV.O', 'two@mail.com', 'i''m smart', 'CP5490ece7ce809two.jpg', 'nay', 2, '2014-12-17 02:39:36'),
(72, 'three_of_hearts', '$2y$10$BzsuxsJ9QaSJC7qAQSlAHuuCXMjQZ4GsT0bWA/IdS0xzf7zxYG59i', 'three@gmail.com', 'i''m beautiful', 'three.jpg', 'nay', 2, '2014-12-12 02:57:11'),
(60, 'cheshire_cat', '$2y$10$V7fujlCmuCchi0fwX2NNoe24wbq4fV4Gjbkl6KhuKsq1xTOwJTWI2', 'o.smirnova1986@gmail.com', 'where do you want to go?', 'CP54851b98f2f48cheshire-cat1.png', 'yey', 2, '2014-12-08 03:31:37'),
(62, 'queen_of_hearts', '$2y$10$a6ITT8Ob8PNob1t13/cj/uhsyBEyfLWRHFSQIQkpS6teOHljZ9/rS', 'queen@gmail.com', 'if you don''t think, than you shouldn''t talk', 'CP54854289a3ff0queen.jpg', 'yey', 2, '2014-12-08 06:17:45'),
(70, 'alice', '$2y$10$M131WCR5uDB8THgXs8ck6ePqedtgRIMPVzqzCQmzUAeekUZzZGC/u', 'alice@gmail.com', 'if I have a world of my own..', 'alice.jpg', 'yey', 2, '2014-12-10 00:46:36'),
(83, 'jsmirnov', '$2y$10$AzGJG0kC4Z5OvpoVO4b7ZuCo9f4MERTIanYI2opVGWWqqLv9KV.m.', 'jsmirnov@me.com', 'Brother', 'image.jpg', 'yey', 2, '2014-12-17 06:17:43'),
(84, 'steve', '$2y$10$7Kr0cLFI/NKqWRxv0xNY3.57kp74IqiVqaa6n7CWgz2qO6m830J7e', 'steve.yeoman@!yoobee.ac.nz', 'Why should I tell you anything about me? Curiouser and curiouser.', 'bridge.jpg', 'yey', 2, '2015-01-06 02:06:31');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
