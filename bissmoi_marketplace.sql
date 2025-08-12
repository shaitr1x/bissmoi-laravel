-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 10 août 2025 à 18:21
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bissmoi_marketplace`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
CREATE TABLE IF NOT EXISTS `admin_notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_notifications_is_read_created_at_index` (`is_read`,`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admin_notifications`
--

INSERT INTO `admin_notifications` (`id`, `title`, `message`, `type`, `icon`, `data`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(28, 'Badge vérifié accordé', 'Le badge vérifié a été accordé à edgard.', 'success', 'badge-check', NULL, 0, NULL, '2025-08-09 20:10:48', '2025-08-09 20:10:48'),
(29, 'Badge vérifié retiré', 'Le badge vérifié a été retiré à edgard.', 'warning', 'badge-off', NULL, 1, '2025-08-09 20:19:11', '2025-08-09 20:10:59', '2025-08-09 20:19:11'),
(27, 'Commerçant approuvé', 'Le commerçant edgard a été approuvé.', 'success', 'check-circle', NULL, 0, NULL, '2025-08-09 16:07:04', '2025-08-09 16:07:04'),
(24, 'Nouvelle demande de vérification', 'Le marchand alan doko a soumis une demande de badge de vérification.', 'merchant_verification', NULL, NULL, 1, '2025-08-09 04:48:25', '2025-08-09 03:35:40', '2025-08-09 04:48:25'),
(25, 'Badge vérifié accordé', 'Le badge vérifié a été accordé à alan doko.', 'success', 'badge-check', NULL, 1, '2025-08-09 04:48:18', '2025-08-09 03:36:44', '2025-08-09 04:48:18'),
(26, 'Nouvelle demande commerçant', 'Un utilisateur a soumis une demande pour devenir commerçant.', 'info', 'user-plus', '{\"user_id\": 8, \"user_email\": \"commercant@gmail.com\"}', 0, NULL, '2025-08-09 16:05:48', '2025-08-09 16:05:48'),
(32, 'Badge vérifié accordé', 'Le badge vérifié a été accordé à Commerçant Test.', 'success', 'badge-check', NULL, 0, NULL, '2025-08-10 13:26:56', '2025-08-10 13:26:56'),
(31, 'Système optimisé', 'Le système a été optimisé pour de meilleures performances.', 'success', 'lightning-bolt', NULL, 1, '2025-08-09 20:19:03', '2025-08-09 20:18:00', '2025-08-09 20:19:03'),
(33, 'Badge vérifié retiré', 'Le badge vérifié a été retiré à Commerçant Test.', 'warning', 'badge-off', NULL, 0, NULL, '2025-08-10 13:27:05', '2025-08-10 13:27:05'),
(34, 'Commerçant rejeté', 'L\'approbation du commerçant Commerçant Test a été révoquée.', 'warning', 'x-circle', NULL, 0, NULL, '2025-08-10 13:27:14', '2025-08-10 13:27:14'),
(35, 'Commerçant approuvé', 'Le commerçant Commerçant Test a été approuvé.', 'success', 'check-circle', NULL, 0, NULL, '2025-08-10 13:27:21', '2025-08-10 13:27:21'),
(36, 'Commerçant rejeté', 'L\'approbation du commerçant Commerçant Test a été révoquée.', 'warning', 'x-circle', NULL, 0, NULL, '2025-08-10 13:27:29', '2025-08-10 13:27:29'),
(37, 'Commerçant approuvé', 'Le commerçant Commerçant Test a été approuvé.', 'success', 'check-circle', NULL, 0, NULL, '2025-08-10 13:27:48', '2025-08-10 13:27:48'),
(38, 'Commerçant rejeté', 'L\'approbation du commerçant alan doko a été révoquée.', 'warning', 'x-circle', NULL, 0, NULL, '2025-08-10 13:48:01', '2025-08-10 13:48:01'),
(39, 'Commerçant approuvé', 'Le commerçant alan doko a été approuvé.', 'success', 'check-circle', NULL, 0, NULL, '2025-08-10 13:48:09', '2025-08-10 13:48:09');

-- --------------------------------------------------------

--
-- Structure de la table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `content`, `published`, `published_at`, `created_at`, `updated_at`) VALUES
(1, '\"Recevoir des V‑Bucks gratuits à chaque saison de Fortnite : la vérité dévoilée\"', 'recevoir-des-vbucks-gratuits-a-chaque-saison-de-fortnite-la-verite-devoilee', 'Depuis 2023, WhatsApp pour PC (Windows ou Mac) permet de partager son écran pendant un appel vidéo, un peu comme sur Zoom ou Teams.\r\n\r\n📌 Pour le faire :\r\n\r\nOuvre WhatsApp Desktop (pas la version web, mais l’application installée).\r\n\r\nLance un appel vidéo avec la personne ou le groupe.\r\n\r\nEn bas de l’écran, clique sur l’icône Partager l’écran (un écran avec une flèche).\r\n\r\nChoisis tout l’écran ou une fenêtre spécifique.\r\n\r\nClique sur Partager.\r\n\r\n⚠️ Conditions :\r\n\r\nAvoir la dernière version de WhatsApp Desktop.\r\n\r\nFonctionne uniquement en appel vidéo (pas audio seul).\r\n\r\nCertaines fonctions peuvent ne pas être dispo sur WhatsApp Web dans un navigateur.\r\n\r\nSi tu veux, je peux aussi te dire comment activer l’option si elle n’apparaît pas.\r\nVeux-tu que je te montre ça ?', 1, '2025-08-09 20:12:36', '2025-08-09 20:12:36', '2025-08-09 20:12:36');

-- --------------------------------------------------------

--
-- Structure de la table `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  KEY `carts_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `parent_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Électronique', 'electronique', 'Tous les produits électroniques', NULL, NULL, 1, '2025-08-07 15:30:56', '2025-08-07 15:30:56'),
(2, 'Mode & Vêtements', 'mode-vetements', 'Vêtements et accessoires de mode', NULL, NULL, 1, '2025-08-07 15:30:56', '2025-08-07 15:30:56'),
(3, 'Maison & Jardin', 'maison-jardin', 'Articles pour la maison et le jardin', NULL, NULL, 1, '2025-08-07 15:30:56', '2025-08-07 15:30:56'),
(4, 'Smartphones', 'smartphones', 'Téléphones mobiles et smartphones', NULL, 1, 1, '2025-08-07 15:30:56', '2025-08-07 15:30:56'),
(5, 'Ordinateurs', 'ordinateurs', 'Ordinateurs portables et de bureau', NULL, 1, 1, '2025-08-07 15:30:56', '2025-08-07 15:30:56'),
(6, 'Vêtements Homme', 'vetements-homme', 'Vêtements pour hommes', NULL, 2, 1, '2025-08-07 15:30:56', '2025-08-07 15:30:56'),
(7, 'Vêtements Femme', 'vetements-femme', 'Vêtements pour femmes', NULL, 2, 1, '2025-08-07 15:30:56', '2025-08-07 15:30:56'),
(8, 'Décoration', 'decoration', 'Articles de décoration intérieure', NULL, NULL, 1, '2025-08-07 15:30:56', '2025-08-10 12:18:47');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `merchant_verification_requests`
--

DROP TABLE IF EXISTS `merchant_verification_requests`;
CREATE TABLE IF NOT EXISTS `merchant_verification_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `business_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_physical_office` tinyint(1) DEFAULT NULL,
  `office_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_or_social` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_description` text COLLATE utf8mb4_unicode_ci,
  `business_experience` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `merchant_verification_requests_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `merchant_verification_requests`
--

INSERT INTO `merchant_verification_requests` (`id`, `user_id`, `status`, `message`, `created_at`, `updated_at`, `business_phone`, `has_physical_office`, `office_address`, `website_or_social`, `business_description`, `business_experience`) VALUES
(1, 6, 'approved', 'pour un test', '2025-08-09 03:02:29', '2025-08-09 03:17:27', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 6, 'rejected', 'test 2', '2025-08-09 03:20:14', '2025-08-09 03:20:30', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 6, 'approved', NULL, '2025-08-09 03:35:40', '2025-08-09 03:37:05', '9814583', 1, 'mrezga cafe des jeunes', 'https://www.bissmoi.com', 'commerce electronique', '3 ans');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_08_06_133229_add_role_to_users_table', 1),
(6, '2025_08_06_133309_create_categories_table', 1),
(7, '2025_08_06_133453_create_products_table', 1),
(8, '2025_08_06_133542_create_orders_table', 1),
(9, '2025_08_06_133619_create_order_items_table', 1),
(10, '2025_08_06_133713_create_reviews_table', 1),
(11, '2025_08_06_133743_create_carts_table', 1),
(12, '2025_08_06_181503_create_admin_notifications_table', 1),
(13, '2025_08_06_221000_alter_orders_add_default_total_amount', 1),
(14, '2025_08_06_221500_alter_orders_make_billing_address_nullable', 1),
(15, '2025_08_06_230000_add_delivery_address_phone_to_orders', 1),
(16, '2025_08_07_000001_add_merchant_id_to_orders_table', 1),
(17, '2025_08_07_000001_alter_products_price_columns', 1),
(18, '2025_08_07_000002_fill_merchant_id_on_orders', 1),
(19, '2025_08_07_010930_create_sessions_table', 1),
(20, '2025_08_07_120000_add_is_active_to_users_table', 1),
(21, '2025_08_07_120000_add_merchant_fields_to_users_table', 1),
(22, '2025_08_08_000000_remove_merchant_nif_and_id_doc_from_users_table', 1),
(23, '2025_08_08_120000_create_user_notifications_table', 1),
(26, '2025_08_08_150000_create_seo_settings_table', 2),
(27, '2025_08_08_170000_create_blog_posts_table', 2),
(28, '2025_08_09_020017_add_helpful_votes_to_reviews_table', 2),
(29, '2025_08_09_000001_add_is_verified_merchant_to_users_table', 3),
(30, '2025_08_09_000002_create_merchant_verification_requests_table', 4),
(31, '2025_08_09_000003_enrich_merchant_verification_requests_table', 5);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `merchant_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `billing_address` json DEFAULT NULL,
  `shipping_address` json DEFAULT NULL,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `delivery_address` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_merchant_id_foreign` (`merchant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `merchant_id`, `status`, `total_amount`, `tax_amount`, `shipping_amount`, `billing_address`, `shipping_address`, `payment_method`, `payment_status`, `notes`, `created_at`, `updated_at`, `delivery_address`, `phone`) VALUES
(1, 'BSM-6895420097756', 7, 6, 'processing', '310000.00', '0.00', '0.00', NULL, NULL, NULL, 'pending', NULL, '2025-08-07 23:17:04', '2025-08-08 17:25:34', 'Nabeul Mrezga café de la jeunesse', '25790606'),
(2, 'BSM-6896D65205C0D', 4, 6, 'delivered', '170000.00', '0.00', '0.00', NULL, NULL, NULL, 'pending', 'fragile', '2025-08-09 04:02:10', '2025-08-09 04:06:41', 'mrezga anouar', '58274682'),
(3, 'BSM-6896DF82A9509', 4, 6, 'processing', '37000.00', '0.00', '0.00', NULL, NULL, NULL, 'pending', 'taille 43', '2025-08-09 04:41:22', '2025-08-09 04:41:53', 'mere poule temoin de jehova', '99185904'),
(4, 'BSM-689780DEECC27', 4, 8, 'processing', '168500.00', '0.00', '0.00', NULL, NULL, NULL, 'pending', NULL, '2025-08-09 16:09:50', '2025-08-09 16:10:29', 'mrezga', '99185904'),
(5, 'BSM-6897AD5984781', 4, 8, 'pending', '320000.00', '0.00', '0.00', NULL, NULL, NULL, 'pending', NULL, '2025-08-09 19:19:37', '2025-08-09 19:19:37', 'Enia-Bertoua', '659485440');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 1, '310000.00', '310000.00', '2025-08-07 23:17:04', '2025-08-07 23:17:04'),
(2, 2, 7, 1, '170000.00', '170000.00', '2025-08-09 04:02:10', '2025-08-09 04:02:10'),
(3, 3, 8, 2, '18500.00', '37000.00', '2025-08-09 04:41:22', '2025-08-09 04:41:22'),
(4, 4, 9, 1, '150000.00', '150000.00', '2025-08-09 16:09:50', '2025-08-09 16:09:50'),
(5, 4, 8, 1, '18500.00', '18500.00', '2025-08-09 16:09:50', '2025-08-09 16:09:50'),
(6, 5, 9, 1, '150000.00', '150000.00', '2025-08-09 19:19:37', '2025-08-09 19:19:37'),
(7, 5, 7, 1, '170000.00', '170000.00', '2025-08-09 19:19:37', '2025-08-09 19:19:37');

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `manage_stock` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('active','inactive','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `images` json DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_user_id_foreign` (`user_id`),
  KEY `products_category_id_foreign` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `stock_quantity`, `manage_stock`, `status`, `images`, `weight`, `sku`, `featured`, `user_id`, `category_id`, `created_at`, `updated_at`) VALUES
(9, 'site web', 'site-web', 'vos site web', NULL, '150000.00', NULL, 3, 1, 'active', '[\"images/products/1754759325.png\", \"images/products/1754759325_6897809daa5f1.png\", \"images/products/1754759325_6897809daac21.png\", \"images/products/1754759325_6897809dab2a2.png\"]', NULL, NULL, 0, 8, 1, '2025-08-09 16:08:45', '2025-08-09 20:09:08'),
(7, 'Ps4', 'ps4', 'Test', NULL, '170000.00', NULL, 4, 1, 'active', '[\"images/products/1754681672.jpg\", \"images/products/1754681672_689651482fb46.jpg\", \"images/products/1754681672_68965148301aa.jpg\", \"images/products/1754681672_68965148307c2.jpg\"]', NULL, NULL, 0, 6, 1, '2025-08-08 18:34:32', '2025-08-09 19:19:37'),
(8, 'nike-sb', 'nike-sb', 'nike-sb toutes les taille preciser la taille durant cotre commande', NULL, '25000.00', '18500.00', 44, 1, 'active', '[\"images/products/1754716383.jpg\", \"images/products/1754716383_6896d8df92cf2.jpg\", \"images/products/1754716383_6896d8df934d9.jpg\"]', NULL, NULL, 1, 6, 2, '2025-08-09 04:13:03', '2025-08-10 14:15:07');

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `rating` int NOT NULL DEFAULT '5',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `helpful_votes` int NOT NULL DEFAULT '0',
  `approved_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_product_id_user_id_unique` (`product_id`,`user_id`),
  KEY `reviews_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `approved`, `created_at`, `updated_at`, `helpful_votes`, `approved_at`) VALUES
(1, 7, 4, 4, 'produit très intéressant je le recommande', 0, '2025-08-09 01:16:54', '2025-08-09 03:43:11', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `seo_settings`
--

DROP TABLE IF EXISTS `seo_settings`;
CREATE TABLE IF NOT EXISTS `seo_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `meta_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `custom_head` text COLLATE utf8mb4_unicode_ci,
  `robots_txt` text COLLATE utf8mb4_unicode_ci,
  `sitemap_xml` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('client','merchant','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `merchant_approved` tinyint(1) NOT NULL DEFAULT '0',
  `is_verified_merchant` tinyint(1) NOT NULL DEFAULT '0',
  `merchant_description` text COLLATE utf8mb4_unicode_ci,
  `merchant_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `merchant_address` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `shop_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `merchant_website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `merchant_approved`, `is_verified_merchant`, `merchant_description`, `merchant_phone`, `merchant_address`, `is_active`, `shop_name`, `merchant_website`) VALUES
(5, 'admin1', 'admin@icloud.com', NULL, '$2y$10$RCNFfDETj3m5Z5dZ3.EfLO1sIckuz3wS/fZ3T/.XvNw4tKEMSVKQq', NULL, '2025-08-07 15:38:57', '2025-08-07 15:38:57', 'admin', 0, 0, NULL, NULL, NULL, 1, NULL, NULL),
(2, 'Commerçant Test', 'merchant@bissmoi.com', '2025-08-07 15:30:56', '$2y$10$A3vCvAWkz9MuL9x2zDpebeNBa1ACi.kVhu1v6qjKbucRIiStTgBMq', NULL, '2025-08-07 15:30:56', '2025-08-10 13:27:48', 'merchant', 1, 0, 'Boutique de test pour démonstration', '0123456789', '123 Rue de Test, 75000 Paris', 1, NULL, NULL),
(4, 'jordy', 'jordy@gmail.com', NULL, '$2y$10$j7m70LpyzpeqgCg1W7b.J.PJcNnQZylrhj9YCB7vixIHBPOtCNJUe', NULL, '2025-08-07 15:37:19', '2025-08-09 19:14:57', 'client', 0, 0, NULL, NULL, NULL, 1, NULL, NULL),
(6, 'alan doko', 'dokoalanfranck@gmail.com', NULL, '$2y$10$DPZ8y/ht2oMmXx4iicY7ze0SPKvGKW88MZCfw2lKW2OmloBXGNKrO', 'rDisPXqYvT0LsiwK4at3gYKC8dKBB8O4wcUkvXkL5n1rFXVEZyKJxsBfMrgj', '2025-08-07 16:10:26', '2025-08-10 13:58:22', 'merchant', 1, 1, 'je vends des appareil electronique', '99185904', 'mrezga', 1, 'alanshop', NULL),
(7, 'Laurianne', 'lauriannengoue15@gmail.com', NULL, '$2y$10$SuV3nWDLQ7VkhtS1jaPOHetML4.fQP2YOPY9.qLGalfkLsEEo2FPm', NULL, '2025-08-07 23:15:35', '2025-08-07 23:15:35', 'client', 0, 0, NULL, NULL, NULL, 1, NULL, NULL),
(8, 'edgard', 'commercant@gmail.com', NULL, '$2y$10$EexhlPU.ZeZYfPdRnuKCeuioQR446X44iIzzpAHSwHs7ZELpuS7.S', NULL, '2025-08-09 15:52:09', '2025-08-09 20:10:59', 'merchant', 1, 0, 'vente de logo', '99185904', 'lile france', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_notifications`
--

DROP TABLE IF EXISTS `user_notifications`;
CREATE TABLE IF NOT EXISTS `user_notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_notifications_user_id_is_read_created_at_index` (`user_id`,`is_read`,`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_notifications`
--

INSERT INTO `user_notifications` (`id`, `user_id`, `title`, `message`, `type`, `icon`, `data`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 6, 'Demande commerçant envoyée', 'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.', 'info', 'store', NULL, 0, NULL, '2025-08-07 16:11:58', '2025-08-07 16:11:58'),
(2, 6, 'Demande commerçant envoyée', 'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.', 'info', 'store', NULL, 0, NULL, '2025-08-07 16:13:48', '2025-08-07 16:13:48'),
(4, 8, 'Demande commerçant envoyée', 'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.', 'info', 'store', NULL, 0, NULL, '2025-08-09 15:53:03', '2025-08-09 15:53:03'),
(5, 8, 'Demande commerçant envoyée', 'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.', 'info', 'store', NULL, 0, NULL, '2025-08-09 15:59:12', '2025-08-09 15:59:12'),
(6, 8, 'Demande commerçant envoyée', 'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.', 'info', 'store', NULL, 0, NULL, '2025-08-09 15:59:25', '2025-08-09 15:59:25'),
(7, 8, 'Demande commerçant envoyée', 'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.', 'info', 'store', NULL, 0, NULL, '2025-08-09 16:00:15', '2025-08-09 16:00:15'),
(8, 8, 'Demande commerçant envoyée', 'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.', 'info', 'store', NULL, 0, NULL, '2025-08-09 16:00:50', '2025-08-09 16:00:50'),
(9, 8, 'Demande commerçant envoyée', 'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.', 'info', 'store', NULL, 0, NULL, '2025-08-09 16:01:39', '2025-08-09 16:01:39'),
(10, 8, 'Demande commerçant envoyée', 'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.', 'info', 'store', NULL, 0, NULL, '2025-08-09 16:03:01', '2025-08-09 16:03:01'),
(11, 8, 'Demande commerçant envoyée', 'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.', 'info', 'store', NULL, 0, NULL, '2025-08-09 16:03:54', '2025-08-09 16:03:54'),
(12, 8, 'Demande commerçant envoyée', 'Votre demande pour devenir commerçant a bien été envoyée. Elle sera validée par un administrateur.', 'info', 'store', NULL, 0, NULL, '2025-08-09 16:05:48', '2025-08-09 16:05:48');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
