-- Database tables for About and Contact pages management
-- Execute this SQL to create comprehensive content management tables

-- About Page Content Table
CREATE TABLE `about_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_key` (`section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Team Members Table
CREATE TABLE `team_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Company Statistics Table
CREATE TABLE `company_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stat_key` varchar(100) NOT NULL COMMENT 'Unique identifier for statistic',
  `label` varchar(200) NOT NULL,
  `value` int(11) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `suffix` varchar(20) DEFAULT '+',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `stat_key` (`stat_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Company Timeline Table
CREATE TABLE `company_timeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Core Values Table
CREATE TABLE `core_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Expertise Areas Table
CREATE TABLE `expertise_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- About FAQs Table
CREATE TABLE `about_faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact Information Table
CREATE TABLE `contact_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `info_key` varchar(100) NOT NULL COMMENT 'Unique identifier for contact info',
  `title` varchar(200) NOT NULL,
  `value` text NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `link_type` enum('none','tel','mailto','url') DEFAULT 'none',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `info_key` (`info_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Business Hours Table
CREATE TABLE `business_hours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day_key` varchar(20) NOT NULL,
  `day_label` varchar(50) NOT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `is_closed` tinyint(1) DEFAULT 0,
  `custom_text` varchar(100) DEFAULT NULL COMMENT 'For custom text like "Closed" or "By Appointment"',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `day_key` (`day_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact Form Messages Table
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data for About page
INSERT INTO `about_content` (`section_key`, `title`, `subtitle`, `content`, `sort_order`, `meta_data`) VALUES
('hero', 'About Fair Surveying & Mapping', 'Excellence in Land Surveying, Mapping and Environmental Solutions', NULL, 1, NULL),
('story', 'Our Story', NULL, 'Fair Surveying and Mapping Ltd was established with a vision to deliver exceptional land surveying, mapping, and environmental solutions across Rwanda and beyond. Founded by HATANGIMANA Fulgence, a certified surveyor with extensive experience in the field, our company has grown to become a trusted name in the industry.\n\nWith our deep expertise in geospatial technologies and commitment to precision, we provide comprehensive surveying services that help clients make informed decisions about their land and construction projects. Our team comprises highly skilled professionals who combine traditional surveying methods with cutting-edge technology to deliver accurate, reliable results.\n\nWhether you\'re looking to register your land for the first time, merge or subdivide parcels, correct boundary issues, or require environmental impact assessments, our team is ready to assist with professional expertise and personalized service.', 2, '{"mission": "Our mission is to provide precise, reliable, and innovative surveying and mapping solutions that empower our clients to make informed decisions about their land and environmental assets."}'),
('expertise_intro', 'Our Areas of Expertise', NULL, 'At Fair Surveying and Mapping Ltd, we offer a comprehensive range of professional services tailored to meet diverse needs in land management, construction support, and environmental consultancy.', 3, NULL),
('values_intro', 'Our Core Values', NULL, 'The principles that guide our work and define our approach to every project:', 4, NULL),
('team_intro', 'Meet Our Leadership', NULL, 'Our team is led by experienced professionals with extensive knowledge in surveying, mapping, and environmental sciences. Together, we work collaboratively to deliver exceptional results for our clients.', 5, NULL),
('timeline_intro', 'Our Journey', NULL, NULL, 6, NULL),
('faq_intro', 'Frequently Asked Questions', NULL, NULL, 7, NULL),
('cta', 'Ready to Work With Us?', NULL, 'Whether you need land surveying, mapping services, construction support, or environmental consultancy, our team is ready to deliver expert solutions tailored to your specific needs.', 8, NULL);

-- Insert sample team members
INSERT INTO `team_members` (`name`, `role`, `bio`, `image`, `social_links`, `specializations`, `sort_order`) VALUES
('HATANGIMANA Fulgence', 'Founder & Lead Surveyor', 'With over a decade of experience in land surveying and mapping, Fulgence brings unparalleled expertise to every project. Certified with surveyor code LS00280, he has led numerous high-profile projects across Rwanda.', 'mine.jpg', '{"linkedin": "#", "twitter": "#", "facebook": "#"}', '["Land Surveying", "Project Management", "GIS Analysis"]', 1),
('MUVUNYI Germain', 'Environmental Specialist', 'Germain leads our environmental consultancy services, bringing specialized knowledge in environmental impact assessments and sustainable development practices.', NULL, '{"linkedin": "#", "twitter": "#", "facebook": "#"}', '["Environmental Impact Assessment", "Sustainability", "Regulatory Compliance"]', 2);

-- Insert sample company statistics
INSERT INTO `company_stats` (`stat_key`, `label`, `value`, `icon`, `suffix`, `sort_order`) VALUES
('years_experience', 'Years of Experience', 12, 'fas fa-calendar-alt', '+', 1),
('projects_completed', 'Projects Completed', 850, 'fas fa-project-diagram', '+', 2),
('satisfied_clients', 'Satisfied Clients', 500, 'fas fa-users', '+', 3),
('team_members', 'Team Members', 15, 'fas fa-user-tie', '', 4);

-- Insert sample timeline
INSERT INTO `company_timeline` (`year`, `title`, `description`, `icon`, `sort_order`) VALUES
('2019', 'Company Founded', 'Fair Surveying and Mapping was established by HATANGIMANA Fulgence to provide professional surveying services in Rwanda.', 'fas fa-flag', 1),
('2015', 'Service Expansion', 'Expanded our services to include environmental consultancy and building support services to meet growing client demands.', 'fas fa-expand-arrows-alt', 2),
('2018', 'Technical Training Program', 'Launched our technical training program to share expertise in surveying equipment, software, and geospatial analysis techniques.', 'fas fa-graduation-cap', 3),
('2020', 'AI Integration', 'Pioneered the integration of artificial intelligence and machine learning into our geospatial analysis services.', 'fas fa-robot', 4),
('2023', 'Research Support Division', 'Established a dedicated research support division to assist academic and commercial research projects in geospatial sciences.', 'fas fa-microscope', 5);

-- Insert sample core values
INSERT INTO `core_values` (`title`, `description`, `icon`, `sort_order`) VALUES
('Reliability', 'We deliver on our promises and commitments, ensuring dependable service that clients can count on every time.', 'fas fa-clipboard-check', 1),
('Professionalism', 'We maintain the highest standards of conduct, expertise, and service delivery in all our client interactions.', 'fas fa-user-tie', 2),
('Expert Solutions', 'We leverage our deep industry knowledge to provide specialized solutions tailored to each client\'s unique needs.', 'fas fa-lightbulb', 3);

-- Insert sample expertise areas
INSERT INTO `expertise_areas` (`title`, `description`, `icon`, `sort_order`) VALUES
('Land Surveying & Mapping', 'Comprehensive land surveying services including first registration, parcel merging, subdivision, and boundary correction using advanced technologies.', 'fas fa-map', 1),
('Building & Construction', 'Professional support for construction projects including building permits acquisition, road consultancy, and house plan development.', 'fas fa-building', 2),
('Environmental Consultancy', 'Expert environmental impact assessment (EIA) services to ensure sustainability and compliance with environmental regulations.', 'fas fa-leaf', 3),
('Technical Training', 'Specialized training in surveying equipment, software, data analysis, GIS, remote sensing, and artificial intelligence for geospatial applications.', 'fas fa-laptop-code', 4),
('AI & Machine Learning', 'Application of artificial intelligence and machine learning techniques for advanced data analysis and pattern recognition in spatial data.', 'fas fa-robot', 5),
('Research Support', 'Comprehensive research support services for academic and commercial projects related to geospatial analysis and environmental studies.', 'fas fa-search', 6);

-- Insert sample About FAQs
INSERT INTO `about_faqs` (`question`, `answer`, `sort_order`) VALUES
('What is the process for first-time land registration?', 'The process for first-time land registration (Kubarura Umurima Bwa Mbere) involves several steps including boundary survey, documentation preparation, and submission to the land registry. Our team handles the entire process, ensuring compliance with all legal requirements and regulations.', 1),
('How long does a typical land survey take?', 'The duration of a land survey depends on various factors including the size of the land, complexity of the terrain, and the specific survey requirements. Typically, a standard residential property survey can be completed within 1-3 days, while larger or more complex projects may take longer.', 2),
('What technologies do you use for surveying?', 'We utilize advanced surveying technologies including Total Station equipment, GPS/GNSS systems, drone mapping, and specialized software like AutoCAD and ArcGIS. Our combination of traditional and modern methods ensures high accuracy and efficiency in all our surveying projects.', 3),
('Do you provide training for organizations?', 'Yes, we offer customized training programs for organizations in surveying equipment operation, software utilization, data analysis techniques, GIS applications, and AI/ML integration. Our training can be tailored to various skill levels and specific organizational needs.', 4),
('What is involved in an Environmental Impact Assessment?', 'An Environmental Impact Assessment (EIA) involves comprehensive evaluation of potential environmental effects of a proposed project or development. Our EIA services include baseline studies, impact prediction, mitigation planning, and reporting in compliance with national and international standards.', 5);

-- Insert sample contact information
INSERT INTO `contact_info` (`info_key`, `title`, `value`, `icon`, `link_type`, `sort_order`) VALUES
('location', 'Our Location', 'Kigali, Rwanda', 'fas fa-map-marker-alt', 'none', 1),
('phone', 'Phone Number', '0788331697', 'fas fa-phone-alt', 'tel', 2),
('email', 'Email Address', 'fsamcompanyltd@gmail.com', 'fas fa-envelope', 'mailto', 3),
('professional', 'Professional Details', 'HATANGIMANA Fulgence\nSurveyor code: LS00280', 'fas fa-user-tie', 'none', 4);

-- Insert sample business hours
INSERT INTO `business_hours` (`day_key`, `day_label`, `opening_time`, `closing_time`, `sort_order`) VALUES
('monday_friday', 'Monday - Friday', '08:00:00', '17:00:00', 1),
('saturday', 'Saturday', '09:00:00', '13:00:00', 2),
('sunday', 'Sunday', NULL, NULL, 3);

-- Update Sunday to be closed
UPDATE `business_hours` SET `is_closed` = 1, `custom_text` = 'Closed' WHERE `day_key` = 'sunday';
