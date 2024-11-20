-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Nov 17, 2024 at 06:04 AM
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
CREATE DATABASE IF NOT EXISTS `obs_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `obs_db`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `name` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `pass` varchar(40) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

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
  `book_isbn` VARCHAR(20) NOT NULL,
  `book_title` VARCHAR(60) DEFAULT NULL,
  `book_author` VARCHAR(60) DEFAULT NULL,
  `book_image` VARCHAR(40) DEFAULT NULL,
  `book_descr` TEXT,
  `book_price` DECIMAL(6,2) NOT NULL,
  `publisherid` INT(10) UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`book_isbn`)
)
 ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_isbn`, `book_title`, `book_author`, `book_image`, `book_descr`, `book_price`, `publisherid`, `created_at`) VALUES
('978-99933-527-10', 'Mayur Times', 'Narayan Wagle', 'mayur_times.jpg', 'Mayur Times is a gripping thriller set in the context of investigative journalism, diving into corruption and politics.', 20.00, 2, '2024-11-15 00:00:00'),
('978-99933-527-11', 'Mad Country', 'Narayan Wagle', 'mad_country.jpg', 'Mad Country explores political turmoil and societal changes in Nepal, weaving personal struggles into larger national narratives.', 20.00, 2, '2024-11-15 00:00:00'),
('978-99933-527-13', 'Seto Bagh', 'Amar Neupane', 'seto_bagh.jpg', 'Seto Bagh narrates the story of the Rana regime in Nepal, focusing on intrigue, romance, and tragedy.', 20.00, 3, '2024-11-15 00:00:00'),
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
  `customerid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(60) NOT NULL,
  `address` varchar(80) NOT NULL,
  `contact` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Dumping data for table `customers`
--

-- INSERT INTO `customers` (`customerid`, `name`, `address`, `contact`) VALUES
-- (1, 'John Doe', 'Lalitpur', '123456789'),
-- (2, 'Jane Smith', 'Kathmandu', '987654321'),
-- (3, 'Alice Johnson', 'Bhaktapur', '123123123'),
-- (4, 'Robert Brown', 'Pokhara', '321321321'),
-- (5, 'Charlie White', 'Chitwan', '555555555'),
-- (6, 'Eve Green', 'Lalitpur', '666666666'),
-- (7, 'Grace Blue', 'Kathmandu', '777777777');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderid` INT(10) UNSIGNED NOT NULL,
  `customerid` INT(10) UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `address` VARCHAR(100) NOT NULL,
  `contact` VARCHAR(100) NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `order_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`orderid`)
)
ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `orders`
--

-- INSERT INTO `orders` (`orderid`, `customerid`, `amount`, `date`) VALUES
-- (1, 1, 60.00, '2024-11-15 00:00:00'),
-- (2, 2, 80.00, '2024-11-15 00:00:00'),
-- (3, 3, 60.00, '2024-11-15 00:00:00'),
-- (4, 4, 80.00, '2024-11-15 00:00:00'),
-- (5, 5, 65.00, '2024-11-15 00:00:00'),
-- (6, 6, 86.00, '2024-11-15 00:00:00'),
-- (7, 7, 65.00, '2024-11-15 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT UNSIGNED NOT NULL,
  `book_isbn` VARCHAR(20) NOT NULL,
  `book_price` DECIMAL(10, 2) NOT NULL,
  `quantity` INT NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`orderid`),
  FOREIGN KEY (`book_isbn`) REFERENCES `books` (`book_isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Dumping data for table `order_items`
--

-- INSERT INTO `order_items` (`id`, `orderid`, `book_isbn`, `item_price`, `quantity`) VALUES
-- (1, 1, '978-99933-527-10', 20.00, 2),
-- (2, 1, '978-99933-527-11', 20.00, 1),
-- (3, 2, '978-99933-527-13', 20.00, 3),
-- (4, 2, '978-99933-527-3', 20.00, 1),
-- (5, 3, '978-99933-527-4', 20.00, 2),
-- (6, 3, '978-99933-527-9', 20.00, 1),
-- (7, 4, '978-99933-527-7', 20.00, 1),
-- (8, 4, '978-99933-527-8', 20.00, 3),
-- (9, 5, '978-99933-527-6', 20.00, 2),
-- (10, 5, '978-99933-528-0', 25.00, 1),
-- (11, 6, '978-99933-528-1', 30.00, 1),
-- (12, 6, '978-99933-528-2', 28.00, 2),
-- (13, 7, '978-99933-528-3', 30.00, 1),
-- (14, 7, '978-99933-528-4', 35.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `publisher`
--

CREATE TABLE `publisher` (
  `publisherid` int(10) UNSIGNED NOT NULL PRIMARY key,
  `publisher_name` varchar(60) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

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


--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderid`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderid` (`orderid`),
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
-- ALTER TABLE `customers`
--   MODIFY `customerid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `orders` (`orderid`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`book_isbn`) REFERENCES `books` (`book_isbn`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
