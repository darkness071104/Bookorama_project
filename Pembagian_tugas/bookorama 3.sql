-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2023 at 07:48 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookorama`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminid` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminid`, `email`, `password`) VALUES
(1, 'michelle@yahoo.com', '827ccb0eea8a706c4c34a16891f84e7b');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `isbn` varchar(13) NOT NULL,
  `author` varchar(50) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `price` float(4,2) DEFAULT NULL,
  `categoryid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`isbn`, `author`, `title`, `price`, `categoryid`) VALUES
('0-672-31697-8', 'Michael Morgan', 'Java 2 for Professional Developers', 34.99, 1),
('0-672-31745-1', 'Thomas Down', 'Installing Debian GNU/Linux', 24.99, 1),
('0-672-31509-2', 'Pruitt, et al.', 'Teach Yourself GIMP in 24 Hours', 24.99, 1),
('0-672-31769-9', 'Thomas Schenk', 'Caldera OpenLinux System Administration Unleashed', 49.99, 1),
('0-672-31281-1', 'Clarita Michelle', 'Jakarta dan Debu Adalah Kawan', 66.99, 2),
('978-623-306-1', 'Abdullah Fingki', 'Mount Lemongan horror story', 25.99, 5),
('978-074329890', 'John Connolly', 'The Book of Lost Things', 40.00, 4),
('978-602-260-1', 'R. A. Kartini', 'Habis gelap terbitlah terang', 5.14, 8),
('1912918560', 'Christopher Lloyd', 'Britannica All New Kids Encyclopedia: What We Know & What We Don\'t', 23.69, 3),
('978-125006611', 'Michael Greger M.D. FACLM & Gene Stone', 'How Not to Die: Discover the Foods Scientifically Proven to Prevent and Reverse Disease', 18.29, 11),
('978-198211735', 'Siddhartha Mukherjee', 'The Song of the Cell: An Exploration of Medicine and the New Human', 17.89, 10),
('978-144722279', 'Malcolm Bradbury', 'Eating People is Wrong', 36.85, 9),
('978-031642074', 'Nikita Gill', 'Fierce Fairytales: Poems and Stories to Stir Your Soul', 11.63, 7),
('978-198488098', 'Richard Osman', 'The Thursday Murder Club: A Novel (A Thursday Murder Club Mystery)', 10.49, 6);

-- --------------------------------------------------------

--
-- Table structure for table `book_reviews`
--

CREATE TABLE `book_reviews` (
  `isbn` char(13) NOT NULL,
  `review` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `book_reviews`
--

INSERT INTO `book_reviews` (`isbn`, `review`) VALUES
('0-672-31697-8', 'Morgan\'s book is clearly written and goes well beyond \r\n                     most of the basic Java books out there.'),
('0-672-31769-9', 'This is a very well written book covering topics that are more advanced than the usual beginners book. Topics are covered very clearly, and are written in a manner that will help anyone in their progression of using and administrating Caldera OpenLinux.'),
('978-125006611', 'Excellent. Excellent. Read this book and prepare to fall in love with fruits and vegetables, beans, nuts, seeds and whole grains. Love spices: turmeric, cinnamon, oregano, cumin, cardamom, saffron. How could you not?');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryid`, `name`) VALUES
(1, 'Programming'),
(2, 'Novel'),
(3, 'Encyclopedia'),
(4, 'Fantasy'),
(5, 'Horror'),
(6, 'Mistery'),
(7, 'Fairytale'),
(8, 'History'),
(9, 'Humor'),
(10, 'Science'),
(11, 'Health');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customerid` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customerid`, `name`, `address`, `city`) VALUES
(1, 'Michelle Arthur', '357 North Road A', 'Yarraville'),
(2, 'Anne', 'Boulevard \' Street', 'Box Hill'),
(3, 'Melly', 'Vicoria Street', 'Airport West'),
(4, 'Rose', 'Queen Street', 'Airport West'),
(5, 'Alan Wonga', 'Buanglow Street', 'Airport West'),
(6, 'Dionysius Mario', 'Jl. Nangka IV No. 39, RT 04/RW 01, Cengkareng Barat', 'Box Hill');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderid` int(10) UNSIGNED NOT NULL,
  `customerid` int(10) UNSIGNED NOT NULL,
  `amount` float(6,2) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderid`, `customerid`, `amount`, `date`) VALUES
(1, 3, 69.98, '2000-04-02'),
(2, 1, 49.99, '2000-04-15'),
(3, 2, 74.98, '2000-04-19'),
(4, 3, 24.99, '2000-05-01');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `orderid` int(10) UNSIGNED NOT NULL,
  `isbn` char(13) NOT NULL,
  `quantity` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`orderid`, `isbn`, `quantity`) VALUES
(1, '0-672-31697-8', 2),
(2, '0-672-31769-9', 1),
(3, '0-672-31769-9', 1),
(3, '0-672-31509-2', 1),
(4, '0-672-31745-1', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`isbn`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indexes for table `book_reviews`
--
ALTER TABLE `book_reviews`
  ADD PRIMARY KEY (`isbn`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryid`);

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
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`orderid`,`isbn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customerid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
