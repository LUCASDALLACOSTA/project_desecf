-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 07 sep. 2023 à 14:37
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
  `date_debut_stage` date DEFAULT NULL,
  `nom_structure` varchar(50) NOT NULL,
  `type_structure` varchar(50) NOT NULL,
  `statut_stage_2` varchar(50) DEFAULT NULL,
  `duree_stage_2` varchar(50) DEFAULT NULL,
  `date_debut_stage_2` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `professionnel`
--

INSERT INTO `professionnel` (`id_professionnel`, `nom`, `prenom`, `adresse`, `latitude`, `longitude`, `telephone`, `mail`, `statut`, `duree_stage`, `date_debut_stage`, `nom_structure`, `type_structure`, `statut_stage_2`, `duree_stage_2`, `date_debut_stage_2`) VALUES
(1, 'Elfie', 'Camille', '15 rue des pâquerettes, 31500', 43.6223, 1.46431, '0765008131', 'camille.elfie@gmail.com', 'non disponible', '20 semaines', '2023-07-31', 'Hopital', '', 'non disponible', '10 semaines', '2023-09-19'),
(2, 'Claushe', 'Marc', '3 Rue Frida Kahlo, 31200 Toulouse', 43.6294, 1.43374, '0542675291', 'marc.claushe@gmail.com', 'non disponible', '10 semaines', '2023-07-31', 'Maison de retraite', '', 'disponible', '10 semaines', '2023-09-11'),
(3, 'Dutran', 'Thomas', '18 Av. d\'Occitanie, 31520 Ramonville-Saint-Agne', 43.5437, 1.47873, '0687641122', 'thomas.dutran@gmail.com', 'disponible', '10 semaines', '2023-10-01', 'Hopital', '', 'non disponible', '10 semaines', '2023-09-27'),
(6, 'Sezco', 'Julie', '3 Rue Caussette, 31000 Toulouse', 43.6055, 1.44619, '0789165255', 'sezco.julie@gmail.com', 'disponible', '8 semaines', '2023-09-11', 'Hopital', '', 'disponible', '10 semaines', '2023-09-11'),
(10, 'Qoup', 'Jacques', '2 Pl. Émile Male, 31300 Toulouse', 43.5915, 1.41929, '0929372677', 'jacques.quoc@gmail.com', 'disponible', '6 semaines', '2023-09-10', 'Ecole', '', 'en attente', '10 semaines', '2023-09-12'),
(11, 'Aylouil', 'Karim', '10 Rue Marceau, 34000 Montpellier', 43.6078, 3.87299, '0298376678', 'Aylouil.karm@gmail.com', 'non disponible', '10 semaines', '2023-10-07', 'Hopital', '', 'non disponible', '10 semaines', '2023-11-04');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `professionnel`
--
ALTER TABLE `professionnel`
  ADD PRIMARY KEY (`id_professionnel`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `professionnel`
--
ALTER TABLE `professionnel`
  MODIFY `id_professionnel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
