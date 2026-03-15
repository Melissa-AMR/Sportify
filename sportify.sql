-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 15 mars 2026 à 14:20
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
-- Base de données : `sportify`
--

-- --------------------------------------------------------

--
-- Structure de la table `activites`
--

CREATE TABLE `activites` (
  `id` int(11) NOT NULL,
  `nom_activite` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `duree` int(11) NOT NULL COMMENT 'Durée en minutes',
  `max_participants` int(11) NOT NULL,
  `niveau` int(11) NOT NULL,
  `nom_moniteur` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `activites`
--

INSERT INTO `activites` (`id`, `nom_activite`, `description`, `prix`, `duree`, `max_participants`, `niveau`, `nom_moniteur`, `date`) VALUES
(1, 'Yoga', 'Le yoga est une discipline qui allie force, flexibilité et relaxation. Nos cours collectifs d 1h sont ouverts à tous, avec trois niveaux \r\n                adaptés à vos besoins : débutant, intermédiaire et avancé. Encadrés par Michelle Legrand, ces cours sont limités à 5 participants pour un suivi personnalisé. Ce programme vise à améliorer\r\n                 votre bien-être général, en combinant des postures et des exercices respiratoires pour renforcer votre corps tout en apaisant votre esprit.', 15.00, 60, 5, 0, 'Michelle Legrand', '2026-03-15 12:13:31'),
(2, 'Pilates', 'Le Pilates est un entraînement doux qui vise à renforcer votre corps en profondeur, améliorer votre posture et augmenter votre flexibilité.\r\n                 Nos cours collectifs d 1h, avec un maximum de 5 participants, sont adaptés à trois niveaux : débutant, intermédiaire et avancé. Sous l expertise de nos coachs, vous développerez votre stabilité, votre équilibre et votre coordination. Chaque session\r\n                 est personnalisée pour répondre à vos objectifs spécifiques, que ce soit pour améliorer votre tonus musculaire ou corriger des déséquilibres.', 18.00, 60, 3, 0, 'Marion May', '2026-03-15 12:13:31'),
(3, 'Renforcement musculaire', 'Le renforcement musculaire est une méthode efficace pour améliorer votre force, tonifier vos muscles et augmenter votre endurance. Nos séances\r\n                 d 1h, limitées à 5 participants, sont adaptées à trois niveaux : débutant, intermédiaire et avancé. Chaque programme est personnalisé en fonction de vos objectifs, que ce soit pour sculpter votre corps\r\n                 ou améliorer votre performance physique. Vous serez accompagné à chaque étape par nos coachs pour garantir des progrès visibles et durables.', 12.00, 45, 5, 0, 'Camille Lemont', '2026-03-15 12:13:31'),
(4, 'Cycling', 'Le cycling est un entraînement cardio intense, réalisé sur un vélo stationnaire, qui améliore votre endurance et votre condition physique\r\n                 globale. Nos sessions collectives d 1h sont disponibles à trois niveaux (débutant, intermédiaire, avancé) et sont encadrées par des coachs expérimentés. Avec des séances dynamiques et motivantes, vous pourrez brûler des calories\r\n                 tout en développant votre force et votre endurance. Le programme est ajusté en fonction de vos capacités et de vos objectifs personnels.', 20.00, 45, 3, 0, 'Amy Taylor', '2026-03-15 12:13:31'),
(5, 'Fitness', 'Le fitness est une activité polyvalente qui combine des exercices de cardio, de renforcement musculaire et de stretching. Nos cours collectifs\r\n                 d 1h sont adaptés à tous les niveaux (débutant, intermédiaire, avancé), et chaque programme est personnalisé selon vos besoins spécifiques. Que vous souhaitiez améliorer votre forme générale,\r\n                 perdre du poids ou tonifier votre corps, nos coachs vous guideront pour atteindre vos objectifs de manière progressive et sécurisée.', 10.00, 45, 5, 0, 'Laura Jones', '2026-03-15 12:13:31'),
(6, 'Programme personnalisé', 'Notre programme personnalisé est conçu pour répondre à vos objectifs spécifiques, qu il s agisse de perdre du poids, de gagner en force, \r\n                d améliorer votre flexibilité ou de maintenir une bonne condition physique. Chaque plan est ajusté à votre niveau, vos préférences et votre rythme. Grâce à \r\n                un suivi régulier, vous serez guidé par nos coachs pour maximiser vos performances et atteindre vos résultats en toute sécurité et durabilité.', 50.00, 60, 1, 0, 'Laura Marins', '2026-03-15 12:13:31');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `activite_id` int(11) NOT NULL,
  `note` int(11) DEFAULT NULL CHECK (`note` >= 1 and `note` <= 5),
  `commentaire` text DEFAULT NULL,
  `date_avis` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `utilisateur_id`, `activite_id`, `note`, `commentaire`, `date_avis`) VALUES
(1, 1, 1, 5, 'TRES BONNE EXPERIENCE ', '2026-03-15 12:34:51');

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

CREATE TABLE `devis` (
  `id` int(11) NOT NULL,
  `reservation_id` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_type` varchar(20) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `supplements` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_status` varchar(20) DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `devis`
--

INSERT INTO `devis` (`id`, `reservation_id`, `user_id`, `course_type`, `user_email`, `base_price`, `supplements`, `total_price`, `payment_status`, `payment_date`, `created_at`) VALUES
(1, 'RES_69b6a7cbdfb93', 1, 'group', 'amari.melissa23@gmail.com', 30.00, 0.00, 30.00, 'pending', NULL, '2026-03-15 12:40:22');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` varchar(20) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `activite_id` int(11) NOT NULL,
  `niveau` varchar(20) DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `date_reservation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `utilisateur_id`, `activite_id`, `niveau`, `prix`, `date_reservation`) VALUES
('RES_69b6a7b83becb', 1, 2, 'débutant', 18.00, '2026-03-15 12:36:08'),
('RES_69b6a7b83becb', 1, 3, 'unique', 12.00, '2026-03-15 12:36:08'),
('RES_69b6a7cbdfb93', 1, 2, 'débutant', 18.00, '2026-03-15 12:36:27'),
('RES_69b6a7cbdfb93', 1, 3, 'unique', 12.00, '2026-03-15 12:36:27'),
('RES_69b6a979b7e1b', 1, 2, 'débutant', 18.00, '2026-03-15 12:43:37'),
('RES_69b6a979b7e1b', 1, 5, 'unique', 10.00, '2026-03-15 12:43:37');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `password`, `date_inscription`) VALUES
(1, 'amari.melissa', 'amari.melissa23@gmail.com', '$2y$10$bEVNYbCfoSBMBfBA96Zi/usVhiCZXVitWAS6Xut9Ew7DD3.Iy/4tS', '2026-03-15 12:30:17');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activites`
--
ALTER TABLE `activites`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `activite_id` (`activite_id`);

--
-- Index pour la table `devis`
--
ALTER TABLE `devis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`,`activite_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `activite_id` (`activite_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activites`
--
ALTER TABLE `activites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `devis`
--
ALTER TABLE `devis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`activite_id`) REFERENCES `activites` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `devis`
--
ALTER TABLE `devis`
  ADD CONSTRAINT `devis_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `devis_ibfk_2` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`activite_id`) REFERENCES `activites` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
