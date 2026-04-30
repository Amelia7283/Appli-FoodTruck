-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 30 avr. 2026 à 12:15
-- Version du serveur : 8.0.27
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `foodtruck`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `idUtilisateur` int NOT NULL,
  `localisationClient` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`idUtilisateur`, `localisationClient`) VALUES
(14, 'Paris'),
(19, 'Lille'),
(22, 'Lfnebbf'),
(24, 'bld'),
(34, 'Bar-le-Duc'),
(36, 'Bar-le-Duc'),
(39, 'Verdun');

-- --------------------------------------------------------

--
-- Structure de la table `comptessupprimes`
--

DROP TABLE IF EXISTS `comptessupprimes`;
CREATE TABLE IF NOT EXISTS `comptessupprimes` (
  `idSupprimes` int NOT NULL AUTO_INCREMENT,
  `idUtilisateur` int NOT NULL,
  `dateSuppression` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idSupprimes`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `horairehebdo`
--

DROP TABLE IF EXISTS `horairehebdo`;
CREATE TABLE IF NOT EXISTS `horairehebdo` (
  `idHoraire` int NOT NULL AUTO_INCREMENT,
  `idUtilisateur` int NOT NULL,
  `jourSemaine` tinyint NOT NULL,
  `arrive` time NOT NULL,
  `depart` time NOT NULL,
  `idLieu` bigint UNSIGNED NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idHoraire`),
  KEY `idx_horaire_utilisateur` (`idUtilisateur`),
  KEY `idx_horaire_lieu` (`idLieu`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `horairehebdo`
--

INSERT INTO `horairehebdo` (`idHoraire`, `idUtilisateur`, `jourSemaine`, `arrive`, `depart`, `idLieu`, `actif`) VALUES
(1, 38, 3, '09:00:00', '18:00:00', 8, 1),
(2, 38, 4, '08:00:00', '12:00:00', 8, 1),
(3, 38, 4, '08:00:00', '16:00:00', 10, 1);

-- --------------------------------------------------------

--
-- Structure de la table `lieu`
--

DROP TABLE IF EXISTS `lieu`;
CREATE TABLE IF NOT EXISTS `lieu` (
  `idLieu` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cp` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rue` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `coordLat` decimal(9,6) NOT NULL,
  `coordLong` decimal(9,6) NOT NULL,
  PRIMARY KEY (`idLieu`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lieu`
--

INSERT INTO `lieu` (`idLieu`, `cp`, `ville`, `rue`, `coordLat`, `coordLong`) VALUES
(1, '55100', 'Verdun', 'Avenue de Paris', '0.000000', '0.000000'),
(2, '55840', 'Thierville sur Meuse', 'Av Pierre Goubert', '0.000000', '0.000000'),
(3, '55000', 'Bar-le-Duc', 'Boulvard Poincaré ', '0.000000', '0.000000'),
(4, '52100', 'SAINT-DIZIER', 'Gambetta', '0.000000', '0.000000'),
(5, '55000', 'Bar-le-Duc', 'Tour de l\'horloge', '0.000000', '0.000000'),
(6, '55000', 'Bar-le-Duc', 'place Lemagny', '0.000000', '0.000000'),
(7, '93250', 'Villemomble', 'Allée du Cimetière', '0.000000', '0.000000'),
(8, '55000', 'Bar-le-Duc', 'Rue du Four', '48.776159', '5.160342'),
(9, '55000', 'Bar-le-Duc', 'Rue du Four', '48.775968', '5.160350'),
(10, '55000', 'Bar-le-Duc', 'Rue de la Maréchale', '48.770674', '5.162368');

-- --------------------------------------------------------

--
-- Structure de la table `logsconnexion`
--

DROP TABLE IF EXISTS `logsconnexion`;
CREATE TABLE IF NOT EXISTS `logsconnexion` (
  `idLog` int NOT NULL AUTO_INCREMENT,
  `idUtilisateur` int NOT NULL,
  `dateConnexion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idLog`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `idMessage` int NOT NULL AUTO_INCREMENT,
  `idClient` int NOT NULL,
  `idVendeur` int NOT NULL,
  `contenu` text COLLATE utf8mb4_general_ci NOT NULL,
  `dateEnvoi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idMessage`),
  KEY `idClient` (`idClient`),
  KEY `idVendeur` (`idVendeur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `presence`
--

DROP TABLE IF EXISTS `presence`;
CREATE TABLE IF NOT EXISTS `presence` (
  `idPresence` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `arrive` time NOT NULL,
  `depart` time NOT NULL,
  `actif` tinyint(1) NOT NULL,
  `idUtilisateur` int NOT NULL,
  `idLieu` int NOT NULL,
  PRIMARY KEY (`idPresence`),
  KEY `idUtilisateur` (`idUtilisateur`),
  KEY `idLieu` (`idLieu`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `presence`
--

INSERT INTO `presence` (`idPresence`, `date`, `arrive`, `depart`, `actif`, `idUtilisateur`, `idLieu`) VALUES
(4, '2025-10-13', '11:00:00', '14:00:00', 0, 23, 1),
(5, '2025-10-16', '16:00:00', '19:00:00', 1, 23, 2),
(6, '2025-10-17', '08:00:00', '14:00:00', 1, 23, 3),
(7, '2025-10-16', '18:00:00', '01:00:00', 0, 29, 4),
(8, '2025-10-17', '19:00:00', '22:00:00', 0, 29, 1),
(9, '2025-10-16', '17:00:00', '19:00:00', 1, 30, 3),
(10, '2025-10-17', '08:49:00', '10:00:00', 1, 31, 5),
(11, '2025-11-13', '16:42:00', '17:00:00', 1, 33, 6),
(12, '2026-04-28', '11:00:00', '22:00:00', 0, 38, 8),
(13, '2026-04-29', '11:00:00', '22:00:00', 1, 38, 8),
(14, '2026-04-29', '11:00:00', '18:00:00', 1, 38, 8),
(15, '2026-05-06', '11:00:00', '18:00:00', 1, 38, 8),
(16, '2026-05-13', '11:00:00', '18:00:00', 1, 38, 8),
(17, '2026-05-20', '11:00:00', '18:00:00', 1, 38, 8),
(18, '2026-05-27', '11:00:00', '18:00:00', 1, 38, 8),
(19, '2026-06-03', '11:00:00', '18:00:00', 1, 38, 8),
(20, '2026-06-10', '11:00:00', '18:00:00', 1, 38, 8),
(21, '2026-06-17', '11:00:00', '18:00:00', 1, 38, 8),
(22, '2026-06-24', '11:00:00', '18:00:00', 1, 38, 8),
(23, '2026-04-29', '09:00:00', '18:00:00', 1, 38, 8),
(24, '2026-05-06', '09:00:00', '18:00:00', 1, 38, 8),
(25, '2026-05-13', '09:00:00', '18:00:00', 1, 38, 8),
(26, '2026-05-20', '09:00:00', '18:00:00', 1, 38, 8),
(27, '2026-05-27', '09:00:00', '18:00:00', 1, 38, 8),
(28, '2026-06-03', '09:00:00', '18:00:00', 1, 38, 8),
(29, '2026-06-10', '09:00:00', '18:00:00', 1, 38, 8),
(30, '2026-06-17', '09:00:00', '18:00:00', 1, 38, 8),
(31, '2026-06-24', '09:00:00', '18:00:00', 1, 38, 8),
(32, '2026-04-30', '08:00:00', '12:00:00', 1, 38, 8),
(33, '2026-05-07', '08:00:00', '12:00:00', 1, 38, 8),
(34, '2026-05-14', '08:00:00', '12:00:00', 1, 38, 8),
(35, '2026-05-21', '08:00:00', '12:00:00', 1, 38, 8),
(36, '2026-05-28', '08:00:00', '12:00:00', 1, 38, 8),
(37, '2026-06-04', '08:00:00', '12:00:00', 1, 38, 8),
(38, '2026-06-11', '08:00:00', '12:00:00', 1, 38, 8),
(39, '2026-06-18', '08:00:00', '12:00:00', 1, 38, 8),
(40, '2026-04-30', '08:00:00', '16:00:00', 1, 38, 10),
(41, '2026-05-07', '08:00:00', '16:00:00', 1, 38, 10),
(42, '2026-05-14', '08:00:00', '16:00:00', 1, 38, 10),
(43, '2026-05-21', '08:00:00', '16:00:00', 1, 38, 10),
(44, '2026-05-28', '08:00:00', '16:00:00', 1, 38, 10),
(45, '2026-06-04', '08:00:00', '16:00:00', 1, 38, 10),
(46, '2026-06-11', '08:00:00', '16:00:00', 1, 38, 10),
(47, '2026-06-18', '08:00:00', '16:00:00', 1, 38, 10),
(48, '2026-06-25', '08:00:00', '12:00:00', 1, 38, 8),
(49, '2026-06-25', '08:00:00', '16:00:00', 1, 38, 10);

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE IF NOT EXISTS `reponse` (
  `idReponse` int NOT NULL AUTO_INCREMENT,
  `idMessage` int NOT NULL,
  `contenu` text COLLATE utf8mb4_general_ci NOT NULL,
  `dateEnvoi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idReponse`),
  KEY `idMessage` (`idMessage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idUtilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `mdp` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('client','vendeur','admin') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`idUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `nom`, `prenom`, `email`, `telephone`, `mdp`, `role`) VALUES
(2, 'Dupont', 'Jean', 'jean.dupont@example.com', '0601020304', '5a01da4f454fcb35994bf2fa773cb0be24a518ed905f9956c975461dbc75a553', 'admin'),
(3, 'Arens', 'Amélia', 'arens@gmail.com', '0651478598', '$2y$10$GKNTLczpCRoPUzptiwzWAO6BzAnDc38Cc0rD.E5JxObtRPzAYFvGC', 'client'),
(4, 'Louis', 'Jean', 'Louis@gmail.com', '0789854565', '$2y$10$cu8Grj7PPhBovqVlEwmq4u0vJeNLDm.gmDQvIdiJ23ajfL03Bv9uW', 'client'),
(5, 'Lois', 'Pierre', 'Pierre@gmail.com', '0652578963', '$2y$10$/DwrVr0CiNnB8vCoqABAou1CWdX2/wnI/9Kop91TZSAxm4p1RJQce', 'client'),
(6, 'Francois', 'Lise', 'lise@gmail.com', '0741258963', '$2y$10$fA5mH37xD2YhwWHGL.ftTOSerQulajgfWcWKS1VngOyLjn70wlWZS', 'vendeur'),
(7, 'Renaud', 'Laurent', 'laurent@gmail.com', '0658211485', '$2y$10$siq38E8U3DipOCDFAuZTyuW9IMsKuzZrIWs/apS09OPLdu93GsiGm', 'vendeur'),
(8, 'Paul', 'Louis', 'paul@gmail.com', '0752124586', '$2y$10$xNNcAUEy4/cz8rh3fDilNuCc98zcAhHlGiaWpyxTzxqQRvzEthxty', 'vendeur'),
(9, 'Arens', 'Charlotte', 'charlotte@gmail.com', '0615478521', '$2y$10$3MIdHMl.eus1wCNKSkps2euvbr/wtBt4cpGcJZC1lE.mLMEfSA2Xm', 'client'),
(10, 'Lise', 'François', 'francois@gmail.com', '0748521456', '$2y$10$xWWiryd9qRomu/VWt5UhQ.Bth/bv5.dzgug//c9DDtXIye2opZ6FO', 'client'),
(11, 'Losac', 'Lola', 'lola@gmail.com', '0785426985', '$2y$10$4F2WcaPJ0av1HGpfOghg3O2JePBhDTIZtiDp78CAJudUEr7cawAIm', 'client'),
(12, 'Loris', 'Louis', 'louis@gmail.com', '0658922354', '$2y$10$PHsuaZhkEgxrx2MMNU76YeZcxjuTCIAdqyaevVRS6JvfnfKICU3by', 'vendeur'),
(13, 'Loris', 'Louis', 'loris@gmail.com', '0658741225', '$2y$10$zyFn9by6YXWvuhrfk/BUVuQCbZaKqdadGLhKFjtG59QYjYEmD.1Hi', 'vendeur'),
(14, 'Nora', 'Laura', 'nora@gmail.com', '0658741224', '$2y$10$1ubuW3kg4RPaPo/wIujAyOT0QUMR313ZpMCopQdXHfFZxJAqgZWzq', 'client'),
(19, 'Loic', 'Vincent', 'vincent@gmail.com', '0785544112', '$2y$10$rUG4i3.DY1Ekxx.1XOeC/e3GvbBBmRgxgKyUrHD8epxvs0jli/mSO', 'client'),
(21, 'Admin', 'Principal', 'admin@mail.com', '0000000000', '$2y$10$8jG6a8PuQfOUr8QawpP0l.YMuStZV5xodHu6ZNgBOwm0zEF1x4oIS', 'admin'),
(22, 'eùj', 'ioh', 'client@mail.com', '05649812', '$2y$10$fP0cxzoPi5G4n.ferZkkOeG1PRYQrJErYzwcy3FXWrUeUG8OURSwO', 'client'),
(23, 'Vendeur', '1', 'vendeur@mail.com', '789745', '$2y$10$c9MepIirg1yIWlPZhKUpqOR70qy6BJp4EqfICsgbfNVUXTi6tyyhS', 'vendeur'),
(24, 'tuani', 'ilhan', 'ilhan@ilhan.com', '0766751400', '$2y$10$BHcO/uFF2Xtl0l1/zSjxaOr5oBMxPdrjEMP/pU5qlgG9mI5Fip8Du', 'client'),
(25, 'dondelinger', 'eric', 'eric@dondelinger.com', '0600000000', '$2y$10$AjNo2AG4DoYt4dwGhzXZ7eCBPrY923S0FovYfY3tQkqZNUIMIUhoO', 'vendeur'),
(26, 'oui', 'oui', 'etudiant@etudiant.com', '0600000000', '$2y$10$9/yBLgWIex9pm5.LeLOWouFaMjFuGzCB1fiUVw3cg2U.ew98HADuW', 'vendeur'),
(27, 'oui', 'oui', 'oui@oui.com', '0000000000', '$2y$10$7sA8dc9eQ3RJT4qeC90jy.Pt3XoLoKW4q8RcMDSWVCU.ssYwjKAte', 'vendeur'),
(28, 'Vendeur', 'Attente', 'attente@mail.com', '0658421542', '$2y$10$Y/DIswdH2a/mfeb6Dz/AGuOY0Knjao2i4THlfRcv9v9mnTGH89jSq', 'vendeur'),
(29, 'Dondelinger', 'Eric', 'edondelinger@gmail.com', '0321214578', '$2y$10$ypNW3JQRcJwUIu93kmr4hOyd9C4LGKdNcVuBI4JJCeXeD9/AOxH/m', 'vendeur'),
(30, 'cvgbhjnk', 'vhbjnk,l', 'teste@mail.com', '0256489', '$2y$10$JC15HyK3ygPBrqdd2WekbO8tpVVZaFJAXiYPEvHLO3ztI07AGswXi', 'vendeur'),
(31, 'Didier', 'oui', 'didier@entreprise.com', '0600000000', '$2y$10$F2fVgp1UinjyabHr5.0r9ets9Y2ETAw8rZOsvyr5Xev/8K7.6cF5u', 'vendeur'),
(32, 'hvfbie', 'bhfilevg', 'test@test.com', '05154875', '$2y$10$0GISX5pR8LS/KJByaHnXgu2sSUYySKibP9DaYyCca4wUvy9SJ2ati', 'vendeur'),
(33, 'oui', 'cloe', 'cloe@gmail.com', '00000000', '$2y$10$BQtfzAOyGI2y57Bqk3fHy.8btdF5L1mk50bTdDgRZsRoGEi3maNYa', 'vendeur'),
(34, 'ARENS', 'Amélia', 'Arensamelia@gmail.com', '0612587423', '$2y$10$AZwANnIqaJqDi3m3ZpO1aOPnxoSgnvwSesvlpVV0N4v1I3zxIXqii', 'client'),
(35, 'Test', 'Vendeur', 'TestVendeur@gmail.com', '0758962412', '$2y$10$Wm8eJPsdLuxS/K.kgMtKV.rWZjHTr.jBxejhIXq83rPHQy0Em4/6m', 'vendeur'),
(36, 'Arens', 'Amélia', 'arensamelia55@gmail.com', '0615584752', '$2y$10$EXXf4tigzFu9ljK43r8HGueg6lLUwck/skPDLYRGHIzckyBgG6m1y', 'client'),
(38, 'Test', 'Vendeur', 'Testvendeur@gmail.com', '0658963258', '$2y$10$S9tm2VkhmVTadmB2wIORfuEdKICPGiMg3xUKUBoUMakNt13ZMfKcm', 'vendeur'),
(39, 'Test', 'Ville', 'TestVille55@gmail.com', '0748565215', '$2y$10$HNhGfjGsJrZl.TN.YIq4ze6YxZ8hSRY2Z/h/CM5rMeMcC9pJTtltq', 'client');

-- --------------------------------------------------------

--
-- Structure de la table `vendeur`
--

DROP TABLE IF EXISTS `vendeur`;
CREATE TABLE IF NOT EXISTS `vendeur` (
  `idUtilisateur` int NOT NULL AUTO_INCREMENT,
  `nomFoodTruck` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `validePar` int DEFAULT NULL,
  `dateValidation` date DEFAULT NULL,
  `statut` enum('en_attente','valide','refuse') COLLATE utf8mb4_general_ci DEFAULT 'en_attente',
  `actif` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vendeur`
--

INSERT INTO `vendeur` (`idUtilisateur`, `nomFoodTruck`, `validePar`, `dateValidation`, `statut`, `actif`) VALUES
(8, 'kebab', 21, '2025-10-09', 'refuse', 0),
(23, 'Pizza', 21, '2025-10-09', 'valide', 0),
(27, 'ouii', 21, '2025-11-06', 'refuse', 0),
(28, 'Look', 21, '2025-10-17', 'valide', 0),
(29, 'mange ta soupe', 21, '2025-10-16', 'valide', 0),
(30, 'cvbhjnk,l;', 21, '2025-10-16', 'valide', 0),
(31, 'ouii', 21, '2025-10-17', 'valide', 0),
(32, 'Friterie', 21, '2025-11-06', 'valide', 0),
(33, 'le camion de cloe', 21, '2025-11-13', 'valide', 0),
(35, 'TestResto', NULL, NULL, 'valide', 0),
(38, 'TestVendeur', 21, '2026-04-28', 'valide', 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `Client_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `comptessupprimes`
--
ALTER TABLE `comptessupprimes`
  ADD CONSTRAINT `ComptesSupprimes_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`);

--
-- Contraintes pour la table `logsconnexion`
--
ALTER TABLE `logsconnexion`
  ADD CONSTRAINT `LogsConnexion_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `Message_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `utilisateur` (`idUtilisateur`),
  ADD CONSTRAINT `Message_ibfk_2` FOREIGN KEY (`idVendeur`) REFERENCES `utilisateur` (`idUtilisateur`);

--
-- Contraintes pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD CONSTRAINT `Reponse_ibfk_1` FOREIGN KEY (`idMessage`) REFERENCES `message` (`idMessage`);

--
-- Contraintes pour la table `vendeur`
--
ALTER TABLE `vendeur`
  ADD CONSTRAINT `Vendeur_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
