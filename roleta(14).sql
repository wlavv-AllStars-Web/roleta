-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 04 juin 2025 à 16:01
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
  `admin_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_superadmin` tinyint(1) DEFAULT 1,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `wheel_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `is_superadmin`, `active`, `wheel_active`) VALUES
(1, 'lorenzo', '$2y$10$L9rms/T.aFrOLCO/4JepQ.iv2FSyQP13xGlumWXPb6XiVQd/8/2wC', 0, 1, 1),
(2, 'All Stars', '$2y$10$eYATud1YQAmnl.4PPlnDl.DVnOb6XQLGzVw6684A8Ft0wzZYj6vwK', 1, 1, 1),
(3, 'Jesuislorenzo', '$2y$10$CX3pHapMh0wks1DkW/EKrOZnKb2ZWNwqfUFD7SZxSIl/Jmfox9xT6', 0, 1, 1),
(4, 'Engineer77', '$2y$10$2dcVBT9pgK75ELGPvx5Md.JUGCmZOZq.0d4epHWBOYQhd3EM4CQvS', 0, 1, 1);

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
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `probability` float DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `admin_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roleta_prizes`
--

INSERT INTO `roleta_prizes` (`id`, `name`, `stock`, `probability`, `active`, `admin_id`) VALUES
(2, 'bonbon', 3, NULL, 1, 1),
(5, 'Porsche 911', 2, NULL, 1, 3),
(6, 'Lamborghini Urus', 3, NULL, 1, 3),
(7, 'BMW M4', 1, NULL, 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `roleta_results`
--

CREATE TABLE `roleta_results` (
  `id` int(10) UNSIGNED NOT NULL,
  `prize_id` int(10) UNSIGNED NOT NULL,
  `played_at` datetime NOT NULL,
  `admin_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roleta_results`
--

INSERT INTO `roleta_results` (`id`, `prize_id`, `played_at`, `admin_id`) VALUES
(2, 2, '2025-05-29 18:00:00', 1),
(3, 6, '2025-06-03 13:36:04', 3),
(4, 5, '2025-06-03 13:36:12', 3),
(5, 5, '2025-06-03 15:20:34', 3),
(6, 5, '2025-06-03 15:56:43', 3),
(7, 6, '2025-06-03 16:26:34', 3),
(8, 6, '2025-06-03 17:27:35', 3),
(9, 5, '2025-06-03 17:27:45', 3),
(10, 6, '2025-06-03 17:51:09', 3),
(11, 7, '2025-06-03 17:53:37', 3),
(12, 5, '2025-06-04 09:24:21', 3),
(13, 5, '2025-06-04 09:24:30', 3),
(14, 5, '2025-06-04 09:25:11', 3),
(15, 6, '2025-06-04 09:25:30', 3),
(16, 5, '2025-06-04 09:25:37', 3),
(17, 6, '2025-06-04 09:32:00', 3),
(18, 6, '2025-06-04 09:32:34', 3),
(19, 5, '2025-06-04 09:41:33', 3),
(20, 7, '2025-06-04 09:43:05', 3),
(21, 7, '2025-06-04 10:54:03', 3),
(22, 7, '2025-06-04 11:44:50', 3),
(23, 5, '2025-06-04 12:11:39', 3),
(24, 6, '2025-06-04 12:12:21', 3),
(25, 6, '2025-06-04 12:14:46', 3),
(26, 5, '2025-06-04 12:19:04', 3),
(27, 6, '2025-06-04 12:19:44', 3),
(28, 6, '2025-06-04 14:24:56', 3);

-- --------------------------------------------------------

--
-- Structure de la table `roleta_slices`
--

CREATE TABLE `roleta_slices` (
  `position` int(11) NOT NULL,
  `prize_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `admin_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`id`, `setting_name`, `setting_value`, `admin_id`) VALUES
(1, 'color1', '#be2323', 1),
(2, 'color2', '#20c144', 1),
(3, 'color1', '#45609c', 1),
(4, 'color2', '#20c144', 1),
(5, 'background', 'uploads/bg_1748452167_bmwm4.jpg', 1),
(6, 'color1', '#45609c', 1),
(7, 'color2', '#20c144', 1),
(8, 'color1', '#485a99', 2),
(9, 'color2', '#be2323', 2),
(10, 'color1', '#485a99', 2),
(11, 'color2', '#be2323', 2),
(12, 'color1', '#2883b9', 3),
(13, 'color2', '#be2323', 3),
(14, 'color1', '#2883b9', 3),
(15, 'color2', '#18c933', 3),
(16, 'color1', '#ff8000', 3),
(17, 'color2', '#ff8040', 3),
(18, 'background', 'uploads/bg_1748954304_asm.jpg', 3),
(19, 'logo', 'uploads/logo_1748954304_roletalogo.png', 3),
(20, 'color1', '#ff8000', 3),
(21, 'color2', '#ff8040', 3),
(22, 'color1', '#fe0101', 3),
(23, 'color2', '#c0c0c0', 3),
(24, 'color1', '#fe0101', 3),
(25, 'color2', '#c5bebc', 3),
(26, 'logo', 'uploads/logo_1748960200_bmwlogo.jpg', 3),
(27, 'color1', '#fe0101', 3),
(28, 'color2', '#c5bebc', 3),
(29, 'logo', 'uploads/logo_1748960414_lambologo.png', 3),
(30, 'color1', '#fe0101', 3),
(31, 'color2', '#c5bebc', 3),
(32, 'logo', 'uploads/logo_1748960512_bmw.png', 3),
(33, 'color1', '#fe0101', 3),
(34, 'color2', '#c5bebc', 3),
(35, 'color1', '#49c43c', 3),
(36, 'color2', '#c5bebc', 3),
(37, 'color1', '#ff0000', 3),
(38, 'color2', '#c5bebc', 3),
(39, 'color1', '#3725da', 3),
(40, 'color2', '#9f99e8', 3),
(41, 'color1', '#3fc837', 3),
(42, 'color2', '#9f99e8', 3),
(43, 'color1', '#a956a7', 3),
(44, 'color2', '#9f99e8', 3),
(45, 'color1', '#ff0000', 3),
(46, 'color2', '#c7c1ba', 3);

-- --------------------------------------------------------

--
-- Structure de la table `spin_history`
--

CREATE TABLE `spin_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `spin_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `rotation_angle` int(11) DEFAULT NULL,
  `prize_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `spin_results`
--

CREATE TABLE `spin_results` (
  `id` int(10) UNSIGNED NOT NULL,
  `prize_id` int(10) UNSIGNED DEFAULT NULL,
  `spin_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(10) UNSIGNED DEFAULT NULL
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
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
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
  ADD UNIQUE KEY `uuid` (`uuid`);

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
  ADD KEY `admin_id` (`admin_id`);

--
-- Index pour la table `roleta_results`
--
ALTER TABLE `roleta_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prize_id` (`prize_id`),
  ADD KEY `admin_id` (`admin_id`);

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
  ADD KEY `admin_id` (`admin_id`);

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
  ADD KEY `admin_id` (`admin_id`);

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
  MODIFY `admin_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roleta_prizes`
--
ALTER TABLE `roleta_prizes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `roleta_results`
--
ALTER TABLE `roleta_results`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `spin_history`
--
ALTER TABLE `spin_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `spin_results`
--
ALTER TABLE `spin_results`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `roleta_prizes`
--
ALTER TABLE `roleta_prizes`
  ADD CONSTRAINT `roleta_prizes_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`);

--
-- Contraintes pour la table `roleta_results`
--
ALTER TABLE `roleta_results`
  ADD CONSTRAINT `roleta_results_ibfk_1` FOREIGN KEY (`prize_id`) REFERENCES `roleta_prizes` (`id`),
  ADD CONSTRAINT `roleta_results_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`);

--
-- Contraintes pour la table `roleta_slices`
--
ALTER TABLE `roleta_slices`
  ADD CONSTRAINT `roleta_slices_ibfk_1` FOREIGN KEY (`prize_id`) REFERENCES `roleta_prizes` (`id`);

--
-- Contraintes pour la table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`);

--
-- Contraintes pour la table `spin_history`
--
ALTER TABLE `spin_history`
  ADD CONSTRAINT `spin_history_ibfk_1` FOREIGN KEY (`prize_id`) REFERENCES `roleta_prizes` (`id`);

--
-- Contraintes pour la table `spin_results`
--
ALTER TABLE `spin_results`
  ADD CONSTRAINT `spin_results_ibfk_1` FOREIGN KEY (`prize_id`) REFERENCES `roleta_prizes` (`id`),
  ADD CONSTRAINT `spin_results_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
