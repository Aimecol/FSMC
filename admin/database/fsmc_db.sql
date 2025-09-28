-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 12:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fsmc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_content`
--

CREATE TABLE `about_content` (
  `id` int(11) NOT NULL,
  `section_key` varchar(100) NOT NULL COMMENT 'Unique identifier for content section',
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(500) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `meta_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata for flexible content',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about_content`
--

INSERT INTO `about_content` (`id`, `section_key`, `title`, `subtitle`, `content`, `image`, `icon`, `sort_order`, `is_active`, `meta_data`, `created_at`, `updated_at`) VALUES
(1, 'hero', 'About Fair Surveying & Mapping', 'Excellence in Land Surveying, Mapping and Environmental Solutions', '', NULL, NULL, 1, 1, NULL, '2025-09-24 01:50:39', '2025-09-24 02:52:31'),
(2, 'story', 'Our Story', '', 'Fair Surveying and Mapping Ltd was established with a vision to deliver exceptional land surveying, mapping, and environmental solutions across Rwanda and beyond. Founded by HATANGIMANA Fulgence, a certified surveyor with extensive experience in the field, our company has grown to become a trusted name in the industry.\r\n\r\nWith our deep expertise in geospatial technologies and commitment to precision, we provide comprehensive surveying services that help clients make informed decisions about their land and construction projects. Our team comprises highly skilled professionals who combine traditional surveying methods with cutting-edge technology to deliver accurate, reliable results.\r\n\r\nWhether you\'re looking to register your land for the first time, merge or subdivide parcels, correct boundary issues, or require environmental impact assessments, our team is ready to assist with professional expertise and personalized service.', NULL, NULL, 2, 1, '{\"mission\": \"Our mission is to provide precise, reliable, and innovative surveying and mapping solutions that empower our clients to make informed decisions about their land and environmental assets.\"}', '2025-09-24 01:50:39', '2025-09-24 02:52:31'),
(3, 'expertise_intro', 'Our Areas of Expertise', '', 'At Fair Surveying and Mapping Ltd, we offer a comprehensive range of professional services tailored to meet diverse needs in land management, construction support, and environmental consultancy.', NULL, NULL, 3, 1, NULL, '2025-09-24 01:50:39', '2025-09-24 02:52:31'),
(4, 'values_intro', 'Our Core Values', '', 'The principles that guide our work and define our approach to every project:', NULL, NULL, 4, 1, NULL, '2025-09-24 01:50:39', '2025-09-24 02:52:31'),
(5, 'team_intro', 'Meet Our Leadership', '', 'Our team is led by experienced professionals with extensive knowledge in surveying, mapping, and environmental sciences. Together, we work collaboratively to deliver exceptional results for our clients.', NULL, NULL, 5, 1, NULL, '2025-09-24 01:50:39', '2025-09-24 02:52:31'),
(6, 'timeline_intro', 'Our Journey', '', '', NULL, NULL, 6, 1, NULL, '2025-09-24 01:50:39', '2025-09-24 02:52:31'),
(7, 'faq_intro', 'Frequently Asked Questions', '', '', NULL, NULL, 7, 1, NULL, '2025-09-24 01:50:39', '2025-09-24 02:52:31'),
(8, 'cta', 'Ready to Work With Us?', '', 'Whether you need land surveying, mapping services, construction support, or environmental consultancy, our team is ready to deliver expert solutions tailored to your specific needs.', NULL, NULL, 8, 1, NULL, '2025-09-24 01:50:39', '2025-09-24 02:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `about_faqs`
--

CREATE TABLE `about_faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about_faqs`
--

INSERT INTO `about_faqs` (`id`, `question`, `answer`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'What is the process for first-time land registration?', 'The process for first-time land registration (Kubarura Umurima Bwa Mbere) involves several steps including boundary survey, documentation preparation, and submission to the land registry. Our team handles the entire process, ensuring compliance with all legal requirements and regulations.', 1, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(2, 'How long does a typical land survey take?', 'The duration of a land survey depends on various factors including the size of the land, complexity of the terrain, and the specific survey requirements. Typically, a standard residential property survey can be completed within 1-3 days, while larger or more complex projects may take longer.', 2, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(3, 'What technologies do you use for surveying?', 'We utilize advanced surveying technologies including Total Station equipment, GPS/GNSS systems, drone mapping, and specialized software like AutoCAD and ArcGIS. Our combination of traditional and modern methods ensures high accuracy and efficiency in all our surveying projects.', 3, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(4, 'Do you provide training for organizations?', 'Yes, we offer customized training programs for organizations in surveying equipment operation, software utilization, data analysis techniques, GIS applications, and AI/ML integration. Our training can be tailored to various skill levels and specific organizational needs.', 4, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(5, 'What is involved in an Environmental Impact Assessment?', 'An Environmental Impact Assessment (EIA) involves comprehensive evaluation of potential environmental effects of a proposed project or development. Our EIA services include baseline studies, impact prediction, mitigation planning, and reporting in compliance with national and international standards.', 5, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 17:55:18'),
(2, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 18:04:04'),
(3, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 19:04:17'),
(4, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:11:05'),
(5, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:13:15'),
(6, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:17:03'),
(7, 1, 'Service Updated', 'services', 1, '{\"id\":1,\"title\":\"First Registration\",\"slug\":\"first-registration\",\"description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property. Our team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.\",\"short_description\":\"Comprehensive first-time registration services for land parcels with accurate measurements and legal documentation.\",\"icon\":\"fas fa-file-signature\",\"languages\":\"[\\\"English\\\", \\\"Kinyarwanda\\\"]\",\"price\":null,\"duration\":null,\"features\":\"[\\\"Accurate land measurements\\\", \\\"Legal documentation\\\", \\\"State-of-the-art equipment\\\", \\\"Authority compliance\\\"]\",\"image\":null,\"gallery\":null,\"status\":\"active\",\"sort_order\":1,\"meta_title\":null,\"meta_description\":null,\"created_at\":\"2025-09-22 17:48:06\",\"updated_at\":\"2025-09-22 17:48:06\"}', '{\"title\":\"First Registration\",\"slug\":\"first-registration\",\"description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property. Our team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.\",\"short_description\":\"Comprehensive first-time registration services for land parcels with accurate measurements and legal documentation.\",\"icon\":\"fas fa-file-signature\",\"languages\":\"[\\\"English\\\",\\\"Kinyarwanda\\\"]\",\"price\":null,\"duration\":\"\",\"features\":\"[\\\"Accurate land measurements\\\",\\\"Legal documentation\\\",\\\"State-of-the-art equipment\\\",\\\"Authority compliance\\\"]\",\"status\":\"active\",\"sort_order\":1,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:21:24'),
(8, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:55:07'),
(9, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:35:29'),
(10, 1, 'Company Settings Updated', 'settings', 0, NULL, '{\"company_name\":\"Fair Surveying &amp; Mapping Ltd\",\"company_tagline\":\"Reliable | Professional | Expert Solutions\",\"company_description\":\"\",\"company_address\":\"Kigali, Rwanda\",\"company_city\":\"Kigali\",\"company_country\":\"Rwanda\",\"company_postal_code\":\"\",\"company_phone\":\"+250 788 331 697\",\"company_email\":\"fsamcompanyltd@gmail.com\",\"company_website\":\"\",\"company_founded\":\"\",\"company_employees\":\"\",\"company_license\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:35:43'),
(11, 1, 'Contact Settings Updated', 'settings', 0, NULL, '{\"contact_phone_primary\":\"+250 788 331 697\",\"contact_phone_secondary\":\"\",\"contact_email_general\":\"fsamcompanyltd@gmail.com\",\"contact_email_support\":\"\",\"contact_email_sales\":\"\",\"contact_hours_weekday\":\"Monday - Friday: 8:00 AM - 6:00 PM\",\"contact_hours_weekend\":\"Saturday: 8:00 AM - 2:00 PM\",\"contact_emergency\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:35:47'),
(12, 1, 'Social Media Settings Updated', 'settings', 0, NULL, '{\"social_facebook\":\"\",\"social_twitter\":\"\",\"social_linkedin\":\"\",\"social_instagram\":\"\",\"social_youtube\":\"\",\"social_whatsapp\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:35:53'),
(13, 1, 'Product Updated', 'products', 1, '{\"id\":1,\"title\":\"Total Station Professional\",\"slug\":\"total-station\",\"description\":\"High-precision surveying instrument for accurate measurements in the field. Ideal for construction layout, topographic surveys, and boundary determinations.\",\"short_description\":\"High-precision surveying instrument for accurate field measurements.\",\"category\":\"equipment\",\"manufacturer\":\"Trimble\",\"model\":\"SX10\",\"price\":null,\"warranty\":\"2 Years\",\"support\":\"24\\/7 Technical Support\",\"specifications\":null,\"features\":null,\"icon\":\"fa-broadcast-tower\",\"image\":null,\"gallery\":null,\"status\":\"active\",\"sort_order\":1,\"meta_title\":null,\"meta_description\":null,\"created_at\":\"2025-09-22 17:48:06\",\"updated_at\":\"2025-09-22 17:48:06\"}', '{\"title\":\"Total Station Professional\",\"slug\":\"total-station\",\"description\":\"High-precision surveying instrument for accurate measurements in the field. Ideal for construction layout, topographic surveys, and boundary determinations.\",\"short_description\":\"High-precision surveying instrument for accurate field measurements.\",\"category\":\"equipment\",\"manufacturer\":\"Trimble\",\"model\":\"SX10\",\"specifications\":\"[]\",\"price\":null,\"warranty\":\"2 Years\",\"support\":\"24\\/7 Technical Support\",\"icon\":\"fa-broadcast-tower\",\"status\":\"active\",\"sort_order\":1,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:37:43'),
(14, 1, 'Product Status Changed', 'products', 8, '{\"status\":\"active\"}', '{\"status\":\"inactive\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:37:57'),
(15, 1, 'Product Status Changed', 'products', 8, '{\"status\":\"inactive\"}', '{\"status\":\"active\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:38:00'),
(16, 1, 'Product Deleted', 'products', 2, '{\"title\":\"RTK GPS System\"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:50:58'),
(17, 1, 'Service Deleted', 'services', 3, '{\"title\":\"House Plans\"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:53:06'),
(18, 1, 'Service Created', 'services', 6, NULL, '{\"title\":\"vschbsvjk sdvf\",\"slug\":\"vschbsvjk-sdvf\",\"description\":\"sdvsfbxfv fvsfvsf\",\"short_description\":\"\",\"icon\":\"\",\"languages\":\"[]\",\"price\":null,\"duration\":\"\",\"features\":\"[]\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:53:51'),
(19, 1, 'Service Deleted', 'services', 6, '{\"title\":\"vschbsvjk sdvf\"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:53:59'),
(20, 1, 'Product Created', 'products', 10, NULL, '{\"title\":\"fbfsvsvsvs sfvs fsvs f\",\"slug\":\"fbfsvsvsvs-sfvs-fsvs-f\",\"description\":\"fvsgv fbsx\",\"short_description\":\"\",\"category\":\"software\",\"manufacturer\":\"\",\"model\":\"\",\"specifications\":\"[]\",\"price\":null,\"warranty\":\"\",\"support\":\"\",\"icon\":\"\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:54:58'),
(21, 1, 'Product Deleted', 'products', 10, '{\"title\":\"fbfsvsvsvs sfvs fsvs f\"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:55:07'),
(22, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 06:29:49'),
(23, 1, 'User Created', 'admin_users', 2, NULL, '{\"username\":\"Aimecol\",\"email\":\"aimecol314@gmail.com\",\"full_name\":\"Aimecol\",\"role\":\"super_admin\",\"status\":\"active\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 06:47:48'),
(24, 1, 'Company Settings Updated', 'settings', 0, NULL, '{\"company_name\":\"Fair Surveying &amp;amp; Mapping Ltd\",\"company_tagline\":\"Reliable | Professional | Expert Solutions\",\"company_description\":\"dvdfvdf\",\"company_address\":\"Kigali, Rwanda\",\"company_city\":\"Kigali\",\"company_country\":\"Rwanda\",\"company_postal_code\":\"\",\"company_phone\":\"+250 788 331 697\",\"company_email\":\"fsamcompanyltd@gmail.com\",\"company_website\":\"https:\\/\\/fsamcompanyltd.com\",\"company_founded\":\"2025\",\"company_employees\":\"5\",\"company_license\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 06:48:55'),
(25, 1, 'Company Settings Updated', 'settings', 0, NULL, '{\"company_name\":\"Fair Surveying &amp;amp;amp; Mapping Ltd\",\"company_tagline\":\"Reliable | Professional | Expert Solutions\",\"company_description\":\"\",\"company_address\":\"Kigali, Rwanda\",\"company_city\":\"Kigali\",\"company_country\":\"Rwanda\",\"company_postal_code\":\"\",\"company_phone\":\"+250 788 331 697\",\"company_email\":\"fsamcompanyltd@gmail.com\",\"company_website\":\"https:\\/\\/fsamcompanyltd.com\",\"company_founded\":\"2025\",\"company_employees\":\"5\",\"company_license\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 06:49:02'),
(26, 1, 'Research Project Deleted', 'research_projects', 1, '{\"title\":\"AI-Enhanced Land Cover Classification\"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 07:16:16'),
(27, 1, 'Research Project Status Changed', 'research_projects', 2, '{\"status\":\"completed\"}', '{\"status\":\"published\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 07:16:18'),
(28, 1, 'Research Project Status Changed', 'research_projects', 2, '{\"status\":\"published\"}', '{\"status\":\"draft\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 07:16:20'),
(29, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 07:49:27'),
(30, 1, 'Training Program Created', 'training_programs', 5, NULL, '{\"title\":\"fgfdgaaga\",\"slug\":\"fgfdgaaga\",\"description\":\"fveefvda\",\"short_description\":\"\",\"category\":\"surveying\",\"price\":0,\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":[\"\"],\"image\":\"\",\"gallery\":\"[]\",\"instructor\":\"fvvdfvdf\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 07:49:55'),
(31, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 11:49:25'),
(32, 1, 'Research Project Status Changed', 'research_projects', 2, '{\"status\":\"draft\"}', '{\"status\":\"published\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:01:56'),
(33, 1, 'Research Project Status Changed', 'research_projects', 2, '{\"status\":\"published\"}', '{\"status\":\"draft\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:02:09'),
(34, 1, 'Training Program Status Changed', 'training_programs', 4, '{\"status\":\"active\"}', '{\"status\":\"inactive\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:13:29'),
(35, 1, 'Training Program Status Changed', 'training_programs', 4, '{\"status\":\"inactive\"}', '{\"status\":\"active\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:13:35'),
(36, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:22:47'),
(37, 1, 'Training Program Deleted', 'training_programs', 4, '{\"title\":\"Remote Sensing & Image Analysis\"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:28:10'),
(38, 1, 'Research Project Status Changed', 'research_projects', 2, '{\"status\":\"draft\"}', '{\"status\":\"published\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:31:46'),
(39, 1, 'Research Project Status Changed', 'research_projects', 2, '{\"status\":\"published\"}', '{\"status\":\"draft\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:31:57'),
(40, 1, 'Research Project Status Changed', 'research_projects', 2, '{\"status\":\"draft\"}', '{\"status\":\"published\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:32:04'),
(41, 1, 'Training Program Created', 'training_programs', 6, NULL, '{\"title\":\"fvsfbgf\",\"slug\":\"fvsfbgf\",\"description\":\"bgbgbsf\",\"short_description\":\"\",\"category\":\"surveying\",\"price\":0,\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":[\"\"],\"image\":\"\",\"gallery\":\"[]\",\"instructor\":\"\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:40:34'),
(42, 1, 'Training Program Updated', 'training_programs', 6, '{\"id\":6,\"title\":\"fvsfbgf\",\"slug\":\"fvsfbgf\",\"description\":\"bgbgbsf\",\"short_description\":\"\",\"category\":\"surveying\",\"price\":\"0.00\",\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":\"Array\",\"image\":\"\",\"gallery\":\"[]\",\"instructor\":\"\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\",\"created_at\":\"2025-09-23 12:40:34\",\"updated_at\":\"2025-09-23 12:40:34\"}', '{\"title\":\"fvsfbgf\",\"slug\":\"fvsfbgf\",\"description\":\"bgbgbsf\",\"short_description\":\"\",\"category\":\"surveying\",\"price\":2000000,\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":[\"\"],\"image\":\"\",\"gallery\":\"[]\",\"instructor\":\"\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:45:06'),
(43, 1, 'Training Program Updated', 'training_programs', 6, '{\"id\":6,\"title\":\"fvsfbgf\",\"slug\":\"fvsfbgf\",\"description\":\"bgbgbsf\",\"short_description\":\"\",\"category\":\"surveying\",\"price\":\"2000000.00\",\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":\"Array\",\"image\":\"\",\"gallery\":\"[]\",\"instructor\":\"\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\",\"created_at\":\"2025-09-23 12:40:34\",\"updated_at\":\"2025-09-23 12:45:06\"}', '{\"title\":\"fvsfbgf\",\"slug\":\"fvsfbgf\",\"description\":\"bgbgbsf\",\"short_description\":\"\",\"category\":\"surveying\",\"price\":2000000,\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":[\"\"],\"image\":\"\",\"gallery\":\"[]\",\"instructor\":\"fvvdfvdf\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:45:20'),
(44, 1, 'Training Program Updated', 'training_programs', 6, '{\"id\":6,\"title\":\"fvsfbgf\",\"slug\":\"fvsfbgf\",\"description\":\"bgbgbsf\",\"short_description\":\"\",\"category\":\"surveying\",\"price\":\"2000000.00\",\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":\"Array\",\"image\":\"\",\"gallery\":\"[]\",\"instructor\":\"fvvdfvdf\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\",\"created_at\":\"2025-09-23 12:40:34\",\"updated_at\":\"2025-09-23 12:45:20\"}', '{\"title\":\"fvsfbgf\",\"slug\":\"fvsfbgf\",\"description\":\"bgbgbsf\",\"short_description\":\"\",\"category\":\"surveying\",\"price\":0,\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":[\"\"],\"image\":\"\",\"gallery\":\"[]\",\"instructor\":\"fvvdfvdf\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:45:40'),
(45, 1, 'Training Program Updated', 'training_programs', 5, '{\"id\":5,\"title\":\"fgfdgaaga\",\"slug\":\"fgfdgaaga\",\"description\":\"fveefvda\",\"short_description\":\"\",\"category\":\"surveying\",\"price\":\"0.00\",\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":\"Array\",\"image\":\"\",\"gallery\":\"[]\",\"instructor\":\"fvvdfvdf\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\",\"created_at\":\"2025-09-23 07:49:55\",\"updated_at\":\"2025-09-23 07:49:55\"}', '{\"title\":\"fgfdgaaga\",\"slug\":\"fgfdgaaga\",\"description\":\"fveefvda\",\"short_description\":\"\",\"category\":\"surveying\",\"price\":0,\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":[\"\"],\"image\":\"\",\"gallery\":\"[]\",\"instructor\":\"fvvdfvdf\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 12:47:01'),
(46, 1, 'File Uploaded', 'file_uploads', 1, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758632747_4a80431f9f65faea.png\",\"file_path\":\"images\\/services\\/1758632747_4a80431f9f65faea.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 13:05:48'),
(47, 1, 'File Uploaded', 'file_uploads', 2, NULL, '{\"original_name\":\"screencapture-billing-augmentcode-c-pay-cs-live-a1hlIRx5SWGhov7D6BuyVAnnsqDg1tCEmujjCp3aLyD8bqQyaGEikP5HSb-2025-09-13-18_26_16.png\",\"file_name\":\"1758632768_e036c40e905fc40b.png\",\"file_path\":\"images\\/products\\/1758632768_e036c40e905fc40b.png\",\"file_size\":74982,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 13:06:08'),
(48, 1, 'File Uploaded', 'file_uploads', 3, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758633131_ec942ce699215acc.png\",\"file_path\":\"images\\/services\\/1758633131_ec942ce699215acc.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 13:12:11'),
(49, 1, 'File Uploaded', 'file_uploads', 4, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758633135_18644843bf00b39c.png\",\"file_path\":\"images\\/research\\/1758633135_18644843bf00b39c.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 13:12:15'),
(50, 1, 'File Uploaded', 'file_uploads', 5, NULL, '{\"original_name\":\"screencapture-billing-augmentcode-c-pay-cs-live-a1hlIRx5SWGhov7D6BuyVAnnsqDg1tCEmujjCp3aLyD8bqQyaGEikP5HSb-2025-09-13-18_26_16.png\",\"file_name\":\"1758633138_f77f6dae5fbb681f.png\",\"file_path\":\"images\\/products\\/1758633138_f77f6dae5fbb681f.png\",\"file_size\":74982,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 13:12:18'),
(51, 1, 'File Uploaded', 'file_uploads', 6, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758633307_1f6faae24849af5d.png\",\"file_path\":\"images\\/services\\/1758633307_1f6faae24849af5d.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 13:15:08'),
(52, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 17:51:06'),
(53, 1, 'File Uploaded', 'file_uploads', 7, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758650160_c282f8beb9a0bbdb.png\",\"file_path\":\"images\\/services\\/1758650160_c282f8beb9a0bbdb.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 17:56:00'),
(54, 1, 'File Uploaded', 'file_uploads', 8, NULL, '{\"original_name\":\"screencapture-hpanel-hostinger-websites-fsamcompanyltd-com-databases-php-my-admin-2025-09-19-11_49_21.png\",\"file_name\":\"1758650216_5cf013a7abb709d3.png\",\"file_path\":\"images\\/products\\/1758650216_5cf013a7abb709d3.png\",\"file_size\":139273,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 17:56:57'),
(55, 1, 'File Uploaded', 'file_uploads', 9, NULL, '{\"original_name\":\"screencapture-file-C-Users-HOSA-LTD-AppData-Local-Microsoft-Windows-INetCache-IE-7CZ29GIL-SUSURUKA-full-page-1-pdf-2025-09-17-18_11_54.png\",\"file_name\":\"1758650259_adcb9a088e8ab277.png\",\"file_path\":\"images\\/training\\/1758650259_adcb9a088e8ab277.png\",\"file_size\":340231,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 17:57:40'),
(56, 1, 'File Uploaded', 'file_uploads', 10, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758650306_c58d739ad050636e.png\",\"file_path\":\"images\\/research\\/1758650306_c58d739ad050636e.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 17:58:28'),
(57, 1, 'File Uploaded', 'file_uploads', 11, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758650373_6670c7fe149f3709.png\",\"file_path\":\"images\\/research\\/1758650373_6670c7fe149f3709.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 17:59:33'),
(58, 1, 'File Uploaded', 'file_uploads', 12, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758650974_f614603114e158be.png\",\"file_path\":\"images\\/research\\/1758650974_f614603114e158be.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 18:09:34'),
(59, 1, 'Research Project Created', 'research_projects', 3, NULL, '{\"title\":\"dvfda vvdsbg\",\"slug\":\"dvfda-vvdsbg\",\"abstract\":\"The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\\r\\n\\r\\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &quot;less than&quot; or &quot;greater than&quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.\",\"description\":\"The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\\r\\n\\r\\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &quot;less than&quot; or &quot;greater than&quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.\",\"methodology\":\"The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\\r\\n\\r\\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &quot;less than&quot; or &quot;greater than&quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.\",\"key_findings\":\"[\\\"[]\\\"]\",\"authors\":\"[\\\"[]\\\"]\",\"publication_date\":\"2025-09-23\",\"journal\":\"\",\"doi\":\"\",\"keywords\":\"[]\",\"category\":\"surveying\",\"status\":\"draft\",\"featured\":1,\"image\":\"images\\/research\\/1758650974_f614603114e158be.png\",\"gallery\":\"[]\",\"documents\":\"[]\",\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 18:09:34'),
(60, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:08:11'),
(61, 1, 'File Uploaded', 'file_uploads', 15, NULL, '{\"original_name\":\"screencapture-localhost-ikimina-GRMS-public-2025-09-21-12_25_38.png\",\"file_name\":\"1758654528_ab92e5df5583d538.png\",\"file_path\":\"images\\/training\\/1758654528_ab92e5df5583d538.png\",\"file_size\":1341549,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:08:49'),
(62, 1, 'Training Program Created', 'training_programs', 7, NULL, '{\"title\":\"scn jdv dvs\",\"slug\":\"scn-jdv-dvs\",\"description\":\"dfvnfmjgnjv damjkA system error occurred. Please contact the administrator.\",\"short_description\":\"\",\"category\":\"surveying\",\"price\":0,\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":[\"\"],\"image\":\"images\\/training\\/1758654528_ab92e5df5583d538.png\",\"gallery\":\"[]\",\"instructor\":\"\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:08:49'),
(63, 1, 'File Uploaded', 'file_uploads', 16, NULL, '{\"original_name\":\"screencapture-file-C-Users-HOSA-LTD-AppData-Local-Microsoft-Windows-INetCache-IE-7CZ29GIL-SUSURUKA-full-page-1-pdf-2025-09-17-18_11_54.png\",\"file_name\":\"1758654588_5472d3ca4481c77f.png\",\"file_path\":\"images\\/products\\/1758654588_5472d3ca4481c77f.png\",\"file_size\":340231,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:09:48'),
(64, 1, 'File Uploaded', 'file_uploads', 17, NULL, '{\"original_name\":\"screencapture-file-C-Users-HOSA-LTD-AppData-Local-Microsoft-Windows-INetCache-IE-7CZ29GIL-SUSURUKA-full-page-1-pdf-2025-09-17-18_11_54.png\",\"file_name\":\"1758654628_0981bd77a5246180.png\",\"file_path\":\"images\\/services\\/1758654628_0981bd77a5246180.png\",\"file_size\":340231,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:10:28'),
(65, 1, 'File Uploaded', 'file_uploads', 18, NULL, '{\"original_name\":\"screencapture-file-C-Users-HOSA-LTD-AppData-Local-Microsoft-Windows-INetCache-IE-7CZ29GIL-SUSURUKA-full-page-1-pdf-2025-09-17-18_11_54.png\",\"file_name\":\"1758654663_2d0340c111f03e46.png\",\"file_path\":\"images\\/services\\/1758654663_2d0340c111f03e46.png\",\"file_size\":340231,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:11:04'),
(66, 1, 'File Uploaded', 'file_uploads', 19, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758654697_ee9a1291ed3307cf.png\",\"file_path\":\"images\\/products\\/1758654697_ee9a1291ed3307cf.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:11:37'),
(67, NULL, 'File Uploaded', 'file_uploads', 22, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758654977_89794555517ad776.png\",\"file_path\":\"images\\/test\\/1758654977_89794555517ad776.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":null,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:16:18'),
(68, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:16:37'),
(69, 1, 'File Uploaded', 'file_uploads', 23, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758655013_b9a8a9433b2b3898.png\",\"file_path\":\"images\\/products\\/1758655013_b9a8a9433b2b3898.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:16:53'),
(70, 1, 'File Uploaded', 'file_uploads', 24, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758655038_c9e958ebd9ff5d4c.png\",\"file_path\":\"images\\/products\\/1758655038_c9e958ebd9ff5d4c.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:17:19'),
(71, 1, 'File Uploaded', 'file_uploads', 25, NULL, '{\"original_name\":\"screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png\",\"file_name\":\"1758655074_83e6d2ce7a540d24.png\",\"file_path\":\"images\\/products\\/1758655074_83e6d2ce7a540d24.png\",\"file_size\":41541,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 19:17:54'),
(72, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:28:43'),
(73, 1, 'User Login', 'admin_users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:29:06'),
(74, 1, 'File Uploaded', 'file_uploads', 26, NULL, '{\"original_name\":\"13.09.2025_01.58.18_REC.png\",\"file_name\":\"1758670202_3b6b5142f06e6c5d.png\",\"file_path\":\"images\\/services\\/1758670202_3b6b5142f06e6c5d.png\",\"file_size\":52437,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:30:02'),
(75, 1, 'File Uploaded', 'file_uploads', 27, NULL, '{\"original_name\":\"13.09.2025_01.59.36_REC.png\",\"file_name\":\"1758670228_62cda2226fb4a37e.png\",\"file_path\":\"images\\/products\\/1758670228_62cda2226fb4a37e.png\",\"file_size\":54556,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:30:28'),
(76, 1, 'File Uploaded', 'file_uploads', 28, NULL, '{\"original_name\":\"13.09.2025_02.02.47_REC.png\",\"file_name\":\"1758670268_b60ea2da094a9898.png\",\"file_path\":\"images\\/training\\/1758670268_b60ea2da094a9898.png\",\"file_size\":149548,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:31:08'),
(77, 1, 'Training Program Created', 'training_programs', 8, NULL, '{\"title\":\"dvdfbgfbbg fdfdb gab\",\"slug\":\"dvdfbgfbbg-fdfdb-gab-\",\"description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.\",\"short_description\":\"\",\"category\":\"cartography\",\"price\":0,\"duration\":\"\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":[\"\"],\"image\":\"images\\/training\\/1758670268_b60ea2da094a9898.png\",\"gallery\":\"[]\",\"instructor\":\"\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:31:08'),
(78, 1, 'File Uploaded', 'file_uploads', 29, NULL, '{\"original_name\":\"23.09.2025_02.02.44_REC.png\",\"file_name\":\"1758670373_f2bdb2067096ce19.png\",\"file_path\":\"images\\/research\\/1758670373_f2bdb2067096ce19.png\",\"file_size\":120895,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:32:53'),
(79, 1, 'File Uploaded', 'file_uploads', 30, NULL, '{\"original_name\":\"23.09.2025_02.02.44_REC.png\",\"file_name\":\"1758670436_0a5013c14912c355.png\",\"file_path\":\"images\\/research\\/1758670436_0a5013c14912c355.png\",\"file_size\":120895,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:33:56'),
(80, 1, 'Research Project Created', 'research_projects', 4, NULL, '{\"title\":\"fgtbgdg fdv f\",\"slug\":\"fgtbgdg-fdv-f\",\"abstract\":\"Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank\",\"description\":\"Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank\",\"methodology\":\"Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank\",\"key_findings\":\"[\\\"Aimecol, Jack, Frank\\\"]\",\"authors\":\"[\\\"Aimecol\\\",\\\"Jack\\\",\\\"Frank\\\"]\",\"publication_date\":\"2025-09-24\",\"journal\":\"Aimecol, Jack, Frank\",\"doi\":\"\",\"keywords\":\"[]\",\"category\":\"surveying\",\"status\":\"draft\",\"featured\":0,\"image\":\"images\\/research\\/1758670436_0a5013c14912c355.png\",\"gallery\":\"[]\",\"documents\":\"[]\",\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:33:56'),
(81, 1, 'File Uploaded', 'file_uploads', 31, NULL, '{\"original_name\":\"13.09.2025_01.58.18_REC.png\",\"file_name\":\"1758670655_6e828f86d4e85b05.png\",\"file_path\":\"images\\/training\\/1758670655_6e828f86d4e85b05.png\",\"file_size\":52437,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:37:35'),
(82, 1, 'Training Program Created', 'training_programs', 9, NULL, '{\"title\":\"vbd egrl nkj gk\",\"slug\":\"vbd-egrl-nkj-gk\",\"description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.\",\"short_description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.\",\"category\":\"surveying\",\"price\":0,\"duration\":\"5days\",\"max_students\":20,\"language\":\"English\",\"level\":\"beginner\",\"features\":\"[]\",\"curriculum\":\"[]\",\"requirements\":[\"\"],\"image\":\"images\\/training\\/1758670655_6e828f86d4e85b05.png\",\"gallery\":\"[]\",\"instructor\":\"Aimecol\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"gg g rg er gr\",\"meta_description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:37:35'),
(83, 1, 'File Uploaded', 'file_uploads', 32, NULL, '{\"original_name\":\"23.09.2025_02.02.44_REC.png\",\"file_name\":\"1758670704_b87c6ac41bae9158.png\",\"file_path\":\"images\\/services\\/1758670704_b87c6ac41bae9158.png\",\"file_size\":120895,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:38:24'),
(84, 1, 'File Uploaded', 'file_uploads', 33, NULL, '{\"original_name\":\"23.09.2025_02.02.44_REC.png\",\"file_name\":\"1758670959_d5ad7767d31ef463.png\",\"file_path\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"file_size\":120895,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:42:39'),
(85, 1, 'Service Created', 'services', 7, NULL, '{\"title\":\"sdvfdv dfD GRF\",\"slug\":\"sdvfdv-dfd-grf\",\"description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.\",\"short_description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.\",\"icon\":\"\",\"languages\":\"[]\",\"price\":null,\"duration\":\"\",\"features\":\"[]\",\"image\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:42:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(86, 1, 'File Uploaded', 'file_uploads', 34, NULL, '{\"original_name\":\"23.09.2025_02.04.45_REC.png\",\"file_name\":\"1758671608_613ed1b5fbe4b1d7.png\",\"file_path\":\"images\\/products\\/1758671608_613ed1b5fbe4b1d7.png\",\"file_size\":137325,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:53:28'),
(87, 1, 'Product Created', 'products', 11, NULL, '{\"title\":\"stht gr r ewgr\",\"slug\":\"stht-gr-r-ewgr\",\"description\":\"INSERT INTO `company_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `category`, `description`, `is_public`, `created_at`, `updated_at`) VALUES\\r\\n(1, &#039;company_name&#039;, &#039;Fair Surveying &amp; Mapping Ltd&#039;, &#039;text&#039;, &#039;general&#039;, &#039;Company name&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(2, &#039;company_tagline&#039;, &#039;Reliable | Professional | Expert Solutions&#039;, &#039;text&#039;, &#039;general&#039;, &#039;Company tagline&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(3, &#039;company_email&#039;, &#039;fsamcompanyltd@gmail.com&#039;, &#039;text&#039;, &#039;contact&#039;, &#039;Primary email address&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(4, &#039;company_phone&#039;, &#039;+250 788 331 697&#039;, &#039;text&#039;, &#039;contact&#039;, &#039;Primary phone number&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(5, &#039;company_address&#039;, &#039;Kigali, Rwanda&#039;, &#039;text&#039;, &#039;contact&#039;, &#039;Company address&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(6, &#039;surveyor_name&#039;, &#039;HATANGIMANA Fulgence&#039;, &#039;text&#039;, &#039;general&#039;, &#039;Licensed surveyor name&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(7, &#039;surveyor_code&#039;, &#039;LS00280&#039;, &#039;text&#039;, &#039;general&#039;, &#039;Surveyor license code&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(8, &#039;establishment_year&#039;, &#039;2023&#039;, &#039;text&#039;, &#039;general&#039;, &#039;Year company was established&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(9, &#039;facebook_url&#039;, &#039;#&#039;, &#039;text&#039;, &#039;social&#039;, &#039;Facebook page URL&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(10, &#039;twitter_url&#039;, &#039;#&#039;, &#039;text&#039;, &#039;social&#039;, &#039;Twitter profile URL&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(11, &#039;linkedin_url&#039;, &#039;#&#039;, &#039;text&#039;, &#039;social&#039;, &#039;LinkedIn profile URL&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(12, &#039;instagram_url&#039;, &#039;#&#039;, &#039;text&#039;, &#039;social&#039;, &#039;Instagram profile URL&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(13, &#039;site_title&#039;, &#039;Fair Surveying &amp; Mapping Ltd&#039;, &#039;text&#039;, &#039;seo&#039;, &#039;Default site title&#039;, 0, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(14, &#039;site_description&#039;, &#039;Professional surveying and mapping services in Rwanda&#039;, &#039;textarea&#039;, &#039;seo&#039;, &#039;Default site description&#039;, 0, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(15, &#039;admin_email_notifications&#039;, &#039;1&#039;, &#039;boolean&#039;, &#039;admin&#039;, &#039;Enable email notifications for admin&#039;, 0, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\\r\\n(16, &#039;max_file_upload_size&#039;, &#039;10485760&#039;, &#039;number&#039;, &#039;system&#039;, &#039;Maximum file upload size in bytes (10MB)&#039;, 0, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;);\",\"short_description\":\"\",\"category\":\"equipment\",\"manufacturer\":\"\",\"model\":\"\",\"specifications\":\"[]\",\"price\":null,\"warranty\":\"\",\"support\":\"\",\"icon\":\"\",\"image\":\"images\\/products\\/1758671608_613ed1b5fbe4b1d7.png\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 23:53:28'),
(88, 1, 'Research Project Updated', 'research_projects', 3, '{\"id\":3,\"title\":\"dvfda vvdsbg\",\"slug\":\"dvfda-vvdsbg\",\"abstract\":\"The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\\r\\n\\r\\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &quot;less than&quot; or &quot;greater than&quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.\",\"description\":\"The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\\r\\n\\r\\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &quot;less than&quot; or &quot;greater than&quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.\",\"methodology\":\"The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\\r\\n\\r\\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &quot;less than&quot; or &quot;greater than&quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.\",\"key_findings\":\"[\\\"[]\\\"]\",\"authors\":\"[\\\"[]\\\"]\",\"publication_date\":\"2025-09-23\",\"journal\":\"\",\"doi\":\"\",\"keywords\":\"[]\",\"category\":\"surveying\",\"status\":\"draft\",\"featured\":1,\"image\":\"images\\/research\\/1758650974_f614603114e158be.png\",\"gallery\":\"[]\",\"documents\":\"[]\",\"meta_title\":\"\",\"meta_description\":\"\",\"created_at\":\"2025-09-23 18:09:34\",\"updated_at\":\"2025-09-23 18:09:34\"}', '{\"title\":\"dvfda vvdsbg\",\"slug\":\"dvfda-vvdsbg\",\"abstract\":\"The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&amp;#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\\r\\n\\r\\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &amp;quot;less than&amp;quot; or &amp;quot;greater than&amp;quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.\",\"description\":\"The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&amp;#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\\r\\n\\r\\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &amp;quot;less than&amp;quot; or &amp;quot;greater than&amp;quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.\",\"methodology\":\"The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&amp;#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\\r\\n\\r\\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &amp;quot;less than&amp;quot; or &amp;quot;greater than&amp;quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.\",\"key_findings\":\"[\\\"[&quot;[]&quot;]\\\"]\",\"authors\":\"[\\\"[&quot;[]&quot;]\\\"]\",\"publication_date\":\"2025-09-23\",\"journal\":\"\",\"doi\":\"\",\"keywords\":\"[]\",\"category\":\"surveying\",\"status\":\"published\",\"featured\":1,\"image\":\"images\\/research\\/1758650974_f614603114e158be.png\",\"gallery\":\"[]\",\"documents\":\"[]\",\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 00:04:04'),
(89, 1, 'Research Project Updated', 'research_projects', 4, '{\"id\":4,\"title\":\"fgtbgdg fdv f\",\"slug\":\"fgtbgdg-fdv-f\",\"abstract\":\"Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank\",\"description\":\"Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank\",\"methodology\":\"Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank\",\"key_findings\":\"[\\\"Aimecol, Jack, Frank\\\"]\",\"authors\":\"[\\\"Aimecol\\\",\\\"Jack\\\",\\\"Frank\\\"]\",\"publication_date\":\"2025-09-24\",\"journal\":\"Aimecol, Jack, Frank\",\"doi\":\"\",\"keywords\":\"[]\",\"category\":\"surveying\",\"status\":\"draft\",\"featured\":0,\"image\":\"images\\/research\\/1758670436_0a5013c14912c355.png\",\"gallery\":\"[]\",\"documents\":\"[]\",\"meta_title\":\"\",\"meta_description\":\"\",\"created_at\":\"2025-09-23 23:33:56\",\"updated_at\":\"2025-09-23 23:33:56\"}', '{\"title\":\"fgtbgdg fdv f\",\"slug\":\"fgtbgdg-fdv-f\",\"abstract\":\"Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank\",\"description\":\"Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank\",\"methodology\":\"Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank\",\"key_findings\":\"[\\\"[&quot;Aimecol, Jack, Frank&quot;]\\\"]\",\"authors\":\"[\\\"[&quot;Aimecol&quot;\\\",\\\"&quot;Jack&quot;\\\",\\\"&quot;Frank&quot;]\\\"]\",\"publication_date\":\"2025-09-24\",\"journal\":\"Aimecol, Jack, Frank\",\"doi\":\"\",\"keywords\":\"[]\",\"category\":\"surveying\",\"status\":\"published\",\"featured\":0,\"image\":\"images\\/research\\/1758670436_0a5013c14912c355.png\",\"gallery\":\"[]\",\"documents\":\"[]\",\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 00:04:37'),
(90, 1, 'Contact Settings Updated', 'settings', 0, NULL, '{\"contact_phone_primary\":\"+250 789375245\",\"contact_phone_secondary\":\"\",\"contact_email_general\":\"fsamcompanyltd@gmail.com\",\"contact_email_support\":\"\",\"contact_email_sales\":\"\",\"contact_hours_weekday\":\"Monday - Friday: 8:00 AM - 6:00 PM\",\"contact_hours_weekend\":\"Saturday: 8:00 AM - 2:00 PM\",\"contact_emergency\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 00:16:27'),
(91, 1, 'File Uploaded', 'file_uploads', 35, NULL, '{\"original_name\":\"13.09.2025_02.01.18_REC.png\",\"file_name\":\"1758676789_734da63357c1a758.png\",\"file_path\":\"images\\/services\\/1758676789_734da63357c1a758.png\",\"file_size\":16524,\"mime_type\":\"image\\/png\",\"file_type\":\"image\",\"related_table\":null,\"related_id\":null,\"uploaded_by\":1,\"alt_text\":null,\"caption\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 01:19:49'),
(92, 1, 'Service Updated', 'services', 5, '{\"id\":5,\"title\":\"Environmental Assessment\",\"slug\":\"environmental-assessment\",\"description\":\"Comprehensive environmental impact assessments for development projects, ensuring compliance with environmental regulations and sustainable development practices.\",\"short_description\":\"Environmental impact assessments ensuring regulatory compliance and sustainability.\",\"icon\":\"fas fa-leaf\",\"languages\":\"[\\\"English\\\", \\\"Kinyarwanda\\\"]\",\"price\":null,\"duration\":null,\"features\":\"[\\\"Impact assessment\\\", \\\"Regulatory compliance\\\", \\\"Sustainability focus\\\", \\\"Development support\\\"]\",\"image\":null,\"gallery\":null,\"process_steps\":null,\"benefits\":null,\"requirements\":null,\"faqs\":null,\"status\":\"active\",\"sort_order\":5,\"meta_title\":null,\"meta_description\":null,\"created_at\":\"2025-09-22 17:48:06\",\"updated_at\":\"2025-09-22 17:48:06\"}', '{\"title\":\"Environmental Assessment\",\"slug\":\"environmental-assessment\",\"description\":\"Comprehensive environmental impact assessments for development projects, ensuring compliance with environmental regulations and sustainable development practices.\",\"short_description\":\"Environmental impact assessments ensuring regulatory compliance and sustainability.\",\"icon\":\"fas fa-leaf\",\"languages\":\"[\\\"English\\\",\\\"Kinyarwanda\\\"]\",\"price\":null,\"duration\":\"\",\"features\":\"[\\\"Impact assessment\\\",\\\"Regulatory compliance\\\",\\\"Sustainability focus\\\",\\\"Development support\\\"]\",\"process_steps\":\"[{\\\"step\\\":1,\\\"title\\\":\\\"Initial Consultation\\\",\\\"description\\\":\\\"We meet with you to understand your needs and gather initial information.\\\",\\\"icon\\\":\\\"fas fa-check-circle\\\"},{\\\"step\\\":2,\\\"title\\\":\\\"Assessment\\\",\\\"description\\\":\\\"Our professional team conducts a thorough assessment of your requirements.\\\",\\\"icon\\\":\\\"fas fa-check-circle\\\"},{\\\"step\\\":3,\\\"title\\\":\\\"Implementation\\\",\\\"description\\\":\\\"We implement the service according to professional standards and best practices.\\\",\\\"icon\\\":\\\"fas fa-check-circle\\\"},{\\\"step\\\":4,\\\"title\\\":\\\"Completion\\\",\\\"description\\\":\\\"We deliver the completed service and provide all necessary documentation.\\\",\\\"icon\\\":\\\"fas fa-check-circle\\\"}]\",\"benefits\":\"[{\\\"title\\\":\\\"Professional Service\\\",\\\"description\\\":\\\"Our experienced team provides professional and reliable service that meets industry standards and client expectations.\\\",\\\"icon\\\":\\\"fas fa-check-circle\\\"},{\\\"title\\\":\\\"Timely Delivery\\\",\\\"description\\\":\\\"We complete projects within agreed timeframes, ensuring your schedule and deadlines are met efficiently.\\\",\\\"icon\\\":\\\"fas fa-check-circle\\\"},{\\\"title\\\":\\\"Quality Assurance\\\",\\\"description\\\":\\\"All our work is backed by quality assurance processes and professional guarantees for your peace of mind.\\\",\\\"icon\\\":\\\"fas fa-check-circle\\\"}]\",\"requirements\":\"[{\\\"category\\\":\\\"General Requirements\\\",\\\"items\\\":[\\\"Valid identification documents\\\",\\\"Relevant project or property documentation\\\"]},{\\\"category\\\":\\\"special Requirements\\\",\\\"items\\\":[\\\"Contact information and availability for consultation\\\",\\\"Clear project specifications and requirements\\\"]}]\",\"faqs\":\"[{\\\"question\\\":\\\"How long does the service take?\\\",\\\"answer\\\":\\\"The duration varies depending on the complexity and scope of the project. We provide estimated timelines during the initial consultation and keep you updated throughout the process.\\\"},{\\\"question\\\":\\\"What is the cost of this service?\\\",\\\"answer\\\":\\\"Costs vary based on project requirements and complexity. We provide detailed quotes after the initial consultation, with transparent pricing and no hidden charges.\\\"},{\\\"question\\\":\\\"Do you provide ongoing support?\\\",\\\"answer\\\":\\\"Yes, we provide comprehensive support throughout the entire process and offer post-completion assistance to ensure your satisfaction with our services.\\\"}]\",\"image\":\"images\\/services\\/1758676789_734da63357c1a758.png\",\"status\":\"active\",\"sort_order\":5,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 01:19:49'),
(93, 1, 'Service Updated', 'services', 7, '{\"id\":7,\"title\":\"sdvfdv dfD GRF\",\"slug\":\"sdvfdv-dfd-grf\",\"description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.\",\"short_description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.\",\"icon\":\"\",\"languages\":\"[]\",\"price\":null,\"duration\":\"\",\"features\":\"[]\",\"image\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"gallery\":null,\"process_steps\":null,\"benefits\":null,\"requirements\":null,\"faqs\":null,\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\",\"created_at\":\"2025-09-23 23:42:39\",\"updated_at\":\"2025-09-23 23:42:39\"}', '{\"title\":\"sdvfdv dfD GRF\",\"slug\":\"sdvfdv-dfd-grf\",\"description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"short_description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.\",\"icon\":\"\",\"languages\":\"[]\",\"price\":null,\"duration\":\"\",\"features\":\"[]\",\"process_steps\":\"[]\",\"benefits\":\"[]\",\"requirements\":\"[]\",\"faqs\":\"[]\",\"image\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 01:26:00'),
(94, 1, 'Service Updated', 'services', 7, '{\"id\":7,\"title\":\"sdvfdv dfD GRF\",\"slug\":\"sdvfdv-dfd-grf\",\"description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"short_description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.\",\"icon\":\"\",\"languages\":\"[]\",\"price\":null,\"duration\":\"\",\"features\":\"[]\",\"image\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"gallery\":null,\"process_steps\":\"[]\",\"benefits\":\"[]\",\"requirements\":\"[]\",\"faqs\":\"[]\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\",\"created_at\":\"2025-09-23 23:42:39\",\"updated_at\":\"2025-09-24 01:26:00\"}', '{\"title\":\"sdvfdv dfD GRF\",\"slug\":\"sdvfdv-dfd-grf\",\"description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"short_description\":\"\",\"icon\":\"\",\"languages\":\"[]\",\"price\":null,\"duration\":\"\",\"features\":\"[]\",\"process_steps\":\"[]\",\"benefits\":\"[]\",\"requirements\":\"[]\",\"faqs\":\"[]\",\"image\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 01:26:35'),
(95, 1, 'Service Updated', 'services', 7, '{\"id\":7,\"title\":\"sdvfdv dfD GRF\",\"slug\":\"sdvfdv-dfd-grf\",\"description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\\r\\n\\r\\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"short_description\":\"\",\"icon\":\"\",\"languages\":\"[]\",\"price\":null,\"duration\":\"\",\"features\":\"[]\",\"image\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"gallery\":null,\"process_steps\":\"[]\",\"benefits\":\"[]\",\"requirements\":\"[]\",\"faqs\":\"[]\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\",\"created_at\":\"2025-09-23 23:42:39\",\"updated_at\":\"2025-09-24 01:26:35\"}', '{\"title\":\"sdvfdv dfD GRF\",\"slug\":\"sdvfdv-dfd-grf\",\"description\":\"Our team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"short_description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"icon\":\"\",\"languages\":\"[]\",\"price\":null,\"duration\":\"\",\"features\":\"[]\",\"process_steps\":\"[]\",\"benefits\":\"[]\",\"requirements\":\"[]\",\"faqs\":\"[]\",\"image\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 01:27:10'),
(96, 1, 'Service Updated', 'services', 7, '{\"id\":7,\"title\":\"sdvfdv dfD GRF\",\"slug\":\"sdvfdv-dfd-grf\",\"description\":\"Our team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"short_description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"icon\":\"\",\"languages\":\"[]\",\"price\":null,\"duration\":\"\",\"features\":\"[]\",\"image\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"gallery\":null,\"process_steps\":\"[]\",\"benefits\":\"[]\",\"requirements\":\"[]\",\"faqs\":\"[]\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\",\"created_at\":\"2025-09-23 23:42:39\",\"updated_at\":\"2025-09-24 01:27:10\"}', '{\"title\":\"sdvfdv dfD GRF\",\"slug\":\"sdvfdv-dfd-grf\",\"description\":\"Our team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"short_description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"icon\":\"\",\"languages\":\"[\\\"English\\\"]\",\"price\":null,\"duration\":\"\",\"features\":\"[]\",\"process_steps\":\"[]\",\"benefits\":\"[]\",\"requirements\":\"[]\",\"faqs\":\"[]\",\"image\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 01:27:39'),
(97, 1, 'Service Updated', 'services', 7, '{\"id\":7,\"title\":\"sdvfdv dfD GRF\",\"slug\":\"sdvfdv-dfd-grf\",\"description\":\"Our team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"short_description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"icon\":\"\",\"languages\":\"[\\\"English\\\"]\",\"price\":null,\"duration\":\"\",\"features\":\"[]\",\"image\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"gallery\":null,\"process_steps\":\"[]\",\"benefits\":\"[]\",\"requirements\":\"[]\",\"faqs\":\"[]\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\",\"created_at\":\"2025-09-23 23:42:39\",\"updated_at\":\"2025-09-24 01:27:39\"}', '{\"title\":\"sdvfdv dfD GRF\",\"slug\":\"sdvfdv-dfd-grf\",\"description\":\"Our team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"short_description\":\"We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\",\"icon\":\"\",\"languages\":\"[\\\"English\\\"]\",\"price\":null,\"duration\":\"\",\"features\":\"[{\\\"title\\\":\\\"dsgbfsfb\\\",\\\"description\\\":\\\"dvsbfsbbsb\\\",\\\"icon\\\":\\\"fas fa-check-circle\\\"}]\",\"process_steps\":\"[]\",\"benefits\":\"[]\",\"requirements\":\"[]\",\"faqs\":\"[]\",\"image\":\"images\\/services\\/1758670959_d5ad7767d31ef463.png\",\"status\":\"active\",\"sort_order\":0,\"meta_title\":\"\",\"meta_description\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 01:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('super_admin','admin','editor') DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `failed_login_attempts` int(11) DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`, `status`, `last_login`, `failed_login_attempts`, `locked_until`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@fsmc.com', '$2y$10$zIchwnspy/sJAS2b8ydIv.m25oNW2onD7vjnynIg/lzuP2y5qLJ.G', 'System Administrator', 'super_admin', 'active', '2025-09-23 23:29:06', 0, NULL, '2025-09-22 17:48:06', '2025-09-23 23:29:06'),
(2, 'Aimecol', 'aimecol314@gmail.com', '$2y$10$4BhyLH6X27Uors0kEnGv/uPXFLAWjZYnYg91O6P93zVNgG28m4Ry2', 'Aimecol', 'super_admin', 'active', NULL, 0, NULL, '2025-09-23 06:47:48', '2025-09-23 06:47:48');

-- --------------------------------------------------------

--
-- Table structure for table `business_hours`
--

CREATE TABLE `business_hours` (
  `id` int(11) NOT NULL,
  `day_key` varchar(20) NOT NULL,
  `day_label` varchar(50) NOT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `is_closed` tinyint(1) DEFAULT 0,
  `custom_text` varchar(100) DEFAULT NULL COMMENT 'For custom text like "Closed" or "By Appointment"',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_hours`
--

INSERT INTO `business_hours` (`id`, `day_key`, `day_label`, `opening_time`, `closing_time`, `is_closed`, `custom_text`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'monday_friday', 'Monday - Friday', '08:00:00', '17:00:00', 0, NULL, 1, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(2, 'saturday', 'Saturday', '09:00:00', '13:00:00', 0, NULL, 2, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(3, 'sunday', 'Sunday', NULL, NULL, 1, 'Closed', 3, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','number','boolean','json','file') DEFAULT 'text',
  `category` varchar(50) DEFAULT 'general',
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `category`, `description`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'company_name', 'Fair Surveying & Mapping Ltd', 'text', 'general', 'Company name', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(2, 'company_tagline', 'Reliable | Professional | Expert Solutions', 'text', 'general', 'Company tagline', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(3, 'company_email', 'fsamcompanyltd@gmail.com', 'text', 'contact', 'Primary email address', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(4, 'company_phone', '+250 788 331 697', 'text', 'contact', 'Primary phone number', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(5, 'company_address', 'Kigali, Rwanda', 'text', 'contact', 'Company address', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(6, 'surveyor_name', 'HATANGIMANA Fulgence', 'text', 'general', 'Licensed surveyor name', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(7, 'surveyor_code', 'LS00280', 'text', 'general', 'Surveyor license code', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(8, 'establishment_year', '2023', 'text', 'general', 'Year company was established', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(9, 'facebook_url', '#', 'text', 'social', 'Facebook page URL', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(10, 'twitter_url', '#', 'text', 'social', 'Twitter profile URL', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(11, 'linkedin_url', '#', 'text', 'social', 'LinkedIn profile URL', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(12, 'instagram_url', '#', 'text', 'social', 'Instagram profile URL', 1, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(13, 'site_title', 'Fair Surveying & Mapping Ltd', 'text', 'seo', 'Default site title', 0, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(14, 'site_description', 'Professional surveying and mapping services in Rwanda', 'textarea', 'seo', 'Default site description', 0, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(15, 'admin_email_notifications', '1', 'boolean', 'admin', 'Enable email notifications for admin', 0, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(16, 'max_file_upload_size', '10485760', 'number', 'system', 'Maximum file upload size in bytes (10MB)', 0, '2025-09-22 17:48:06', '2025-09-22 17:48:06');

-- --------------------------------------------------------

--
-- Table structure for table `company_stats`
--

CREATE TABLE `company_stats` (
  `id` int(11) NOT NULL,
  `stat_key` varchar(100) NOT NULL COMMENT 'Unique identifier for statistic',
  `label` varchar(200) NOT NULL,
  `value` int(11) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `suffix` varchar(20) DEFAULT '+',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_stats`
--

INSERT INTO `company_stats` (`id`, `stat_key`, `label`, `value`, `icon`, `suffix`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'years_experience', 'Years of Experience', 12, 'fas fa-calendar-alt', '+', 1, 1, '2025-09-24 01:50:39', '2025-09-24 01:50:39'),
(2, 'projects_completed', 'Projects Completed', 850, 'fas fa-project-diagram', '+', 2, 1, '2025-09-24 01:50:39', '2025-09-24 01:50:39'),
(3, 'satisfied_clients', 'Satisfied Clients', 500, 'fas fa-users', '+', 3, 1, '2025-09-24 01:50:39', '2025-09-24 01:50:39'),
(4, 'team_members', 'Team Members', 15, 'fas fa-user-tie', '', 4, 1, '2025-09-24 01:50:39', '2025-09-24 01:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `company_timeline`
--

CREATE TABLE `company_timeline` (
  `id` int(11) NOT NULL,
  `year` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_timeline`
--

INSERT INTO `company_timeline` (`id`, `year`, `title`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '2019', 'Company Founded', 'Fair Surveying and Mapping was established by HATANGIMANA Fulgence to provide professional surveying services in Rwanda.', 'fas fa-flag', 1, 1, '2025-09-24 01:50:39', '2025-09-24 01:50:39'),
(2, '2015', 'Service Expansion', 'Expanded our services to include environmental consultancy and building support services to meet growing client demands.', 'fas fa-expand-arrows-alt', 2, 1, '2025-09-24 01:50:39', '2025-09-24 01:50:39'),
(3, '2018', 'Technical Training Program', 'Launched our technical training program to share expertise in surveying equipment, software, and geospatial analysis techniques.', 'fas fa-graduation-cap', 3, 1, '2025-09-24 01:50:39', '2025-09-24 01:50:39'),
(4, '2020', 'AI Integration', 'Pioneered the integration of artificial intelligence and machine learning into our geospatial analysis services.', 'fas fa-robot', 4, 1, '2025-09-24 01:50:39', '2025-09-24 01:50:39'),
(5, '2023', 'Research Support Division', 'Established a dedicated research support division to assist academic and commercial research projects in geospatial sciences.', 'fas fa-microscope', 5, 1, '2025-09-24 01:50:39', '2025-09-24 01:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `contact_info`
--

CREATE TABLE `contact_info` (
  `id` int(11) NOT NULL,
  `info_key` varchar(100) NOT NULL COMMENT 'Unique identifier for contact info',
  `title` varchar(200) NOT NULL,
  `value` text NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `link_type` enum('none','tel','mailto','url') DEFAULT 'none',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_info`
--

INSERT INTO `contact_info` (`id`, `info_key`, `title`, `value`, `icon`, `link_type`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'location', 'Our Location', 'Kigali, Rwanda', 'fas fa-map-marker-alt', 'none', 1, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(2, 'phone', 'Phone Number', '0788331697', 'fas fa-phone-alt', 'tel', 2, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(3, 'email', 'Email Address', 'fsamcompanyltd@gmail.com', 'fas fa-envelope', 'mailto', 3, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(4, 'professional', 'Professional Details', 'HATANGIMANA Fulgence\nSurveyor code: LS00280', 'fas fa-user-tie', 'none', 4, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `contact_inquiries`
--

CREATE TABLE `contact_inquiries` (
  `id` int(11) NOT NULL,
  `type` enum('general','service','product','training','research') DEFAULT 'general',
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `related_id` int(11) DEFAULT NULL,
  `related_type` varchar(50) DEFAULT NULL,
  `status` enum('new','read','replied','closed') DEFAULT 'new',
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `assigned_to` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(300) DEFAULT NULL,
  `service_interest` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` enum('new','read','replied','archived') DEFAULT 'new',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `service_interest`, `message`, `ip_address`, `user_agent`, `status`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 'Aime', 'aime@gmail.com', '0789375245', 'love', 'Building Permits', 'I love this', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'new', NULL, '2025-09-24 02:31:09', '2025-09-24 02:31:09');

-- --------------------------------------------------------

--
-- Table structure for table `core_values`
--

CREATE TABLE `core_values` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `core_values`
--

INSERT INTO `core_values` (`id`, `title`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Reliability', 'We deliver on our promises and commitments, ensuring dependable service that clients can count on every time.', 'fas fa-clipboard-check', 1, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(2, 'Professionalism', 'We maintain the highest standards of conduct, expertise, and service delivery in all our client interactions.', 'fas fa-user-tie', 2, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(3, 'Expert Solutions', 'We leverage our deep industry knowledge to provide specialized solutions tailored to each client\'s unique needs.', 'fas fa-lightbulb', 3, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `expertise_areas`
--

CREATE TABLE `expertise_areas` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expertise_areas`
--

INSERT INTO `expertise_areas` (`id`, `title`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Land Surveying & Mapping', 'Comprehensive land surveying services including first registration, parcel merging, subdivision, and boundary correction using advanced technologies.', 'fas fa-map', 1, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(2, 'Building & Construction', 'Professional support for construction projects including building permits acquisition, road consultancy, and house plan development.', 'fas fa-building', 2, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(3, 'Environmental Consultancy', 'Expert environmental impact assessment (EIA) services to ensure sustainability and compliance with environmental regulations.', 'fas fa-leaf', 3, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(4, 'Technical Training', 'Specialized training in surveying equipment, software, data analysis, GIS, remote sensing, and artificial intelligence for geospatial applications.', 'fas fa-laptop-code', 4, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(5, 'AI & Machine Learning', 'Application of artificial intelligence and machine learning techniques for advanced data analysis and pattern recognition in spatial data.', 'fas fa-robot', 5, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40'),
(6, 'Research Support', 'Comprehensive research support services for academic and commercial projects related to geospatial analysis and environmental studies.', 'fas fa-search', 6, 1, '2025-09-24 01:50:40', '2025-09-24 01:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `file_uploads`
--

CREATE TABLE `file_uploads` (
  `id` int(11) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `file_type` enum('image','document','video','other') NOT NULL,
  `related_table` varchar(50) DEFAULT NULL,
  `related_id` int(11) DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_uploads`
--

INSERT INTO `file_uploads` (`id`, `original_name`, `file_name`, `file_path`, `file_size`, `mime_type`, `file_type`, `related_table`, `related_id`, `uploaded_by`, `alt_text`, `caption`, `created_at`) VALUES
(1, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758632747_4a80431f9f65faea.png', 'images/services/1758632747_4a80431f9f65faea.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 13:05:48'),
(2, 'screencapture-billing-augmentcode-c-pay-cs-live-a1hlIRx5SWGhov7D6BuyVAnnsqDg1tCEmujjCp3aLyD8bqQyaGEikP5HSb-2025-09-13-18_26_16.png', '1758632768_e036c40e905fc40b.png', 'images/products/1758632768_e036c40e905fc40b.png', 74982, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 13:06:08'),
(3, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758633131_ec942ce699215acc.png', 'images/services/1758633131_ec942ce699215acc.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 13:12:11'),
(4, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758633135_18644843bf00b39c.png', 'images/research/1758633135_18644843bf00b39c.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 13:12:15'),
(5, 'screencapture-billing-augmentcode-c-pay-cs-live-a1hlIRx5SWGhov7D6BuyVAnnsqDg1tCEmujjCp3aLyD8bqQyaGEikP5HSb-2025-09-13-18_26_16.png', '1758633138_f77f6dae5fbb681f.png', 'images/products/1758633138_f77f6dae5fbb681f.png', 74982, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 13:12:18'),
(6, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758633307_1f6faae24849af5d.png', 'images/services/1758633307_1f6faae24849af5d.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 13:15:08'),
(7, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758650160_c282f8beb9a0bbdb.png', 'images/services/1758650160_c282f8beb9a0bbdb.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 17:56:00'),
(8, 'screencapture-hpanel-hostinger-websites-fsamcompanyltd-com-databases-php-my-admin-2025-09-19-11_49_21.png', '1758650216_5cf013a7abb709d3.png', 'images/products/1758650216_5cf013a7abb709d3.png', 139273, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 17:56:57'),
(9, 'screencapture-file-C-Users-HOSA-LTD-AppData-Local-Microsoft-Windows-INetCache-IE-7CZ29GIL-SUSURUKA-full-page-1-pdf-2025-09-17-18_11_54.png', '1758650259_adcb9a088e8ab277.png', 'images/training/1758650259_adcb9a088e8ab277.png', 340231, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 17:57:40'),
(10, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758650306_c58d739ad050636e.png', 'images/research/1758650306_c58d739ad050636e.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 17:58:28'),
(11, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758650373_6670c7fe149f3709.png', 'images/research/1758650373_6670c7fe149f3709.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 17:59:33'),
(12, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758650974_f614603114e158be.png', 'images/research/1758650974_f614603114e158be.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 18:09:34'),
(15, 'screencapture-localhost-ikimina-GRMS-public-2025-09-21-12_25_38.png', '1758654528_ab92e5df5583d538.png', 'images/training/1758654528_ab92e5df5583d538.png', 1341549, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 19:08:49'),
(16, 'screencapture-file-C-Users-HOSA-LTD-AppData-Local-Microsoft-Windows-INetCache-IE-7CZ29GIL-SUSURUKA-full-page-1-pdf-2025-09-17-18_11_54.png', '1758654588_5472d3ca4481c77f.png', 'images/products/1758654588_5472d3ca4481c77f.png', 340231, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 19:09:48'),
(17, 'screencapture-file-C-Users-HOSA-LTD-AppData-Local-Microsoft-Windows-INetCache-IE-7CZ29GIL-SUSURUKA-full-page-1-pdf-2025-09-17-18_11_54.png', '1758654628_0981bd77a5246180.png', 'images/services/1758654628_0981bd77a5246180.png', 340231, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 19:10:28'),
(18, 'screencapture-file-C-Users-HOSA-LTD-AppData-Local-Microsoft-Windows-INetCache-IE-7CZ29GIL-SUSURUKA-full-page-1-pdf-2025-09-17-18_11_54.png', '1758654663_2d0340c111f03e46.png', 'images/services/1758654663_2d0340c111f03e46.png', 340231, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 19:11:04'),
(19, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758654697_ee9a1291ed3307cf.png', 'images/products/1758654697_ee9a1291ed3307cf.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 19:11:37'),
(22, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758654977_89794555517ad776.png', 'images/test/1758654977_89794555517ad776.png', 41541, 'image/png', 'image', NULL, NULL, NULL, NULL, NULL, '2025-09-23 19:16:18'),
(23, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758655013_b9a8a9433b2b3898.png', 'images/products/1758655013_b9a8a9433b2b3898.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 19:16:53'),
(24, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758655038_c9e958ebd9ff5d4c.png', 'images/products/1758655038_c9e958ebd9ff5d4c.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 19:17:19'),
(25, 'screencapture-app-augmentcode-account-subscription-2025-09-14-14_31_47.png', '1758655074_83e6d2ce7a540d24.png', 'images/products/1758655074_83e6d2ce7a540d24.png', 41541, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 19:17:54'),
(26, '13.09.2025_01.58.18_REC.png', '1758670202_3b6b5142f06e6c5d.png', 'images/services/1758670202_3b6b5142f06e6c5d.png', 52437, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 23:30:02'),
(27, '13.09.2025_01.59.36_REC.png', '1758670228_62cda2226fb4a37e.png', 'images/products/1758670228_62cda2226fb4a37e.png', 54556, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 23:30:28'),
(28, '13.09.2025_02.02.47_REC.png', '1758670268_b60ea2da094a9898.png', 'images/training/1758670268_b60ea2da094a9898.png', 149548, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 23:31:08'),
(29, '23.09.2025_02.02.44_REC.png', '1758670373_f2bdb2067096ce19.png', 'images/research/1758670373_f2bdb2067096ce19.png', 120895, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 23:32:53'),
(30, '23.09.2025_02.02.44_REC.png', '1758670436_0a5013c14912c355.png', 'images/research/1758670436_0a5013c14912c355.png', 120895, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 23:33:56'),
(31, '13.09.2025_01.58.18_REC.png', '1758670655_6e828f86d4e85b05.png', 'images/training/1758670655_6e828f86d4e85b05.png', 52437, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 23:37:35'),
(32, '23.09.2025_02.02.44_REC.png', '1758670704_b87c6ac41bae9158.png', 'images/services/1758670704_b87c6ac41bae9158.png', 120895, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 23:38:24'),
(33, '23.09.2025_02.02.44_REC.png', '1758670959_d5ad7767d31ef463.png', 'images/services/1758670959_d5ad7767d31ef463.png', 120895, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 23:42:39'),
(34, '23.09.2025_02.04.45_REC.png', '1758671608_613ed1b5fbe4b1d7.png', 'images/products/1758671608_613ed1b5fbe4b1d7.png', 137325, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-23 23:53:28'),
(35, '13.09.2025_02.01.18_REC.png', '1758676789_734da63357c1a758.png', 'images/services/1758676789_734da63357c1a758.png', 16524, 'image/png', 'image', NULL, NULL, 1, NULL, NULL, '2025-09-24 01:19:49');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `type` enum('general','service','product','training','research','support') DEFAULT 'general',
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','responded','closed') DEFAULT 'new',
  `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
  `assigned_to` int(11) DEFAULT NULL,
  `response` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `type`, `name`, `email`, `phone`, `subject`, `message`, `status`, `priority`, `assigned_to`, `response`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 'general', 'John Doe', 'john@example.com', NULL, 'General Inquiry', 'This is a test inquiry message.', 'new', 'normal', NULL, NULL, NULL, NULL, '2025-09-22 23:51:20', '2025-09-22 23:51:20'),
(2, 'general', 'John Doe', 'john@example.com', NULL, 'General Inquiry', 'This is a test inquiry message.', 'new', 'normal', NULL, NULL, NULL, NULL, '2025-09-23 02:16:27', '2025-09-23 02:16:27'),
(3, 'general', 'John Doe', 'john@example.com', NULL, 'General Inquiry', 'This is a test inquiry message.', 'new', 'normal', NULL, NULL, NULL, NULL, '2025-09-23 07:49:00', '2025-09-23 07:49:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `category` enum('equipment','software','training','bundle') NOT NULL,
  `manufacturer` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `warranty` varchar(100) DEFAULT NULL,
  `support` varchar(200) DEFAULT NULL,
  `specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`)),
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `icon` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `status` enum('active','inactive','draft') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `slug`, `description`, `short_description`, `category`, `manufacturer`, `model`, `price`, `warranty`, `support`, `specifications`, `features`, `icon`, `image`, `gallery`, `status`, `sort_order`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 'Total Station Professional', 'total-station', 'High-precision surveying instrument for accurate measurements in the field. Ideal for construction layout, topographic surveys, and boundary determinations.', 'High-precision surveying instrument for accurate field measurements.', 'equipment', 'Trimble', 'SX10', NULL, '2 Years', '24/7 Technical Support', '[]', NULL, 'fa-broadcast-tower', NULL, NULL, 'active', 1, '', '', '2025-09-22 17:48:06', '2025-09-23 01:37:43'),
(3, 'Digital Level', 'digital-level', 'Advanced digital level for precise elevation measurements and construction layout work.', 'Advanced digital level for precise elevation measurements.', 'equipment', 'Topcon', 'DL-503', NULL, '2 Years', 'Email & Phone Support', NULL, NULL, 'fa-ruler-horizontal', NULL, NULL, 'active', 3, NULL, NULL, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(4, 'ArcGIS Software', 'arcgis', 'Comprehensive GIS software for spatial data analysis and mapping. Analyze patterns, visualize data, and create professional maps.', 'Comprehensive GIS software for spatial analysis and mapping.', 'software', 'Esri', 'ArcGIS Pro', NULL, 'Annual Subscription', 'Forum & Knowledge Base', NULL, NULL, 'fa-map', NULL, NULL, 'active', 4, NULL, NULL, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(5, 'Python for Data Analysis', 'python-analysis', 'Python packages for geospatial data processing and analysis. Custom solutions for data manipulation, visualization, and modeling.', 'Python packages for geospatial data processing and analysis.', 'software', 'Banner Fair', 'Custom Package', NULL, '6 Months Support', 'Email Support', NULL, NULL, 'fab fa-python', NULL, NULL, 'active', 5, NULL, NULL, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(6, 'AI for GIS Analysis', 'ai-gis', 'Training materials for implementing AI in geospatial data analysis. Learn machine learning and deep learning techniques for geospatial applications.', 'AI training materials for geospatial data analysis.', 'training', 'Banner Fair', 'Version 2025', NULL, '1 Year Updates', 'Forum Support', NULL, NULL, 'fa-brain', NULL, NULL, 'active', 6, NULL, NULL, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(7, 'Remote Sensing Analysis', 'remote-sensing', 'Comprehensive training on satellite imagery and remote sensing techniques. Master image classification, change detection, and environmental monitoring.', 'Comprehensive remote sensing training with satellite imagery techniques.', 'training', 'Banner Fair', 'Version 2025', NULL, '1 Year Updates', 'Email Support', NULL, NULL, 'fa-satellite', NULL, NULL, 'active', 7, NULL, NULL, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(8, 'Surveyor Starter Bundle', 'starter-bundle', 'Complete package for beginners in land surveying. Includes equipment, software, and training to get you started.', 'Complete starter package for land surveying beginners.', 'bundle', 'Banner Fair', 'Starter Bundle 2025', NULL, '2 Years', 'Priority Support', NULL, NULL, 'fa-layer-group', NULL, NULL, 'active', 8, NULL, NULL, '2025-09-22 17:48:06', '2025-09-23 01:38:00'),
(9, 'GIS Professional Bundle', 'gis-bundle', 'Advanced GIS software and training for professionals. Comprehensive solution for spatial data analysis and mapping.', 'Advanced GIS software and training bundle for professionals.', 'bundle', 'Banner Fair', 'Pro Bundle 2025', NULL, '3 Years', 'Premium Support', NULL, NULL, 'fa-cubes', NULL, NULL, 'active', 9, NULL, NULL, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(11, 'stht gr r ewgr', 'stht-gr-r-ewgr', 'INSERT INTO `company_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `category`, `description`, `is_public`, `created_at`, `updated_at`) VALUES\r\n(1, &#039;company_name&#039;, &#039;Fair Surveying &amp; Mapping Ltd&#039;, &#039;text&#039;, &#039;general&#039;, &#039;Company name&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(2, &#039;company_tagline&#039;, &#039;Reliable | Professional | Expert Solutions&#039;, &#039;text&#039;, &#039;general&#039;, &#039;Company tagline&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(3, &#039;company_email&#039;, &#039;fsamcompanyltd@gmail.com&#039;, &#039;text&#039;, &#039;contact&#039;, &#039;Primary email address&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(4, &#039;company_phone&#039;, &#039;+250 788 331 697&#039;, &#039;text&#039;, &#039;contact&#039;, &#039;Primary phone number&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(5, &#039;company_address&#039;, &#039;Kigali, Rwanda&#039;, &#039;text&#039;, &#039;contact&#039;, &#039;Company address&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(6, &#039;surveyor_name&#039;, &#039;HATANGIMANA Fulgence&#039;, &#039;text&#039;, &#039;general&#039;, &#039;Licensed surveyor name&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(7, &#039;surveyor_code&#039;, &#039;LS00280&#039;, &#039;text&#039;, &#039;general&#039;, &#039;Surveyor license code&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(8, &#039;establishment_year&#039;, &#039;2023&#039;, &#039;text&#039;, &#039;general&#039;, &#039;Year company was established&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(9, &#039;facebook_url&#039;, &#039;#&#039;, &#039;text&#039;, &#039;social&#039;, &#039;Facebook page URL&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(10, &#039;twitter_url&#039;, &#039;#&#039;, &#039;text&#039;, &#039;social&#039;, &#039;Twitter profile URL&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(11, &#039;linkedin_url&#039;, &#039;#&#039;, &#039;text&#039;, &#039;social&#039;, &#039;LinkedIn profile URL&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(12, &#039;instagram_url&#039;, &#039;#&#039;, &#039;text&#039;, &#039;social&#039;, &#039;Instagram profile URL&#039;, 1, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(13, &#039;site_title&#039;, &#039;Fair Surveying &amp; Mapping Ltd&#039;, &#039;text&#039;, &#039;seo&#039;, &#039;Default site title&#039;, 0, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(14, &#039;site_description&#039;, &#039;Professional surveying and mapping services in Rwanda&#039;, &#039;textarea&#039;, &#039;seo&#039;, &#039;Default site description&#039;, 0, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(15, &#039;admin_email_notifications&#039;, &#039;1&#039;, &#039;boolean&#039;, &#039;admin&#039;, &#039;Enable email notifications for admin&#039;, 0, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;),\r\n(16, &#039;max_file_upload_size&#039;, &#039;10485760&#039;, &#039;number&#039;, &#039;system&#039;, &#039;Maximum file upload size in bytes (10MB)&#039;, 0, &#039;2025-09-22 17:48:06&#039;, &#039;2025-09-22 17:48:06&#039;);', '', 'equipment', '', '', NULL, '', '', '[]', NULL, '', 'images/products/1758671608_613ed1b5fbe4b1d7.png', NULL, 'active', 0, '', '', '2025-09-23 23:53:28', '2025-09-23 23:53:28');

-- --------------------------------------------------------

--
-- Table structure for table `product_inquiries`
--

CREATE TABLE `product_inquiries` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `inquiry_type` enum('quote','demo','support','general') DEFAULT 'general',
  `status` enum('new','contacted','quoted','closed') DEFAULT 'new',
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `assigned_to` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `research_projects`
--

CREATE TABLE `research_projects` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `abstract` text NOT NULL,
  `description` text NOT NULL,
  `methodology` text DEFAULT NULL,
  `key_findings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`key_findings`)),
  `authors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`authors`)),
  `publication_date` date DEFAULT NULL,
  `journal` varchar(200) DEFAULT NULL,
  `doi` varchar(100) DEFAULT NULL,
  `keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`keywords`)),
  `category` varchar(100) DEFAULT NULL,
  `status` enum('ongoing','completed','published','draft') DEFAULT 'ongoing',
  `featured` tinyint(1) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `research_projects`
--

INSERT INTO `research_projects` (`id`, `title`, `slug`, `abstract`, `description`, `methodology`, `key_findings`, `authors`, `publication_date`, `journal`, `doi`, `keywords`, `category`, `status`, `featured`, `image`, `gallery`, `documents`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(2, 'Precision Agriculture Mapping', 'precision-agriculture-mapping', 'Development of precision agriculture techniques using drone-based mapping and sensor technology for optimized crop management.', 'This project focuses on developing advanced mapping techniques for precision agriculture using unmanned aerial vehicles and multi-spectral sensors.', 'Field data collection using drones, sensor calibration, data processing and analysis, and validation with ground truth measurements.', '[\"20% increase in crop yield\", \"30% reduction in fertilizer use\", \"Improved soil health monitoring\", \"Cost savings for farmers\"]', '[\"Dr. Agricultural Expert\", \"HATANGIMANA Fulgence\"]', '2024-11-20', NULL, NULL, NULL, 'Agriculture', 'published', 1, NULL, NULL, NULL, NULL, NULL, '2025-09-22 17:48:06', '2025-09-23 12:32:04'),
(3, 'dvfda vvdsbg', 'dvfda-vvdsbg', 'The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&amp;#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\r\n\r\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &amp;quot;less than&amp;quot; or &amp;quot;greater than&amp;quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.', 'The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&amp;#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\r\n\r\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &amp;quot;less than&amp;quot; or &amp;quot;greater than&amp;quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.', 'The first option the tool allows you to adjust is the number of random words to be generated. You can choose as many or as few as you&amp;#039;d like. You also have the option of choosing words that only begin with a certain letter, only end with a certain letter or only begin and end with certain letters. If you leave these blank, the randomized words that appear will be from the complete list.\r\n\r\nAnother option you have is choosing the number of syllables of the words or the word length of the randomized words. There are also ways to further refine these by choosing the &amp;quot;less than&amp;quot; or &amp;quot;greater than&amp;quot; options for both syllables and word length. Again, if you leave the space blank, the complete list of randomized words will be used.', '[\"[&quot;[]&quot;]\"]', '[\"[&quot;[]&quot;]\"]', '2025-09-23', '', '', '[]', 'surveying', 'published', 1, 'images/research/1758650974_f614603114e158be.png', '[]', '[]', '', '', '2025-09-23 18:09:34', '2025-09-24 00:04:04'),
(4, 'fgtbgdg fdv f', 'fgtbgdg-fdv-f', 'Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank', 'Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank', 'Aimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, FrankAimecol, Jack, Frank', '[\"[&quot;Aimecol, Jack, Frank&quot;]\"]', '[\"[&quot;Aimecol&quot;\",\"&quot;Jack&quot;\",\"&quot;Frank&quot;]\"]', '2025-09-24', 'Aimecol, Jack, Frank', '', '[]', 'surveying', 'published', 0, 'images/research/1758670436_0a5013c14912c355.png', '[]', '[]', '', '', '2025-09-23 23:33:56', '2025-09-24 00:04:37');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `languages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`languages`)),
  `price` decimal(10,2) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `image` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `process_steps` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of process steps',
  `benefits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of service benefits',
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of service requirements',
  `faqs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of frequently asked questions',
  `status` enum('active','inactive','draft') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `slug`, `description`, `short_description`, `icon`, `languages`, `price`, `duration`, `features`, `image`, `gallery`, `process_steps`, `benefits`, `requirements`, `faqs`, `status`, `sort_order`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 'First Registration', 'first-registration', 'We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property. Our team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.', 'Comprehensive first-time registration services for land parcels with accurate measurements and legal documentation.', 'fas fa-file-signature', '[\"English\",\"Kinyarwanda\"]', NULL, '', '[\"Accurate land measurements\",\"Legal documentation\",\"State-of-the-art equipment\",\"Authority compliance\"]', NULL, NULL, NULL, NULL, NULL, NULL, 'active', 1, '', '', '2025-09-22 17:48:06', '2025-09-22 23:21:24'),
(2, 'Land Subdivision', 'land-subdivision', 'Professional land subdivision services to help you divide your property according to legal requirements and zoning regulations. We handle all technical aspects including surveying, mapping, and documentation preparation for subdivision approval.', 'Professional land subdivision services with legal compliance and technical documentation.', 'fas fa-map-marked-alt', '[\"English\", \"Kinyarwanda\"]', NULL, NULL, '[\"Legal compliance\", \"Zoning regulations\", \"Technical surveying\", \"Approval documentation\"]', NULL, NULL, NULL, NULL, NULL, NULL, 'active', 2, NULL, NULL, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(4, 'Building Permits', 'building-permits', 'Complete building permit application services including document preparation, technical drawings, and liaison with local authorities to ensure smooth approval process for your construction project.', 'Complete building permit services with document preparation and authority liaison.', 'fas fa-clipboard-check', '[\"English\", \"Kinyarwanda\"]', NULL, NULL, '[\"Document preparation\", \"Technical drawings\", \"Authority liaison\", \"Smooth approval process\"]', NULL, NULL, NULL, NULL, NULL, NULL, 'active', 4, NULL, NULL, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(5, 'Environmental Assessment', 'environmental-assessment', 'Comprehensive environmental impact assessments for development projects, ensuring compliance with environmental regulations and sustainable development practices.', 'Environmental impact assessments ensuring regulatory compliance and sustainability.', 'fas fa-leaf', '[\"English\",\"Kinyarwanda\"]', NULL, '', '[\"Impact assessment\",\"Regulatory compliance\",\"Sustainability focus\",\"Development support\"]', 'images/services/1758676789_734da63357c1a758.png', NULL, '[{\"step\":1,\"title\":\"Initial Consultation\",\"description\":\"We meet with you to understand your needs and gather initial information.\",\"icon\":\"fas fa-check-circle\"},{\"step\":2,\"title\":\"Assessment\",\"description\":\"Our professional team conducts a thorough assessment of your requirements.\",\"icon\":\"fas fa-check-circle\"},{\"step\":3,\"title\":\"Implementation\",\"description\":\"We implement the service according to professional standards and best practices.\",\"icon\":\"fas fa-check-circle\"},{\"step\":4,\"title\":\"Completion\",\"description\":\"We deliver the completed service and provide all necessary documentation.\",\"icon\":\"fas fa-check-circle\"}]', '[{\"title\":\"Professional Service\",\"description\":\"Our experienced team provides professional and reliable service that meets industry standards and client expectations.\",\"icon\":\"fas fa-check-circle\"},{\"title\":\"Timely Delivery\",\"description\":\"We complete projects within agreed timeframes, ensuring your schedule and deadlines are met efficiently.\",\"icon\":\"fas fa-check-circle\"},{\"title\":\"Quality Assurance\",\"description\":\"All our work is backed by quality assurance processes and professional guarantees for your peace of mind.\",\"icon\":\"fas fa-check-circle\"}]', '[{\"category\":\"General Requirements\",\"items\":[\"Valid identification documents\",\"Relevant project or property documentation\"]},{\"category\":\"special Requirements\",\"items\":[\"Contact information and availability for consultation\",\"Clear project specifications and requirements\"]}]', '[{\"question\":\"How long does the service take?\",\"answer\":\"The duration varies depending on the complexity and scope of the project. We provide estimated timelines during the initial consultation and keep you updated throughout the process.\"},{\"question\":\"What is the cost of this service?\",\"answer\":\"Costs vary based on project requirements and complexity. We provide detailed quotes after the initial consultation, with transparent pricing and no hidden charges.\"},{\"question\":\"Do you provide ongoing support?\",\"answer\":\"Yes, we provide comprehensive support throughout the entire process and offer post-completion assistance to ensure your satisfaction with our services.\"}]', 'active', 5, '', '', '2025-09-22 17:48:06', '2025-09-24 01:19:49'),
(7, 'sdvfdv dfD GRF', 'sdvfdv-dfd-grf', 'Our team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.', 'We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.', '', '[\"English\"]', NULL, '', '[{\"title\":\"dsgbfsfb\",\"description\":\"dvsbfsbbsb\",\"icon\":\"fas fa-check-circle\"}]', 'images/services/1758670959_d5ad7767d31ef463.png', NULL, '[]', '[]', '[]', '[]', 'active', 0, '', '', '2025-09-23 23:42:39', '2025-09-24 01:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','number','boolean','json','file') DEFAULT 'text',
  `category` varchar(50) DEFAULT 'general',
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `category`, `description`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'company_name', 'Fair Surveying &amp;amp;amp; Mapping Ltd', 'text', 'general', NULL, 0, '2025-09-22 23:51:20', '2025-09-23 06:49:02'),
(2, 'company_email', 'fsamcompanyltd@gmail.com', 'text', 'general', NULL, 0, '2025-09-22 23:51:20', '2025-09-22 23:51:20'),
(3, 'company_phone', '+250 788 331 697', 'text', 'general', NULL, 0, '2025-09-22 23:51:20', '2025-09-22 23:51:20'),
(4, 'site_title', 'FSMC Admin System', 'text', 'general', NULL, 0, '2025-09-22 23:51:20', '2025-09-22 23:51:20'),
(6, 'company_tagline', 'Reliable | Professional | Expert Solutions', 'text', 'general', NULL, 0, '2025-09-23 01:35:43', '2025-09-23 01:35:43'),
(7, 'company_description', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:43', '2025-09-23 06:49:02'),
(8, 'company_address', 'Kigali, Rwanda', 'text', 'general', NULL, 0, '2025-09-23 01:35:43', '2025-09-23 01:35:43'),
(9, 'company_city', 'Kigali', 'text', 'general', NULL, 0, '2025-09-23 01:35:43', '2025-09-23 01:35:43'),
(10, 'company_country', 'Rwanda', 'text', 'general', NULL, 0, '2025-09-23 01:35:43', '2025-09-23 01:35:43'),
(11, 'company_postal_code', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:43', '2025-09-23 01:35:43'),
(14, 'company_website', 'https://fsamcompanyltd.com', 'text', 'general', NULL, 0, '2025-09-23 01:35:43', '2025-09-23 06:48:55'),
(15, 'company_founded', '2025', 'text', 'general', NULL, 0, '2025-09-23 01:35:43', '2025-09-23 06:48:55'),
(16, 'company_employees', '5', 'text', 'general', NULL, 0, '2025-09-23 01:35:43', '2025-09-23 06:48:55'),
(17, 'company_license', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:43', '2025-09-23 01:35:43'),
(18, 'contact_phone_primary', '+250 789375245', 'text', 'general', NULL, 0, '2025-09-23 01:35:47', '2025-09-24 00:16:27'),
(19, 'contact_phone_secondary', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:47', '2025-09-23 01:35:47'),
(20, 'contact_email_general', 'fsamcompanyltd@gmail.com', 'text', 'general', NULL, 0, '2025-09-23 01:35:47', '2025-09-23 01:35:47'),
(21, 'contact_email_support', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:47', '2025-09-23 01:35:47'),
(22, 'contact_email_sales', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:47', '2025-09-23 01:35:47'),
(23, 'contact_hours_weekday', 'Monday - Friday: 8:00 AM - 6:00 PM', 'text', 'general', NULL, 0, '2025-09-23 01:35:47', '2025-09-23 01:35:47'),
(24, 'contact_hours_weekend', 'Saturday: 8:00 AM - 2:00 PM', 'text', 'general', NULL, 0, '2025-09-23 01:35:47', '2025-09-23 01:35:47'),
(25, 'contact_emergency', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:47', '2025-09-23 01:35:47'),
(26, 'social_facebook', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:53', '2025-09-23 01:35:53'),
(27, 'social_twitter', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:53', '2025-09-23 01:35:53'),
(28, 'social_linkedin', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:53', '2025-09-23 01:35:53'),
(29, 'social_instagram', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:53', '2025-09-23 01:35:53'),
(30, 'social_youtube', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:53', '2025-09-23 01:35:53'),
(31, 'social_whatsapp', '', 'text', 'general', NULL, 0, '2025-09-23 01:35:53', '2025-09-23 01:35:53');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `role` varchar(200) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `social_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON social media links',
  `specializations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of specializations',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `role`, `bio`, `image`, `email`, `phone`, `social_links`, `specializations`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'HATANGIMANA Fulgence', 'Founder & Lead Surveyor', 'With over a decade of experience in land surveying and mapping, Fulgence brings unparalleled expertise to every project. Certified with surveyor code LS00280, he has led numerous high-profile projects across Rwanda.', NULL, '', '', '{\"linkedin\":\"http:\\/\\/localhost\\/ikimina\\/FSMC\\/pages\\/about.php\",\"twitter\":\"http:\\/\\/localhost\\/ikimina\\/FSMC\\/pages\\/about.php\",\"facebook\":\"http:\\/\\/localhost\\/ikimina\\/FSMC\\/pages\\/about.php\"}', '[\"Land Surveying\",\" Project Management\",\" GIS Analysis\"]', 1, 1, '2025-09-24 01:50:39', '2025-09-24 04:13:54'),
(2, 'MUVUNYI Germain', 'Environmental Specialist', 'Germain leads our environmental consultancy services, bringing specialized knowledge in environmental impact assessments and sustainable development practices.', NULL, NULL, NULL, '{\"linkedin\": \"#\", \"twitter\": \"#\", \"facebook\": \"#\"}', '[\"Environmental Impact Assessment\", \"Sustainability\", \"Regulatory Compliance\"]', 2, 1, '2025-09-24 01:50:39', '2025-09-24 01:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `training_enrollments`
--

CREATE TABLE `training_enrollments` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `experience_level` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `special_requirements` text DEFAULT NULL,
  `payment_status` enum('pending','partial','paid','refunded') DEFAULT 'pending',
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `enrollment_status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `certificate_issued` tinyint(1) DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_programs`
--

CREATE TABLE `training_programs` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(100) NOT NULL,
  `max_students` int(11) DEFAULT 20,
  `language` varchar(100) DEFAULT 'English',
  `level` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `curriculum` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`curriculum`)),
  `requirements` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `instructor` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive','draft') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `training_programs`
--

INSERT INTO `training_programs` (`id`, `title`, `slug`, `description`, `short_description`, `category`, `price`, `duration`, `max_students`, `language`, `level`, `features`, `curriculum`, `requirements`, `image`, `gallery`, `instructor`, `status`, `sort_order`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 'Total Station Operation & Survey Data Processing', 'total-station-training', 'Learn to operate total stations and process survey data efficiently for various applications including construction layout, topographic surveys, and boundary determinations.', 'Comprehensive total station operation and data processing training.', 'surveying', 250000.00, '5 days', 12, 'English, French', 'beginner', '[\"Hands-on practice\", \"Field exercises\", \"Data processing\", \"Certificate\", \"Job placement assistance\"]', NULL, NULL, NULL, NULL, NULL, 'active', 1, NULL, NULL, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(2, 'GIS Fundamentals with ArcGIS', 'gis-fundamentals', 'Master the fundamentals of Geographic Information Systems using ArcGIS software. Learn spatial analysis, data management, and map creation.', 'Master GIS fundamentals with ArcGIS software and spatial analysis.', 'gis', 300000.00, '1 week', 15, 'English', 'beginner', '[\"Software training\", \"Spatial analysis\", \"Map creation\", \"Certificate\", \"Project work\"]', NULL, NULL, NULL, NULL, NULL, 'active', 2, NULL, NULL, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(3, 'Python for Data Analysis', 'python-data-analysis', 'Learn data manipulation, visualization, and modeling using Python programming language and its powerful libraries for geospatial applications.', 'Python programming for data analysis and geospatial applications.', 'programming', 200000.00, '3 days', 10, 'English', 'intermediate', '[\"Programming basics\", \"Data visualization\", \"Geospatial libraries\", \"Certificate\", \"Code examples\"]', NULL, NULL, NULL, NULL, NULL, 'active', 3, NULL, NULL, '2025-09-22 17:48:06', '2025-09-22 17:48:06'),
(5, 'fgfdgaaga', 'fgfdgaaga', 'fveefvda', '', 'surveying', 0.00, '', 20, 'English', 'beginner', '[]', '[]', 'Array', '', '[]', 'fvvdfvdf', 'active', 0, '', '', '2025-09-23 07:49:55', '2025-09-23 12:47:01'),
(6, 'fvsfbgf', 'fvsfbgf', 'bgbgbsf', '', 'surveying', 0.00, '', 20, 'English', 'beginner', '[]', '[]', 'Array', '', '[]', 'fvvdfvdf', 'active', 0, '', '', '2025-09-23 12:40:34', '2025-09-23 12:45:40'),
(7, 'scn jdv dvs', 'scn-jdv-dvs', 'dfvnfmjgnjv damjkA system error occurred. Please contact the administrator.', '', 'surveying', 0.00, '', 20, 'English', 'beginner', '[]', '[]', 'Array', 'images/training/1758654528_ab92e5df5583d538.png', '[]', '', 'active', 0, '', '', '2025-09-23 19:08:49', '2025-09-23 19:08:49'),
(8, 'dvdfbgfbbg fdfdb gab', 'dvdfbgfbbg-fdfdb-gab-', 'We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\r\n\r\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.', '', 'cartography', 0.00, '', 20, 'English', 'beginner', '[]', '[]', 'Array', 'images/training/1758670268_b60ea2da094a9898.png', '[]', '', 'active', 0, '', '', '2025-09-23 23:31:08', '2025-09-23 23:31:08'),
(9, 'vbd egrl nkj gk', 'vbd-egrl-nkj-gk', 'We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\r\n\r\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\r\n\r\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\r\n\r\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\r\n\r\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\r\n\r\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.', 'We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.\r\n\r\nOur team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.', 'surveying', 0.00, '5days', 20, 'English', 'beginner', '[]', '[]', 'Array', 'images/training/1758670655_6e828f86d4e85b05.png', '[]', 'Aimecol', 'active', 0, 'gg g rg er gr', 'We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property.', '2025-09-23 23:37:35', '2025-09-23 23:37:35');

-- --------------------------------------------------------

--
-- Table structure for table `training_schedules`
--

CREATE TABLE `training_schedules` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `max_participants` int(11) DEFAULT 20,
  `current_participants` int(11) DEFAULT 0,
  `status` enum('scheduled','ongoing','completed','cancelled') DEFAULT 'scheduled',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_content`
--
ALTER TABLE `about_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `section_key` (`section_key`);

--
-- Indexes for table `about_faqs`
--
ALTER TABLE `about_faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activity_logs_user` (`user_id`),
  ADD KEY `idx_activity_logs_table` (`table_name`,`record_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `business_hours`
--
ALTER TABLE `business_hours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `day_key` (`day_key`);

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `company_stats`
--
ALTER TABLE `company_stats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stat_key` (`stat_key`);

--
-- Indexes for table `company_timeline`
--
ALTER TABLE `company_timeline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `info_key` (`info_key`);

--
-- Indexes for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `idx_inquiries_status` (`status`),
  ADD KEY `idx_inquiries_type` (`type`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `core_values`
--
ALTER TABLE `core_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expertise_areas`
--
ALTER TABLE `expertise_areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_uploads`
--
ALTER TABLE `file_uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `idx_file_uploads_related` (`related_table`,`related_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_products_category` (`category`),
  ADD KEY `idx_products_status` (`status`);

--
-- Indexes for table `product_inquiries`
--
ALTER TABLE `product_inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `research_projects`
--
ALTER TABLE `research_projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_research_status` (`status`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_services_status` (`status`),
  ADD KEY `idx_services_slug` (`slug`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training_enrollments`
--
ALTER TABLE `training_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `idx_enrollments_status` (`enrollment_status`);

--
-- Indexes for table `training_programs`
--
ALTER TABLE `training_programs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_training_status` (`status`);

--
-- Indexes for table `training_schedules`
--
ALTER TABLE `training_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_content`
--
ALTER TABLE `about_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `about_faqs`
--
ALTER TABLE `about_faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `business_hours`
--
ALTER TABLE `business_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `company_stats`
--
ALTER TABLE `company_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `company_timeline`
--
ALTER TABLE `company_timeline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `core_values`
--
ALTER TABLE `core_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expertise_areas`
--
ALTER TABLE `expertise_areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `file_uploads`
--
ALTER TABLE `file_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_inquiries`
--
ALTER TABLE `product_inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `research_projects`
--
ALTER TABLE `research_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `training_enrollments`
--
ALTER TABLE `training_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_programs`
--
ALTER TABLE `training_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `training_schedules`
--
ALTER TABLE `training_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  ADD CONSTRAINT `contact_inquiries_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `file_uploads`
--
ALTER TABLE `file_uploads`
  ADD CONSTRAINT `file_uploads_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD CONSTRAINT `inquiries_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_inquiries`
--
ALTER TABLE `product_inquiries`
  ADD CONSTRAINT `product_inquiries_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_inquiries_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `training_enrollments`
--
ALTER TABLE `training_enrollments`
  ADD CONSTRAINT `training_enrollments_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `training_schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `training_schedules`
--
ALTER TABLE `training_schedules`
  ADD CONSTRAINT `training_schedules_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `training_programs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
