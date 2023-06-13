-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 26 juin 2022 à 17:31
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projetwebp2`
--
CREATE DATABASE IF NOT EXISTS `projetwebp2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `projetwebp2`;

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE IF NOT EXISTS `administrateur` (
  `ID_Administrateur` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(20) NOT NULL,
  `Prenom` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Code_confidentiel` varchar(255) NOT NULL,
  `Premiere_connexion` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Administrateur`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`ID_Administrateur`, `Nom`, `Prenom`, `Email`, `Code_confidentiel`, `Premiere_connexion`) VALUES
(1, 'admin', 'Matthieu', 'matdegramont@gmail.com', '$2y$10$fyJ.PDYPXaY6z8Vf5APIXO0gtX4lDFbYgVv84Is/iZvdhdBbOiuq2', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `ID_Classe` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(10) NOT NULL,
  PRIMARY KEY (`ID_Classe`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`ID_Classe`, `Nom`) VALUES
(1, 'BRPI'),
(2, 'BTS SIO'),
(3, 'BTS SN'),
(4, 'BTS MCO');

-- --------------------------------------------------------

--
-- Structure de la table `compose`
--

CREATE TABLE IF NOT EXISTS `compose` (
  `IdGroupe` int(11) NOT NULL,
  `ID_Classe` int(11) NOT NULL,
  PRIMARY KEY (`IdGroupe`,`ID_Classe`),
  KEY `compose_Classes1_FK` (`ID_Classe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `compose`
--

INSERT INTO `compose` (`IdGroupe`, `ID_Classe`) VALUES
(1, 1),
(2, 2),
(3, 2),
(4, 1),
(5, 4),
(6, 4);

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

CREATE TABLE IF NOT EXISTS `cours` (
  `ID_Cours` int(11) NOT NULL AUTO_INCREMENT,
  `Matiere` varchar(20) NOT NULL,
  `ID_Classe` int(11) NOT NULL,
  `ID_Groupe` int(11) DEFAULT NULL,
  `ID_Enseignant` int(11) NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY (`ID_Cours`),
  KEY `Cours_Classes1_FK` (`ID_Classe`),
  KEY `Cours_Enseignant2_FK` (`ID_Enseignant`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`ID_Cours`, `Matiere`, `ID_Classe`, `ID_Groupe`, `ID_Enseignant`, `Date`) VALUES
(7, 'Unity', 1, 1, 1, '2022-06-24'),
(8, 'Linux', 1, 4, 1, '2022-06-25'),
(9, 'SQL', 2, 2, 1, '2022-06-29'),
(10, 'Presentation Projet ', 1, NULL, 1, '2022-06-24'),
(11, 'C#', 1, 1, 2, '2022-03-12'),
(12, 'Evaluation Java', 1, 1, 1, '2022-07-04'),
(13, 'Windows', 1, NULL, 1, '2022-06-25'),
(14, 'Unity', 1, 1, 2, '2022-06-25'),
(15, 'Maths', 1, 4, 2, '2022-06-25'),
(16, 'Algo', 1, NULL, 2, '2022-06-30');

-- --------------------------------------------------------

--
-- Structure de la table `eleves`
--

CREATE TABLE IF NOT EXISTS `eleves` (
  `ID_Eleve` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(20) NOT NULL,
  `Prenom` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Code_confidentiel` varchar(255) NOT NULL,
  `Premiere_connexion` int(11) DEFAULT NULL,
  `ID_Classe` int(11) NOT NULL,
  `ID_Groupe` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Eleve`),
  KEY `Eleves_Classes0_FK` (`ID_Classe`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `eleves`
--

INSERT INTO `eleves` (`ID_Eleve`, `Nom`, `Prenom`, `Email`, `Code_confidentiel`, `Premiere_connexion`, `ID_Classe`, `ID_Groupe`) VALUES
(1, 'Boubee de Gramont', 'Matthieu', 'matdegramont@gmail.com', '$2y$10$R7DvLfcYcUJmsLGWNkcGWumqsDa3dtp82yEH6m8vu.JaOo78FwR8W', NULL, 1, 1),
(2, 'Dallas Costa', 'Lucas', 'lucas.dallascosta@gmail.com', '$2y$10$fyJ.PDYPXaY6z8Vf5APIXO0gtX4lDFbYgVv84Is/iZvdhdBbOiuq2', NULL, 1, 1),
(3, 'Bluzat', 'Clement', 'clement.bluzat@gmail.com', '$2y$10$fyJ.PDYPXaY6z8Vf5APIXO0gtX4lDFbYgVv84Is/iZvdhdBbOiuq2', NULL, 1, 1),
(4, 'Lemoine', 'Gaspard', 'clement.bluzat@gmail.com', '$2y$10$fyJ.PDYPXaY6z8Vf5APIXO0gtX4lDFbYgVv84Is/iZvdhdBbOiuq2', NULL, 1, 4),
(5, 'Franco', 'Loic', 'loic.franco@gmail.com', '$2y$10$fyJ.PDYPXaY6z8Vf5APIXO0gtX4lDFbYgVv84Is/iZvdhdBbOiuq2', NULL, 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE IF NOT EXISTS `enseignant` (
  `ID_Enseignant` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(20) NOT NULL,
  `Prenom` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Code_confidentiel` varchar(255) NOT NULL,
  `Premiere_connexion` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Enseignant`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `enseignant`
--

INSERT INTO `enseignant` (`ID_Enseignant`, `Nom`, `Prenom`, `Email`, `Code_confidentiel`, `Premiere_connexion`) VALUES
(1, 'Belkacem', 'Michel', 'michel.belkacem@gmail.com', '$2y$10$mkV4HneTjUtF61o0qyYXAefs6Df91r4tpYVJ7CkBtqCU1oSRbP59G', NULL),
(2, 'Bonnet', 'Mickael', 'mickael.bonnet@gmail.com', '$2y$10$fyJ.PDYPXaY6z8Vf5APIXO0gtX4lDFbYgVv84Is/iZvdhdBbOiuq2', NULL),
(4, 'Ayene', 'Pierre', 'ayene.pierre@gmail.com', '$2y$10$pHXLO87wqAz/ovxvPVA/aubD.LIucfV31/OAB2L9L0ahKRtVcHJV6', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `feuille`
--

CREATE TABLE IF NOT EXISTS `feuille` (
  `ID_Feuille` int(11) NOT NULL AUTO_INCREMENT,
  `Date_debut` varchar(5) NOT NULL,
  `Date_fin` varchar(5) NOT NULL,
  `ID_Cours` int(11) NOT NULL,
  PRIMARY KEY (`ID_Feuille`),
  KEY `Feuille_Cours0_FK` (`ID_Cours`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `feuille`
--

INSERT INTO `feuille` (`ID_Feuille`, `Date_debut`, `Date_fin`, `ID_Cours`) VALUES
(10, '12:00', '17:00', 7),
(11, '10:00', '12:00', 8),
(12, '13:00', '15:00', 9),
(13, '12:00', '16:00', 10),
(14, '15:00', '19:00', 11),
(15, '09:00', '12:00', 12),
(16, '17:53', '19:53', 13),
(17, '13:00', '15:00', 14),
(18, '17:00', '23:00', 15),
(19, '09:00', '10:00', 16);

-- --------------------------------------------------------

--
-- Structure de la table `groupes`
--

CREATE TABLE IF NOT EXISTS `groupes` (
  `IdGroupe` int(11) NOT NULL AUTO_INCREMENT,
  `NomGroupe` varchar(10) NOT NULL,
  PRIMARY KEY (`IdGroupe`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `groupes`
--

INSERT INTO `groupes` (`IdGroupe`, `NomGroupe`) VALUES
(1, 'DSN'),
(2, 'SLAM'),
(3, 'SISR'),
(4, 'CIR'),
(5, 'Marketing'),
(6, 'Mercatique');

-- --------------------------------------------------------

--
-- Structure de la table `journaux_feuille`
--

CREATE TABLE IF NOT EXISTS `journaux_feuille` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Eleve` int(11) NOT NULL,
  `ID_Feuille` int(11) NOT NULL,
  `Signature_eleve` int(11) DEFAULT NULL,
  `Signature_enseignant` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `journaux_feuille`
--

INSERT INTO `journaux_feuille` (`ID`, `ID_Eleve`, `ID_Feuille`, `Signature_eleve`, `Signature_enseignant`) VALUES
(16, 1, 13, NULL, 1),
(17, 3, 10, NULL, 1),
(18, 1, 10, NULL, 1),
(19, 2, 10, NULL, 1),
(20, 5, 11, NULL, 1),
(21, 4, 11, NULL, 1),
(22, 3, 14, NULL, 1),
(23, 1, 14, NULL, 1),
(24, 2, 14, NULL, NULL),
(25, 3, 13, NULL, 1),
(26, 2, 13, NULL, 1),
(27, 5, 13, NULL, 1),
(28, 4, 13, NULL, 1),
(29, 1, 15, NULL, 1),
(30, 1, 16, 1, NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `compose`
--
ALTER TABLE `compose`
  ADD CONSTRAINT `compose_Classes1_FK` FOREIGN KEY (`ID_Classe`) REFERENCES `classes` (`ID_Classe`),
  ADD CONSTRAINT `compose_Groupes0_FK` FOREIGN KEY (`IdGroupe`) REFERENCES `groupes` (`IdGroupe`);

--
-- Contraintes pour la table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `Cours_Classes1_FK` FOREIGN KEY (`ID_Classe`) REFERENCES `classes` (`ID_Classe`),
  ADD CONSTRAINT `Cours_Enseignant2_FK` FOREIGN KEY (`ID_Enseignant`) REFERENCES `enseignant` (`ID_Enseignant`);

--
-- Contraintes pour la table `eleves`
--
ALTER TABLE `eleves`
  ADD CONSTRAINT `Eleves_Classes0_FK` FOREIGN KEY (`ID_Classe`) REFERENCES `classes` (`ID_Classe`);

--
-- Contraintes pour la table `feuille`
--
ALTER TABLE `feuille`
  ADD CONSTRAINT `Feuille_Cours0_FK` FOREIGN KEY (`ID_Cours`) REFERENCES `cours` (`ID_Cours`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
