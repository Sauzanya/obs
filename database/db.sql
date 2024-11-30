-- Database: `obs_db`

CREATE DATABASE IF NOT EXISTS `obs_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `obs_db`;

-- Table: admin
CREATE TABLE `admin` (
  `name` VARCHAR(20) NOT NULL,
  `pass` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`name`, `pass`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `admin` (`name`, `pass`) VALUES
('admin', 'f865b53623b121fd34ee5426c792e5c33af8c227');

-- Table: books
CREATE TABLE `books` (
  `book_isbn` VARCHAR(20) NOT NULL,
  `book_title` VARCHAR(60),
  `book_author` VARCHAR(60),
  `book_image` VARCHAR(40),
  `book_descr` TEXT,
  `book_price` DECIMAL(6,2) NOT NULL,
  `publisherid` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`book_isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `books` (`book_isbn`, `book_title`, `book_author`, `book_image`, `book_descr`, `book_price`, `publisherid`, `created_at`) VALUES
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
('978-99933-528-4', 'Software Engineering', 'I. Sommerville', 'software_engineering.jpg', 'A detailed guide to the software engineering process, covering topics such as software development models, project management, and software testing.', 35.00, 3, '2024-11-15 00:00:00'),
('9789993302100 ','Basanti','Diamond Shamsher Rana','vasanti.jpg','Basanti is a historical love story based on the novel by Diamond Shamsher.It depicts the story of a girl along with the accounts of the historical events during the time of Junga Bahadur Rana, the first Rana Prime Minister of Nepal.',350.00,5,'2024-11-16 00:00:00');





-- Table: customers
CREATE TABLE `customers` (
  `customerid` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(60) NOT NULL,
  `address` VARCHAR(80) NOT NULL,
  `contact` VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table: orders
CREATE TABLE `orders` (
  `orderid` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `customerid` INT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `address` VARCHAR(100) NOT NULL,
  `contact` VARCHAR(20) NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `order_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` VARCHAR(50) NOT NULL,
  FOREIGN KEY (`customerid`) REFERENCES `customers`(`customerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table: order_items
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT UNSIGNED NOT NULL,
  `book_isbn` VARCHAR(20) NOT NULL,
  `book_price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`orderid`),
  FOREIGN KEY (`book_isbn`) REFERENCES `books`(`book_isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table: publisher
CREATE TABLE `publisher` (
  `publisherid` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `publisher_name` VARCHAR(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `publisher` (`publisherid`, `publisher_name`) VALUES
(1, 'Publisher One'),
(2, 'Publisher Two'),
(3, 'Publisher Three');

ALTER TABLE books ADD COLUMN sales_count INT DEFAULT 0;

