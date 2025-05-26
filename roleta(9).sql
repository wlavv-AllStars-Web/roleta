-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 26 mai 2025 à 16:07
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
-- Base de données : `roleta`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'adminASM', '$2y$10$yRMDZ8Aa4.vRd0Y6YcvH2Oismg.9qnOq2nDvS2Mw2nvnYWD65.p2W'),
(2, 'paulo', '$2y$10$TXLyr2etbidZeyWbO2g8COcN6FjrdiArVxzfb.X7y3THrCbFlfIHa');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roleta_prizes`
--

CREATE TABLE `roleta_prizes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `probability` float DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roleta_prizes`
--

INSERT INTO `roleta_prizes` (`id`, `name`, `stock`, `probability`, `active`, `user_id`) VALUES
(62, 'bonbon', 2, NULL, 1, 2),
(63, 'chocolat', 3, NULL, 1, 2),
(64, 'oui', 3, NULL, 1, 3),
(65, 'non', 2, NULL, 1, 3),
(66, 'zzzz', 6, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `roleta_results`
--

CREATE TABLE `roleta_results` (
  `id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL,
  `played_at` datetime NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roleta_results`
--

INSERT INTO `roleta_results` (`id`, `prize_id`, `played_at`, `user_id`) VALUES
(113, 65, '2025-05-23 16:16:06', 3),
(114, 64, '2025-05-23 16:19:10', 3),
(115, 62, '2025-05-23 16:55:46', 2),
(116, 66, '2025-05-23 17:27:15', 1),
(117, 62, '2025-05-23 17:28:33', 2),
(118, 64, '2025-05-23 17:41:28', 3),
(119, 64, '2025-05-23 17:41:41', 3),
(120, 64, '2025-05-23 17:42:16', 3),
(121, 65, '2025-05-23 17:45:08', 3),
(122, 62, '2025-05-23 17:51:39', 2),
(123, 63, '2025-05-23 17:51:54', 2),
(124, 63, '2025-05-23 17:54:11', 2),
(125, 63, '2025-05-23 17:54:20', 2),
(126, 63, '2025-05-23 17:55:33', 2),
(127, 63, '2025-05-23 17:55:51', 2),
(128, 63, '2025-05-23 17:56:13', 2),
(129, 66, '2025-05-26 09:27:18', 1),
(130, 63, '2025-05-26 09:27:46', 2),
(131, 62, '2025-05-26 10:38:23', 2);

-- --------------------------------------------------------

--
-- Structure de la table `roleta_slices`
--

CREATE TABLE `roleta_slices` (
  `position` int(11) NOT NULL,
  `prize_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('dMjc0gLYRM19gWpllkrNB3oUKrFmqUF6om9whbEl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:138.0) Gecko/20100101 Firefox/138.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibUs5aHJOU0ZJSU5xdzJuU2R3UGMxbElobnVFYW9aZ2M1NkE5REl4dyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Qvcm9sZXRhL3B1YmxpYyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1746705423);

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`id`, `setting_name`, `setting_value`, `user_id`) VALUES
(20, 'color1', '#2877d7', 1),
(21, 'color2', '#ff6347', 1),
(22, 'color1', '#2877d7', 1),
(23, 'color2', '#000000', 1),
(24, 'color1', '#3562ac', 2),
(25, 'color2', '#bc4949', 2),
(26, 'color1', '#2833b9', 2),
(27, 'color2', '#ffff00', 2),
(28, 'background', 'uploads/bg_1747993365_OIP.jpeg', 2),
(29, 'color1', '#be2323', 2),
(30, 'color2', '#bc4949', 2),
(31, 'color1', '#00ff00', 2),
(32, 'color2', '#000000', 2),
(33, 'color1', '#5a876d', 2),
(34, 'color2', '#bc4949', 2),
(35, 'color1', '#5a876d', 2),
(36, 'color2', '#bc4949', 2),
(37, 'color1', '#5a876d', 2),
(38, 'color2', '#bc4949', 2),
(39, 'color1', '#5a876d', 2),
(40, 'color2', '#4524e1', 2),
(41, 'background', 'uploads/bg_1747999939_OIP.jpeg', 1),
(42, 'color1', '#2877d7', 1),
(43, 'color2', '#000000', 1),
(44, 'background', 'uploads/bg_1748000043_processo encomenda perdida.png', 1),
(45, 'color1', '#2877d7', 1),
(46, 'color2', '#000000', 1),
(47, 'logo', 'uploads/logo_1748012399_euromuscleparts.png', 2),
(48, 'color1', '#5a876d', 2),
(49, 'color2', '#4524e1', 2),
(50, 'color1', '#2d42d2', 3),
(51, 'color2', '#ff6347', 3),
(52, 'color1', '#ff0000', 3),
(53, 'color2', '#ff6347', 3),
(54, 'color1', '#3878c7', 3),
(55, 'color2', '#ff6347', 3),
(56, 'background', 'uploads/bg_1748013173_euromuscleparts.png', 3),
(57, 'color1', '#ff0000', 3),
(58, 'color2', '#ff6347', 3),
(59, 'color1', '#5a876d', 2),
(60, 'color2', '#4524e1', 2),
(61, 'color1', '#ff0000', 3),
(62, 'color2', '#ff6347', 3),
(63, 'background', 'uploads/bg_1748013829_Lost parcel process.png', 2),
(64, 'color1', '#5a876d', 2),
(65, 'color2', '#4524e1', 2),
(66, 'background', 'uploads/bg_1748014300_Capture d’écran 2025-05-08 16.59.58.png', 2),
(67, 'color1', '#5a876d', 2),
(68, 'color2', '#4524e1', 2),
(69, 'color1', '#5a876d', 2),
(70, 'color2', '#4524e1', 2),
(71, 'background', 'uploads/bg_1748016156_OIP.jpeg', 2),
(72, 'color1', '#5a876d', 2),
(73, 'color2', '#4524e1', 2);

-- --------------------------------------------------------

--
-- Structure de la table `spin_history`
--

CREATE TABLE `spin_history` (
  `id` int(11) NOT NULL,
  `spin_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `rotation_angle` int(11) DEFAULT NULL,
  `prize_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `spin_results`
--

CREATE TABLE `spin_results` (
  `id` int(11) NOT NULL,
  `prize_id` int(11) DEFAULT NULL,
  `spin_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'lorenzo', 'lorenzo.domingues45@gmail.com', '$2y$10$qyVBYzXcm3zov/llng3wS.rBqdsRyuQyWLp9DxUAySgTUvR1JdAWu', '2025-05-23 08:37:42'),
(2, 'adminASM', 'geral@autostylo.com', '$2y$10$s2zb4Lxo.BVtkYmvs7mNNua6tLMBecUNeKUOF71LR5IdPb7nKMSbW', '2025-05-23 09:21:00'),
(3, 'lolo', 'lolo@gmail.com', '$2y$10$wG16uwVg1tHcOqUo2D0JmuDLV0CyYJv9e4W.ADTC7xmuLIFqeitxe', '2025-05-23 15:08:27');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `roleta_prizes`
--
ALTER TABLE `roleta_prizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `roleta_results`
--
ALTER TABLE `roleta_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prize_id` (`prize_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `roleta_slices`
--
ALTER TABLE `roleta_slices`
  ADD PRIMARY KEY (`position`),
  ADD KEY `prize_id` (`prize_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `spin_history`
--
ALTER TABLE `spin_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prize_id` (`prize_id`);

--
-- Index pour la table `spin_results`
--
ALTER TABLE `spin_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prize_id` (`prize_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `roleta_prizes`
--
ALTER TABLE `roleta_prizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT pour la table `roleta_results`
--
ALTER TABLE `roleta_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT pour la table `spin_history`
--
ALTER TABLE `spin_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `spin_results`
--
ALTER TABLE `spin_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `roleta_prizes`
--
ALTER TABLE `roleta_prizes`
  ADD CONSTRAINT `roleta_prizes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `roleta_results`
--
ALTER TABLE `roleta_results`
  ADD CONSTRAINT `roleta_results_ibfk_1` FOREIGN KEY (`prize_id`) REFERENCES `roleta_prizes` (`id`),
  ADD CONSTRAINT `roleta_results_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `roleta_slices`
--
ALTER TABLE `roleta_slices`
  ADD CONSTRAINT `roleta_slices_ibfk_1` FOREIGN KEY (`prize_id`) REFERENCES `roleta_prizes` (`id`);

--
-- Contraintes pour la table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `spin_history`
--
ALTER TABLE `spin_history`
  ADD CONSTRAINT `spin_history_ibfk_1` FOREIGN KEY (`prize_id`) REFERENCES `roleta_prizes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
