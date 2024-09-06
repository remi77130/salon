-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 20 août 2024 à 19:37
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `messagerie`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `department` varchar(50) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `avatar`, `age`, `department`, `ville`, `gender`, `created_at`) VALUES
(11, 'test06', 'uploads/66bb19ce14a2c3.92968662.png', 89, '89', '', 'male', '2024-08-13 08:31:10'),
(12, 'test07', 'uploads/66bb1cac1de700.66172320.png', 89, '77', '', 'male', '2024-08-13 08:43:24'),
(13, 'test08', 'uploads/66bb21d1e2ff52.73784314.jpg', 89, '77', '', 'female', '2024-08-13 09:05:21'),
(14, 'JEAN', 'uploads/66bb5db0e6a5f5.35231789.png', 18, '77', '', 'male', '2024-08-13 13:20:48'),
(15, 'decerver', 'uploads/66bdb9c434fe73.77460919.jpg', 78, '77', '', 'male', '2024-08-15 08:18:12'),
(16, 'frve', 'uploads/66beff554d0d11.46068345.png', 89, '77', '', 'male', '2024-08-16 07:27:17'),
(17, 'Kevin07', 'uploads/66beff8d3444f8.27475999.png', 89, '77', '', 'male', '2024-08-16 07:28:13'),
(18, 'sandra92', 'uploads/66bf015d5a8608.12823859.png', 38, '77', '', 'female', '2024-08-16 07:35:57'),
(19, 'ddddddd', 'uploads/66bf05cc6437b2.14110265.png', 77, '77', '', 'male', '2024-08-16 07:54:52'),
(20, 'jeremy77', 'uploads/66bf064f391b54.75849274.png', 77, '77', '', 'male', '2024-08-16 07:57:03'),
(21, 'test07', 'uploads/66bf074498c986.65038113.jpg', 38, '77', '', 'male', '2024-08-16 08:01:08'),
(22, 'test07', 'uploads/66c0aeaa829873.41966633.jpg', 38, '77', '', 'male', '2024-08-17 14:07:38'),
(23, 'test07', 'uploads/66c0b0b7c43284.38287922.jpg', 88, '77', '', 'male', '2024-08-17 14:16:23'),
(24, 'jeremy77', 'uploads/66c0b0e1a21605.55235463.png', 77, '77', '', 'male', '2024-08-17 14:17:05'),
(25, 'test07', 'uploads/66c0b166bab735.00220232.png', 77, '77', '', 'male', '2024-08-17 14:19:18'),
(26, 'test07', 'uploads/66c0b191e58596.89999698.png', 77, '77', '', 'male', '2024-08-17 14:20:01'),
(27, 'ddu', 'uploads/66c0b6b0219b24.80638487.png', 88, '77', '', 'male', '2024-08-17 14:41:52'),
(28, 'ddu', 'uploads/66c0b6bb7d22e9.26306107.png', 88, '77', '', 'male', '2024-08-17 14:42:03'),
(29, 'test07', 'uploads/66c0b6e652f704.40145180.png', 77, '77', '', 'male', '2024-08-17 14:42:46'),
(30, 'test07', 'uploads/66c0b7c723b5d1.25193910.png', 77, '77', '', 'male', '2024-08-17 14:46:31'),
(31, 'test07', 'uploads/66c0b872bad828.50069422.png', 77, '77', '', 'male', '2024-08-17 14:49:22'),
(32, 'test07', 'uploads/66c0b925575866.96989609.png', 77, '77', '', 'male', '2024-08-17 14:52:21'),
(33, 'test07', 'uploads/66c0b953e4b0e6.89065635.png', 44, '77', '', 'male', '2024-08-17 14:53:07'),
(34, 'test07', 'uploads/66c0b9e2aec6b6.61289491.png', 44, '77', '', 'male', '2024-08-17 14:55:30'),
(35, 'test078', 'uploads/66c0bb2601bf13.66032408.png', 77, '77', '', 'male', '2024-08-17 15:00:54'),
(36, 'pleturre', 'uploads/66c1ba2bafb2e6.28821143.png', 34, '77130', '', 'female', '2024-08-18 09:08:59'),
(37, 'test07(t', 'uploads/66c1bd808a82a5.45524067.png', 34, '77130', '', 'male', '2024-08-18 09:23:12'),
(38, 'fhgfhgf', 'uploads/66c1c1b3301c09.64001325.png', 88, '13', '', 'male', '2024-08-18 09:41:07'),
(39, 'vhyhh', 'uploads/66c1c2205934f7.76807106.png', 55, '75', '', 'male', '2024-08-18 09:42:56'),
(40, 'jhytjt', 'uploads/66c1d2e7e5d386.30515606.png', 86, '77130', '', 'male', '2024-08-18 10:54:31'),
(41, 'gththt', 'uploads/66c1d895e916b4.22076058.png', 85, '77130', '', 'male', '2024-08-18 11:18:45'),
(42, 'l:m!po', 'uploads/66c1d9b8590c33.27170023.png', 88, '85', '', 'male', '2024-08-18 11:23:36'),
(43, 'fjfjff', 'uploads/66c2216f13cef0.48399992.png', 88, '85180', '', 'male', '2024-08-18 16:29:35'),
(44, 'jyjuyj', 'uploads/66c2e586ca1c00.00132648.png', 88, '85185', '', 'male', '2024-08-19 06:26:14'),
(45, 'ggggg', 'uploads/66c2e8c30eee63.90977997.png', 55, '85', '', 'male', '2024-08-19 06:40:03'),
(46, 'HHHHH', 'uploads/66c3a494cfd2a4.49828037.png', 58, '85180', '', 'male', '2024-08-19 20:01:24'),
(47, 'gthtrhtr', 'uploads/66c3b375ba1a53.07193662.png', 32, '2122', '', 'male', '2024-08-19 21:04:53'),
(48, 'iukiki', 'uploads/66c46d6268ce10.11910723.png', 88, '91', '', 'male', '2024-08-20 10:18:10'),
(49, 'vgrbrb', 'uploads/66c4862f71ca33.21639767.png', 89, '31780', '', 'female', '2024-08-20 12:03:59'),
(50, 'deverver', 'uploads/66c487b3866503.90669040.png', 45, '45100', '', 'male', '2024-08-20 12:10:27'),
(51, 'deverv', 'uploads/66c48d376b49b5.27567736.png', 45, '45100', '', 'male', '2024-08-20 12:33:59'),
(52, 'czscze', 'uploads/66c48dfbc3b3f3.83384140.png', 88, '85180', '', 'male', '2024-08-20 12:37:15'),
(53, 'czsczd', 'uploads/66c491705157d5.27718455.png', 87, '77130', '', 'male', '2024-08-20 12:52:00'),
(54, 'tamere', 'uploads/66c4d3b0dbea59.90655913.png', 28, '85180', '', 'male', '2024-08-20 17:34:40');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
