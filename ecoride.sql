-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 17 fév. 2025 à 20:00
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
-- Base de données : `ecoride`
--

-- --------------------------------------------------------

--
-- Structure de la table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `covoiturage_id` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plaque_immatriculation` varchar(20) NOT NULL,
  `date_immatriculation` date NOT NULL,
  `modele` varchar(255) DEFAULT NULL,
  `marque` varchar(50) NOT NULL,
  `couleur` varchar(20) NOT NULL,
  `nb_places` int(11) NOT NULL,
  `fumeur` enum('oui','non') DEFAULT 'non',
  `animaux` enum('oui','non') DEFAULT 'non',
  `preferences` text DEFAULT NULL,
  `eco` enum('oui','non') DEFAULT 'non'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cars`
--

INSERT INTO `cars` (`id`, `user_id`, `plaque_immatriculation`, `date_immatriculation`, `modele`, `marque`, `couleur`, `nb_places`, `fumeur`, `animaux`, `preferences`, `eco`) VALUES
(11, 9, 'AB-123-CD', '2021-06-15', 'Model 5', 'Tesla', 'bleu', 4, 'non', 'non', 'Musique douce', 'oui'),
(13, 8, 'AA-123-BB', '2025-01-01', 'Modele S', 'Tesla', 'Blanc', 4, 'non', 'non', 'calme', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `covoiturages`
--

CREATE TABLE `covoiturages` (
  `id` int(11) NOT NULL,
  `chauffeur_id` int(11) NOT NULL,
  `vehicule_id` int(11) NOT NULL,
  `ville_depart` varchar(255) DEFAULT NULL,
  `ville_arrivee` varchar(255) DEFAULT NULL,
  `date_depart` date DEFAULT NULL,
  `prix` int(20) DEFAULT NULL,
  `nb_places` int(11) NOT NULL,
  `statut` enum('disponible','complet','annulé') DEFAULT 'disponible',
  `eco` enum('oui','non') DEFAULT 'non',
  `photo` varchar(255) NOT NULL DEFAULT 'https://www.gravatar.com/avatar/?d=mp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `covoiturages`
--

INSERT INTO `covoiturages` (`id`, `chauffeur_id`, `vehicule_id`, `ville_depart`, `ville_arrivee`, `date_depart`, `prix`, `nb_places`, `statut`, `eco`, `photo`) VALUES
(5, 8, 13, 'paris', 'marseille', '2025-12-31', 5, 4, 'disponible', 'oui', 'https://www.gravatar.com/avatar/?d=mp'),
(6, 9, 11, 'lyon', 'paris', '2025-12-31', 5, 4, 'annulé', 'oui', 'https://www.gravatar.com/avatar/?d=mp');

-- --------------------------------------------------------

--
-- Structure de la table `credits_transactions`
--

CREATE TABLE `credits_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `départ` varchar(100) NOT NULL,
  `arrivée` varchar(100) NOT NULL,
  `date_heure_depart` datetime NOT NULL,
  `date_heure_arrivée` datetime NOT NULL,
  `prix` int(11) NOT NULL,
  `nb_places_restantes` int(11) NOT NULL,
  `statut` enum('écologique','non écologique') NOT NULL,
  `état` enum('en cours','terminé','annulé') DEFAULT 'en cours'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trip_passengers`
--

CREATE TABLE `trip_passengers` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `statut` enum('validé','annulé','problème signalé') DEFAULT 'validé'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employe','chauffeur','passager','chauffeur-passager') NOT NULL,
  `credits` int(11) DEFAULT 20,
  `photo` varchar(255) NOT NULL DEFAULT 'https://www.gravatar.com/avatar/?d=mp',
  `note_moyenne` decimal(2,1) DEFAULT 5.0 CHECK (`note_moyenne` between 0 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `password`, `role`, `credits`, `photo`, `note_moyenne`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$pBCDelWaHPstDZ3keupg7OyYnZHfdok5PMOUi3.C9ZoMPot4mLUwS', 'admin', 100, 'https://www.gravatar.com/avatar/?d=mp', 5.0),
(2, 'employé', 'employe@example.com', '$2y$10$yITRIAuOBVWsLO.4.u79CuTkBiwmBIcnJSR6/drvzATMcTv6nQuxa', 'employe', 20, 'https://www.gravatar.com/avatar/?d=mp', 5.0),
(8, 'Chauffeur', 'chauffeur@example.com', '$2y$10$X..hAMK4qmd/F1L8Xu/WhOiUxjvlNGATJS7B0pZq2pt2QAJYIKnDS', 'chauffeur', 20, 'https://www.gravatar.com/avatar/?d=mp', 5.0),
(9, 'both', 'both@example.com', '$2y$10$uVFrzl5/S7a3eLp1rb7Fs.GL9md8exFMelKzsM37KwzN/3rSNqvVS', 'chauffeur-passager', 20, 'https://www.gravatar.com/avatar/?d=mp', 5.0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `covoiturage_id` (`covoiturage_id`);

--
-- Index pour la table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plaque_immatriculation` (`plaque_immatriculation`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `covoiturages`
--
ALTER TABLE `covoiturages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chauffeur_id` (`chauffeur_id`),
  ADD KEY `vehicule_id` (`vehicule_id`);

--
-- Index pour la table `credits_transactions`
--
ALTER TABLE `credits_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Index pour la table `trip_passengers`
--
ALTER TABLE `trip_passengers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_id` (`trip_id`),
  ADD KEY `passenger_id` (`passenger_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `covoiturages`
--
ALTER TABLE `covoiturages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `credits_transactions`
--
ALTER TABLE `credits_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trip_passengers`
--
ALTER TABLE `trip_passengers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `covoiturages`
--
ALTER TABLE `covoiturages`
  ADD CONSTRAINT `covoiturages_ibfk_1` FOREIGN KEY (`chauffeur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `covoiturages_ibfk_2` FOREIGN KEY (`vehicule_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `credits_transactions`
--
ALTER TABLE `credits_transactions`
  ADD CONSTRAINT `credits_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trip_passengers`
--
ALTER TABLE `trip_passengers`
  ADD CONSTRAINT `trip_passengers_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trip_passengers_ibfk_2` FOREIGN KEY (`passenger_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
