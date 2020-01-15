-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 15, 2020 at 12:15 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wurqe`
--

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Cleaning Service', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(2, 'Child Care', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(3, 'Elderly Care', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(4, 'Electrician', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(5, 'Food Truck', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(6, 'Caterer', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(7, 'Baker', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(8, 'Gardener', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(9, 'Landscaping Service', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(10, 'Pet grooming Service', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(11, 'Home Staging', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(12, 'Home Painting', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(13, 'Handyman', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(14, 'Printing', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(15, 'Direct Mailer', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(16, 'Personal Shopper', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(17, 'Event Planner', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(18, 'Errand Service', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(19, 'Food Delivery', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(20, 'Florist', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(21, 'Appliance Repair', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(22, 'Coaching ', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(23, 'Courier', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(24, 'Learning', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(25, 'Decoration', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(26, 'Disposal', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(27, 'Plumbing', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(28, 'DJing', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(29, 'Furniture / Design/Assembly/ Removal', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(30, 'Academic Writing', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(31, 'Tutor', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(32, 'Dog Walker', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(33, 'Mobile Retail Boutique', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(34, 'Car Wash ', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(35, ' Farming', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(36, 'Tour Guide', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(37, 'Beautify', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(38, 'Barber ', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(39, 'Security Service', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(40, 'Artist', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(41, 'Home decor', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(42, 'Home Chores ', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(43, 'Massage Therapy', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(44, 'Personal Trainer', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(45, 'Dance Classes', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(46, 'Music Lessons', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(47, 'Moving Service', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(48, 'Tax Preparation', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(49, 'Hair Salon - ladies and gents', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(50, 'Hair Stylist', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(51, 'Health Care', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(52, 'Photography/Videography ', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(53, 'Research', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(54, 'Legal Practitioner', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(55, 'Local Job', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(56, 'Achitech ', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(57, 'Reseller', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(58, 'Retail Sales', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(59, 'Scripting', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(60, 'Shipping', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(61, 'Engineering', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(62, 'Training', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51'),
(63, 'Voice Artist', NULL, '2020-01-15 10:13:51', '2020-01-15 10:13:51');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
