-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Nov 21, 2024 at 08:01 AM
-- Server version: 5.7.44
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `obs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `name` varchar(20) NOT NULL,
  `pass` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`name`, `pass`) VALUES
('admin', 'f865b53623b121fd34ee5426c792e5c33af8c227');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_isbn` varchar(20) NOT NULL,
  `book_title` varchar(60) DEFAULT NULL,
  `book_author` varchar(60) DEFAULT NULL,
  `book_image` varchar(40) DEFAULT NULL,
  `book_descr` text,
  `book_price` decimal(6,2) NOT NULL,
  `publisherid` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_isbn`, `book_title`, `book_author`, `book_image`, `book_descr`, `book_price`, `publisherid`, `created_at`) VALUES
('123', 'paniko gham', 'Amar Neupane', '', 'mhjgfsk', 256.00, 3, '2024-11-21 04:28:03'),
('978-99933-527-10', 'Mayur Times', 'Narayan Wagle', 'mayur_times.jpg', 'Mayur Times is a gripping thriller set in the context of investigative journalism, diving into corruption and politics.', 20.00, 2, '2024-11-15 00:00:00'),
('978-99933-527-11', 'Mad Country', 'Narayan Wagle', 'mad_country.jpg', 'Mad Country explores political turmoil and societal changes in Nepal, weaving personal struggles into larger national narratives.', 20.00, 2, '2024-11-15 00:00:00'),
('978-99933-527-13', 'Seto Bagh', 'Diamond Shumsher Rana', 'seto_bagh.jpg', 'Seto Bagh narrates the story of the Rana regime in Nepal, focusing on intrigue, romance, and tragedy.', 20.00, 3, '2024-11-15 00:00:00'),
('978-99933-527-3', 'Basain', 'Shankar B. Thapa', 'basain.jpg', 'A poignant story of migration, loss, and the emotional struggles faced by people who leave their homes in rural Nepal for better opportunities.', 20.00, 2, '2024-11-15 00:00:00'),
('978-99933-527-4', 'Muna Madan', 'Laxmi Prasad Devkota', 'muna_madan.jpg', 'A timeless Nepali classic that portrays the love story of Muna and Madan, and their emotional and cultural journey.', 20.00, 1, '2024-11-15 00:00:00'),
('978-99933-527-6', 'Shirishko Phool', 'Parijat', 'shirishko_phool.jpg', 'This is a psychological novel about the struggles and alienation faced by a woman in a patriarchal society in Nepal.', 20.00, 1, '2024-11-15 00:00:00'),
('978-99933-527-7', 'Seto Dharti', 'Jhamak Ghimire', 'seto_dharti.jpg', 'Seto Dharti tells the story of a woman’s struggles against the societal norms and her fight for identity and independence in a male-dominated society.', 20.00, 3, '2024-11-15 00:00:00'),
('978-99933-527-8', 'Palpasa Cafe', 'Narayan Wagle', 'palpasa_cafe.jpg', 'Palpasa Cafe is a gripping tale set against the backdrop of the Maoist insurgency in Nepal, with love, politics, and personal loss interwoven throughout.', 20.00, 2, '2024-11-15 00:00:00'),
('978-99933-527-9', 'Karnali Blues', 'Shankar Lamichhane', 'karnali_blues.jpg', 'A powerful and emotional story about the relationship between a father and son, set in the backdrop of the Karnali region of Nepal.', 20.00, 1, '2024-11-15 00:00:00'),
('978-99933-528-0', 'Data Structures and Algorithms', 'R.K. Gupta', 'data_structures_algorithms.jpg', 'This book provides a comprehensive introduction to data structures and algorithms for BCA students.', 25.00, 6, '2024-11-15 00:00:00'),
('978-99933-528-1', 'Computer Networks', 'James Kurose', 'computer_networks.jpg', 'This book is an introduction to computer networks, providing detailed coverage of various networking technologies and protocols.', 30.00, 6, '2024-11-15 00:00:00'),
('978-99933-528-2', 'Operating System Concepts', 'Abraham Silberschatz', 'operating_system_concepts.jpg', 'A widely used textbook that covers the fundamental concepts of operating systems for BCA students.', 28.00, 3, '2024-11-15 00:00:00'),
('978-99933-528-3', 'Database Management Systems', 'Ramez Elmasri', 'dbms.jpg', 'This book offers an in-depth understanding of database management systems, covering various database models, design techniques, and SQL.', 30.00, 1, '2024-11-15 00:00:00'),
('978-99933-528-4', 'Software Engineering', 'I. Sommerville', 'software_engineering.jpg', 'A detailed guide to the software engineering process, covering topics such as software development models, project management, and software testing.', 35.00, 3, '2024-11-15 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customerid` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `address` varchar(80) NOT NULL,
  `contact` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customerid`, `name`, `address`, `contact`) VALUES
(1, 'admin', 'hk', 'jgkj'),
(2, 'admin', 'bbkbj', 'jgkj'),
(3, 'admin', 'ughj', 'gjk'),
(4, 'admin', 'hlk', 'ufy'),
(5, 'Saujanya Poudel', 'gkjg', 'jdgfh'),
(6, 'saujanya', 'fy', '986037689'),
(7, 'sau', 'ujgl', '868'),
(8, 'Saujanya Poudel', 'rgdh', '2345671890'),
(9, 'ritu', 'sg', '3675231785'),
(10, '12', 'hjfhgj', '3675231785'),
(11, 'ywfduw', 'gjhdgsfj', '8674883932'),
(12, 'Ritu', 'lalitpur', '9860309718');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderid` int(10) UNSIGNED NOT NULL,
  `customerid` int(10) UNSIGNED NOT NULL,
  `book_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderid`, `customerid`, `book_id`, `name`, `address`, `contact`, `total_price`, `order_date`, `payment_method`) VALUES
(1, 3, '978-99933-527-10', 'admin', 'ughj', 'gjk', 2600.00, '2024-11-20 15:21:38', 'cod'),
(2, 3, '978-99933-527-10', 'admin', 'ughj', 'gjk', 2600.00, '2024-11-20 15:24:05', 'cod'),
(3, 4, '978-99933-527-10', 'admin', 'hlk', 'ufy', 5200.00, '2024-11-20 15:24:23', 'cod'),
(4, 4, '978-99933-527-10', 'admin', 'hlk', 'ufy', 5200.00, '2024-11-20 15:28:18', 'cod'),
(5, 5, '978-99933-527-10', 'Saujanya Poudel', 'gkjg', 'jdgfh', 2600.00, '2024-11-20 15:35:11', 'cod'),
(6, 6, '978-99933-527-10', 'saujanya', 'fy', '986037689', 5200.00, '2024-11-20 15:52:38', 'cod'),
(7, 6, '978-99933-527-10', 'saujanya', 'fy', '986037689', 5200.00, '2024-11-20 15:57:07', 'cod'),
(8, 6, '978-99933-527-10', 'saujanya', 'fy', '986037689', 5200.00, '2024-11-20 16:04:26', 'cod'),
(9, 7, '978-99933-527-10', 'sau', 'ujgl', '868', 7800.00, '2024-11-20 16:06:20', 'cod'),
(10, 8, '978-99933-527-10', 'Saujanya Poudel', 'rgdh', '2345671890', 7800.00, '2024-11-20 16:10:37', 'khalti'),
(11, 9, '978-99933-527-10', 'ritu', 'sg', '3675231785', 7800.00, '2024-11-20 16:15:13', 'cod'),
(12, 9, '978-99933-527-10', 'ritu', 'sg', '3675231785', 7800.00, '2024-11-20 16:17:46', 'cod'),
(13, 9, '978-99933-527-10', 'ritu', 'sg', '3675231785', 7800.00, '2024-11-20 16:17:52', 'cod'),
(14, 9, '978-99933-527-10', 'ritu', 'sg', '3675231785', 7800.00, '2024-11-20 16:17:58', 'cod'),
(15, 10, '978-99933-527-10', '12', 'hjfhgj', '3675231785', 7800.00, '2024-11-20 16:18:30', 'cod'),
(16, 11, '978-99933-527-10', 'ywfduw', 'gjhdgsfj', '8674883932', 20.00, '2024-11-21 03:54:48', 'cod'),
(17, 12, '978-99933-527-10', 'Ritu', 'lalitpur', '9860309718', 20.00, '2024-11-21 04:14:22', 'cod');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `book_isbn` varchar(20) NOT NULL,
  `book_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `publisher`
--

CREATE TABLE `publisher` (
  `publisherid` int(10) UNSIGNED NOT NULL,
  `publisher_name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `publisher`
--

INSERT INTO `publisher` (`publisherid`, `publisher_name`) VALUES
(1, 'Publisher One'),
(2, 'Publisher Two'),
(3, 'Publisher Three');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`name`,`pass`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_isbn`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customerid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderid`),
  ADD KEY `customerid` (`customerid`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `book_isbn` (`book_isbn`);

--
-- Indexes for table `publisher`
--
ALTER TABLE `publisher`
  ADD PRIMARY KEY (`publisherid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customerid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `publisher`
--
ALTER TABLE `publisher`
  MODIFY `publisherid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `customers` (`customerid`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`orderid`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`book_isbn`) REFERENCES `books` (`book_isbn`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
