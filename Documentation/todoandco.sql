-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 21 sep. 2022 à 13:40
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `todoandco`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220708095612', '2022-07-08 09:56:27', 105);

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_done` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `task`
--

INSERT INTO `task` (`id`, `created_at`, `title`, `content`, `author_id`, `is_done`) VALUES
(9, '2022-09-14 08:32:17', 'email1@test.come', 'User1 va faire une tâche 1totest', '1', 0),
(10, '2022-09-21 10:23:20', 'email2@test.com', 'User2 va faire une tâche 2e', '2', 0),
(11, '2022-09-06 13:35:42', 'email3@test.com', 'User3 va faire une tâche 3', '3', 0),
(12, '2022-09-06 13:35:42', 'email4@test.com', 'User4 va faire une tâche 4', '4', 0),
(13, '2022-09-06 13:35:42', 'email5@test.com', 'User5 va faire une tâche 5', '5', 0),
(14, '2022-09-06 13:35:42', 'email6@test.com', 'User6 va faire une tâche 6', '6', 1),
(15, '2022-09-06 13:35:42', 'email7@test.com', 'User7 va faire une tâche 7', '7', 0),
(16, '2022-09-06 13:35:42', 'email8@test.com', 'User8 va faire une tâche 8', '8', 0),
(17, '2022-09-06 13:35:42', 'email9@test.com', 'User9 va faire une tâche 9', '9', 0),
(18, '2022-09-06 13:35:42', 'email10@test.com', 'User10 va faire une tâche 10', '10', 1),
(19, '2022-09-12 14:35:21', 'test de la tâche', 'zefzefzef', '28', 0),
(20, '2022-09-12 14:37:30', 'test de la tâche', 'tyjty', '28', 0),
(21, '2022-09-13 07:45:51', 'test de la tâche', 'ezffzefze', '18', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `username`) VALUES
(18, 'email1@test.com', '[\"ROLE_USER\", \"ROLE_CUSTOMER\"]', '$2y$13$tcRfFUVtQCqGV5rcyTpB7OGjcDcmdNQArBNbkGkTtMuABo79I.g2i', 'User1'),
(19, 'email2@test.com', '[\"ROLE_USER\"]', '597f0ce6d11567cc691b3f5df35594cb', 'User2'),
(20, 'email3@test.com', '[\"ROLE_USER\"]', '887d85a74cb6ad0ae098e92318f2b283', 'User3'),
(21, 'email4@test.com', '[\"ROLE_USER\"]', '4305dc076b3ba2bf8d55524cddf5a72d', 'User4'),
(22, 'email5@test.com', '[\"ROLE_USER\"]', '1da0c5bca25615eea13618e8d5895048', 'User5'),
(23, 'email6@test.com', '[\"ROLE_USER\"]', '89f733698e5360121e98acf7477bb5fa', 'User6'),
(24, 'email7@test.com', '[\"ROLE_USER\"]', '1c97403c20d90b9695c3eb7227d41fbf', 'User7'),
(25, 'email8@test.com', '[\"ROLE_USER\"]', '562364e06744a39a8078728c4da6031d', 'User8'),
(26, 'email9@test.com', '[\"ROLE_USER\"]', 'aaaa', 'User9'),
(27, 'email10@test.com', '[\"ROLE_USER\"]', '54092341ec4e8bea001560627b007695', 'User10'),
(28, 'cyril@glanum.com', '[\"ROLE_ADMIN\", \"ROLE_USER\", \"ROLE_CUSTOMER\"]', '$2y$13$tcRfFUVtQCqGV5rcyTpB7OGjcDcmdNQArBNbkGkTtMuABo79I.g2i', 'flaskiiiizz'),
(29, 'cyril@glanum.comaaaaa', '[\"ROLE_USER\"]', '$2y$13$D7prlMVIc9n8zsJ8rajIUeWP.RfEKwzxmvv7SwLCdu4HybOTrARv6', 'cyril@glanum.comaaaa');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
