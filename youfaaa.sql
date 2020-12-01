-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 28 juil. 2019 à 09:40
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `youfaaa`
--

-- --------------------------------------------------------

--
-- Structure de la table `composant`
--

DROP TABLE IF EXISTS `composant`;
CREATE TABLE IF NOT EXISTS `composant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `type_tab_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `liste_id` int(11) DEFAULT NULL,
  `tableau_id` int(11) DEFAULT NULL,
  `last_editor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_EC8486C9E85441D8` (`liste_id`),
  KEY `IDX_EC8486C9AEDBF81B` (`type_tab_id`),
  KEY `IDX_EC8486C9B062D5BC` (`tableau_id`),
  KEY `IDX_EC8486C97E5A734A` (`last_editor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `composant`
--

INSERT INTO `composant` (`id`, `nom`, `contenu`, `enabled`, `type_tab_id`, `type`, `liste_id`, `tableau_id`, `last_editor_id`) VALUES
(149, 'nom vendeur', NULL, 1, 1, 'text', NULL, NULL, NULL),
(150, 'nom vendeur', 'nom', 1, NULL, 'text', NULL, 26, 21),
(152, 'prenom vendeur', NULL, 1, 1, 'text', NULL, NULL, NULL),
(153, 'prenom vendeur', 'prenom', 1, NULL, 'text', NULL, 26, 21),
(177, 'mode de payement', NULL, 1, 1, 'liste', 51, NULL, NULL),
(178, 'mode de payement', 'carte', 1, NULL, 'liste', 52, 26, 21),
(180, 'approuver', NULL, 0, 1, 'oui/non', NULL, NULL, NULL),
(181, 'approuver', 'on', 0, NULL, 'oui/non', NULL, 26, 21);

-- --------------------------------------------------------

--
-- Structure de la table `droit_accee`
--

DROP TABLE IF EXISTS `droit_accee`;
CREATE TABLE IF NOT EXISTS `droit_accee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `droit_accee`
--

INSERT INTO `droit_accee` (`id`, `titre`) VALUES
(3, 'ajouter'),
(5, 'supprimer'),
(6, 'consulter'),
(7, 'modifier');

-- --------------------------------------------------------

--
-- Structure de la table `liste`
--

DROP TABLE IF EXISTS `liste`;
CREATE TABLE IF NOT EXISTS `liste` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenu` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `liste`
--

INSERT INTO `liste` (`id`, `titre`, `contenu`) VALUES
(51, 'mode de payement', 'a:3:{i:0;s:6:\"cheque\";i:1;s:5:\"carte\";i:2;s:5:\"cache\";}'),
(52, 'mode de payement', 'a:3:{i:0;s:6:\"cheque\";i:1;s:5:\"carte\";i:2;s:5:\"cache\";}');

-- --------------------------------------------------------

--
-- Structure de la table `liste_compo`
--

DROP TABLE IF EXISTS `liste_compo`;
CREATE TABLE IF NOT EXISTS `liste_compo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poste_id` int(11) DEFAULT NULL,
  `droit_accee_id` int(11) DEFAULT NULL,
  `type_tab_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D6339437A0905086` (`poste_id`),
  KEY `IDX_D63394375121283C` (`droit_accee_id`),
  KEY `IDX_D6339437AEDBF81B` (`type_tab_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `liste_compo`
--

INSERT INTO `liste_compo` (`id`, `poste_id`, `droit_accee_id`, `type_tab_id`) VALUES
(15, 8, 5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `liste_compo_composant`
--

DROP TABLE IF EXISTS `liste_compo_composant`;
CREATE TABLE IF NOT EXISTS `liste_compo_composant` (
  `liste_compo_id` int(11) NOT NULL,
  `composant_id` int(11) NOT NULL,
  PRIMARY KEY (`liste_compo_id`,`composant_id`),
  KEY `IDX_7F1FCF46ED9818FB` (`liste_compo_id`),
  KEY `IDX_7F1FCF467F3310E7` (`composant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `liste_compo_composant`
--

INSERT INTO `liste_compo_composant` (`liste_compo_id`, `composant_id`) VALUES
(15, 152);

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE IF NOT EXISTS `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190709224455', '2019-07-09 22:45:20'),
('20190709233819', '2019-07-09 23:38:28'),
('20190710083426', '2019-07-10 08:34:52'),
('20190710085150', '2019-07-10 08:52:01'),
('20190711103356', '2019-07-11 10:34:21'),
('20190711105419', '2019-07-11 10:54:32'),
('20190711112159', '2019-07-11 11:22:24'),
('20190711113451', '2019-07-11 11:35:01'),
('20190714221118', '2019-07-14 22:11:41'),
('20190714221413', '2019-07-14 22:14:22'),
('20190714221630', '2019-07-14 22:16:37'),
('20190717162938', '2019-07-17 16:30:16'),
('20190718101613', '2019-07-18 10:16:37'),
('20190718180454', '2019-07-18 18:05:15'),
('20190718180818', '2019-07-18 18:08:26'),
('20190718181516', '2019-07-18 18:15:28'),
('20190719122459', '2019-07-19 12:25:21'),
('20190720103047', '2019-07-20 10:31:10'),
('20190720105003', '2019-07-20 10:50:13'),
('20190722125139', '2019-07-22 12:52:05'),
('20190722204038', '2019-07-22 20:40:50'),
('20190722204337', '2019-07-22 20:43:44'),
('20190722210253', '2019-07-22 21:03:08'),
('20190724175452', '2019-07-24 17:55:29'),
('20190724185640', '2019-07-24 18:56:48'),
('20190724185946', '2019-07-24 18:59:52'),
('20190724191911', '2019-07-24 19:19:33'),
('20190724192546', '2019-07-24 19:25:55'),
('20190724194533', '2019-07-24 19:46:30'),
('20190724194958', '2019-07-24 19:50:06'),
('20190724201800', '2019-07-24 20:18:16'),
('20190724202736', '2019-07-24 20:27:44'),
('20190724235752', '2019-07-24 23:58:02'),
('20190725000222', '2019-07-25 00:02:33'),
('20190725000447', '2019-07-25 00:04:57'),
('20190725000728', '2019-07-25 00:07:38'),
('20190725000958', '2019-07-25 00:10:05'),
('20190725203428', '2019-07-25 20:34:47'),
('20190726001444', '2019-07-26 00:15:08'),
('20190726231942', '2019-07-26 23:20:09');

-- --------------------------------------------------------

--
-- Structure de la table `poste`
--

DROP TABLE IF EXISTS `poste`;
CREATE TABLE IF NOT EXISTS `poste` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `poste`
--

INSERT INTO `poste` (`id`, `nom`) VALUES
(8, 'charger vendeur');

-- --------------------------------------------------------

--
-- Structure de la table `tableau`
--

DROP TABLE IF EXISTS `tableau`;
CREATE TABLE IF NOT EXISTS `tableau` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_tab_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C6744DB1AEDBF81B` (`type_tab_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tableau`
--

INSERT INTO `tableau` (`id`, `nom`, `type_tab_id`) VALUES
(26, 'vendeur1', 1);

-- --------------------------------------------------------

--
-- Structure de la table `type_tab`
--

DROP TABLE IF EXISTS `type_tab`;
CREATE TABLE IF NOT EXISTS `type_tab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_tab`
--

INSERT INTO `type_tab` (`id`, `type`) VALUES
(1, 'vendeur'),
(2, 'commande'),
(3, 'communication');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `presnom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `derniere_cnx` datetime DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `poste_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8D93D649A0905086` (`poste_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `nom`, `presnom`, `email`, `image`, `is_active`, `derniere_cnx`, `role`, `poste_id`) VALUES
(21, '06973325', '$2y$13$B4D04LLUht6aVfE35i.NCugV6Fpxmo3Q67rYxVrhJJ49wWrB2hgRi', 'Ben Ticha', 'Zouhour', 'zoubour@gmail.com', 'image', 1, NULL, 'role_admin', 8);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `composant`
--
ALTER TABLE `composant`
  ADD CONSTRAINT `FK_EC8486C97E5A734A` FOREIGN KEY (`last_editor_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_EC8486C9AEDBF81B` FOREIGN KEY (`type_tab_id`) REFERENCES `type_tab` (`id`),
  ADD CONSTRAINT `FK_EC8486C9B062D5BC` FOREIGN KEY (`tableau_id`) REFERENCES `tableau` (`id`),
  ADD CONSTRAINT `FK_EC8486C9E85441D8` FOREIGN KEY (`liste_id`) REFERENCES `liste` (`id`);

--
-- Contraintes pour la table `liste_compo`
--
ALTER TABLE `liste_compo`
  ADD CONSTRAINT `FK_D63394375121283C` FOREIGN KEY (`droit_accee_id`) REFERENCES `droit_accee` (`id`),
  ADD CONSTRAINT `FK_D6339437A0905086` FOREIGN KEY (`poste_id`) REFERENCES `poste` (`id`),
  ADD CONSTRAINT `FK_D6339437AEDBF81B` FOREIGN KEY (`type_tab_id`) REFERENCES `type_tab` (`id`);

--
-- Contraintes pour la table `liste_compo_composant`
--
ALTER TABLE `liste_compo_composant`
  ADD CONSTRAINT `FK_7F1FCF467F3310E7` FOREIGN KEY (`composant_id`) REFERENCES `composant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_7F1FCF46ED9818FB` FOREIGN KEY (`liste_compo_id`) REFERENCES `liste_compo` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tableau`
--
ALTER TABLE `tableau`
  ADD CONSTRAINT `FK_C6744DB1AEDBF81B` FOREIGN KEY (`type_tab_id`) REFERENCES `type_tab` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649A0905086` FOREIGN KEY (`poste_id`) REFERENCES `poste` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
