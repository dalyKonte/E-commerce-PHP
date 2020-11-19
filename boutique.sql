-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 nov. 2020 à 14:09
-- Version du serveur :  10.4.14-MariaDB
-- Version de PHP : 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `boutique`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  `etat` enum('en_cours_de_traitement','envoye','livre') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `membre_id`, `montant`, `date_enregistrement`, `etat`) VALUES
(6, 7, 15, '2020-11-09 15:08:03', 'en_cours_de_traitement'),
(7, 7, 15, '2020-11-09 15:23:37', 'en_cours_de_traitement'),
(8, 7, 19, '2020-11-09 15:25:13', 'en_cours_de_traitement');

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE `details_commande` (
  `id_details_commande` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(5) NOT NULL,
  `prix` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `details_commande`
--

INSERT INTO `details_commande` (`id_details_commande`, `commande_id`, `produit_id`, `quantite`, `prix`) VALUES
(7, 6, 9, 1, 15),
(8, 7, 9, 1, 15),
(9, 8, 3, 1, 19);

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id_membre` int(11) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('homme','femme') NOT NULL,
  `ville` varchar(20) NOT NULL,
  `code_postal` int(5) UNSIGNED ZEROFILL NOT NULL,
  `adresse` text NOT NULL,
  `statut` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `ville`, `code_postal`, `adresse`, `statut`) VALUES
(7, 'dalylyda', '$2y$10$sOBZk1UGLhwC81Yb1CsOgu6MHyluGzeuaHQHv28qcFOSfjXxeQLOC', 'konte', 'daly', 'dalyyy@gmail.com', 'femme', 'paris', 75004, 'route de 40 sous', 1),
(9, 'perso1', '$2y$10$ZswyfEOl0yrEvwZzATEc4u3HpbRDm4IHFVJTiwfkE3RCPWEWy42G6', 'KONTE', 'Kandia', 'perso1@perso1', 'homme', 'poissy', 78300, 'rue de la bas', 0);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `categorie` varchar(20) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `couleur` varchar(20) NOT NULL,
  `taille` varchar(5) NOT NULL,
  `public` enum('homme','femme','mixte') NOT NULL,
  `photo` varchar(255) NOT NULL,
  `prix` int(5) NOT NULL,
  `stock` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `reference`, `categorie`, `titre`, `description`, `couleur`, `taille`, `public`, `photo`, `prix`, `stock`) VALUES
(3, '3', 'pull', 'pull vert', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam corporis ea, ipsam dignissimos id culpa quasi quidem modi consequuntur aperiam nesciunt possimus voluptas hic. Cumque voluptatum incidunt corporis laudantium voluptas.', 'vert', 's', 'femme', 'http://localhost/PHP/09-boutique/photo/3-3-pull_vert.jpg', 19, 9),
(4, '4', 'pull', 'pull blanc', 'pull léger parfait pour la mi saison', 'blanc', 's', 'femme', 'http://localhost/PHP/09-boutique/photo/4-4-4-pull_blanc.jpg', 15, 10),
(9, '23', 'pantalon', 'pantacourt', 'style sportswear', 'noir', 's', 'femme', 'http://localhost/PHP/09-boutique/photo/23-pantacourt.jpg', 15, 9);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`);

--
-- Index pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD PRIMARY KEY (`id_details_commande`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `details_commande`
--
ALTER TABLE `details_commande`
  MODIFY `id_details_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
