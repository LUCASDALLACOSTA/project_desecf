-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 13 juin 2023 à 10:53
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `desecf`
--

-- --------------------------------------------------------

--
-- Structure de la table `accessible_par`
--

CREATE TABLE `accessible_par` (
  `id_transport` int(11) NOT NULL,
  `id_professionnel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `professionnel`
--

CREATE TABLE `professionnel` (
  `id_professionnel` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `statut` varchar(50) NOT NULL,
  `duree_stage` varchar(50) DEFAULT NULL,
  `date_debut_stage` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `professionnel`
--

INSERT INTO `professionnel` (`id_professionnel`, `nom`, `prenom`, `adresse`, `latitude`, `longitude`, `telephone`, `mail`, `statut`, `duree_stage`, `date_debut_stage`) VALUES
(1, 'Elfie', 'Camille', '15 rue des pâquerettes, 31500', 43.6223, 1.46431, '0765008131', '$ mail', 'disponible', '6 semaines', '2023-07-31'),
(2, 'Claushe', 'Marc', '3 Rue Frida Kahlo, 31200 Toulouse', 43.6294, 1.43374, '0542675291', 'marc.claushe@gmail.com', 'disponible', '12 semaines', '2023-07-31'),
(3, 'Dutran', 'Thomas', '18 Av. d\'Occitanie, 31520 Ramonville-Saint-Agne', 43.5437, 1.47873, '0687641122', 'thomas.dutran@gmail.com', 'en attente', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `transport`
--

CREATE TABLE `transport` (
  `id_transport` int(11) NOT NULL,
  `Nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accessible_par`
--
ALTER TABLE `accessible_par`
  ADD PRIMARY KEY (`id_transport`,`id_professionnel`),
  ADD KEY `accessible_par_professionnel0_FK` (`id_professionnel`);

--
-- Index pour la table `professionnel`
--
ALTER TABLE `professionnel`
  ADD PRIMARY KEY (`id_professionnel`);

--
-- Index pour la table `transport`
--
ALTER TABLE `transport`
  ADD PRIMARY KEY (`id_transport`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `professionnel`
--
ALTER TABLE `professionnel`
  MODIFY `id_professionnel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `transport`
--
ALTER TABLE `transport`
  MODIFY `id_transport` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `accessible_par`
--
ALTER TABLE `accessible_par`
  ADD CONSTRAINT `accessible_par_professionnel0_FK` FOREIGN KEY (`id_professionnel`) REFERENCES `professionnel` (`id_professionnel`),
  ADD CONSTRAINT `accessible_par_transport_FK` FOREIGN KEY (`id_transport`) REFERENCES `transport` (`id_transport`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
