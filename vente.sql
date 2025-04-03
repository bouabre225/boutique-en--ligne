-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 10:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vente`
--

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `detail` varchar(100) NOT NULL,
  `categorie` varchar(20) NOT NULL,
  `image` varchar(100) NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `remise` int(11) NOT NULL,
  `stock` int(11) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `detail`, `categorie`, `image`, `prix`, `remise`, `stock`) VALUES
(1, 'banane', 'trop bon', 'Fruit', 'images.jpg', 10, 1, 100),
(2, 'pomme', 'bon', 'Fruit', 'téléchargement (2).jpg', 10000, 0, 100),
(3, 'biscuit', 'bon bon', 'Legume', 'imgres.jpg', 123, 1, 100),
(4, 'kiwi', 'sale', 'Legume', 'WhatsApp Image 2024-04-30 à 07.57.09_2833ec1b.jpg', 1236, 1, 100),
(5, 'pinwheel', 'sss', 'Fruit', 'téléchargement (1).jpg', 123, 1, 100);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
