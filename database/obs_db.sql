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

-- Reset and recreate the database
DROP DATABASE IF EXISTS obs_db;
CREATE DATABASE obs_db;
USE obs_db;

-- --------------------------------------------------------
-- Table: admin
-- --------------------------------------------------------
CREATE TABLE `admin` (
  `name` VARCHAR(20) COLLATE latin1_general_ci NOT NULL,
  `pass` VARCHAR(40) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`name`, `pass`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------
-- Table: customers
-- --------------------------------------------------------
CREATE TABLE `customers` (
  `customerid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) COLLATE latin1_general_ci NOT NULL,
  `address` VARCHAR(80) COLLATE latin1_general_ci NOT NULL,
  `contact` VARCHAR(20) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`customerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------
-- Table: books
-- --------------------------------------------------------
CREATE TABLE `books` (
  `book_isbn` VARCHAR(20) COLLATE latin1_general_ci NOT NULL,
  `book_title` VARCHAR(60) COLLATE latin1_general_ci DEFAULT NULL,
  `book_author` VARCHAR(60) COLLATE latin1_general_ci DEFAULT NULL,
  `book_image` VARCHAR(40) COLLATE latin1_general_ci DEFAULT NULL,
  `book_descr` TEXT COLLATE latin1_general_ci DEFAULT NULL,
  `book_price` DECIMAL(6,2) NOT NULL,
  `publisherid` INT(10) UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`book_isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------
-- Table: orders
-- --------------------------------------------------------
CREATE TABLE `orders` (
  `orderid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customerid` INT(10) UNSIGNED NOT NULL,
  `amount` DECIMAL(6,2) DEFAULT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`orderid`),
  FOREIGN KEY (`customerid`) REFERENCES `customers` (`customerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------
-- Table: order_items
-- --------------------------------------------------------
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `orderid` INT(10) UNSIGNED NOT NULL,
  `book_isbn` VARCHAR(20) COLLATE latin1_general_ci NOT NULL,
  `item_price` DECIMAL(6,2) NOT NULL,
  `quantity` TINYINT(3) UNSIGNED NOT NULL,
  FOREIGN KEY (`orderid`) REFERENCES `orders` (`orderid`),
  FOREIGN KEY (`book_isbn`) REFERENCES `books` (`book_isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------
-- Table: publisher
-- --------------------------------------------------------
CREATE TABLE `publisher` (
  `publisherid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `publisher_name` VARCHAR(60) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`publisherid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------
-- Insert Sample Data
-- --------------------------------------------------------

-- Admin
INSERT INTO `admin` (`name`, `pass`) VALUES ('admin', 'f865b53623b121fd34ee5426c792e5c33af8c227');

-- Customers
INSERT INTO `customers` (`name`, `address`, `contact`) VALUES
('John Doe', '123 Main St', '123456789'),
('Jane Smith', '456 Elm St', '987654321'),
('Alice Johnson', '789 Oak St', '123123123');

-- Books
INSERT INTO books (book_isbn, book_title, book_author, book_image, book_descr, book_price, publisherid, created_at) VALUES
('978-99933-527-10', 'Mayur Times', 'Narayan Wagle', 'mayur_times.jpg', 'Mayur Times is a gripping thriller set in the context of investigative journalism, diving into corruption and politics.', '20.00', 2, '2024-11-15'),
('978-99933-527-11', 'Mad Country', 'Narayan Wagle', 'mad_country.jpg', 'Mad Country explores political turmoil and societal changes in Nepal, weaving personal struggles into larger national narratives.', '20.00', 2, '2024-11-15'),
('978-99933-527-13', 'Seto Bagh', 'Amar Neupane', 'seto_bagh.jpg', 'Seto Bagh narrates the story of the Rana regime in Nepal, focusing on intrigue, romance, and tragedy.', '20.00', 3, '2024-11-15'),
('978-99933-527-3', 'Basain', 'Shankar B. Thapa', 'basain.jpg', 'A poignant story of migration, loss, and the emotional struggles faced by people who leave their homes in rural Nepal for better opportunities.', '20.00', 2, '2024-11-15'),
('978-99933-527-4', 'Muna Madan', 'Laxmi Prasad Devkota', 'muna_madan.jpg', 'A timeless Nepali classic that portrays the love story of Muna and Madan, and their emotional and cultural journey.', '20.00', 1, '2024-11-15'),
('978-99933-527-9', 'Karnali Blues', 'Shankar Lamichhane', 'karnali_blues.jpg', 'A powerful and emotional story about the relationship between a father and son, set in the backdrop of the Karnali region of Nepal.', '20.00', 1, '2024-11-15'),
('978-99933-527-7', 'Seto Dharti', 'Jhamak Ghimire', 'seto_dharti.jpg', 'Seto Dharti tells the story of a woman’s struggles against the societal norms and her fight for identity and independence in a male-dominated society.', '20.00', 3, '2024-11-15'),
('978-99933-527-8', 'Palpasa Cafe', 'Narayan Wagle', 'palpasa_cafe.jpg', 'Palpasa Cafe is a gripping tale set against the backdrop of the Maoist insurgency in Nepal, with love, politics, and personal loss interwoven throughout.', '20.00', 2, '2024-11-15'),
('978-99933-527-6', 'Shirishko Phool', 'Parijat', 'shirishko_phool.jpg', 'This is a psychological novel about the struggles and alienation faced by a woman in a patriarchal society in Nepal.', '20.00', 4, '2024-11-15'),
('978-99933-528-0', 'Data Structures and Algorithms', 'R.K. Gupta', 'data_structures_algorithms.jpg', 'This book provides a comprehensive introduction to data structures and algorithms for BCA students.', '25.00', 6, '2024-11-15'),
('978-99933-528-1', 'Computer Networks', 'James Kurose', 'computer_networks.jpg', 'This book is an introduction to computer networks, providing detailed coverage of various networking technologies and protocols.', '30.00', 6, '2024-11-15'),
('978-99933-528-2', 'Operating System Concepts', 'Abraham Silberschatz', 'operating_system_concepts.jpg', 'A widely used textbook that covers the fundamental concepts of operating systems for BCA students.', '28.00', 6, '2024-11-15'),
('978-99933-528-3', 'Database Management Systems', 'Ramez Elmasri', 'dbms.jpg', 'This book offers an in-depth understanding of database management systems, covering various database models, design techniques, and SQL.', '30.00', 6, '2024-11-15'),
('978-99933-528-4', 'Software Engineering', 'I. Sommerville', 'software_engineering.jpg', 'A detailed guide to the software engineering process, covering topics such as software development models, project management, and software testing.', '35.00', 6, '2024-11-15');



-- Publishers
INSERT INTO `publisher` (`publisher_name`) VALUES
('Publisher One'),
('Publisher Two'),
('Publisher Three');

-- Orders
INSERT INTO `orders` (`customerid`, `amount`, `date`) VALUES
(1, 70.00, NOW()),
(2, 50.00, NOW());

-- Order Items
INSERT INTO `order_items` (`orderid`, `book_isbn`, `item_price`, `quantity`) VALUES
(1, '978-1-234-56789-0', 20.00, 2),
(1, '978-1-234-56789-1', 25.00, 1),
(2, '978-1-234-56789-2', 30.00, 1),
(2, '978-1-234-56789-0', 20.00, 1);

-- --------------------------------------------------------
-- Query for Admin Panel
-- --------------------------------------------------------

SELECT 
    orders.orderid,
    customers.name AS customer_name,
    customers.address AS customer_address,
    orders.amount AS order_total,
    orders.date AS order_date,
    books.book_title,
    books.book_author,
    books.book_price,
    order_items.item_price,
    order_items.quantity
FROM 
    orders
JOIN customers ON orders.customerid = customers.customerid
JOIN order_items ON orders.orderid = order_items.orderid
JOIN books ON order_items.book_isbn = books.book_isbn;
