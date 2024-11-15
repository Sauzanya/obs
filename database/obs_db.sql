-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2022 at 10:46 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `obs_db`
--

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
CREATE TABLE user_activity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    action_type ENUM('view', 'purchase'),
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP)
;

INSERT INTO user_activity (user_id, book_id, action_type, timestamp) VALUES
(1, 1, 'view', '2024-11-15 10:00:00'),   -- User 1 viewed book with ID 1 (Karnali Blues)
(2, 2, 'purchase', '2024-11-15 10:05:00'), -- User 2 purchased book with ID 2 (Palpasa Cafe)
(3, 3, 'view', '2024-11-15 10:10:00'),    -- User 3 viewed book with ID 3 (Seto Dharti)
(4, 4, 'purchase', '2024-11-15 10:15:00'), -- User 4 purchased book with ID 4 (Shirishko Phool)
(5, 5, 'view', '2024-11-15 10:20:00'),    -- User 5 viewed book with ID 5 (Famous Five)
(6, 6, 'purchase', '2024-11-15 10:25:00'), -- User 6 purchased book with ID 6 (Sambodhan)
(7, 7, 'view', '2024-11-15 10:30:00'),    -- User 7 viewed book with ID 7 (Jeevan Katha)
(8, 8, 'purchase', '2024-11-15 10:35:00'), -- User 8 purchased book with ID 8 (Hami Yestai Ta Ho Nepal)
(1, 9, 'purchase', '2024-11-15 10:40:00'), -- User 1 purchased book with ID 9 (Raatko Paat Jhaar)
(2, 10, 'view', '2024-11-15 10:45:00');    -- User 2 viewed book with ID 10 (Dhauko Manchhe)

CREATE TABLE `books` (
  `book_isbn` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `book_title` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `book_author` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `book_image` varchar(40) COLLATE latin1_general_ci DEFAULT NULL,
  `book_descr` text COLLATE latin1_general_ci DEFAULT NULL,
  `book_price` decimal(6,2) NOT NULL,
  `publisherid` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `books`
--

IINSERT INTO books (book_isbn, book_title, book_author, book_image, book_descr, book_price, publisherid, created_at) VALUES
('978-99933-527-9', 'Karnali Blues', 'Shankar Lamichhane', 'karnali_blues.jpg', 'A powerful and emotional story about the relationship between a father and son, set in the backdrop of the Karnali region of Nepal.', '20.00', 1, '2024-11-15'),
('978-99933-527-8', 'Palpasa Cafe', 'Narayan Wagle', 'palpasa_cafe.jpg', 'Palpasa Cafe is a gripping tale set against the backdrop of the Maoist insurgency in Nepal, with love, politics, and personal loss interwoven throughout.', '20.00', 2, '2024-11-15'),
('978-99933-527-7', 'Seto Dharti', 'Jhamak Ghimire', 'seto_dharti.jpg', 'Seto Dharti tells the story of a woman’s struggles against the societal norms and her fight for identity and independence in a male-dominated society.', '20.00', 3, '2024-11-15'),
('978-99933-527-6', 'Shirishko Phool', 'Parijat', 'shirishko_phool.jpg', 'This is a psychological novel about the struggles and alienation faced by a woman in a patriarchal society in Nepal.', '20.00', 4, '2024-11-15'),
('978-99933-527-5', 'Jeevan Antim', 'Parijat', 'jeevan_antim.jpg', 'Jeevan Antim is another powerful tale by Parijat, a heart-wrenching story about love, life, and death.', '20.00', 4, '2024-11-15'),
('978-0-999-23456-7', 'Sambodhan', 'Buddhiman Shrestha', 'sambodhan.jpg', 'Sambodhan is a philosophical novel that explores the deeper meanings of life, death, and human nature through introspective characters and reflective dialogue.', '20.00', 6, '2024-11-15'),
('978-0-999-23456-8', 'Jeevan Katha', 'Sujan Poudel', 'jeevan_katha.jpg', 'Jeevan Katha is a personal narrative that dives into the challenges of identity, survival, and the pursuit of meaning in a changing world.', '20.00', 7, '2024-11-15'),
('978-0-999-23456-9', 'Raatko Paat Jhaar', 'Hari Bansha Acharya', 'raatko_paat.jpg', 'Raatko Paat Jhaar is a contemporary novel that highlights the internal struggles of a young man, reflecting on the broader societal and personal challenges in Nepal.', '20.00', 9, '2024-11-15'),
('978-0-999-23457-0', 'Dhauko Manchhe', 'Bishnu Kumari Waiba', 'dhauko_manchhe.jpg', 'Dhauko Manchhe explores themes of love, human emotions, and relationships, set in the rural backdrop of Nepal.', '20.00', 10, '2024-11-15'),
('978-0-999-23457-1', 'Chhiso Paani', 'Bishnu Kumari Waiba', 'chhiso_paani.jpg', 'Chhiso Paani is another beautiful work by Bishnu Kumari Waiba that delves into human emotions, particularly dealing with familial ties and struggles.', '20.00', 10, '2024-11-15'),
('978-99933-527-4', 'Muna Madan', 'Laxmi Prasad Devkota', 'muna_madan.jpg', 'A timeless Nepali classic that portrays the love story of Muna and Madan, and their emotional and cultural journey.', '20.00', 1, '2024-11-15'),
('978-99933-527-3', 'Basain', 'Shankar B. Thapa', 'basain.jpg', 'A poignant story of migration, loss, and the emotional struggles faced by people who leave their homes in rural Nepal for better opportunities.', '20.00', 2, '2024-11-15'),
('978-99933-527-2', 'Rupa Rani', 'Nepal Bhasa', 'rupa_rani.jpg', 'Rupa Rani tells a story of a woman’s fight for love, social acceptance, and freedom in a society burdened by restrictions on women.', '20.00', 3, '2024-11-15'),
('978-99933-527-1', 'Jiwanko Yatra', 'Bhanu A. Acharya', 'jiwanko_yatra.jpg', 'A novel about life’s journey, examining the philosophical and emotional aspects of human experience from a Nepali perspective.', '20.00', 4, '2024-11-15'),
('978-99933-527-0', 'Buddha and His Dhamma', 'Dr. B.R. Ambedkar', 'buddha_dhamma.jpg', 'This is the life story of Siddhartha Gautama, the Buddha, and his teachings of Dhamma, presented in the light of modern perspectives on human society and equality.', '20.00', 5, '2024-11-15'),
('978-99933-527-10', 'Mayur Times', 'Narayan Wagle', 'mayur_times.jpg', 'Mayur Times is a gripping thriller set in the context of investigative journalism, diving into corruption and politics.', '20.00', 2, '2024-11-15'),
('978-99933-527-11', 'Mad Country', 'Narayan Wagle', 'mad_country.jpg', 'Mad Country explores political turmoil and societal changes in Nepal, weaving personal struggles into larger national narratives.', '20.00', 2, '2024-11-15'),
('978-99933-527-12', 'Kalilo Man', 'Narayan Wagle', 'kalilo_man.jpg', 'A poignant tale about the resilience and emotions of people amidst changing societal dynamics in Nepal.', '20.00', 2, '2024-11-15'),
('978-99933-527-13', 'Seto Bagh', 'Amar Neupane', 'seto_bagh.jpg', 'Seto Bagh narrates the story of the Rana regime in Nepal, focusing on intrigue, romance, and tragedy.', '20.00', 3, '2024-11-15'),
('978-99933-527-14', 'Paniko Gham', 'Amar Neupane', 'paniko_gham.jpg', 'A philosophical tale about human resilience and the pursuit of light in times of darkness.', '20.00', 3, '2024-11-15'),
('978-99933-527-15', 'Aakash Ko Thiyo', 'Jhamak Ghimire', 'aakash_ko_thiyo.jpg', 'Aakash Ko Thiyo tells the life story of a woman’s journey in overcoming adversity and her passion for writing amidst personal challenges.', '20.00', 3, '2024-11-15');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customerid` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `address` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `city` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `zip_code` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `country` varchar(60) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customerid`, `name`, `address`, `city`, `zip_code`, `country`) VALUES
(1, 'a', 'a', 'a', 'a', 'a'),
(2, 'b', 'b', 'b', 'b', 'b'),
(3, 'test', '123 test', 'test city', '12345', 'test country'),
(4, 'Meet Dahal', 'Sample Street', 'Sample City', '12345', 'Sample Country'),
(5, 'Shriti', 'Sample Street', 'Sample City', '12345', 'Sample Country'),
(6, 'Rachu Rizal', 'Lalitpur', 'Lalitpur City', '12345', 'Nepal'),
(7, 'Mark Cooper', 'Laltpur', 'Lalitpur City', '12345', 'Nepal'),
(8, 'Samantha Miller', 'Sample Street', 'Sample City', '12345', 'Sample Country');
-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderid` int(10) UNSIGNED NOT NULL,
  `customerid` int(10) UNSIGNED NOT NULL,
  `amount` decimal(6,2) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `ship_name` char(60) COLLATE latin1_general_ci NOT NULL,
  `ship_address` char(80) COLLATE latin1_general_ci NOT NULL,
  `ship_city` char(30) COLLATE latin1_general_ci NOT NULL,
  `ship_zip_code` char(10) COLLATE latin1_general_ci NOT NULL,
  `ship_country` char(20) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderid`, `customerid`, `amount`, `date`, `ship_name`, `ship_address`) VALUES
(1, 1, '60.00', '2015-12-03 13:30:12', 'a', 'a'),
(2, 2, '60.00', '2015-12-03 13:31:12', 'b', 'b'),
(3, 3, '20.00', '2015-12-03 19:34:21', 'test', '123 test'),
(4, 1, '20.00', '2015-12-04 10:19:14', 'a', 'a'),
(5, 4, '80.00', '2022-06-21 00:09:36', 'Mark Cooper', 'Sample Street'),
(6, 5, '220.00', '2022-06-21 01:35:16', 'Mark', 'Sample Street'),
(7, 6, '20.00', '2022-06-21 01:38:20', 'Richa Rizal', 'Lalitpur'),
(8, 7, '20.00', '2022-06-21 01:40:28', 'Sandy', 'Laltpur'),
(9, 8, '80.00', '2022-06-21 01:42:56', 'Samantha', 'Sample Street');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `orderid` int(10) UNSIGNED NOT NULL,
  `book_isbn` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `item_price` decimal(6,2) NOT NULL,
  `quantity` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO order_items (orderid, book_isbn, item_price, quantity) VALUES
(1, '978-99933-527-9', '200.00', 1),
(1, '978-99933-527-8', '250.00', 1),
(1, '978-99933-527-7', '300.00', 1),
(2, '978-99933-527-9', '400.00', 1),
(2, '978-99933-527-8', '20.00', 1),
(2, '978-99933-527-7', '20.00', 1),
(3, '978-0-999-23456-7', '20.00', 1),
(1, '978-99933-527-7', '20.00', 1),
(5, '978-0-999-23456-7', '20.00', 2),
(5, '978-0-999-23456-8', '20.00', 1),
(5, '978-0-999-23456-9', '20.00', 1),
(6, '978-0-999-23456-7', '20.00', 10),
(6, '978-0-999-23456-8', '20.00', 1),
(7, '978-0-999-23457-0', '20.00', 1),
(8, '978-0-999-23457-1', '20.00', 1),
(9, '978-99933-527-8', '20.00', 4),
(10, '978-99933-527-12', '20.00', 1),  -- Kalilo Man by Narayan Wagle
(11, '978-99933-527-13', '20.00', 1),  -- Seto Bagh by Amar Neupane
(12, '978-99933-527-14', '20.00', 1);  -- Paniko Gham by Amar Neupane


-- --------------------------------------------------------

--
-- Table structure for table `publisher`
--

CREATE TABLE `publisher` (
  `publisherid` int(10) UNSIGNED NOT NULL,
  `publisher_name` varchar(60) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `publisher`
--

INSERT INTO `publisher` (`publisherid`, `publisher_name`) VALUES
(1, 'Publisher 1'),
(2, 'Publisher 2'),
(3, 'Publisher 3'),
(4, 'Publisher 4'),
(5, 'Publisher 5'),
(6, 'Publisher 6');

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
  ADD PRIMARY KEY (`orderid`);

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
  MODIFY `customerid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `publisher`
--
ALTER TABLE `publisher`
  MODIFY `publisherid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;
