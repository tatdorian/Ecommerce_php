-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 03 juin 2025 à 12:06
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecommerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `date_publication` datetime DEFAULT current_timestamp(),
  `auteur_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `titre_livre` varchar(255) DEFAULT NULL,
  `auteur_livre` varchar(255) DEFAULT NULL,
  `date_livre` date DEFAULT NULL,
  `genre_livre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `nom`, `description`, `prix`, `date_publication`, `auteur_id`, `image`, `titre_livre`, `auteur_livre`, `date_livre`, `genre_livre`) VALUES
(23, 'Le Seigneur des Anneaux, J.R.R. Tolkien', 'Un monde de dragons, de quêtes et de magie.', 29.90, '2025-06-03 11:59:34', 1, 'https://www.gallimard-jeunesse.fr/assets/media/cache/cover_medium/gallimard_img/image/J02275.jpg', 'Le Seigneur des Anneaux', 'J.R.R. Tolkien', '1954-07-29', 'Fantasy'),
(24, 'Harry Potter à l\'école des sorciers, J.K. Rowling', 'Un jeune sorcier découvre un monde magique.', 19.99, '2025-06-03 11:59:34', 1, 'https://m.media-amazon.com/images/I/81jVPDq3HKL._AC_UF1000,1000_QL80_.jpg', 'Harry Potter à l\'école des sorciers', 'J.K. Rowling', '1997-06-26', 'Fantasy'),
(25, '1984, George Orwell', 'Une société où tout est contrôlé, même la pensée.', 15.50, '2025-06-03 11:59:34', 1, 'https://tankmuseumshop.org/cdn/shop/products/1984.jpg?v=1588779384&width=640', '1984', 'George Orwell', '1949-06-08', 'Science-fiction'),
(26, 'Dune, Frank Herbert', 'Un roman spatial sur la survie de l\'humanité.', 18.40, '2025-06-03 11:59:34', 1, 'https://m.media-amazon.com/images/I/614RBqUr5lL._AC_UF1000,1000_QL80_.jpg', 'Dune', 'Frank Herbert', '1965-08-01', 'Science-fiction'),
(27, 'Les Misérables, Victor Hugo', 'L’histoire tragique de Jean Valjean.', 14.90, '2025-06-03 11:59:34', 1, 'https://cdn1.booknode.com/book_cover/1364/full/les-miserables-1364334.jpg', 'Les Misérables', 'Victor Hugo', '1862-04-03', 'Classique'),
(28, 'Dracula, Bram Stoker', 'Un vampire légendaire dans les Carpates.', 13.75, '2025-06-03 11:59:34', 1, 'https://m.media-amazon.com/images/I/610v4KK+T5L._AC_UF1000,1000_QL80_.jpg', 'Dracula', 'Bram Stoker', '1897-05-26', 'Horreur'),
(29, 'Orgueil et Préjugés, Jane Austen', 'Une critique des mœurs anglaises au 19e siècle.', 12.99, '2025-06-03 11:59:34', 1, 'https://m.media-amazon.com/images/I/71Kv1sU-7XL._AC_UF1000,1000_QL80_.jpg', 'Orgueil et Préjugés', 'Jane Austen', '1813-01-28', 'Romance'),
(30, 'Moby Dick, Herman Melville', 'La chasse à la plus célèbre des baleines blanches.', 17.80, '2025-06-03 11:59:34', 1, 'https://cdn1.booknode.com/book_cover/921/full/moby-dick-920855.jpg', 'Moby Dick', 'Herman Melville', '1851-10-18', 'Aventure'),
(31, 'Le Silence des Agneaux, Thomas Harris', 'Un tueur en série et un médecin très spécial.', 16.60, '2025-06-03 11:59:34', 1, 'https://static.fnac-static.com/multimedia/PE/Images/FR/NR/8f/53/17/1528719/1507-1/tsp20250325090536/Le-silence-des-agneaux.jpg', 'Le Silence des Agneaux', 'Thomas Harris', '1988-05-19', 'Thriller'),
(32, 'Game of Thrones, George R.R. Martin', 'Le destin d’un royaume entre les mains d’un bâtard.', 22.00, '2025-06-03 11:59:34', 1, 'https://bdi.dlpdomain.com/album/9782205071139-couv.jpg', 'Game of Thrones', 'George R.R. Martin', '1996-08-06', 'Fantasy');

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_transaction` datetime DEFAULT current_timestamp(),
  `montant` decimal(10,2) NOT NULL,
  `adresse_facturation` varchar(255) NOT NULL,
  `ville_facturation` varchar(100) NOT NULL,
  `code_postal_facturation` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `old_article`
--

CREATE TABLE `old_article` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `date_publication` datetime DEFAULT current_timestamp(),
  `auteur_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `titre_livre` varchar(255) DEFAULT NULL,
  `auteur_livre` varchar(255) DEFAULT NULL,
  `date_livre` date DEFAULT NULL,
  `genre_livre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `solde` decimal(10,2) DEFAULT 0.00,
  `photo_profil` longtext DEFAULT NULL,
  `role` enum('client','admin') DEFAULT 'client',
  `nom` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `solde`, `photo_profil`, `role`, `nom`) VALUES
(1, 'admin', '$2y$10$Ry9eo5FhWO4g9QLEh8XPM.V8nJ/1.mHhFEgECGw6NjoLmbUXxreqW', 'admin@admin', 0.00, '', 'admin', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auteur_id` (`auteur_id`);

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Index pour la table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `old_article`
--
ALTER TABLE `old_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auteur_id` (`auteur_id`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `old_article`
--
ALTER TABLE `old_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`auteur_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
