-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : dim. 09 nov. 2025 à 17:08
-- Version du serveur : 5.7.44
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tshirtshop`
--
CREATE DATABASE IF NOT EXISTS `tshirtshop` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `tshirtshop`;

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_count_products_in_category` (IN `inCategoryId` INT)   BEGIN
SELECT COUNT(*) AS categories_count
FROM product p
INNER JOIN product_category pc
ON p.product_id = pc.product_id
WHERE pc.category_id = inCategoryId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_count_products_on_catalog` ()   BEGIN
SELECT COUNT(*) AS products_on_catalog_count
FROM product
WHERE display = 1 OR display = 3;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_count_products_on_department` (IN `inDepartmentId` INT)   BEGIN
SELECT DISTINCT COUNT(*) AS products_on_department_count
FROM product p
INNER JOIN product_category pc
ON p.product_id = pc.product_id
INNER JOIN category c
ON pc.category_id = c.category_id
WHERE (p.display = 2 OR p.display = 3)
AND c.department_id = inDepartmentId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_get_categories_list` (IN `inDepartmentId` INT)   BEGIN
SELECT category_id, name
FROM category
WHERE department_id = inDepartmentId
ORDER BY category_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_get_category_details` (IN `inCategoryId` INT)   BEGIN
SELECT name, description
FROM category
WHERE category_id = inCategoryId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_get_departments_list` ()   BEGIN
SELECT department_id, name FROM department ORDER BY department_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_get_department_details` (IN `inDepartmentId` INT)   BEGIN
SELECT name, description
FROM department
WHERE department_id = inDepartmentId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_get_products_in_category` (IN `inCategoryId` INT, IN `inShortProductDescriptionLength` INT, IN `inProductsPerPage` INT, IN `inStartItem` INT)   BEGIN
-- Prepare statement
PREPARE statement FROM
"SELECT p.product_id, p.name,
IF(LENGTH(p.description) <= ?,
p.description,
CONCAT(LEFT(p.description, ?),
'...')) AS description,
p.price, p.discounted_price, p.thumbnail
FROM product p
INNER JOIN product_category pc
ON p.product_id = pc.product_id
WHERE pc.category_id = ?
ORDER BY p.display DESC
LIMIT ?, ?";
-- Define query parameters
SET @p1 = inShortProductDescriptionLength; 
SET @p2 = inShortProductDescriptionLength; 
SET @p3 = inCategoryId;
SET @p4 = inStartItem; 
SET @p5 = inProductsPerPage; 
-- Execute the statement
EXECUTE statement USING @p1, @p2, @p3, @p4, @p5;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_get_products_on_catalog` (IN `inShortProductDescriptionLength` INT, IN `inProductsPerPage` INT, IN `inStartItem` INT)   BEGIN
PREPARE statement FROM
"SELECT product_id, name,
IF(LENGTH(description) <= ?,
description,
CONCAT(LEFT(description, ?),
'...')) AS description,
price, discounted_price, thumbnail
FROM product
WHERE display = 1 OR display = 3
ORDER BY display DESC
LIMIT ?, ?";
SET @p1 = inShortProductDescriptionLength;
SET @p2 = inShortProductDescriptionLength;
SET @p3 = inStartItem;
SET @p4 = inProductsPerPage;
EXECUTE statement USING @p1, @p2, @p3, @p4;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_get_products_on_department` (IN `inDepartmentId` INT, IN `inShortProductDescriptionLength` INT, IN `inProductsPerPage` INT, IN `inStartItem` INT)   BEGIN
PREPARE statement FROM
"SELECT DISTINCT p.product_id, p.name,p.display,
IF(LENGTH(p.description) <= ?,
p.description,
CONCAT(LEFT(p.description, ?),
'...')) AS description,
p.price, p.discounted_price, p.thumbnail
FROM product p
INNER JOIN product_category pc
ON p.product_id = pc.product_id
INNER JOIN category c
ON pc.category_id = c.category_id
WHERE (p.display = 2 OR p.display = 3)
AND c.department_id = ?
ORDER BY p.display DESC
LIMIT ?, ?";
SET @p1 = inShortProductDescriptionLength;
SET @p2 = inShortProductDescriptionLength;
SET @p3 = inDepartmentId;
SET @p4 = inStartItem;
SET @p5 = inProductsPerPage;
EXECUTE statement USING @p1, @p2, @p3, @p4, @p5;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_get_product_attributes` (IN `inProductId` INT)   BEGIN
SELECT a.name AS attribute_name,
av.attribute_value_id, av.value AS attribute_value
FROM attribute_value av
INNER JOIN attribute a
ON av.attribute_id = a.attribute_id
WHERE av.attribute_value_id IN
(SELECT attribute_value_id
FROM product_attribute
WHERE product_id = inProductId)
ORDER BY a.name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_get_product_details` (IN `inProductId` INT)   BEGIN
SELECT product_id, name, description,
price, discounted_price, image, image_2
FROM product
WHERE product_id = inProductId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `catalog_get_product_locations` (IN `inProductId` INT)   BEGIN
SELECT c.category_id, c.name AS category_name, c.department_id,
(SELECT name
FROM department
WHERE department_id = c.department_id) AS department_name
-- Subquery returns the name of the department of the category
FROM category c
WHERE c.category_id IN
(SELECT category_id
FROM product_category
WHERE product_id = inProductId);
-- Subquery returns the category IDs a product belongs to
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `attribute`
--

CREATE TABLE `attribute` (
  `attribute_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `attribute`
--

INSERT INTO `attribute` (`attribute_id`, `name`) VALUES
(1, 'Size'),
(2, 'Color');

-- --------------------------------------------------------

--
-- Structure de la table `attribute_value`
--

CREATE TABLE `attribute_value` (
  `attribute_value_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `attribute_value`
--

INSERT INTO `attribute_value` (`attribute_value_id`, `attribute_id`, `value`) VALUES
(1, 1, 'S'),
(2, 1, 'M'),
(3, 1, 'L'),
(4, 1, 'XL'),
(5, 1, 'XXL'),
(6, 2, 'White'),
(7, 2, 'Black'),
(8, 2, 'Red'),
(9, 2, 'Orange'),
(10, 2, 'Yellow'),
(11, 2, 'Green'),
(12, 2, 'Blue'),
(13, 2, 'Indigo'),
(14, 2, 'Purple');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`category_id`, `department_id`, `name`, `description`) VALUES
(1, 1, 'Français', 'Les Français ont toujours eu le sens de la beauté. Un coup d\'œil aux T-shirts ci-dessous et vous verrez que la même appréciation a été appliquée abondamment à leurs timbres-poste. Voici quelques-uns de nos T-shirts les plus beaux et colorés, alors naviguez ! Et n\'oubliez pas d\'aller jusqu\'au bas - vous ne voulez en manquer aucun !'),
(2, 1, 'Italien', 'Le trésor complet et resplendissant d\'art, de littérature, de musique et de science que l\'Italie a donné au monde se reflète magnifiquement dans ses timbres-poste. Si nous le pouvions, nous dédierions des centaines de T-shirts à cet étonnant trésor de belles images, mais pour l\'instant, nous devons nous contenter de ce que vous voyez ici. Vous n\'avez pas besoin d\'être Italien pour aimer ces T-shirts magnifiques, juste quelqu\'un qui apprécie les belles choses de la vie !'),
(3, 1, 'Irlandais', 'C\'est Churchill qui a fait remarquer qu\'il trouvait les Irlandais des plus curieux parce qu\'ils ne voulaient pas être Anglais. Comme il avait raison ! Mais après tout, il était à moitié Américain, n\'est-ce pas ? Si vous avez une généalogie irlandaise, vous voudrez ces T-shirts ! Si vous devenez soudainement Irlandais le jour de la Saint-Patrick, vous voudrez aussi ces T-shirts ! Jetez un œil à quelques-uns de nos T-shirts les plus cool !'),
(4, 2, 'Animaux', ' Notre sélection toujours croissante de magnifiques T-shirts d\'animaux représente des créatures de partout, à la fois sauvages et domestiques. Si vous ne voyez pas le T-shirt avec l\'animal que vous cherchez, dites-le nous et nous le trouverons !'),
(5, 2, 'Fleurs', 'Ces T-shirts uniques et magnifiques sont parfaits pour le jardinier, l\'arrangeur floral, le fleuriste, ou l\'amateur général de belles choses. Surprenez la fleur de votre vie avec un de ces beaux T-shirts botaniques ou achetez-en juste quelques-uns pour vous-même !'),
(6, 3, 'Noël', ' Parce que c\'est un T-shirt de Noël unique que vous ne porterez que quelques fois par an, il durera probablement des décennies (sauf si un grincheux vous le pique, bien sûr). Loin dans le futur, après votre départ, vos petits-enfants le ressortiront et se disputeront pour savoir qui le portera. Quelles belles photos ils feront habillés du T-shirt de Noël incroyablement de bon goût et unique de Papy ou Mamie ! Oui, tout le monde se souviendra de vous pour toujours et quel drôle de farceur vous étiez lorsque vous ne portiez que votre barbe et bonnet de Père Noël pour ne pas cacher votre T-shirt chic.'),
(7, 3, 'Saint-Valentin', 'Pour les plus timides, tout ce que vous avez à faire est de porter votre message sincère pour le faire passer. Achetez-en un pour vous et votre (vos) chéri(s) aujourd\'hui !');

-- --------------------------------------------------------

--
-- Structure de la table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `department`
--

INSERT INTO `department` (`department_id`, `name`, `description`) VALUES
(1, 'Regional', 'Proud of your country? Wear a T-shirt with a national \r\nsymbol stamp!'),
(2, 'Nature', 'Find beautiful T-shirts with animals and flowers in our \r\nNature department!'),
(3, 'Seasonal', 'Each time of the year has a special flavor. Our seasonal \r\nT-shirts express traditional symbols using unique postal stamp pictures.');

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discounted_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `image` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_2` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`product_id`, `name`, `description`, `price`, `discounted_price`, `image`, `image_2`, `thumbnail`, `display`) VALUES
(1, 'Cuisinière 4 Feux Inox Premium', 'Cuisinière à gaz 4 feux avec four à convection, finition inox brossé.', 799.99, 749.99, 'prod_inox_1.jpg', 'prod_inox_2.jpg', 'thumb_inox.jpg', 2),
(2, 'Cuisinière Classique 3 Feux', 'Modèle standard, simple et fiable, idéal pour les petites cuisines.', 349.99, 0.00, 'prod_class_1.jpg', 'prod_class_2.jpg', 'thumb_class.jpg', 1),
(3, 'Four Encastrable Haute Performance', 'Four à gaz encastrable avec grill, contrôle électronique de la température.', 450.00, 420.00, 'prod_four_1.jpg', 'prod_four_2.jpg', 'thumb_four.jpg', 2),
(4, 'Plaque de Cuisson 5 Brûleurs', 'Plaque en verre trempé avec 5 brûleurs, dont un wok puissant.', 299.99, 269.99, 'prod_plaque5_1.jpg', 'prod_plaque5_2.jpg', 'thumb_plaque5.jpg', 2),
(5, 'Détendeur Sécurité Norme CE', 'Détendeur pour bouteille de gaz butane, avec indicateur de niveau.', 25.50, 0.00, 'acc_det_1.jpg', 'acc_det_2.jpg', 'thumb_det.jpg', 1),
(6, 'Tuyau de gaz flexible (1.5m)', 'Tuyau de raccordement en caoutchouc renforcé, validité 10 ans.', 15.00, 0.00, 'acc_tuyau_1.jpg', 'acc_tuyau_2.jpg', 'thumb_tuyau.jpg', 1),
(7, 'Allumeur électronique long', 'Allume-gaz électronique sans flamme, rechargeable.', 8.99, 0.00, 'acc_allum_1.jpg', 'acc_allum_2.jpg', 'thumb_allum.jpg', 1),
(8, 'Grille de protection anti-éclaboussures', 'Grille universelle pour protéger le mur des éclaboussures de graisse.', 19.99, 15.99, 'acc_grille_1.jpg', 'acc_grille_2.jpg', 'thumb_grille.jpg', 1),
(9, 'Set de 12 Ustensiles Anti-adhésif', 'Batterie complète de casseroles et poêles, compatible tout feu.', 120.00, 0.00, 'kit_bat_1.jpg', 'kit_bat_2.jpg', 'thumb_bat.jpg', 1),
(10, 'Livre: Cuisiner au gaz comme un Chef', 'Recueil de recettes optimisées pour la cuisson au gaz.', 22.50, 0.00, 'kit_livre_1.jpg', 'kit_livre_2.jpg', 'thumb_livre.jpg', 0),
(11, 'Mini Cuisinière 2 Feux', 'Modèle compact pour studio ou camping, très faible encombrement.', 189.99, 0.00, 'prod_mini_1.jpg', 'prod_mini_2.jpg', 'thumb_mini.jpg', 2),
(12, 'Spray Nettoyant Dégraissant spécial four', 'Formule professionnelle pour enlever les graisses carbonisées.', 9.50, 7.00, 'acc_net_1.jpg', 'acc_net_2.jpg', 'thumb_net.jpg', 1),
(13, 'Wok professionnel Acier Carbone 32cm', 'Idéal pour la cuisson rapide et le sauté sur les brûleurs gaz.', 45.99, 0.00, 'kit_wok_1.jpg', 'kit_wok_2.jpg', 'thumb_wok.jpg', 1),
(14, 'Conteneur Bouteille Propane 13kg', 'Bouteille vide standard pour les systèmes propane.', 75.00, 0.00, 'acc_bouteille_1.jpg', 'acc_bouteille_2.jpg', 'thumb_bouteille.jpg', 0),
(15, 'Thermomètre Four Digital Précis', 'Accessoire pour contrôler la température interne des fours.', 12.99, 0.00, 'acc_thermo_1.jpg', 'acc_thermo_2.jpg', 'thumb_thermo.jpg', 1),
(16, 'Plaque Vitrocéramique à Induction 4 Zones', 'Plaque encastrable moderne à induction pour une cuisson rapide et précise.', 399.00, 350.00, 'prod_induc_1.jpg', 'prod_induc_2.jpg', 'thumb_induc.jpg', 2),
(17, 'Hotte Aspirante Murale Inox', 'Hotte puissante et silencieuse pour éliminer les odeurs et la vapeur.', 199.99, 0.00, 'acc_hotte_1.jpg', 'acc_hotte_2.jpg', 'thumb_hotte.jpg', 1),
(18, 'Poêle à Frire en Fonte Émaillée 28cm', 'Idéale pour saisir et mijoter. Excellente rétention de la chaleur.', 55.99, 49.99, 'kit_fonte_1.jpg', 'kit_fonte_2.jpg', 'thumb_fonte.jpg', 1),
(19, 'Rôtissoire Universelle pour Four', 'Bac de rôtissage en acier inoxydable avec grille amovible.', 29.50, 0.00, 'kit_rotis_1.jpg', 'kit_rotis_2.jpg', 'thumb_rotis.jpg', 1),
(20, 'Nettoyant Spécial Vitres de Four et Tables de Cuisson', 'Formule sans traces pour toutes les surfaces en verre et céramique.', 10.99, 0.00, 'acc_net_vitre_1.jpg', 'acc_net_vitre_2.jpg', 'thumb_net_vitre.jpg', 1),
(21, 'Briquet de Sécurité Allongé (Réglable)', 'Parfait pour allumer les fours et les brûleurs en toute sécurité.', 6.50, 0.00, 'acc_briquet_1.jpg', 'acc_briquet_2.jpg', 'thumb_briquet.jpg', 0),
(22, 'Kit de Conversion Buse Gaz Naturel', 'Jeu de buses de rechange pour convertir la cuisinière au gaz de ville.', 18.00, 0.00, 'acc_buse_1.jpg', 'acc_buse_2.jpg', 'thumb_buse.jpg', 0),
(23, 'Minuteur de Cuisine Digital Magnétique', 'Grand écran, compte à rebours et chronomètre.', 14.50, 11.99, 'kit_timer_1.jpg', 'kit_timer_2.jpg', 'thumb_timer.jpg', 1),
(24, 'Grande Cuisinière Semi-Professionnelle 6 Feux', 'Modèle large avec double four, idéal pour les passionnés de cuisine.', 1499.00, 1399.00, 'prod_semi_pro_1.jpg', 'prod_semi_pro_2.jpg', 'thumb_semi_pro.jpg', 2),
(25, 'Lot de 2 Maniques en Silicone Anti-chaleur', 'Protection maximale pour manipuler les plats chauds du four.', 15.00, 0.00, 'kit_maniq_1.jpg', 'kit_maniq_2.jpg', 'thumb_maniq.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `product_attribute`
--

CREATE TABLE `product_attribute` (
  `product_id` int(11) NOT NULL,
  `attribute_value_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `product_attribute`
--

INSERT INTO `product_attribute` (`product_id`, `attribute_value_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(2, 14),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(3, 8),
(3, 9),
(3, 10),
(3, 11),
(3, 12),
(3, 13),
(3, 14),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 5),
(4, 6),
(4, 7),
(4, 8),
(4, 9),
(4, 10),
(4, 11),
(4, 12),
(4, 13),
(4, 14),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(5, 9),
(5, 10),
(5, 11),
(5, 12),
(5, 13),
(5, 14),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 5),
(6, 6),
(6, 7),
(6, 8),
(6, 9),
(6, 10),
(6, 11),
(6, 12),
(6, 13),
(6, 14),
(7, 1),
(7, 2),
(7, 3),
(7, 4),
(7, 5),
(7, 6),
(7, 7),
(7, 8),
(7, 9),
(7, 10),
(7, 11),
(7, 12),
(7, 13),
(7, 14),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 5),
(8, 6),
(8, 7),
(8, 8),
(8, 9),
(8, 10),
(8, 11),
(8, 12),
(8, 13),
(8, 14),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(9, 5),
(9, 6),
(9, 7),
(9, 8),
(9, 9),
(9, 10),
(9, 11),
(9, 12),
(9, 13),
(9, 14),
(10, 1),
(10, 2),
(10, 3),
(10, 4),
(10, 5),
(10, 6),
(10, 7),
(10, 8),
(10, 9),
(10, 10),
(10, 11),
(10, 12),
(10, 13),
(10, 14),
(11, 1),
(11, 2),
(11, 3),
(11, 4),
(11, 5),
(11, 6),
(11, 7),
(11, 8),
(11, 9),
(11, 10),
(11, 11),
(11, 12),
(11, 13),
(11, 14),
(12, 1),
(12, 2),
(12, 3),
(12, 4),
(12, 5),
(12, 6),
(12, 7),
(12, 8),
(12, 9),
(12, 10),
(12, 11),
(12, 12),
(12, 13),
(12, 14),
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(13, 5),
(13, 6),
(13, 7),
(13, 8),
(13, 9),
(13, 10),
(13, 11),
(13, 12),
(13, 13),
(13, 14),
(14, 1),
(14, 2),
(14, 3),
(14, 4),
(14, 5),
(14, 6),
(14, 7),
(14, 8),
(14, 9),
(14, 10),
(14, 11),
(14, 12),
(14, 13),
(14, 14),
(15, 1),
(15, 2),
(15, 3),
(15, 4),
(15, 5),
(15, 6),
(15, 7),
(15, 8),
(15, 9),
(15, 10),
(15, 11),
(15, 12),
(15, 13),
(15, 14),
(16, 1),
(16, 2),
(16, 3),
(16, 4),
(16, 5),
(16, 6),
(16, 7),
(16, 8),
(16, 9),
(16, 10),
(16, 11),
(16, 12),
(16, 13),
(16, 14),
(17, 1),
(17, 2),
(17, 3),
(17, 4),
(17, 5),
(17, 6),
(17, 7),
(17, 8),
(17, 9),
(17, 10),
(17, 11),
(17, 12),
(17, 13),
(17, 14),
(18, 1),
(18, 2),
(18, 3),
(18, 4),
(18, 5),
(18, 6),
(18, 7),
(18, 8),
(18, 9),
(18, 10),
(18, 11),
(18, 12),
(18, 13),
(18, 14),
(19, 1),
(19, 2),
(19, 3),
(19, 4),
(19, 5),
(19, 6),
(19, 7),
(19, 8),
(19, 9),
(19, 10),
(19, 11),
(19, 12),
(19, 13),
(19, 14),
(20, 1),
(20, 2),
(20, 3),
(20, 4),
(20, 5),
(20, 6),
(20, 7),
(20, 8),
(20, 9),
(20, 10),
(20, 11),
(20, 12),
(20, 13),
(20, 14),
(21, 1),
(21, 2),
(21, 3),
(21, 4),
(21, 5),
(21, 6),
(21, 7),
(21, 8),
(21, 9),
(21, 10),
(21, 11),
(21, 12),
(21, 13),
(21, 14),
(22, 1),
(22, 2),
(22, 3),
(22, 4),
(22, 5),
(22, 6),
(22, 7),
(22, 8),
(22, 9),
(22, 10),
(22, 11),
(22, 12),
(22, 13),
(22, 14),
(23, 1),
(23, 2),
(23, 3),
(23, 4),
(23, 5),
(23, 6),
(23, 7),
(23, 8),
(23, 9),
(23, 10),
(23, 11),
(23, 12),
(23, 13),
(23, 14),
(24, 1),
(24, 2),
(24, 3),
(24, 4),
(24, 5),
(24, 6),
(24, 7),
(24, 8),
(24, 9),
(24, 10),
(24, 11),
(24, 12),
(24, 13),
(24, 14),
(25, 1),
(25, 2),
(25, 3),
(25, 4),
(25, 5),
(25, 6),
(25, 7),
(25, 8),
(25, 9),
(25, 10),
(25, 11),
(25, 12),
(25, 13),
(25, 14);

-- --------------------------------------------------------

--
-- Structure de la table `product_category`
--

CREATE TABLE `product_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(7, 3),
(8, 5),
(9, 4),
(9, 5),
(10, 5),
(11, 3),
(12, 6),
(12, 7),
(13, 4),
(14, 6),
(15, 6),
(15, 7),
(16, 2),
(16, 5),
(17, 3),
(18, 4),
(19, 6),
(20, 7),
(21, 1),
(22, 2),
(23, 3),
(24, 1),
(24, 4),
(25, 5),
(25, 6),
(25, 7);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `attribute`
--
ALTER TABLE `attribute`
  ADD PRIMARY KEY (`attribute_id`);

--
-- Index pour la table `attribute_value`
--
ALTER TABLE `attribute_value`
  ADD PRIMARY KEY (`attribute_value_id`),
  ADD KEY `idx_attribute_value_attribute_id` (`attribute_id`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `idx_category_department_id` (`department_id`);

--
-- Index pour la table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Index pour la table `product_attribute`
--
ALTER TABLE `product_attribute`
  ADD PRIMARY KEY (`product_id`,`attribute_value_id`);

--
-- Index pour la table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_id`,`category_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `attribute`
--
ALTER TABLE `attribute`
  MODIFY `attribute_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `attribute_value`
--
ALTER TABLE `attribute_value`
  MODIFY `attribute_value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
