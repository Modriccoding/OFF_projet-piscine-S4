-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 01 juin 2024 à 12:03
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ece_in`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

DROP TABLE IF EXISTS `administrateurs`;
CREATE TABLE IF NOT EXISTS `administrateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text,
  `date_naissance` date DEFAULT NULL,
  `role` enum('admin') DEFAULT 'admin',
  `photo_profil` varchar(255) DEFAULT NULL,
  `photo_mur` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id`, `pseudo`, `email`, `mot_de_passe`, `nom`, `photo`, `bio`, `date_naissance`, `role`, `photo_profil`, `photo_mur`) VALUES
(1, 'ADMIN', 'admin@example.com', '$2y$10$jiG7mxkAwvYOHBfchr9lpOw597UIANfo9f3enXG2cWStgU.pW6pbe', 'ADMIN', '', 'ADMIN bio', '1980-01-01', 'admin', 'uploads/Capture d\'écran 2024-02-26 091131.png', 'uploads/104109961-artificial-green-grass-floor-texture-background-top-view.webp');

-- --------------------------------------------------------

--
-- Structure de la table `albums`
--

DROP TABLE IF EXISTS `albums`;
CREATE TABLE IF NOT EXISTS `albums` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `admin_id` int DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `albums`
--

INSERT INTO `albums` (`id`, `utilisateur_id`, `admin_id`, `photo`, `uploaded_at`) VALUES
(1, 14, NULL, 'uploads/hl-17939055313.jpg', '2024-05-31 22:04:17'),
(2, 14, NULL, 'uploads/hl-17939055314.jpg', '2024-05-31 22:04:17'),
(3, 14, NULL, 'uploads/hl-17939055317.jpg', '2024-05-31 22:04:17'),
(11, NULL, 1, 'uploads/Capture d\'écran 2023-05-21 150055.png', '2024-05-31 22:39:45'),
(8, 6, NULL, 'uploads/Capture d\'écran 2024-06-01 002106.png', '2024-05-31 22:22:26'),
(9, 6, NULL, 'uploads/Capture d\'écran 2024-06-01 002122.png', '2024-05-31 22:22:26'),
(10, 6, NULL, 'uploads/Capture d\'écran 2024-06-01 002134.png', '2024-05-31 22:22:26');

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

DROP TABLE IF EXISTS `amis`;
CREATE TABLE IF NOT EXISTS `amis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `friend_id` int NOT NULL,
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `friend_id` (`friend_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `amis`
--

INSERT INTO `amis` (`id`, `user_id`, `friend_id`, `date_ajout`) VALUES
(1, 0, 13, '2024-05-28 14:13:23'),
(2, 13, 0, '2024-05-28 14:13:23'),
(3, 0, 13, '2024-05-28 14:13:46'),
(4, 13, 0, '2024-05-28 14:13:46'),
(5, 0, 13, '2024-05-28 14:14:03'),
(6, 13, 0, '2024-05-28 14:14:03'),
(7, 6, 13, '2024-05-28 14:16:26'),
(16, 6, 14, '2024-05-28 15:30:02'),
(18, 1, 6, '2024-05-31 23:54:49');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `photo_id` int NOT NULL,
  `commentaire` text NOT NULL,
  `date_commentaire` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `photo_id` (`photo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `utilisateur_id`, `photo_id`, `commentaire`, `date_commentaire`) VALUES
(1, 6, 3, 'trop beau ', '2024-05-31 22:48:34'),
(2, 6, 2, 'haha', '2024-05-31 22:48:46'),
(3, 14, 8, 'hihi', '2024-05-31 23:01:21'),
(4, 6, 1, 'bg ', '2024-05-31 23:42:02'),
(5, 6, 1, 'gt', '2024-06-01 00:10:53'),
(6, 6, 2, 'haha', '2024-06-01 00:10:59');

-- --------------------------------------------------------

--
-- Structure de la table `demandes_amis`
--

DROP TABLE IF EXISTS `demandes_amis`;
CREATE TABLE IF NOT EXISTS `demandes_amis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `demandeur_id` int NOT NULL,
  `destinataire_id` int NOT NULL,
  `date_demande` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `demandeur_id` (`demandeur_id`),
  KEY `destinataire_id` (`destinataire_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `demandes_amis`
--

INSERT INTO `demandes_amis` (`id`, `demandeur_id`, `destinataire_id`, `date_demande`) VALUES
(2, 14, 13, '2024-05-28 15:08:39');

-- --------------------------------------------------------

--
-- Structure de la table `emplois`
--

DROP TABLE IF EXISTS `emplois`;
CREATE TABLE IF NOT EXISTS `emplois` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `entreprise` varchar(100) NOT NULL,
  `localisation` varchar(100) NOT NULL,
  `type` enum('CDI','CDD','stage','apprentissage') NOT NULL,
  `date_publication` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `emplois`
--

INSERT INTO `emplois` (`id`, `titre`, `description`, `entreprise`, `localisation`, `type`, `date_publication`) VALUES
(1, 'Développeur Web', 'Recherche développeur web pour un projet innovant.', 'Tech Corp', 'Paris', 'CDI', '2024-05-30 00:00:00'),
(2, 'Data Scientist', 'Nous cherchons un data scientist pour notre équipe de recherche.', 'Data Inc', 'Lyon', 'CDI', '2024-05-28 00:00:00'),
(3, 'Ingénieur DevOps', 'Nous recherchons un ingénieur DevOps pour optimiser nos pipelines CI/CD et améliorer notre infrastructure cloud.', 'Cloud Innovators', 'Paris', 'CDI', '2024-06-01 00:00:00'),
(4, 'Développeur Full-Stack', 'Développeur Full-Stack expérimenté pour travailler sur des projets innovants utilisant Node.js et React.', 'Tech Solutions', 'Lyon', 'CDI', '2024-06-01 00:00:00'),
(5, 'Architecte Cloud', 'Architecte Cloud recherché pour concevoir et implémenter des solutions cloud évolutives.', 'Cloud Masters', 'Marseille', 'CDI', '2024-06-01 00:00:00'),
(6, 'Ingénieur en Sécurité Informatique', 'Ingénieur en Sécurité Informatique pour garantir la sécurité de nos systèmes et données.', 'Secure Tech', 'Nice', 'CDI', '2024-06-01 00:00:00'),
(7, 'Analyste Big Data', 'Analyste Big Data pour analyser des ensembles de données volumineux et fournir des insights exploitables.', 'Data Insights', 'Toulouse', 'CDI', '2024-06-01 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE IF NOT EXISTS `evenements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_event` date NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `evenements`
--

INSERT INTO `evenements` (`id`, `titre`, `description`, `date_event`, `image_path`) VALUES
(1, 'Conférence IA', 'Une conférence sur les dernières avancées en intelligence artificielle.', '2024-06-01', 'image1.jpg'),
(2, 'Atelier Développement Web', 'Un atelier pratique sur le développement web moderne.', '2024-06-15', 'image2.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `message_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`message_id`),
  KEY `message_id` (`message_id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `message_id`) VALUES
(17, 14, 12),
(18, 14, 17),
(9, 14, 10),
(38, 6, 15),
(19, 14, 13),
(37, 14, 19),
(41, 14, 23),
(39, 13, 15),
(47, 6, 30),
(43, 6, 24),
(44, 6, 25),
(48, 6, 27),
(49, 6, 28),
(50, 14, 32);

-- --------------------------------------------------------

--
-- Structure de la table `like_photo`
--

DROP TABLE IF EXISTS `like_photo`;
CREATE TABLE IF NOT EXISTS `like_photo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `photo_id` int NOT NULL,
  `date_like` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `photo_id` (`photo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `like_photo`
--

INSERT INTO `like_photo` (`id`, `utilisateur_id`, `photo_id`, `date_like`) VALUES
(6, 14, 9, '2024-05-31 23:10:27'),
(7, 14, 8, '2024-05-31 23:10:31'),
(9, 6, 3, '2024-05-31 23:19:39'),
(10, 6, 2, '2024-05-31 23:19:41'),
(15, 6, 1, '2024-06-01 00:22:08'),
(14, 14, 12, '2024-06-01 00:12:16');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `destinataire_id` int NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` datetime NOT NULL,
  `likes` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `destinataire_id` (`destinataire_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `utilisateur_id`, `destinataire_id`, `contenu`, `date_envoi`, `likes`) VALUES
(1, 1, 2, 'Bonjour Marie, bienvenue sur ECE In!', '2024-06-01 14:00:00', 0),
(2, 2, 1, 'Merci Antoine, heureuse d\'être ici!', '2024-06-01 15:00:00', 0),
(3, 14, 6, 'coucou antoine ', '0000-00-00 00:00:00', 0),
(4, 6, 14, 'ca va chef ?', '0000-00-00 00:00:00', 1),
(5, 14, 6, 'salut chef ', '0000-00-00 00:00:00', 1),
(6, 14, 6, 'hello\r\n', '0000-00-00 00:00:00', 0),
(7, 14, 6, 'salut ', '0000-00-00 00:00:00', 0),
(8, 14, 6, 'cc', '0000-00-00 00:00:00', 0),
(9, 14, 6, 'gg', '0000-00-00 00:00:00', 0),
(10, 14, 6, 'good luck', '0000-00-00 00:00:00', 0),
(11, 6, 14, 'gg', '0000-00-00 00:00:00', 0),
(12, 6, 14, 'dernier', '0000-00-00 00:00:00', 1),
(13, 6, 14, 'hello', '0000-00-00 00:00:00', 1),
(14, 6, 13, 'gogole', '0000-00-00 00:00:00', 0),
(15, 13, 6, 'hey \r\n', '0000-00-00 00:00:00', 2),
(16, 6, 13, 'cutie ', '0000-00-00 00:00:00', 0),
(17, 6, 14, 'coucou', '2024-05-29 17:10:49', 9),
(18, 14, 6, 'salut chefffff\r\n', '2024-05-29 17:11:27', 0),
(19, 14, 6, 'hello\r\n', '2024-05-29 18:10:19', 1),
(20, 14, 6, 'salut ', '2024-05-29 18:16:50', 0),
(21, 14, 6, 'salut boloss\r\n', '2024-05-29 18:21:08', 0),
(22, 14, 6, 'gor\r\n', '2024-05-29 18:27:28', 0),
(23, 14, 6, 'gogog', '2024-05-29 18:32:37', 1),
(24, 6, 14, 'coucou', '2024-05-29 20:23:19', 1),
(25, 6, 14, 'hello', '2024-05-30 18:35:33', 1),
(26, 6, 14, 'hihi', '2024-05-30 18:35:38', 0),
(27, 6, 14, 'message test', '2024-05-30 18:36:11', 1),
(28, 6, 14, 'vbhgghig', '2024-05-31 22:46:45', 1),
(29, 6, 14, 'hehe', '2024-05-31 23:21:47', 0),
(30, 6, 13, 'coucou', '2024-05-31 23:21:54', 1),
(31, 1, 6, 'coucou', '2024-06-01 01:57:37', 0),
(32, 14, 6, 'ghghvbgh', '2024-06-01 02:13:19', 1);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `type` enum('like','comment','other') DEFAULT NULL,
  `photo_id` int DEFAULT NULL,
  `commentaire_id` int DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `photo_id` (`photo_id`),
  KEY `commentaire_id` (`commentaire_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `utilisateur_id`, `type`, `photo_id`, `commentaire_id`, `date`) VALUES
(1, 6, 'comment', 1, 5, '2024-06-01 02:10:53'),
(2, 6, 'comment', 2, 6, '2024-06-01 02:10:59'),
(3, 6, 'like', 1, NULL, '2024-06-01 02:11:04'),
(4, 14, 'like', 12, NULL, '2024-06-01 02:12:16'),
(5, 6, 'like', 1, NULL, '2024-06-01 02:22:08');

-- --------------------------------------------------------

--
-- Structure de la table `photos_videos`
--

DROP TABLE IF EXISTS `photos_videos`;
CREATE TABLE IF NOT EXISTS `photos_videos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `statut_id` int DEFAULT NULL,
  `type` enum('photo','video') DEFAULT NULL,
  `chemin_fichier` varchar(255) NOT NULL,
  `date_publication` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `statut_id` (`statut_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `postulations`
--

DROP TABLE IF EXISTS `postulations`;
CREATE TABLE IF NOT EXISTS `postulations` (
  `utilisateur_id` int NOT NULL,
  `emploi_id` int NOT NULL,
  `date_postulation` datetime NOT NULL,
  `lettre_motivation` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`utilisateur_id`,`emploi_id`),
  KEY `emploi_id` (`emploi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `postulations`
--

INSERT INTO `postulations` (`utilisateur_id`, `emploi_id`, `date_postulation`, `lettre_motivation`) VALUES
(6, 1, '2024-05-29 12:26:41', 'uploads/ece_in (3).sql'),
(6, 2, '2024-05-29 12:25:27', 'uploads/ece_in (3).sql'),
(14, 1, '2024-05-30 17:37:37', 'uploads/image11.jpg'),
(14, 7, '2024-06-01 02:13:35', 'uploads/Capture d\'écran 2023-04-14 151112.png');

-- --------------------------------------------------------

--
-- Structure de la table `statuts`
--

DROP TABLE IF EXISTS `statuts`;
CREATE TABLE IF NOT EXISTS `statuts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `contenu` text NOT NULL,
  `date_publication` datetime NOT NULL,
  `visibilite` enum('public','privé') DEFAULT 'public',
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `statuts`
--

INSERT INTO `statuts` (`id`, `utilisateur_id`, `contenu`, `date_publication`, `visibilite`) VALUES
(1, 1, 'Excité de commencer à utiliser ECE In!', '2024-06-01 09:00:00', 'public'),
(2, 2, 'Hâte de participer à la Conférence IA!', '2024-06-01 11:00:00', 'public');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text,
  `date_naissance` date DEFAULT NULL,
  `role` enum('auteur','admin') DEFAULT 'auteur',
  `photo_profil` varchar(255) DEFAULT NULL,
  `photo_mur` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `pseudo`, `email`, `mot_de_passe`, `nom`, `photo`, `bio`, `date_naissance`, `role`, `photo_profil`, `photo_mur`) VALUES
(6, 'Louivre', 'louis.montagne94@gmail.com', '$2y$10$oxa57tMbfElat4dGGzqEReWyWrP.6WCjP843XIYWXvQjeU31xmpKK', 'Montagne', '', 'coucou c loulou', '1990-01-01', 'auteur', 'uploads/Capture d\'écran 2024-06-01 002122.png', 'uploads/Capture d\'écran 2024-05-15 111655.png'),
(13, 'test1', 'test1@gmail.com', '$2y$10$1o7RPPrRaTSEoN9oSr5kXeWf10WhXZTyQ7qBuQyqD.TpFAXPIzuJi', 'test1', NULL, 'c', NULL, 'auteur', 'uploads/image3.jpg', NULL),
(14, 'christo', 'victorchristol@gmail.com', '$2y$10$QCCtUbZhNYah0ptrmEb/hOHXwtnWWbYu8mh4tFZfuGcNH0FCvCgJC', 'Christolomme', NULL, 'coucou c christo', NULL, 'auteur', 'uploads/WhatsApp Image 2023-11-14 à 13.51.28_76df6aff.jpg', 'uploads/16db2d081755fc93b7b636defcc3379b.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
