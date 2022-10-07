-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 14 jan. 2022 à 10:45
-- Version du serveur :  8.0.21
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `base_magasin`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `qte_vendue`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `qte_vendue` ()  SELECT  article.id_article,designation,prix,categorie,sum(quantite)
from article,ligne
where article.id_article=ligne.id_article
group by article.id_article$$

DROP PROCEDURE IF EXISTS `vente_cat`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `vente_cat` (IN `id_cat` VARCHAR(20), OUT `total_vente` INT(255))  BEGIN

SELECT round(sum(prix*quantite)) into @total_vente
from ligne,article
where article.id_article=ligne.id_article
AND categorie=id_cat;

select @total_vente;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id_article` char(5) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `prix` decimal(8,2) NOT NULL,
  `categorie` enum('tout','photo','video','informatique','telephonie','divers') NOT NULL,
  PRIMARY KEY (`id_article`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `designation`, `prix`, `categorie`) VALUES
('CA300', 'Canon EOS 3000V zoom 28/80', '329.00', 'photo'),
('CAS07', 'Cassette DV60 par 5', '26.90', 'divers'),
('CP100', 'Camescope Panasonic SV-AV 100', '1490.00', 'video'),
('CS330', 'Caméscope Sony DCR-PC330', '1629.00', 'video'),
('DEL30', 'Portable Dell X300', '1715.00', 'informatique'),
('DVD75', 'DVD vierge par 3', '17.50', 'divers'),
('HP497', 'PC Bureau HP497 écran TFT', '1500.00', 'informatique'),
('NIK55', 'Nikon F55+zoom 28/80', '269.00', 'photo'),
('NIK80', 'Nikon F80', '479.00', 'photo'),
('SAX15', 'Portable Samsung X15 XVM', '1999.00', 'informatique'),
('SOXMP', 'PC Portable Sony Z1-XMP', '2399.00', 'informatique');

--
-- Déclencheurs `article`
--
DROP TRIGGER IF EXISTS `trig_after_update_prix`;
DELIMITER $$
CREATE TRIGGER `trig_after_update_prix` AFTER UPDATE ON `article` FOR EACH ROW BEGIN
SELECT prix INTO @prixHT
FROM article
WHERE id_article = old.id_article ;

update ligne set prix_unit= @prixHT where id_article = old.id_article;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id_client` int NOT NULL AUTO_INCREMENT,
  `civilite` varchar(5) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `age` int NOT NULL,
  `adresse` varchar(100) NOT NULL,
  `ville` varchar(30) NOT NULL,
  `code_postal` varchar(5) DEFAULT '19240',
  `mail` varchar(30) DEFAULT NULL,
  `version` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `civilite`, `nom`, `prenom`, `age`, `adresse`, `ville`, `code_postal`, `mail`, `version`) VALUES
(1, 'M.', 'RESTOUEIX', 'Sacha', 36, '5 Avenue Albert Einstein', 'Le Saillant', '19240', 'sacha@gmail.com', 0),
(2, 'M.', 'Rapp', 'Paul', 44, '32 Avenue Foch', 'Paris', NULL, 'rapp@libert.com', 0),
(3, 'M.', 'Devos', 'Marie', 18, '75 Boulevard Hochimin', 'Lille', NULL, 'grav@wanadoo.com', 0),
(4, 'M.', 'Hauchon', 'Paul', 22, '12 Rue Tsétsé', 'Chartres', NULL, 'hauch@gmail.com', 0),
(5, 'M.', 'Grave', 'Nuyen', 18, '75 Boulevard Hochimin', 'Lille', NULL, 'grave@gmail.com', 0),
(6, 'Mme', 'Hachette', 'Jeanne', 45, '60 Rue d\'Amiens', 'Versailles', NULL, NULL, 0),
(7, 'M.', 'Marti', 'Pierre', 25, '4 Avenue Henry 8', 'Paris', NULL, 'marti@gmail.com', 0),
(8, 'M.', 'Mac Neal', 'John', 52, '59 Rue Diana', 'Lyon', NULL, 'macneal@gmail.com', 0),
(9, 'M.', 'Basile', 'Did', 37, '26 Rue Gallas', 'Nantes', NULL, 'bas@walabi.com', 0),
(10, 'Melle', 'Darc', 'Jeanne', 19, '9 Avenue d\'Orléans', 'Paris', NULL, NULL, 0),
(11, 'M.', 'Gate', 'Bill', 75, '9 Boulevard des Bugs', 'Lyon', '78000', 'bill@microhard.be', 0),
(14, 'M.', 'RESTOUEIX', 'Sacha', 52, '25 Route de la Plaine', 'Allassac', '87000', 'sacha8milo@gmail.com', 0);

--
-- Déclencheurs `client`
--
DROP TRIGGER IF EXISTS `before_insert_cp`;
DELIMITER $$
CREATE TRIGGER `before_insert_cp` BEFORE INSERT ON `client` FOR EACH ROW BEGIN
 IF NEW.code_postal IS NOT NULL   -- le code postal est NON NULL
    AND NEW.code_postal NOT BETWEEN "01000" AND "99999"
      THEN
        /* SET NEW.code_postal = "19240"; */
        
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mauvais Code Postal';
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trig_after_delete_client`;
DELIMITER $$
CREATE TRIGGER `trig_after_delete_client` AFTER DELETE ON `client` FOR EACH ROW BEGIN
    INSERT INTO historique_client
      (action, date_action, version, nom, id_original)
    VALUES
      ('delete', NOW(), OLD.version, OLD.nom, OLD.id_client);
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trig_avant_update_client`;
DELIMITER $$
CREATE TRIGGER `trig_avant_update_client` BEFORE UPDATE ON `client` FOR EACH ROW BEGIN
    SET NEW.version = OLD.version + 1;
    INSERT INTO historique_client
      (action, date_action, version, nom, id_original)
    VALUES
      ('update', NOW(), OLD.version, OLD.nom, OLD.id_client);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_comm` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `id_client` int NOT NULL,
  PRIMARY KEY (`id_comm`),
  KEY `commande_client_FK` (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_comm`, `date`, `id_client`) VALUES
(1, '2012-06-11', 5),
(2, '2012-06-25', 9),
(3, '2012-07-12', 1),
(4, '2012-07-14', 3),
(5, '2012-07-31', 9),
(6, '2012-08-08', 10),
(7, '2012-08-25', 2),
(8, '2012-09-04', 7),
(9, '2012-10-15', 11),
(10, '2012-11-23', 4),
(11, '2013-01-21', 8),
(12, '2013-02-01', 5),
(13, '2013-03-03', 9);

-- --------------------------------------------------------

--
-- Structure de la table `historique_client`
--

DROP TABLE IF EXISTS `historique_client`;
CREATE TABLE IF NOT EXISTS `historique_client` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `action` enum('update','delete') DEFAULT NULL,
  `date_action` datetime DEFAULT NULL,
  `version` bigint NOT NULL DEFAULT '0',
  `id_original` bigint NOT NULL DEFAULT '0',
  `nom` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_original` (`id_original`),
  KEY `action` (`action`),
  KEY `date_action` (`date_action`),
  KEY `version` (`version`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `historique_client`
--

INSERT INTO `historique_client` (`id`, `action`, `date_action`, `version`, `id_original`, `nom`) VALUES
(1, 'update', '2020-12-10 18:11:50', 1, 1, 'Martin'),
(2, 'delete', '2020-12-10 18:16:19', 0, 14, 'RESTOUEIX'),
(3, 'update', '2021-01-04 10:22:39', 0, 2, 'Rapp'),
(4, 'update', '2021-01-04 10:23:04', 1, 2, 'Rapp'),
(5, 'update', '2021-01-04 10:23:19', 2, 2, 'Rapp'),
(6, 'update', '2021-01-04 10:24:02', 3, 2, 'Rapp'),
(7, 'update', '2021-01-04 10:24:25', 4, 2, 'Rapp'),
(8, 'update', '2021-01-04 10:24:37', 5, 2, 'Rapp'),
(9, 'update', '2021-01-04 10:24:48', 6, 2, 'Rapp'),
(10, 'update', '2021-01-04 10:27:05', 0, 4, 'Hauchon');

-- --------------------------------------------------------

--
-- Structure de la table `ligne`
--

DROP TABLE IF EXISTS `ligne`;
CREATE TABLE IF NOT EXISTS `ligne` (
  `id_article` char(5) NOT NULL,
  `id_comm` int NOT NULL,
  `quantite` int NOT NULL,
  `prix_unit` float NOT NULL,
  PRIMARY KEY (`id_article`,`id_comm`),
  KEY `ligne_commande0_FK` (`id_comm`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `ligne`
--

INSERT INTO `ligne` (`id_article`, `id_comm`, `quantite`, `prix_unit`) VALUES
('CA300', 5, 1, 100.8),
('CAS07', 1, 3, 50),
('CAS07', 6, 3, 50),
('CAS07', 12, 4, 50),
('CP100', 6, 1, 1490),
('CP100', 8, 1, 1490),
('CS330', 1, 1, 1629),
('CS330', 12, 3, 1629),
('DEL30', 10, 2, 1715),
('DVD75', 4, 2, 17.5),
('DVD75', 11, 10, 17.5),
('HP497', 2, 2, 1500),
('NIK55', 9, 1, 269),
('NIK80', 3, 5, 479),
('SAX15', 7, 5, 1999),
('SAX15', 10, 1, 1999),
('SAX15', 13, 2, 1999),
('SOXMP', 4, 3, 2399),
('SOXMP', 8, 1, 2399);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id_role`, `role`) VALUES
(1, 'admin'),
(2, 'editeur'),
(3, 'lecteur');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `mdp` varchar(20) NOT NULL,
  `id_role` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`),
  KEY `utilisateurs_ibfk_1` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `login`, `mdp`, `id_role`) VALUES
(5, 'kayin', '12345678', 1),
(7, 'Kay', '0', 3);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_client_FK` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`);

--
-- Contraintes pour la table `ligne`
--
ALTER TABLE `ligne`
  ADD CONSTRAINT `ligne_article_FK` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`),
  ADD CONSTRAINT `ligne_commande0_FK` FOREIGN KEY (`id_comm`) REFERENCES `commande` (`id_comm`);

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `utilisateurs_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
