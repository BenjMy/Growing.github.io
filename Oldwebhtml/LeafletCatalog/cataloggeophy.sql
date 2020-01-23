-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 20 jan. 2020 à 16:44
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `test`
--

-- --------------------------------------------------------

--
-- Structure de la table `cataloggeophy`
--

DROP TABLE IF EXISTS `cataloggeophy`;
CREATE TABLE IF NOT EXISTS `cataloggeophy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `x` float DEFAULT NULL,
  `y` float DEFAULT NULL,
  `Method` varchar(255) NOT NULL,
  `Organisation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cataloggeophy`
--

INSERT INTO `cataloggeophy` (`id`, `x`, `y`, `Method`, `Organisation`) VALUES
(1, 45.408, 11.8859, 'ERT', 'UNIPD'),
(2, 36.8625, 10.1956, 'ERT', 'CERTE'),
(3, 37.8717, -122.273, 'IP', 'LBL'),
(4, 37.8717, -122.273, 'ERT', 'LBL');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
