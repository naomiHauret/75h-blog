-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2017 at 06:59 AM
-- Server version: 5.7.11
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_blog_naomi`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL,
  `article_title` varchar(140) NOT NULL,
  `article_intro` text NOT NULL,
  `article_content` longtext NOT NULL,
  `article_authorid` int(11) NOT NULL,
  `article_tagslist` text NOT NULL,
  `article_publicationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `article_updatedate` timestamp NULL DEFAULT NULL,
  `article_coverpath` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`article_id`, `article_title`, `article_intro`, `article_content`, `article_authorid`, `article_tagslist`, `article_publicationdate`, `article_updatedate`, `article_coverpath`) VALUES
(27, 'Le positionnement en CSS', 'TL;DR: absolute, relative, fixed. C\'est comme dans les toilettes en festival: par pitié, n\'en mettez pas partout.', '&quot;position&quot; est une propriété CSS. Comme son nom l\'indique, elle permet de positionner un élément d\'une certaine façon: fixe (fixed), absolue (absolute), relative (relative) et statique (static).\r\n\r\nPar défaut, tous les éléments HTML sont positionnés de façon statique: c\'est pour cela qu\'ils ne sont pas affectés par les propriétés top, bottom, left et right. Un élément positionné en statique n\'est... pas spécialement positionné. En fait, il suit le flux de la page.\r\n\r\nPar contre, une fois positionné de façon relative, un élément se place de façon relative à sa position de départ. Une fois positionné en relatif, le reste du contenu ne comblera pas l\'espace laissé par l\'élément.\r\n\r\nA l\'inverse, pour un élément positionné en absolute, le reste du contenu viendra remplir l\'espace laissé. Un élément absolute se positionne en fonction de son ancêtre avec une position autre que static le plus proche. S\'il n\'en a pas, il va se positionner par rapport au body.\r\n\r\nEnfin, un élément positionné en fixed se place par rapport au viewport: il reste toujours au même endroit, même quand on scroll (à la différence d\'un élément positionné en absolute).\r\n\r\nIl ne faut pas utiliser la propriété position à tout va: ça peut vite rendre le style ingérable.', 5, 'frontend; css3; intégration;', '2017-09-07 03:38:30', NULL, 'dist/assets/img/covers/css.png'),
(28, 'Le flux', 'TL;DR: N\'en sortez pas.', 'Le flux, c\'est l\'ordre dans lequel vont s\'afficher tous les éléments qui composent une page web. La nature étant bien faite, ils s\'affichent (à la base) dans l\'ordre dans lequel ils sont déclarés.\r\n\r\nIl y a 2 grandes familles d\'affichage pour les éléments: en ligne (inline) et à la ligne (block).\r\n\r\nOn peut changer la façon dont les éléments s\'affichent grâce à 3 propriétés principales: position, display et float.\r\n\r\nQu\'on soit clair dès maintenant, les float, c\'est un peu le sheitan: ça fait sortir les éléments du flux, et on peut vite se perdre. Donc non, nous ne parlerons pas des float.\r\n\r\nIl vaut mieux donc utiliser position et display.\r\n\r\nMais avec parcimonie.', 6, 'frontend; css;', '2017-09-07 04:06:14', NULL, 'dist/assets/img/covers/css-awesome.jpg'),
(26, 'Mon stage chez Troopers', 'J\'ai fait un stage de 6 mois chez Troopers. Spoiler: c\'était bien.', 'TL;DR: J\'ai fait un stage de 6 mois chez Troopers en tant que développeuse front-end. Mon travail consistait à analyser les maquettes que me donnait Clément, le designer, et à les transposer en des morceaux de code les plus petits et les plus réutilisables possibles: on appelle ça un styleguide. Après avoir créé ce styleguide à l\'aide d\'HTML/Twig, CSS/Sass et Javascript, je devais intégrer les maquettes complètes à l\'aide de Victoire, le CMS de Troopers (en gros, c\'est comme Wordpress, mais en mieux).\r\n\r\nLa team avec qui j\'ai travaillé a été très cool et j\'ai appris plein de trucs. Je me suis énormément améliorée en intégration. Quand j\'entends le nom Quo Vadis, je me mets en PLS parce que c\'est un des clients qui m\'a donné le plus de fil à retordre.\r\n\r\nMais sinon c\'était vachement bien. C\'était tellement bien que je vais y retourner mon alternance de 2 ans.', 6, 'stage; front-end;', '2017-09-07 03:22:59', NULL, 'dist/assets/img/covers/troopers.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_authorid` int(11) DEFAULT NULL,
  `comment_articleid` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `comment_authorid`, `comment_articleid`, `comment_content`, `comment_date`) VALUES
(17, NULL, 28, 'Quel article fantasique.', '2017-09-07 04:17:22'),
(4, NULL, 2, 'mon super commentaire anonyme', '2017-09-05 09:50:20'),
(5, 0, 2, 'Mon super commentaire pas anonyme', '2017-09-05 09:51:05'),
(18, 4, 28, 'C\'est fabuleux', '2017-09-07 04:21:14'),
(19, 4, 26, 'Meilleure agence du monde !', '2017-09-07 04:22:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_firstname` varchar(25) NOT NULL,
  `user_lastname` varchar(25) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_mail` varchar(60) NOT NULL,
  `user_password` varchar(60) NOT NULL,
  `user_pseudo` varchar(60) DEFAULT NULL,
  `user_is_admin` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_firstname`, `user_lastname`, `user_id`, `user_mail`, `user_password`, `user_pseudo`, `user_is_admin`) VALUES
('Naomi', 'Hauret', 6, 'hauretnaomi@gmail.com', 'motherfuckingpasswordofdoom', '', 1),
('Jean', 'Testeur', 5, 'jeantester@mail.com', 'test', NULL, 0),
('Jon', 'Doe', 4, 'jondoe@mail.com', 'password', NULL, 1),
('Lucas', 'Doe', 8, 'admin@mail.com', 'password', NULL, 1),
('Jane', 'Doe', 9, 'tester@mail.com', 'password', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`article_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
