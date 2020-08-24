-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 24 août 2020 à 17:21
-- Version du serveur :  10.4.13-MariaDB
-- Version de PHP : 7.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `annonce`
--
DROP DATABASE IF EXISTS `annonce`;
CREATE DATABASE IF NOT EXISTS `annonce` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `annonce`;

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

CREATE TABLE `annonces` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `users_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `departements_number` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`id`, `title`, `content`, `image`, `price`, `created_at`, `users_id`, `categories_id`, `departements_number`) VALUES
(1, 'brouette', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec maximus, risus ac pellentesque pellentesque, leo sapien tincidunt est, ac scelerisque ex lectus pharetra eros. Curabitur vitae turpis accumsan, viverra justo et, gravida augue. Phasellus turpis quam, vulputate eget volutpat quis, dapibus et augue. Cras interdum varius tortor, sed congue nunc fermentum sed. Nam dignissim rhoncus ex eu lacinia. Nullam dui odio, condimentum sed lobortis eget, interdum a nisl. Praesent eros orci, scelerisque ut placerat id, mattis ut diam. Mauris tellus sem, placerat tincidunt tempus in, lacinia vitae metus. Phasellus in laoreet quam, in bibendum erat. Suspendisse potenti. Praesent imperdiet porttitor imperdiet. Cras vitae augue et libero placerat iaculis non a metus. Ut congue, purus in feugiat feugiat, augue neque laoreet ex, eleifend vehicula arcu turpis ac ligula. Cras malesuada ligula tortor, a tincidunt mi auctor sed. Praesent condimentum, ante at pellentesque maximus, diam neque fringilla quam, quis malesuada diam risus facilisis arcu. Nulla eget ornare augue. ', '', 20, '2020-08-24 15:23:57', 1, 1, '31'),
(2, 'cuillère', 'Maecenas a lacus efficitur, suscipit justo vitae, auctor urna. Curabitur vulputate ligula in odio viverra, quis blandit erat scelerisque. Maecenas facilisis molestie nibh, eget dignissim leo gravida quis. Pellentesque pulvinar iaculis egestas. Vivamus sed suscipit sem, sit amet lobortis nisl. Sed feugiat, sem ut ultrices suscipit, erat libero semper purus, vitae cursus erat turpis vitae velit. Fusce maximus turpis a libero tristique fringilla. Aliquam erat volutpat. Nulla diam nunc, elementum et elit vitae, iaculis ultricies massa. Suspendisse a quam blandit, aliquam odio sed, dapibus orci. Vestibulum euismod ac mi a tristique. Ut ut odio pulvinar, eleifend ante et, pharetra velit. Nam sed consectetur quam. Quisque malesuada quam ac nisl convallis, vel consequat dolor vestibulum. Integer vel consequat risus. Aliquam a aliquam dolor, in faucibus augue. ', '', 30.5, '2020-08-24 15:23:57', 1, 2, '12'),
(3, 'fourchette', 'Etiam gravida sem sit amet mauris volutpat, eget fringilla diam molestie. Aliquam tristique augue ut lacus tincidunt, ornare ornare orci dapibus. Maecenas eget aliquet justo. Fusce in laoreet magna. Nullam justo lectus, vulputate vitae rutrum id, tincidunt nec sapien. Donec euismod ut ipsum sit amet accumsan. Phasellus efficitur odio a libero molestie sollicitudin. Nulla dolor lacus, ultrices eget faucibus eget, placerat eget quam. Cras aliquet ipsum ut lacus consectetur tempor. Vestibulum eu quam et justo faucibus luctus a in ante. Donec id leo eros. Nunc ut aliquet quam. Sed sem lacus, vulputate in pulvinar non, dictum nec neque. Etiam pellentesque lorem magna, in molestie massa venenatis vitae. ', NULL, 15.25, '2020-08-24 16:12:15', 1, 1, '34 '),
(4, 'gneuu', 'Aenean in lacus at felis dignissim posuere. Sed ut finibus elit. Mauris hendrerit tortor ac libero viverra vestibulum. Vivamus at congue ligula. Sed non eleifend lacus, quis maximus tortor. Mauris urna tortor, aliquam non suscipit vitae, condimentum aliquam est. Donec vitae malesuada nisl, non congue justo. Pellentesque tincidunt congue velit, eget aliquet arcu vestibulum quis. Proin imperdiet nisi vitae tortor posuere posuere. Morbi feugiat felis ac elit suscipit, mollis faucibus lectus faucibus. Morbi purus sapien, feugiat nec nisi non, euismod mattis eros. Etiam ac aliquam neque, et posuere mi. Quisque ut venenatis purus, a tempus neque. Donec porttitor odio sapien, posuere ultricies urna laoreet nec. Mauris sit amet lorem vitae libero posuere sagittis. ', NULL, 60, '2020-08-24 17:07:07', 1, 2, '31 ');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Livres'),
(2, 'Jeux vidéos');

-- --------------------------------------------------------

--
-- Structure de la table `departements`
--

CREATE TABLE `departements` (
  `number` varchar(3) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `departements`
--

INSERT INTO `departements` (`number`, `name`) VALUES
('01', 'Ain'),
('02', 'Aisne'),
('03', 'Allier'),
('04', 'Alpes-de-Haute-Provence'),
('05', 'Hautes-Alpes'),
('06', 'Alpes-Maritimes'),
('07', 'Ardèche'),
('08', 'Ardennes'),
('09', 'Ariège'),
('10', 'Aube'),
('11', 'Aude'),
('12', 'Aveyron'),
('13', 'Bouches-du-Rhône'),
('14', 'Calvados'),
('15', 'Cantal'),
('16', 'Charente'),
('17', 'Charente-Maritime'),
('18', 'Cher'),
('19', 'Corrèze'),
('21', 'Côte-d\'Or'),
('22', 'Côtes-d\'Armor'),
('23', 'Creuse'),
('24', 'Dordogne'),
('25', 'Doubs'),
('26', 'Drôme'),
('27', 'Eure'),
('28', 'Eure-et-Loir'),
('29', 'Finistère'),
('2A', 'Corse-du-Sud'),
('2B', 'Haute-Corse'),
('30', 'Gard'),
('31', 'Haute-Garonne'),
('32', 'Gers'),
('33', 'Gironde'),
('34', 'Hérault'),
('35', 'Ille-et-Vilaine'),
('36', 'Indre'),
('37', 'Indre-et-Loire'),
('38', 'Isère'),
('39', 'Jura'),
('40', 'Landes'),
('41', 'Loir-et-Cher'),
('42', 'Loire'),
('43', 'Haute-Loire'),
('44', 'Loire-Atlantique'),
('45', 'Loiret'),
('46', 'Lot'),
('47', 'Lot-et-Garonne'),
('48', 'Lozère'),
('49', 'Maine-et-Loire'),
('50', 'Manche'),
('51', 'Marne'),
('52', 'Haute-Marne'),
('53', 'Mayenne'),
('54', 'Meurthe-et-Moselle'),
('55', 'Meuse'),
('56', 'Morbihan'),
('57', 'Moselle'),
('58', 'Nièvre'),
('59', 'Nord'),
('60', 'Oise'),
('61', 'Orne'),
('62', 'Pas-de-Calais'),
('63', 'Puy-de-Dôme'),
('64', 'Pyrénées-Atlantiques'),
('65', 'Hautes-Pyrénées'),
('66', 'Pyrénées-Orientales'),
('67', 'Bas-Rhin'),
('68', 'Haut-Rhin'),
('69', 'Rhône'),
('70', 'Haute-Saône'),
('71', 'Saône-et-Loire'),
('72', 'Sarthe'),
('73', 'Savoie'),
('74', 'Haute-Savoie'),
('75', 'Paris'),
('76', 'Seine-Maritime'),
('77', 'Seine-et-Marne'),
('78', 'Yvelines'),
('79', 'Deux-Sèvres'),
('80', 'Somme'),
('81', 'Tarn'),
('82', 'Tarn-et-Garonne'),
('83', 'Var'),
('84', 'Vaucluse'),
('85', 'Vendée'),
('86', 'Vienne'),
('87', 'Haute-Vienne'),
('88', 'Vosges'),
('89', 'Yonne'),
('90', 'Territoire de Belfort'),
('91', 'Essonne'),
('92', 'Hauts-de-Seine'),
('93', 'Seine-Saint-Denis'),
('94', 'Val-de-Marne'),
('95', 'Val-d\'Oise'),
('971', 'Guadeloupe'),
('972', 'Martinique'),
('973', 'Guyane'),
('974', 'La Réunion'),
('976', 'Mayotte');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `tel` varchar(18) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '["ROLE_USER"]' CHECK (json_valid(`roles`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `pseudo`, `tel`, `roles`) VALUES
(1, 'a@a.com', '$argon2id$v=19$m=65536,t=4,p=1$ZmpYbWtnMGJ2NEMubXpGVg$cUch0EOspLNDa+UmCcOyCMk4srQzuWe1QrHeLV4j/9A', 'Cherry Drop', '0601010101', '[\"ROLE_USER\", \"ROLE_ADMIN\"]');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departements_number` (`departements_number`),
  ADD KEY `categories_id` (`categories_id`),
  ADD KEY `users_id` (`users_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`number`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `annonces`
--
ALTER TABLE `annonces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD CONSTRAINT `annonces_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `annonces_ibfk_2` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `annonces_ibfk_3` FOREIGN KEY (`departements_number`) REFERENCES `departements` (`number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
