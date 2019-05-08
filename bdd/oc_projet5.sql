
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `oc_projet5`
--
DROP DATABASE IF EXISTS `oc_projet5`;
CREATE DATABASE IF NOT EXISTS `oc_projet5` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `oc_projet5`;

-- --------------------------------------------------------

--
-- Structure de la table `ocp5_blog_article`
--

DROP TABLE IF EXISTS `ocp5_blog_article`;
CREATE TABLE IF NOT EXISTS `ocp5_blog_article` (
  `ba_id` int(11) NOT NULL AUTO_INCREMENT,
  `ba_title` varchar(255) DEFAULT NULL,
  `ba_content` longtext,
  `ba_chapo` varchar(255) DEFAULT NULL,
  `ba_create_date` datetime DEFAULT NULL,
  `ba_create_user_ref` int(11) DEFAULT NULL,
  `ba_modify_date` datetime DEFAULT NULL,
  `ba_modify_user_ref` int(11) DEFAULT NULL,
  `ba_category_ref` int(11) DEFAULT NULL,
  `ba_state` int(11) NOT NULL DEFAULT '1',
  `ba_state_date` datetime DEFAULT NULL,
  PRIMARY KEY (`ba_id`),
  UNIQUE KEY `ba_id_UNIQUE` (`ba_id`),
  KEY `FK_categorie_ref_idx` (`ba_category_ref`),
  KEY `FK_user_create_ref_idx` (`ba_create_user_ref`),
  KEY `FK_user_modify_ref_idx` (`ba_modify_user_ref`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ocp5_blog_category`
--

DROP TABLE IF EXISTS `ocp5_blog_category`;
CREATE TABLE IF NOT EXISTS `ocp5_blog_category` (
  `bc_id` int(11) NOT NULL AUTO_INCREMENT,
  `bc_title` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`bc_id`),
  UNIQUE KEY `bc_title_UNIQUE` (`bc_title`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ocp5_blog_comment`
--

DROP TABLE IF EXISTS `ocp5_blog_comment`;
CREATE TABLE IF NOT EXISTS `ocp5_blog_comment` (
  `bco_id` int(11) NOT NULL AUTO_INCREMENT,
  `bco_create_date` datetime NOT NULL,
  `bco_create_user_ref` int(11) DEFAULT NULL,
  `bco_content` text NOT NULL,
  `bco_moderator_date` datetime DEFAULT NULL,
  `bco_moderator_user_ref` int(11) DEFAULT NULL,
  `bco_moderator_state` int(2) NOT NULL DEFAULT '10',
  `bco_article_ref` int(11) NOT NULL,
  PRIMARY KEY (`bco_id`),
  UNIQUE KEY `bco_id_UNIQUE` (`bco_id`),
  KEY `FK_create_user_ref_idx` (`bco_create_user_ref`),
  KEY `FK_moderator_user_ref_idx` (`bco_moderator_user_ref`),
  KEY `FK_article_ref_idx` (`bco_article_ref`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ocp5_connexion`
--

DROP TABLE IF EXISTS `ocp5_connexion`;
CREATE TABLE IF NOT EXISTS `ocp5_connexion` (
  `bcon_id` int(11) NOT NULL AUTO_INCREMENT,
  `bcon_date` date DEFAULT NULL,
  `bcon_ip` varchar(15) DEFAULT NULL,
  `bcon_nbr_connexion` int(2) NOT NULL DEFAULT '0',
  `bcon_user_ref` int(2) DEFAULT NULL,
  PRIMARY KEY (`bcon_id`),
  UNIQUE KEY `bcon_id_UNIQUE` (`bcon_id`),
  UNIQUE KEY `couple_ip_date_user` (`bcon_date`,`bcon_ip`,`bcon_user_ref`),
  KEY `user_ref_idx` (`bcon_user_ref`)
) ENGINE=InnoDB AUTO_INCREMENT=403 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ocp5_user`
--

DROP TABLE IF EXISTS `ocp5_user`;
CREATE TABLE IF NOT EXISTS `ocp5_user` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_identifiant` varchar(45) NOT NULL,
  `u_mail` varchar(100) NOT NULL,
  `u_password` varchar(60) NOT NULL,
  `u_forget_hash` varchar(60) DEFAULT NULL,
  `u_forget_date` datetime DEFAULT NULL,
  `u_valid_account_hash` varchar(60) DEFAULT NULL,
  `u_valid_account_date` datetime DEFAULT NULL,
  `u_user_role` int(1) NOT NULL DEFAULT '1',
  `u_actif` int(1) NOT NULL DEFAULT '1',
  `u_actif_date` datetime DEFAULT NULL,
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `u_identifiant_UNIQUE` (`u_identifiant`),
  UNIQUE KEY `u_mail_UNIQUE` (`u_mail`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ocp5_blog_article`
--
ALTER TABLE `ocp5_blog_article`
  ADD CONSTRAINT `FK_categorie_ref` FOREIGN KEY (`ba_category_ref`) REFERENCES `ocp5_blog_category` (`bc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_user_create_ref` FOREIGN KEY (`ba_create_user_ref`) REFERENCES `ocp5_user` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_user_modify_ref` FOREIGN KEY (`ba_modify_user_ref`) REFERENCES `ocp5_user` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ocp5_blog_comment`
--
ALTER TABLE `ocp5_blog_comment`
  ADD CONSTRAINT `FK_article_ref` FOREIGN KEY (`bco_article_ref`) REFERENCES `ocp5_blog_article` (`ba_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_create_user_ref` FOREIGN KEY (`bco_create_user_ref`) REFERENCES `ocp5_user` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_moderator_user_ref` FOREIGN KEY (`bco_moderator_user_ref`) REFERENCES `ocp5_user` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ocp5_connexion`
--
ALTER TABLE `ocp5_connexion`
  ADD CONSTRAINT `FK_user_ref` FOREIGN KEY (`bcon_user_ref`) REFERENCES `ocp5_user` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
