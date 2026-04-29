-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 29 avr. 2026 à 09:25
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `presence`
--

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
