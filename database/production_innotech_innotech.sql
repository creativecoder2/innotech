-- INNOTECH MEDICAL PVT LTD - PRODUCTION DATABASE EXPORT
-- Target Database: innotech_innotech
-- Generated: 2026-09-03 20:37:23

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


-- --------------------------------------------------------
-- Table structure for `blog_comments`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `blog_comments`;
CREATE TABLE `blog_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_comments_blog_id_foreign` (`blog_id`),
  CONSTRAINT `blog_comments_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `blog_comments`
INSERT INTO `blog_comments` (`id`, `blog_id`, `name`, `email`, `phone`, `website`, `comment`, `status`, `is_approved`, `created_at`, `updated_at`) VALUES ('1', '1', 'Dr. Arsalan Khan', 'arsalan.khan@hospital.org', '+92 300 1234567', 'https://hospital.org', 'This telemetry research is very comprehensive and directly addresses ventilator safety.', 'approved', '1', '2026-09-03 06:58:36', '2026-09-03 06:58:36');
INSERT INTO `blog_comments` (`id`, `blog_id`, `name`, `email`, `phone`, `website`, `comment`, `status`, `is_approved`, `created_at`, `updated_at`) VALUES ('2', '2', 'Muhammad Usama hameed', 'creativecoder93@gmail.com', '347324234', NULL, 'test', 'approved', '1', '2026-09-03 07:02:57', '2026-09-03 07:05:06');
INSERT INTO `blog_comments` (`id`, `blog_id`, `name`, `email`, `phone`, `website`, `comment`, `status`, `is_approved`, `created_at`, `updated_at`) VALUES ('3', '2', 'Muhammad Usama hameed', 'creativecoder93@gmail.com', '347324234', NULL, 'asdasds', 'approved', '1', '2026-09-03 07:04:19', '2026-09-03 07:04:58');
INSERT INTO `blog_comments` (`id`, `blog_id`, `name`, `email`, `phone`, `website`, `comment`, `status`, `is_approved`, `created_at`, `updated_at`) VALUES ('4', '1', 'Engr. Bilal Ahmed', 'bilal.biomed@example.com', '+92 312 9876543', 'https://biomedtech.pk', 'Excellent article! Very informative for clinical engineers and biomedical staff.', 'approved', '1', '2026-09-03 07:05:26', '2026-09-03 07:31:44');
INSERT INTO `blog_comments` (`id`, `blog_id`, `name`, `email`, `phone`, `website`, `comment`, `status`, `is_approved`, `created_at`, `updated_at`) VALUES ('5', '2', 'Muhammad Usama hameed', 'creativecoder93@gmail.com', '347324234', NULL, 'asdasd', 'approved', '1', '2026-09-03 07:05:41', '2026-09-03 07:31:43');


-- --------------------------------------------------------
-- Table structure for `blogs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Medical News',
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Innotech Team',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slider_images` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quote` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quote_author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approach_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approach_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approach_points` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_image_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_image_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `views` bigint(20) unsigned NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogs_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `blogs`
INSERT INTO `blogs` (`id`, `title`, `slug`, `category`, `tags`, `author`, `image`, `video_url`, `slider_images`, `summary`, `content`, `quote`, `quote_author`, `approach_title`, `approach_text`, `approach_points`, `meta_image_1`, `meta_image_2`, `status`, `views`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES ('1', 'Transforming Critical Care: Modern Advancements in ICU Monitoring', 'transforming-critical-care-modern-advancements-in-icu-monitoring', 'Medical Technology', 'Critical Care, ICU Systems, AI Monitoring, Telemetry, Patient Safety', 'Dr. S. Akhtar', 'assets/img/blog/blog-thumb-01.jpg', 'https://www.youtube.com/watch?v=OMqWRlxo1oQ', NULL, 'Explore how real-time telemetry and AI-driven vital tracking are redefining patient outcomes in intensive care units.', 'Modern intensive care units rely on split-second clinical decisions. Integrated monitoring systems allow physicians and nursing teams to anticipate physiological degradation earlier than ever before...', 'Continuous biomedical telemetry combined with AI alarm mitigation reduces adverse clinical events in intensive care units by upwards of 42%.', 'Dr. Savannah Nguyen, Senior Critical Care Specialist', 'Our Clinical Verification & Sensor Calibration', 'Every piece of ICU patient monitoring equipment provided by Innotech undergoes strict multiparametric verification and electrical safety testing prior to hospital handover.', 'Multi-parameter telemetry stress testing\r\nContinuous arrhythmia algorithm benchmarking\r\nIntegrated central station nurse alerting\r\nZero-downtime medical battery backup validation', 'assets/img/blog/blog-details-meta-03.jpg', 'assets/img/blog/blog-details-meta-01.jpg', 'published', '1537', '1', '2026-08-28', '2026-08-31 19:06:08', '2026-09-03 19:56:44');
INSERT INTO `blogs` (`id`, `title`, `slug`, `category`, `tags`, `author`, `image`, `video_url`, `slider_images`, `summary`, `content`, `quote`, `quote_author`, `approach_title`, `approach_text`, `approach_points`, `meta_image_1`, `meta_image_2`, `status`, `views`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES ('2', 'Essential Best Practices for Clinical Laboratory Calibration', 'essential-best-practices-for-clinical-laboratory-calibration', 'Quality Assurance', 'Calibration, Laboratory, Quality Assurance, ISO 13485, Diagnostic', 'Innotech Bio-Engineering Team', 'assets/img/blog/blog-thumb-02.jpg', NULL, NULL, 'Key maintenance protocols to ensure diagnostic analyzers adhere to rigorous international laboratory standards.', 'Routine calibration of optical sensors and fluidic systems in automated analyzers is essential to eliminating diagnostic drift and maintaining clinical compliance...', 'Standardized biomedical calibration is not just a regulatory compliance requirement; it is the fundamental foundation of reliable medical diagnostics.', 'Cameron Williamson, Chief Biomedical Engineer', 'Diagnostic Accuracy & Traceable Reference Standards', 'Innotech utilizes traceable primary calibration standards certified by international metrology laboratories to inspect spectrophotometers, centrifuges, and hematology analyzers.', 'Traceable optical density calibration\nThermal gradient verification for PCR equipment\nPreventative transducer alignment\nComprehensive biomedical certificate generation', 'assets/img/blog/blog-details-meta-02.jpg', NULL, 'published', '994', '1', '2026-08-24', '2026-08-31 19:06:08', '2026-09-03 07:09:48');
INSERT INTO `blogs` (`id`, `title`, `slug`, `category`, `tags`, `author`, `image`, `video_url`, `slider_images`, `summary`, `content`, `quote`, `quote_author`, `approach_title`, `approach_text`, `approach_points`, `meta_image_1`, `meta_image_2`, `status`, `views`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES ('3', 'Next-Gen Modular Operating Theatres: Design & Safety Protocols', 'next-gen-modular-operating-theatres-design-safety-protocols', 'Hospital Infrastructure', 'Operating Theatre, Surgery, Cleanroom, Modular OT, Infection Control', 'Engr. M. Bilal', 'assets/img/blog/blog-thumb-03.jpg', NULL, '[\"assets\\/img\\/blog\\/blog-in-01.jpg\",\"assets\\/img\\/blog\\/blog-in-02.jpg\",\"assets\\/img\\/blog\\/blog-in-03.jpg\"]', 'A comprehensive guide to positive pressure ventilation, antimicrobial wall surfaces, and integrated surgical pendents.', 'Modular OT setups drastically minimize surgical site infection risks while providing ergonomic flexibility for surgical teams during complex interventions...', 'Laminar airflow engineering and seamless antibacterial modular paneling create sterile surgical environments that virtually eliminate post-operative infection risks.', 'Innotech Healthcare Infrastructure Division', 'Cleanroom Engineering & HEPA Filtration Protocols', 'Our turnkey modular operating theatres incorporate positive pressure differentials and ultra-low penetration air filtration to exceed Class 100 sterile cleanroom standards.', 'Laminar air flow canopy installation\nAnti-microbial hermetic sliding door mechanisms\nIntegrated surgical lighting and pendant gas delivery\nAutomated climate and humidity sensory telemetry', 'assets/img/blog/blog-details-meta-03.jpg', 'assets/img/blog/blog-details-meta-02.jpg', 'published', '1241', '1', '2026-08-19', '2026-08-31 19:06:08', '2026-09-03 06:53:30');
INSERT INTO `blogs` (`id`, `title`, `slug`, `category`, `tags`, `author`, `image`, `video_url`, `slider_images`, `summary`, `content`, `quote`, `quote_author`, `approach_title`, `approach_text`, `approach_points`, `meta_image_1`, `meta_image_2`, `status`, `views`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES ('4', 'Advances in Digital Radiology & High-Resolution Imaging Systems', 'advances-in-digital-radiology-high-resolution-imaging-systems', 'Radiology & Imaging', 'Radiology, X-Ray, Imaging, Diagnostics, PACS', 'Dr. Marcus Vance, Chief Radiologist', 'assets/img/blog/blog-in-02.jpg', NULL, NULL, 'Digital detector arrays and automated dose reduction algorithms have transformed diagnostic radiology, ensuring ultra-clear musculoskeletal and thoracic imaging at minimum exposure levels.', 'Modern diagnostic radiology is experiencing an exponential leap forward with direct-conversion digital radiography (DR) detectors and automated AI-assisted exposure controls. Digital radiography eliminates traditional analog chemical processing and reduces examination times from minutes to seconds.\n\nInnotech partners with leading global manufacturers to supply cutting-edge digital radiography systems that prioritize low-dose patient safety, rapid detector readout, and seamless PACS / DICOM hospital network integration. Our biomedical engineering team conducts regular calibration of X-ray tube heads, collimator alignment, and detector response curves to guarantee diagnostic clarity and regulatory compliance.', 'Digital radiography minimizes radiation exposure while delivering sub-millimeter anatomical detail essential for acute trauma and cardiopulmonary evaluations.', 'Innotech Imaging Solutions Division', 'Radiation Safety & Detector Calibration Protocols', 'Our radiology engineers perform standardized radiation dose assessments and spatial resolution benchmarking during every installation.', 'Collimator beam alignment and light field congruency testing\nDetector detective quantum efficiency (DQE) evaluation\nLead shielding integrity and scattered radiation surveys\nDICOM archive protocol validation and workstation calibration', NULL, NULL, 'published', '742', '1', '2026-08-31', '2026-09-03 07:11:39', '2026-09-03 07:11:39');
INSERT INTO `blogs` (`id`, `title`, `slug`, `category`, `tags`, `author`, `image`, `video_url`, `slider_images`, `summary`, `content`, `quote`, `quote_author`, `approach_title`, `approach_text`, `approach_points`, `meta_image_1`, `meta_image_2`, `status`, `views`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES ('5', 'Hospital Sterilization & Autoclave Protocol Compliance in 2026', 'hospital-sterilization-autoclave-protocol-compliance-in-2026', 'Infection Control', 'Sterilization, CSSD, Autoclave, Infection Control, Hospital Hygiene', 'Dr. Elena Rostova, Infection Control Specialist', 'assets/img/blog/blog-in-03.jpg', 'https://www.youtube.com/watch?v=OMqWRlxo1oQ', NULL, 'Central Sterile Services Departments (CSSD) rely on validated steam sterilization cycles, vacuum leak tests, and biological spore indicators to safeguard surgical instruments.', 'Sterilization in modern healthcare institutions requires absolute precision and rigorous validation. Steam autoclaving remains the gold standard for heat-tolerant surgical instruments, but modern CSSD operations demand digital cycle recording, automated vacuum pulses, and microbicidal verification.\n\nInnotech supplies high-capacity hospital autoclaves and steam sterilizers featuring programmable touchscreens, independent temperature sensors, and integrated water purification modules. Our technicians carry out routine Bow-Dick leak tests and temperature-pressure data logging to guarantee sterility assurance levels (SAL 10^-6).', 'A sterile surgical tray is non-negotiable. Digital telemetry in modern autoclaves guarantees every instrument pack achieves validated germicidal parameters.', 'Dr. Elena Rostova', 'CSSD Validation & Temperature Data Logging', 'Every autoclave maintained by Innotech is tested with multipoint wireless data loggers across the chamber to verify heat distribution uniformity.', 'Bowie-Dick air removal and steam penetration testing\nBiological spore vial incubation and growth monitoring\nChamber pressure relief valve calibration and hydrostatic certification\nDigital thermal cycle printout archiving and audit trails', NULL, NULL, 'published', '615', '1', '2026-08-28', '2026-09-03 07:11:39', '2026-09-03 07:11:39');
INSERT INTO `blogs` (`id`, `title`, `slug`, `category`, `tags`, `author`, `image`, `video_url`, `slider_images`, `summary`, `content`, `quote`, `quote_author`, `approach_title`, `approach_text`, `approach_points`, `meta_image_1`, `meta_image_2`, `status`, `views`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES ('6', 'AI-Powered Patient Diagnostic Monitors in Modern Emergency Rooms', 'ai-powered-patient-diagnostic-monitors-in-modern-emergency-rooms', 'Medical Technology', 'Emergency, Patient Monitoring, AI Alarms, Critical Care', 'Dr. Savannah Nguyen', 'assets/img/blog/blog-in-01.jpg', NULL, NULL, 'Intelligent alarm filtering and multi-lead ECG processing in emergency triage workstations help clinicians detect early signs of sepsis and hemodynamic instability.', 'Emergency departments operate in fast-paced environments where clinicians are bombarded by medical device alarms. Up to 85% of standard monitor alarms are clinically non-actionable, leading to dangerous alarm fatigue among nursing staff.\n\nNext-generation patient monitors incorporate predictive machine learning algorithms that cross-analyze pulse oximetry, capnography, and invasive arterial pressure before sounding high-priority audible alarms. This contextual awareness ensures true clinical emergencies receive immediate medical intervention.', 'Smart algorithmic alarm filtration helps ER nurses focus on genuine hemodynamic deterioration without constant nuisance noise.', 'Dr. Savannah Nguyen, Critical Care Specialist', NULL, NULL, NULL, NULL, NULL, 'published', '890', '1', '2026-08-25', '2026-09-03 07:11:39', '2026-09-03 07:11:39');
INSERT INTO `blogs` (`id`, `title`, `slug`, `category`, `tags`, `author`, `image`, `video_url`, `slider_images`, `summary`, `content`, `quote`, `quote_author`, `approach_title`, `approach_text`, `approach_points`, `meta_image_1`, `meta_image_2`, `status`, `views`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES ('7', 'Preventative Maintenance Checklist for Clinical Laboratory Centrifuges', 'preventative-maintenance-checklist-clinical-laboratory-centrifuges', 'Laboratory Equipment', 'Laboratory, Centrifuge, Preventative Maintenance, Calibration', 'Cameron Williamson', 'assets/img/blog/blog-in-02.jpg', NULL, NULL, 'Centrifuge rotor imbalances, motor brush wear, and speed tachometer drift can compromise blood component separation and pose severe mechanical hazards in clinical pathology labs.', 'Clinical centrifuges are workhorses of medical pathology laboratories, processing hundreds of blood, urine, and cytology specimens daily. Due to high rotational velocities exceeding 10,000 RPM, regular preventative maintenance is vital to prevent rotor catastrophic failure and ensure sample integrity.\n\nInnotech provides comprehensive centrifuge calibration services including optical tachometer RPM verification, temperature chamber calibration for refrigerated units, dynamic rotor balancing, and electronic safety interlock testing.', 'Routine optical tachometer validation guarantees that blood specimens undergo the exact G-force required for pure serum and platelet plasma separation.', 'Cameron Williamson, Lead Biomedical Engineer', NULL, NULL, NULL, NULL, NULL, 'published', '432', '1', '2026-08-22', '2026-09-03 07:11:39', '2026-09-03 07:11:39');
INSERT INTO `blogs` (`id`, `title`, `slug`, `category`, `tags`, `author`, `image`, `video_url`, `slider_images`, `summary`, `content`, `quote`, `quote_author`, `approach_title`, `approach_text`, `approach_points`, `meta_image_1`, `meta_image_2`, `status`, `views`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES ('8', 'Surgical Lighting & Ergonomic Ceiling Pendants for Modern Operating Rooms', 'surgical-lighting-ergonomic-ceiling-pendants-for-modern-operating-rooms', 'Operating Theatre', 'Operating Room, Surgical Lights, Surgery, Ergonomics, Hospital Design', 'Innotech Engineering Team', 'assets/img/blog/blog-in-03.jpg', NULL, NULL, 'Shadowless LED surgical light heads and motorized ceiling pendants optimize sterile field illumination, medical gas access, and cable-free operating room floors.', 'Modern surgical suites demand flexible spatial organization. Cable clutter on the floor poses tripping hazards and compromises sterile zones. Dual-arm ceiling-mounted surgical pendants suspend anaesthetic gas lines, electrocautery generators, and laparoscopic video systems directly above the operating table.\n\nCoupled with multi-reflector shadowless LED surgical lamps providing 160,000 Lux illumination and adjustable color temperatures (3500K - 5000K), surgeons maintain optimal tissue differentiation without eye strain or thermal radiation on open cavities.', 'Shadow-diluting optical optics and overhead articulation provide surgeons with clear depth perception and total control of the operative field.', 'Innotech Healthcare Infrastructure Division', NULL, NULL, NULL, NULL, NULL, 'published', '670', '1', '2026-08-19', '2026-09-03 07:11:39', '2026-09-03 07:11:39');


-- --------------------------------------------------------
-- Table structure for `cache`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `cache`
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_about_badge', 's:35:\"Welcome to Innotech Medical Pvt Ltd\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_about_description', 's:483:\"Innotech Medical Pvt Ltd is Established & Reputable distributor of top-quality medical equipment across Pakistan. From state-of-the-art Medical Devices and Surgical Disposable solutions to comprehensive turnkey hospital projects, our commitment goes beyond equipment distribution. We provide end-to-end technical support, regulatory compliance, and seamless integration, ensuring that healthcare providers across the nation have access to reliable, cutting-edge medical technologies.\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_about_experience_years', 's:1:\"7\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_about_heading', 's:47:\"Innovating Healthcare with Advance Technologies\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_about_image', 's:32:\"assets/img/about/about-bg-01.png\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_about_italic_text', 's:130:\"—Empowering hospitals, diagnostic labs, and surgical suites with world-class technology and end-to-end engineering support.\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_about_point_1', 's:29:\"Critical Care & ICU Equipment\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_about_point_2', 's:37:\"Advanced Diagnostic & Lab Instruments\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_about_point_3', 's:42:\"Operating Room & General Medical Solutions\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_about_point_4', 's:36:\"Turnkey Projects & Technical Support\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_appointment_image', 's:35:\"assets/img/banner/appoinment-01.jpg\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_appointment_phone', 's:15:\"+92 331 6699992\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_appointment_subtitle', 's:22:\"24/7 Emergency Service\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_appointment_title', 's:20:\"GET IN TOUCH WITH US\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_badge', 's:35:\"Welcome to Innotech Medical Pvt Ltd\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_btn_link', 's:8:\"/contact\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_btn_text', 's:15:\"Contact with Us\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_btn2_link', 's:6:\"/about\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_btn2_text', 's:8:\"About us\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_description', 's:441:\"Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical, we are dedicated to bridging the gap between world-class medical innovation and Pakistan’s healthcare sector. As a leading provider of advanced biomedical technologies, diagnostic systems, and specialized clinical equipment, we empower healthcare institutions to deliver accurate diagnoses and superior patient care.\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_feature_1', 's:26:\"100% Customer Satisfaction\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_feature_2', 's:29:\"Help and Acess is Our Mission\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_feature_3', 's:31:\"100% Quality Laboratory service\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_image', 's:45:\"uploads/sections/1788207663_529_banner-01.png\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_title', 's:48:\"Innovating Health Care with Advance Technologies\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_video_file', 'N;', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_video_type', 's:3:\"url\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_banner_video_url', 's:43:\"https://www.youtube.com/watch?v=d8w5SICzzxc\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_blog_subtitle', 's:17:\"Waht’s New\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_blog_title', 's:14:\"Blog & Article\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_chat_enabled', 's:1:\"1\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_bottom_btn_link', 's:8:\"/contact\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_bottom_btn_text', 's:10:\"Contact Us\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_bottom_text', 's:33:\"Scientific Research Laboratories:\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_card1_desc', 's:140:\"Delivering FDA, CE, and ISO certified medical equipment from global principal brands, ensuring maximum clinical accuracy and patient safety.\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_card1_title', 's:26:\"Global Standards & Quality\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_card2_desc', 's:137:\"Streamlined procurement and rapid installation, delivering complete healthcare projects with minimal turnaround time and total precision.\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_card2_title', 's:22:\"Swift Turnkey Delivery\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_card3_desc', 's:127:\"Round-the-clock technical coverage and rapid dispatch troubleshooting to eliminate critical equipment downtime in ICUs and OTs.\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_card3_title', 's:22:\"24/7 Emergency Support\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_card4_desc', 's:128:\"Backed by OEM-trained engineers executing precision calibration, complex repairs, and proactive maintenance to global standards.\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_card4_title', 's:28:\"Certified Biomedical Experts\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_subtitle', 's:15:\"Our Specialists\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_choose_title', 's:13:\"Why Choose Us\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_contact_address', 's:16:\"Lahore, Pakistan\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_contact_banner_image', 's:35:\"assets/img/banner/breadcrumb-01.jpg\";', '2103826982');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_contact_banner_subtitle', 's:18:\"Innotech : Contact\";', '2103826982');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_contact_banner_title', 's:10:\"Contact us\";', '2103826982');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_copyright_text', 's:76:\"© Copyright ©2026 - 2027 INNOTECH MEDICAL Pvt Ltd. All Rights Reserved\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_counter_1_number', 's:4:\"1492\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_counter_1_title', 's:27:\"Laboratories in 100+ states\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_counter_2_number', 's:3:\"152\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_counter_2_title', 's:22:\"Laboratory specialists\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_counter_3_number', 's:4:\"1022\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_counter_3_title', 's:26:\"Material collection points\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_counter_4_number', 's:5:\"24332\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_counter_4_title', 's:26:\"Patients diagnosed in 2022\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_cta_btn_text', 's:6:\"Call :\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_cta_phone', 's:15:\"+92 331 6699992\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_cta_title', 's:35:\"Looking for a best lebatory Service\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_facebook_url', 's:20:\"https://facebook.com\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_favicon_path', 's:27:\"assets/img/logo/favicon.png\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_footer_about', 's:117:\"Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical.\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_footer_logo', 's:40:\"uploads/sections/1788288342_708_logo.png\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_gallery_btn_link', 's:8:\"/gallery\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_gallery_btn_text', 's:12:\"Explore More\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_gallery_subtitle', 's:12:\"Work Gallery\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_gallery_title', 's:16:\"INNOTECH Gallery\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_helpdesk_email', 's:23:\"info@innotechmedical.pk\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_helpdesk_phone', 's:15:\"+92 331 6699992\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_instagram_url', 's:21:\"https://instagram.com\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_linkedin_url', 's:20:\"https://linkedin.com\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_logo_path', 's:40:\"uploads/sections/1788288342_708_logo.png\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_office_address', 's:98:\"1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_partners_subtitle', 's:21:\"Global Collaborations\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_partners_title', 's:18:\"Our Trust Partners\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_about_enabled', 's:1:\"1\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_appointment_enabled', 's:1:\"1\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_banner_enabled', 's:1:\"1\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_blog_enabled', 's:1:\"1\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_brand_enabled', 's:1:\"1\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_choose_enabled', 's:1:\"1\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_counter_enabled', 's:1:\"1\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_cta_enabled', 's:1:\"1\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_footer_enabled', 's:1:\"1\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_gallery_enabled', 's:1:\"1\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_services_enabled', 's:1:\"1\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_team_enabled', 's:1:\"1\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_section_testimonial_enabled', 's:1:\"1\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_footer_scripts', 'N;', '2103827822');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_google_analytics', 'N;', '2103827822');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_google_tag_manager', 'N;', '2103827822');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_google_verification', 'N;', '2103827822');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_header_scripts', 'N;', '2103827822');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_meta_description', 's:162:\"Leading provider of hospital medical equipment, clinical laboratory analyzers, ICU monitoring systems, and accredited biomedical calibration services in Pakistan.\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_meta_keywords', 's:164:\"medical equipment, biomedical engineering, ICU monitors, laboratory calibration, hospital supply, surgical instruments, Pakistan healthcare, ISO 13485, CE certified\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_meta_robots', 's:13:\"index, follow\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_meta_tagline', 's:48:\"Innovating Healthcare With Advanced Technologies\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_meta_title', 's:69:\"INNOTECH MEDICAL PVT LTD | Advanced Healthcare & Biomedical Solutions\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_og_image', 's:24:\"assets/img/logo/logo.png\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_schema_specialty', 's:49:\"Biomedical Engineering & Medical Equipment Supply\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_schema_type', 's:15:\"MedicalBusiness\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_title_separator', 's:1:\"|\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_twitter_card', 's:19:\"summary_large_image\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_seo_twitter_handle', 's:16:\"@InnotechMedical\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_services_search_placeholder', 's:25:\"What are you looking for?\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_services_subtitle', 's:12:\"our Services\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_services_title', 's:12:\"Service Area\";', '2103827114');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_sidebar_contact_title', 's:10:\"Contact Us\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_sidebar_gallery_enabled', 's:1:\"0\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_sidebar_logo', 's:40:\"uploads/sections/1788288342_708_logo.png\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_sidebar_mission', 's:73:\"Our mission is to ensure the generation of accurate and precise findings.\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_sidebar_newsletter_enabled', 's:1:\"1\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_sidebar_newsletter_title', 's:10:\"Get Update\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_site_tagline', 's:48:\"Innovating Health Care with Advance Technologies\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_site_title', 's:24:\"INNOTECH MEDICAL PVT LTD\";', '2103826941');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_social_facebook', 'N;', '2103827822');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_social_instagram', 'N;', '2103827822');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_social_linkedin', 'N;', '2103827822');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_social_twitter', 'N;', '2103827822');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_social_youtube', 'N;', '2103827822');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_support_email', 's:20:\"info@innotechmed.com\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_team_subtitle', 's:16:\"Specialists Team\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_team_title', 's:20:\"Meet Our Specialists\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_testimonial_subtitle', 's:11:\"Testimonial\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_testimonial_title', 's:17:\"Customer Feedback\";', '2103827115');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_twitter_url', 's:19:\"https://twitter.com\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_whatsapp_default_message', 's:90:\"Hello Innotech Medical, I would like to inquire about your medical equipment and services.\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_whatsapp_enabled', 's:1:\"1\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_whatsapp_phone', 's:12:\"923316699992\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_working_hours', 's:24:\"Office Hours: 10AM - 6PM\";', '2103826943');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('setting_youtube_url', 's:19:\"https://youtube.com\";', '2103826943');


-- --------------------------------------------------------
-- Table structure for `cache_locks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------
-- Table structure for `chat_conversations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `chat_conversations`;
CREATE TABLE `chat_conversations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `unread_admin` int(11) NOT NULL DEFAULT 1,
  `unread_user` int(11) NOT NULL DEFAULT 0,
  `user_message_count` int(11) NOT NULL DEFAULT 1,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chat_conversations_session_token_unique` (`session_token`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `chat_conversations`
INSERT INTO `chat_conversations` (`id`, `session_token`, `name`, `phone`, `email`, `status`, `unread_admin`, `unread_user`, `user_message_count`, `last_message_at`, `created_at`, `updated_at`) VALUES ('3', 'UE3y3w9IFIREDCM1JjGsCbrqpTnFAWZbtYNS', 'Muhammad Usama hameed', '347324234', 'creativecoder93@gmail.com', 'closed', '0', '2', '1', '2026-09-02 20:55:14', '2026-09-02 20:08:08', '2026-09-02 20:55:14');
INSERT INTO `chat_conversations` (`id`, `session_token`, `name`, `phone`, `email`, `status`, `unread_admin`, `unread_user`, `user_message_count`, `last_message_at`, `created_at`, `updated_at`) VALUES ('4', 'vhu7JGuR8TlqMPDba0k64YUpuozzVtLYiiDh', 'Muhammad Usama hameed', '03472663843', 'creativecoder93@gmail.com', 'closed', '0', '3', '1', '2026-09-02 20:58:26', '2026-09-02 20:08:19', '2026-09-02 20:58:26');
INSERT INTO `chat_conversations` (`id`, `session_token`, `name`, `phone`, `email`, `status`, `unread_admin`, `unread_user`, `user_message_count`, `last_message_at`, `created_at`, `updated_at`) VALUES ('5', 'tZOHfJ6BnNOkbOIsyxOwQJCBDXkkAQ7bVzOJ', 'Muhammad Usama hameed', '03472663843', 'creativecoder93@gmail.com', 'closed', '0', '0', '9', '2026-09-02 20:59:09', '2026-09-02 20:15:56', '2026-09-02 20:59:10');
INSERT INTO `chat_conversations` (`id`, `session_token`, `name`, `phone`, `email`, `status`, `unread_admin`, `unread_user`, `user_message_count`, `last_message_at`, `created_at`, `updated_at`) VALUES ('8', 'avXiL8UMDThJ4GpQrbz3qMmyAEKGaZxXHyx2', 'usama', '03132426056', NULL, 'active', '0', '0', '5', '2026-09-02 21:26:19', '2026-09-02 20:59:55', '2026-09-02 21:26:24');
INSERT INTO `chat_conversations` (`id`, `session_token`, `name`, `phone`, `email`, `status`, `unread_admin`, `unread_user`, `user_message_count`, `last_message_at`, `created_at`, `updated_at`) VALUES ('9', 'nBLHITtancbEB2uQdu0AdfeN3hQBta4GRmUY', 'Muhammad Usama hameed', '03472663843', 'creativecoder93@gmail.com', 'closed', '0', '0', '3', '2026-09-02 21:27:20', '2026-09-02 21:26:53', '2026-09-02 21:27:23');
INSERT INTO `chat_conversations` (`id`, `session_token`, `name`, `phone`, `email`, `status`, `unread_admin`, `unread_user`, `user_message_count`, `last_message_at`, `created_at`, `updated_at`) VALUES ('10', '59WA76pXy9AB3HOXhxU7zdqvcl0XKlazZISb', 'Muhammad Usama hameed', '03472663843', 'creativecoder93@gmail.com', 'active', '2', '0', '5', '2026-09-03 08:30:59', '2026-09-03 08:22:30', '2026-09-03 08:30:59');


-- --------------------------------------------------------
-- Table structure for `chat_messages`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint(20) unsigned NOT NULL,
  `sender_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_conversation_id_foreign` (`conversation_id`),
  CONSTRAINT `chat_messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `chat_messages`
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('10', '3', 'user', 'text', 'test', NULL, '0', '2026-09-02 20:08:08', '2026-09-02 20:08:08');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('11', '3', 'bot', 'text', 'Hello! Welcome to INNOTECH MEDICAL PVT LTD. How can our biomedical engineering team assist you today?', NULL, '1', '2026-09-02 20:08:08', '2026-09-02 20:08:08');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('12', '4', 'user', 'text', 'tst', NULL, '0', '2026-09-02 20:08:19', '2026-09-02 20:08:19');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('13', '4', 'bot', 'text', 'Hello! Welcome to INNOTECH MEDICAL PVT LTD. How can our biomedical engineering team assist you today?', NULL, '1', '2026-09-02 20:08:19', '2026-09-02 20:08:19');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('14', '5', 'user', 'text', 'test', NULL, '0', '2026-09-02 20:15:56', '2026-09-02 20:15:56');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('15', '5', 'bot', 'text', 'Hello! Welcome to INNOTECH MEDICAL PVT LTD. How can our biomedical engineering team assist you today?', NULL, '1', '2026-09-02 20:15:56', '2026-09-02 20:15:56');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('16', '5', 'user', 'text', 'i want', NULL, '0', '2026-09-02 20:16:05', '2026-09-02 20:16:05');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('17', '5', 'bot', 'text', 'Thank you for providing the details! An Innotech specialist has been notified and will assist you shortly.', NULL, '1', '2026-09-02 20:16:05', '2026-09-02 20:16:05');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('18', '5', 'user', 'text', 'ok', NULL, '0', '2026-09-02 20:16:16', '2026-09-02 20:16:16');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('19', '5', 'user', 'text', 'thanks', NULL, '0', '2026-09-02 20:16:33', '2026-09-02 20:16:33');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('20', '5', 'user', 'text', 'test', NULL, '0', '2026-09-02 20:17:35', '2026-09-02 20:17:35');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('21', '5', 'user', 'text', 'hyy', NULL, '0', '2026-09-02 20:28:48', '2026-09-02 20:28:48');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('22', '5', 'admin', 'text', 'ok thanks', NULL, '1', '2026-09-02 20:29:00', '2026-09-02 20:29:02');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('23', '5', 'user', 'audio', 'Voice message', 'uploads/chat_audio/voice_1788381260_7J0IjmwK.webm', '0', '2026-09-02 20:34:21', '2026-09-02 20:34:21');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('24', '5', 'user', 'audio', 'Voice message', 'uploads/chat_audio/voice_1788381356_JJbh5kqI.webm', '0', '2026-09-02 20:35:56', '2026-09-02 20:35:56');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('25', '5', 'admin', 'audio', 'Voice message from support', 'uploads/chat_audio/admin_voice_1788381486_H4UGqdgK.webm', '1', '2026-09-02 20:38:06', '2026-09-02 20:38:09');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('26', '5', 'admin', 'text', 'ok', NULL, '1', '2026-09-02 20:40:31', '2026-09-02 20:40:32');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('27', '5', 'admin', 'text', 'test', NULL, '1', '2026-09-02 20:40:58', '2026-09-02 20:41:00');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('28', '5', 'admin', 'text', 'hello world', NULL, '1', '2026-09-02 20:41:44', '2026-09-02 20:41:46');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('29', '4', 'admin', 'text', 'thanks', NULL, '0', '2026-09-02 20:41:57', '2026-09-02 20:41:57');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('30', '4', 'admin', 'text', 'you too', NULL, '0', '2026-09-02 20:44:36', '2026-09-02 20:44:36');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('31', '5', 'admin', 'text', 'test', NULL, '1', '2026-09-02 20:45:13', '2026-09-02 20:45:13');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('32', '5', 'admin', 'audio', 'Voice message from support', 'uploads/chat_audio/admin_voice_1788381994_qAw77aCM.webm', '1', '2026-09-02 20:46:34', '2026-09-02 20:46:35');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('33', '5', 'admin', 'audio', 'Voice message from support', 'uploads/chat_audio/admin_voice_1788382077_oWn05W3K.webm', '1', '2026-09-02 20:47:57', '2026-09-02 20:47:59');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('34', '5', 'admin', 'audio', 'Voice message from support', 'uploads/chat_audio/admin_voice_1788382103_oARyfw1U.webm', '1', '2026-09-02 20:48:23', '2026-09-02 20:48:25');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('35', '5', 'admin', 'audio', 'Voice message from support', 'uploads/chat_audio/admin_voice_1788382120_pkm0JfBy.webm', '1', '2026-09-02 20:48:40', '2026-09-02 20:48:43');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('36', '5', 'user', 'audio', 'Voice message', 'uploads/chat_audio/voice_1788382216_nk9Ko5da.webm', '0', '2026-09-02 20:50:16', '2026-09-02 20:50:16');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('37', '3', 'bot', 'text', 'This conversation has been re-opened by Innotech Support. You may continue your inquiry.', NULL, '1', '2026-09-02 20:54:46', '2026-09-02 20:54:46');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('38', '3', 'bot', 'text', 'This conversation has been closed by Innotech Support. Thank you for connecting with us! If you need any further help or information, feel free to start a new inquiry anytime.', NULL, '1', '2026-09-02 20:55:14', '2026-09-02 20:55:14');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('42', '4', 'bot', 'text', 'This conversation has been closed by Innotech Support. Thank you for connecting with us! If you need any further help or information, feel free to start a new inquiry anytime.', NULL, '1', '2026-09-02 20:58:26', '2026-09-02 20:58:26');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('43', '5', 'bot', 'text', 'This conversation has been closed by Innotech Support. Thank you for connecting with us! If you need any further help or information, feel free to start a new inquiry anytime.', NULL, '1', '2026-09-02 20:59:09', '2026-09-02 20:59:09');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('44', '8', 'user', 'text', 'test', NULL, '0', '2026-09-02 20:59:56', '2026-09-02 20:59:56');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('45', '8', 'bot', 'text', 'Hello! Welcome to INNOTECH MEDICAL PVT LTD. How can our biomedical engineering team assist you today?', NULL, '1', '2026-09-02 20:59:56', '2026-09-02 20:59:56');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('46', '8', 'admin', 'audio', 'Voice message from support', 'uploads/chat_audio/admin_voice_1788382811_RDyvvVpe.webm', '1', '2026-09-02 21:00:11', '2026-09-02 21:00:14');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('47', '8', 'user', 'audio', 'Voice message', 'uploads/chat_audio/voice_1788383726_0M75l926.webm', '0', '2026-09-02 21:15:29', '2026-09-02 21:15:29');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('48', '8', 'bot', 'text', 'Thank you for providing the details! An Innotech specialist has been notified and will assist you shortly.', NULL, '1', '2026-09-02 21:15:29', '2026-09-02 21:15:29');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('49', '8', 'admin', 'text', 'hy', NULL, '1', '2026-09-02 21:16:30', '2026-09-02 21:16:32');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('50', '8', 'admin', 'text', 'test', NULL, '1', '2026-09-02 21:17:11', '2026-09-02 21:17:15');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('51', '8', 'user', 'text', 'safa', NULL, '0', '2026-09-02 21:17:26', '2026-09-02 21:17:26');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('52', '8', 'admin', 'text', 'asfasfaasf', NULL, '1', '2026-09-02 21:17:34', '2026-09-02 21:17:35');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('53', '8', 'admin', 'text', 'fghfghfghfg', NULL, '1', '2026-09-02 21:18:28', '2026-09-02 21:18:29');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('54', '8', 'user', 'text', 'hy', NULL, '0', '2026-09-02 21:25:18', '2026-09-02 21:25:18');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('55', '8', 'admin', 'text', 'hello', NULL, '1', '2026-09-02 21:25:25', '2026-09-02 21:25:26');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('56', '8', 'admin', 'text', 'good', NULL, '1', '2026-09-02 21:25:36', '2026-09-02 21:25:38');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('57', '8', 'admin', 'audio', 'Voice message from support', 'uploads/chat_audio/admin_voice_1788384352_3TBF3GIV.webm', '1', '2026-09-02 21:25:52', '2026-09-02 21:25:54');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('58', '8', 'user', 'audio', 'Voice message', 'uploads/chat_audio/voice_1788384379_7FATO771.webm', '0', '2026-09-02 21:26:19', '2026-09-02 21:26:19');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('59', '9', 'user', 'text', 'sdfghyrhdhd', NULL, '0', '2026-09-02 21:26:53', '2026-09-02 21:26:53');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('60', '9', 'bot', 'text', 'Hello! Welcome to INNOTECH MEDICAL PVT LTD. How can our biomedical engineering team assist you today?', NULL, '1', '2026-09-02 21:26:53', '2026-09-02 21:26:53');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('61', '9', 'user', 'text', 'thanks', NULL, '0', '2026-09-02 21:27:01', '2026-09-02 21:27:01');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('62', '9', 'bot', 'text', 'Thank you for providing the details! An Innotech specialist has been notified and will assist you shortly.', NULL, '1', '2026-09-02 21:27:01', '2026-09-02 21:27:01');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('63', '9', 'user', 'text', 'ok', NULL, '0', '2026-09-02 21:27:08', '2026-09-02 21:27:08');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('64', '9', 'bot', 'text', 'This conversation has been closed by Innotech Support. Thank you for connecting with us! If you need any further help or information, feel free to start a new inquiry anytime.', NULL, '1', '2026-09-02 21:27:20', '2026-09-02 21:27:20');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('65', '10', 'user', 'text', 'asdsad', NULL, '0', '2026-09-03 08:22:30', '2026-09-03 08:22:30');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('66', '10', 'bot', 'text', 'Hello! Welcome to INNOTECH MEDICAL PVT LTD. How can our biomedical engineering team assist you today?', NULL, '1', '2026-09-03 08:22:30', '2026-09-03 08:22:30');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('67', '10', 'user', 'text', 'hy', NULL, '0', '2026-09-03 08:25:30', '2026-09-03 08:25:30');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('68', '10', 'bot', 'text', 'Thank you for providing the details! An Innotech specialist has been notified and will assist you shortly.', NULL, '1', '2026-09-03 08:25:30', '2026-09-03 08:25:30');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('69', '10', 'user', 'text', 'hyy', NULL, '0', '2026-09-03 08:30:07', '2026-09-03 08:30:07');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('70', '10', 'admin', 'text', 'asdas', NULL, '1', '2026-09-03 08:30:26', '2026-09-03 08:30:27');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('71', '10', 'user', 'text', 'sadasdsa', NULL, '0', '2026-09-03 08:30:40', '2026-09-03 08:30:40');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `type`, `message`, `attachment`, `is_read`, `created_at`, `updated_at`) VALUES ('72', '10', 'user', 'text', 'asdsad', NULL, '0', '2026-09-03 08:30:59', '2026-09-03 08:30:59');


-- --------------------------------------------------------
-- Table structure for `companies`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `companies`
INSERT INTO `companies` (`id`, `name`, `slug`, `country`, `logo`, `description`, `website`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Elektro-mag', 'elektro-mag', 'Turkey', 'uploads/companies/1788465026_759_tab-thumb-03.jpg', 'Elektro-mag is a premier Turkish manufacturer specializing in advanced neonatal intensive care equipment, infant radiant warmers, and hospital clinical devices.', NULL, '1', '1', '2026-09-03 19:40:22', '2026-09-03 19:51:25');
INSERT INTO `companies` (`id`, `name`, `slug`, `country`, `logo`, `description`, `website`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'BNG Medical', 'bng-medical', 'Germany', 'assets/img/brand/bng-medical.svg', 'BNG Medical Germany delivers precision 4K laparoscopy towers, endoscopy cameras, and cutting-edge operating theater infrastructure.', 'https://www.bngmedical.de', '2', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');
INSERT INTO `companies` (`id`, `name`, `slug`, `country`, `logo`, `description`, `website`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Meditech Global', 'meditech-global', 'Japan', 'assets/img/brand/meditech.svg', 'Meditech Global Japan produces hospital patient monitoring systems, ICU mechanical ventilators, and high-reliability biomedical devices.', 'https://www.meditech-global.jp', '3', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');
INSERT INTO `companies` (`id`, `name`, `slug`, `country`, `logo`, `description`, `website`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('4', 'Scitek Instruments', 'scitek-instruments', 'United States', 'assets/img/brand/scitek.svg', 'Scitek Instruments USA develops high-resolution mobile C-Arm fluoroscopy units, digital radiography systems, and diagnostic imaging suites.', 'https://www.scitekinstruments.com', '4', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');
INSERT INTO `companies` (`id`, `name`, `slug`, `country`, `logo`, `description`, `website`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('5', 'Lynmou Surgical', 'lynmou-surgical', 'United Kingdom', 'assets/img/brand/lynmou.svg', 'Lynmou Surgical UK is renowned for flexible endoscopic systems, bronchoscopes, gastroscopes, and ergonomic surgical instrumentation.', 'https://www.lynmou-surgical.co.uk', '5', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');
INSERT INTO `companies` (`id`, `name`, `slug`, `country`, `logo`, `description`, `website`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('6', 'Micare Healthcare', 'micare-healthcare', 'South Korea', 'assets/img/brand/micare.svg', 'Micare Healthcare South Korea manufactures microprocessor-controlled electrosurgical diathermy generators and surgical shadowless lighting.', 'https://www.micare-health.kr', '6', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');


-- --------------------------------------------------------
-- Table structure for `failed_jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------
-- Table structure for `gallery_items`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `gallery_items`;
CREATE TABLE `gallery_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `gallery_items`
INSERT INTO `gallery_items` (`id`, `title`, `category`, `image`, `link`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'COVID ANALYSIS', 'Radiologist', 'assets/img/gallery/gal-thum-01.jpg', NULL, '1', '1', '2026-08-31 19:29:08', '2026-08-31 20:13:48');
INSERT INTO `gallery_items` (`id`, `title`, `category`, `image`, `link`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Hiv Analysis & Testing', 'Anaesthetist', 'assets/img/gallery/gal-thum-02.jpg', NULL, '2', '1', '2026-08-31 19:29:08', '2026-08-31 19:29:08');
INSERT INTO `gallery_items` (`id`, `title`, `category`, `image`, `link`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Zyrtec Analysis', 'Gynaecologist', 'assets/img/gallery/gal-thum-03.jpg', NULL, '3', '1', '2026-08-31 19:29:08', '2026-08-31 19:29:08');
INSERT INTO `gallery_items` (`id`, `title`, `category`, `image`, `link`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('4', 'Asthma Apply', 'Genetics', 'assets/img/gallery/gal-thum-04.jpg', NULL, '4', '1', '2026-08-31 19:29:08', '2026-08-31 19:29:08');
INSERT INTO `gallery_items` (`id`, `title`, `category`, `image`, `link`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('5', 'neurological ANALYSIS', 'Forensic', 'assets/img/gallery/gal-thum-05.jpg', NULL, '5', '1', '2026-08-31 19:29:08', '2026-08-31 19:29:08');


-- --------------------------------------------------------
-- Table structure for `inquiries`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `inquiries`;
CREATE TABLE `inquiries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_interested` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `inquiries`
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('1', 'Muhammad Usama hameed', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'test', 'read', '2026-08-31 19:32:13', '2026-08-31 19:34:55');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('2', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to update from sidebar', 'read', '2026-09-01 18:56:36', '2026-09-02 19:05:35');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('3', 'Ali Raza', 'ali.raza@hospital.com', '+92 300 1234567', NULL, 'ICU Ventilator Installation Inquiry', 'We need 5 anesthesia delivery workstations and 10 ventilators for our new surgical wing.', 'read', '2026-09-01 20:19:49', '2026-09-02 20:06:09');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('4', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to newsletter from footer', 'replied', '2026-09-02 20:04:39', '2026-09-02 20:09:20');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('5', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to update from sidebar', 'unread', '2026-09-02 20:14:31', '2026-09-02 20:14:31');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('6', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to newsletter from footer', 'unread', '2026-09-02 20:22:12', '2026-09-02 20:22:12');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('10', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to newsletter from footer', 'unread', '2026-09-02 20:38:51', '2026-09-02 20:38:51');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('11', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to newsletter from footer', 'unread', '2026-09-02 20:38:53', '2026-09-02 20:38:53');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('12', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to newsletter from footer', 'unread', '2026-09-02 20:38:56', '2026-09-02 20:38:56');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('16', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to newsletter from footer', 'unread', '2026-09-02 20:52:13', '2026-09-02 20:52:13');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('17', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to newsletter from footer', 'unread', '2026-09-02 20:52:16', '2026-09-02 20:52:16');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('18', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to newsletter from footer', 'unread', '2026-09-02 20:52:21', '2026-09-02 20:52:21');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('19', 'Muhammad Usama hameed', 'creativecoder93@gmail.com', '347324234', 'Blog: Medical Technology', 'Blog Comment / Inquiry on: Transforming Critical Care: Modern Advancements in ICU Monit...', 'test', 'unread', '2026-09-03 06:55:04', '2026-09-03 06:55:04');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('20', 'Dr. Arsalan Khan', 'arsalan.khan@hospital.org', '+92 300 1234567', 'Blog: Medical Technology', 'New Comment on Blog (Pending Approval): Transforming Critical Care: Modern Advancements in...', 'Comment submitted by Dr. Arsalan Khan (arsalan.khan@hospital.org):\n\n\"This telemetry research is very comprehensive and directly addresses ventilator safety.\"\n\nPlease review and approve/reject in Admin Panel -> Blog Comments.', 'unread', '2026-09-03 06:58:36', '2026-09-03 06:58:36');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('21', 'Muhammad Usama hameed', 'creativecoder93@gmail.com', '347324234', 'Blog: Quality Assurance', 'New Comment on Blog (Pending Approval): Essential Best Practices for Clinical Laboratory C...', 'Comment submitted by Muhammad Usama hameed (creativecoder93@gmail.com):\n\n\"test\"\n\nPlease review and approve/reject in Admin Panel -> Blog Comments.', 'unread', '2026-09-03 07:02:57', '2026-09-03 07:02:57');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('22', 'Muhammad Usama hameed', 'creativecoder93@gmail.com', '347324234', 'Blog: Quality Assurance', 'New Comment on Blog (Pending Approval): Essential Best Practices for Clinical Laboratory C...', 'Comment submitted by Muhammad Usama hameed (creativecoder93@gmail.com):\n\n\"asdasds\"\n\nPlease review and approve/reject in Admin Panel -> Blog Comments.', 'unread', '2026-09-03 07:04:19', '2026-09-03 07:04:19');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('23', 'Engr. Bilal Ahmed', 'bilal.biomed@example.com', '+92 312 9876543', 'Blog: Medical Technology', 'New Comment on Blog (Pending Approval): Transforming Critical Care: Modern Advancements in...', 'Comment submitted by Engr. Bilal Ahmed (bilal.biomed@example.com):\n\n\"Excellent article! Very informative for clinical engineers and biomedical staff.\"\n\nPlease review and approve/reject in Admin Panel -> Blog Comments.', 'unread', '2026-09-03 07:05:26', '2026-09-03 07:05:26');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('24', 'Muhammad Usama hameed', 'creativecoder93@gmail.com', '347324234', 'Blog: Quality Assurance', 'New Comment on Blog (Pending Approval): Essential Best Practices for Clinical Laboratory C...', 'Comment submitted by Muhammad Usama hameed (creativecoder93@gmail.com):\n\n\"asdasd\"\n\nPlease review and approve/reject in Admin Panel -> Blog Comments.', 'replied', '2026-09-03 07:05:41', '2026-09-03 07:13:54');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('27', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to newsletter from footer', 'unread', '2026-09-03 08:21:51', '2026-09-03 08:21:51');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('28', 'Muhammad Usama hameed', 'creativecoder93@gmail.com', '347324234', NULL, 'asdasdsa', 'asdasdas', 'read', '2026-09-03 08:31:25', '2026-09-03 08:31:32');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('29', 'Muhammad Usama hameed', 'creativecoder93@gmail.com', '347324234', 'Demo: Elektro-mag M 308 Infant Radiant Warmer...', 'Request a Demo: Elektro-mag M 308 Infant Radiant Warmer Unit (TFT)', 'Demo / Quote Request for: Elektro-mag M 308 Infant Radiant Warmer Unit (TFT)\nManufacturer: Elektro-mag\nHospital / Clinic: National Medical Complex\n\nUser Notes:\ntest', 'replied', '2026-09-03 19:48:49', '2026-09-03 20:14:43');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('30', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to update from sidebar', 'unread', '2026-09-03 19:55:47', '2026-09-03 19:55:47');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('31', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to update from sidebar', 'unread', '2026-09-03 19:56:06', '2026-09-03 19:56:06');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('32', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to update from sidebar', 'unread', '2026-09-03 19:56:21', '2026-09-03 19:56:21');
INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `service_interested`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES ('33', 'Newsletter Subscriber', 'creativecoder93@gmail.com', NULL, NULL, NULL, 'Subscribed to newsletter from footer', 'replied', '2026-09-03 20:12:23', '2026-09-03 20:12:42');


-- --------------------------------------------------------
-- Table structure for `job_batches`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------
-- Table structure for `jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------
-- Table structure for `migrations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_09_01_000001_create_innotech_tables', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_08_31_192832_create_gallery_and_team_tables', '3');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_08_31_193450_add_is_active_to_tables', '4');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_08_31_193919_create_nav_menus_table', '5');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_09_01_000002_add_detail_fields_to_services_table', '6');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2026_09_01_000003_add_detail_fields_to_team_members_table', '7');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_09_03_000001_create_pages_table', '8');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2026_09_03_000002_create_chat_tables', '9');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2026_09_03_000003_add_type_and_attachment_to_chat_messages_table', '10');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2026_09_03_000004_add_detail_fields_to_blogs_table', '11');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('14', '2026_09_03_000005_create_blog_comments_table', '12');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('15', '2026_09_03_000006_create_visitor_analytics_tables', '13');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('16', '2026_09_04_000001_create_companies_and_products_tables', '14');


-- --------------------------------------------------------
-- Table structure for `nav_menus`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `nav_menus`;
CREATE TABLE `nav_menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `page_route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'custom',
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `show_on_home` tinyint(1) NOT NULL DEFAULT 1,
  `show_on_inner` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `target_blank` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nav_menus_parent_id_foreign` (`parent_id`),
  CONSTRAINT `nav_menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `nav_menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `nav_menus`
INSERT INTO `nav_menus` (`id`, `title`, `url`, `page_route`, `parent_id`, `order`, `show_on_home`, `show_on_inner`, `is_active`, `target_blank`, `created_at`, `updated_at`) VALUES ('1', 'Home', '/', 'home', NULL, '1', '1', '1', '1', '0', '2026-08-31 19:40:45', '2026-08-31 19:48:09');
INSERT INTO `nav_menus` (`id`, `title`, `url`, `page_route`, `parent_id`, `order`, `show_on_home`, `show_on_inner`, `is_active`, `target_blank`, `created_at`, `updated_at`) VALUES ('2', 'About', '/about', 'about', NULL, '2', '1', '1', '1', '0', '2026-08-31 19:40:45', '2026-08-31 19:40:45');
INSERT INTO `nav_menus` (`id`, `title`, `url`, `page_route`, `parent_id`, `order`, `show_on_home`, `show_on_inner`, `is_active`, `target_blank`, `created_at`, `updated_at`) VALUES ('3', 'Products', '/products', 'products', NULL, '3', '1', '1', '1', '0', '2026-08-31 19:40:45', '2026-09-03 19:40:22');
INSERT INTO `nav_menus` (`id`, `title`, `url`, `page_route`, `parent_id`, `order`, `show_on_home`, `show_on_inner`, `is_active`, `target_blank`, `created_at`, `updated_at`) VALUES ('7', 'SPECIALISTS', '/specialists', 'partners', NULL, '4', '0', '1', '1', '0', '2026-08-31 19:40:45', '2026-09-03 19:53:16');
INSERT INTO `nav_menus` (`id`, `title`, `url`, `page_route`, `parent_id`, `order`, `show_on_home`, `show_on_inner`, `is_active`, `target_blank`, `created_at`, `updated_at`) VALUES ('8', 'Contact', '/contact', 'contact', NULL, '8', '1', '1', '1', '0', '2026-08-31 19:40:45', '2026-09-03 19:54:12');
INSERT INTO `nav_menus` (`id`, `title`, `url`, `page_route`, `parent_id`, `order`, `show_on_home`, `show_on_inner`, `is_active`, `target_blank`, `created_at`, `updated_at`) VALUES ('9', 'GALLERY', '/gallery', 'gallery', NULL, '4', '1', '1', '1', '0', '2026-08-31 20:30:25', '2026-08-31 20:30:25');
INSERT INTO `nav_menus` (`id`, `title`, `url`, `page_route`, `parent_id`, `order`, `show_on_home`, `show_on_inner`, `is_active`, `target_blank`, `created_at`, `updated_at`) VALUES ('10', 'Blogs', '/blog', 'custom', NULL, '5', '1', '1', '1', '0', '2026-09-03 06:44:19', '2026-09-03 19:54:23');


-- --------------------------------------------------------
-- Table structure for `page_analytics`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `page_analytics`;
CREATE TABLE `page_analytics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_views` bigint(20) unsigned NOT NULL DEFAULT 0,
  `unique_visitors` bigint(20) unsigned NOT NULL DEFAULT 0,
  `total_duration_seconds` bigint(20) unsigned NOT NULL DEFAULT 0,
  `avg_duration_seconds` int(10) unsigned NOT NULL DEFAULT 0,
  `last_visited_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_analytics_page_url_unique` (`page_url`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `page_analytics`
INSERT INTO `page_analytics` (`id`, `page_url`, `page_title`, `total_views`, `unique_visitors`, `total_duration_seconds`, `avg_duration_seconds`, `last_visited_at`, `created_at`, `updated_at`) VALUES ('1', '/innotech/contact', 'Contact Us | 24/7 Clinical Equipment Support - INNOTECH MEDICAL PVT LTD', '1', '1', '0', '0', '2026-09-03 08:32:09', '2026-09-03 08:32:09', '2026-09-03 08:32:09');
INSERT INTO `page_analytics` (`id`, `page_url`, `page_title`, `total_views`, `unique_visitors`, `total_duration_seconds`, `avg_duration_seconds`, `last_visited_at`, `created_at`, `updated_at`) VALUES ('2', '/innotech/', 'INNOTECH MEDICAL PVT LTD', '1', '1', '0', '0', '2026-09-03 08:32:15', '2026-09-03 08:32:15', '2026-09-03 08:32:15');
INSERT INTO `page_analytics` (`id`, `page_url`, `page_title`, `total_views`, `unique_visitors`, `total_duration_seconds`, `avg_duration_seconds`, `last_visited_at`, `created_at`, `updated_at`) VALUES ('3', '/innotech/public/products', 'Medical Products & Equipment | INNOTECH MEDICAL PVT LTD', '1', '1', '0', '0', '2026-09-03 19:46:38', '2026-09-03 19:46:38', '2026-09-03 19:46:38');
INSERT INTO `page_analytics` (`id`, `page_url`, `page_title`, `total_views`, `unique_visitors`, `total_duration_seconds`, `avg_duration_seconds`, `last_visited_at`, `created_at`, `updated_at`) VALUES ('4', '/innotech/products', 'Medical Products & Equipment | INNOTECH MEDICAL PVT LTD', '1', '1', '0', '0', '2026-09-03 19:47:21', '2026-09-03 19:47:21', '2026-09-03 19:47:21');
INSERT INTO `page_analytics` (`id`, `page_url`, `page_title`, `total_views`, `unique_visitors`, `total_duration_seconds`, `avg_duration_seconds`, `last_visited_at`, `created_at`, `updated_at`) VALUES ('5', '/innotech/products/elektro-mag-m-308-infant-radiant-warmer-unit-tft', 'Elektro-mag M 308 Infant Radiant Warmer Unit (TFT) | Elektro-mag - INNOTECH MEDICAL PVT LTD', '1', '1', '0', '0', '2026-09-03 19:48:01', '2026-09-03 19:48:01', '2026-09-03 19:48:01');
INSERT INTO `page_analytics` (`id`, `page_url`, `page_title`, `total_views`, `unique_visitors`, `total_duration_seconds`, `avg_duration_seconds`, `last_visited_at`, `created_at`, `updated_at`) VALUES ('6', '/innotech/public/products/elektro-mag-m-308-infant-radiant-warmer-unit-tft', 'Elektro-mag M 308 Infant Radiant Warmer Unit (TFT) | Elektro-mag - INNOTECH MEDICAL PVT LTD', '1', '1', '0', '0', '2026-09-03 19:50:53', '2026-09-03 19:50:53', '2026-09-03 19:50:53');
INSERT INTO `page_analytics` (`id`, `page_url`, `page_title`, `total_views`, `unique_visitors`, `total_duration_seconds`, `avg_duration_seconds`, `last_visited_at`, `created_at`, `updated_at`) VALUES ('7', '/innotech/about', 'About Us | Medical Engineering Excellence - INNOTECH MEDICAL PVT LTD', '1', '1', '0', '0', '2026-09-03 19:55:25', '2026-09-03 19:55:25', '2026-09-03 19:55:25');
INSERT INTO `page_analytics` (`id`, `page_url`, `page_title`, `total_views`, `unique_visitors`, `total_duration_seconds`, `avg_duration_seconds`, `last_visited_at`, `created_at`, `updated_at`) VALUES ('8', '/innotech/blog', 'Blog & Medical Research | Healthcare Articles - INNOTECH MEDICAL PVT LTD', '1', '1', '0', '0', '2026-09-03 19:56:41', '2026-09-03 19:56:41', '2026-09-03 19:56:41');
INSERT INTO `page_analytics` (`id`, `page_url`, `page_title`, `total_views`, `unique_visitors`, `total_duration_seconds`, `avg_duration_seconds`, `last_visited_at`, `created_at`, `updated_at`) VALUES ('9', '/innotech/blog/transforming-critical-care-modern-advancements-in-icu-monitoring', 'Transforming Critical Care: Modern Advancements in ICU Monitoring | Clinical Research & Insights - INNOTECH MEDICAL PVT LTD', '1', '1', '0', '0', '2026-09-03 19:56:47', '2026-09-03 19:56:47', '2026-09-03 19:56:47');
INSERT INTO `page_analytics` (`id`, `page_url`, `page_title`, `total_views`, `unique_visitors`, `total_duration_seconds`, `avg_duration_seconds`, `last_visited_at`, `created_at`, `updated_at`) VALUES ('10', '/innotech/products/greater-scooping-at-ease-endoscopic-system', 'GREATER SCOOPING AT EASE Endoscopic System | Lynmou Surgical - INNOTECH MEDICAL PVT LTD', '1', '1', '0', '0', '2026-09-03 20:16:19', '2026-09-03 20:16:19', '2026-09-03 20:16:19');


-- --------------------------------------------------------
-- Table structure for `pages`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'custom',
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_in_footer` tinyint(1) NOT NULL DEFAULT 1,
  `footer_placement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bottom_bar',
  `order` int(11) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `pages`
INSERT INTO `pages` (`id`, `title`, `slug`, `subtitle`, `template_type`, `content`, `meta_description`, `show_in_footer`, `footer_placement`, `order`, `is_published`, `created_at`, `updated_at`) VALUES ('1', 'Terms and Conditions', 'terms-and-conditions', 'Customer & Institutional Supply Terms', 'terms', '<h3>1. General Overview</h3><p>Welcome to INNOTECH MEDICAL PVT LTD. These Terms and Conditions govern the procurement, supply, technical installation, and preventative maintenance of biomedical equipment, diagnostic instrumentation, and clinical devices distributed across Pakistan.</p><h3>2. Equipment Supply & Technical Warranty</h3><p>All biomedical devices and turnkey systems supplied by Innotech Medical are certified under international healthcare standards (CE/ISO/FDA where applicable). Warranties cover manufacturer defects and include calibration services by certified engineers.</p><h3>3. Hospital Installation & Compliance</h3><p>Our biomedical engineering department provides end-to-end commissioning, site inspection, and operational training for clinical staff upon handover.</p><h3>4. Contact & Support</h3><p>For contractual or institutional procurement inquiries, please contact our administrative desk at info@innotecmedical.org.</p>', NULL, '1', 'bottom_bar', '1', '1', '2026-09-02 19:37:22', '2026-09-02 19:37:22');
INSERT INTO `pages` (`id`, `title`, `slug`, `subtitle`, `template_type`, `content`, `meta_description`, `show_in_footer`, `footer_placement`, `order`, `is_published`, `created_at`, `updated_at`) VALUES ('2', 'Privacy Policy', 'privacy-policy', 'Data Protection & Clinical Privacy Statement', 'privacy', '<h3>1. Commitment to Privacy</h3><p>At INNOTECH MEDICAL PVT LTD, we recognize the critical importance of privacy in healthcare institutions, diagnostic laboratories, and hospital management. This privacy statement outlines how we collect, process, and safeguard institutional and customer information.</p><h3>2. Information We Collect</h3><p>We collect procurement details, hospital facility specifications, contact inquiries, and equipment service logs strictly for technical support, quotation delivery, and preventative maintenance scheduling.</p><h3>3. Data Security</h3><p>All institutional data and communication channels are encrypted and handled in compliance with applicable Pakistani corporate data standards and healthcare confidentiality guidelines.</p>', NULL, '1', 'bottom_bar', '2', '1', '2026-09-02 19:37:22', '2026-09-02 19:37:22');


-- --------------------------------------------------------
-- Table structure for `partners`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `partners`;
CREATE TABLE `partners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `partners`
INSERT INTO `partners` (`id`, `name`, `logo`, `url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'SCITEK', 'uploads/partners/1788376845_307_tanmiahimg.png', NULL, '1', '1', '2026-09-01 20:25:55', '2026-09-02 19:28:10');
INSERT INTO `partners` (`id`, `name`, `logo`, `url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'MICARE', 'uploads/partners/1788376861_599_61caa7175be6b.png', 'https://micare.com', '2', '1', '2026-09-01 20:25:55', '2026-09-02 19:21:01');
INSERT INTO `partners` (`id`, `name`, `logo`, `url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'MEDITECH', 'uploads/partners/1788376883_115_dfffb1416c481ec1972cf2d3d32be97b.jpg', 'https://meditech.com', '3', '1', '2026-09-01 20:25:55', '2026-09-02 19:21:23');
INSERT INTO `partners` (`id`, `name`, `logo`, `url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('4', 'BNG MEDICAL', 'uploads/partners/1788376895_369_jarir-voucher.png', 'https://bngmedical.com', '4', '1', '2026-09-01 20:25:55', '2026-09-02 19:21:35');
INSERT INTO `partners` (`id`, `name`, `logo`, `url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('5', 'LYNMOU', 'uploads/partners/1788376915_464_chatgpt-image-jul-30-2026-03-21-49-pm.png', 'https://lynmou.com', '5', '1', '2026-09-01 20:25:55', '2026-09-02 19:21:55');


-- --------------------------------------------------------
-- Table structure for `password_reset_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------
-- Table structure for `products`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key_features` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_company_id_foreign` (`company_id`),
  CONSTRAINT `products_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `products`
INSERT INTO `products` (`id`, `company_id`, `title`, `slug`, `sku`, `image`, `gallery`, `short_description`, `description`, `key_features`, `order`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES ('1', '1', 'Elektro-mag M 308 Infant Radiant Warmer Unit (TFT)', 'elektro-mag-m-308-infant-radiant-warmer-unit-tft', 'EM-M308-TFT', 'uploads/products/1788467258_266_tab-thumb-03.jpg', NULL, 'The Elektro-mag M 308 Infant Radiant Warmer Unit is a state-of-the-art neonatal care solution from Turkey.', 'The Elektro-mag M 308 Infant Radiant Warmer Unit is engineered to provide precise thermal support and critical intervention capabilities for neonates in intensive care units and delivery suites.\r\n\r\nFeaturing advanced ceramic heating technology and intelligent PID thermal algorithms, the unit ensures uniform heat distribution while minimizing insensible fluid loss. The intuitive 7\" full-color TFT touch screen provides instant telemetry readouts, visual alarms, and APGAR countdown timing for clinical teams.', '[\"7\\\" Colored TFT Touch Screen Display\",\"PID Digital Temperature Control (\\u00b10.1\\u00baC accuracy)\",\"Three Modes: Preheat, Manual, Baby Servo Control\",\"Ceramic Heater Technology\",\"Integrated APGAR Timer and examination light\",\"Built-in Digital Scale\",\"Trendelenburg Positioning\"]', '1', '1', '1', '2026-09-03 19:40:22', '2026-09-03 20:34:03');
INSERT INTO `products` (`id`, `company_id`, `title`, `slug`, `sku`, `image`, `gallery`, `short_description`, `description`, `key_features`, `order`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES ('2', '5', 'GREATER SCOOPING AT EASE Endoscopic System', 'greater-scooping-at-ease-endoscopic-system', 'LYN-ENDO-400', 'assets/img/shop/shop-02.jpg', NULL, 'High-resolution flexible video endoscope system engineered with exceptional scooping maneuverability and optical clarity.', 'Engineered for superior endoscopic intervention, the GREATER SCOOPING AT EASE system allows gastrointestinal specialists to perform detailed mucosa inspections and delicate biopsic scoops with minimal patient discomfort.\n\nEquipped with dual LED illumination, CMOS high-fidelity imaging sensors, and ultra-flexible insertion catheters.', '[\"140\\u00b0 Ultra-Wide Viewing Angle with zero optical distortion\",\"Smooth 4-Way Articulation with high-torque locking mechanism\",\"Water Jet High-Pressure Channel for quick lens cleansing\",\"Integrated NBI (Narrow Band Imaging) for vascular contrast\",\"Fully Submersible and Autoclavable design\"]', '2', '1', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');
INSERT INTO `products` (`id`, `company_id`, `title`, `slug`, `sku`, `image`, `gallery`, `short_description`, `description`, `key_features`, `order`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES ('3', '2', 'Full HD 4K Laparoscopy Surgical Tower', 'full-hd-4k-laparoscopy-surgical-tower', 'BNG-LAP-4K', 'assets/img/shop/shop-03.jpg', NULL, 'Complete modular surgical video tower integrating 4K camera console, 300W cold LED light, and 45L high-flow CO2 insufflator.', 'Designed for advanced laparoscopic and general surgery suites, this modular system provides crisp resolution and true color reproduction. Ergonomically housed in an anti-static medical trolley with isolated power transformers.', '[\"4K UHD Sony CMOS 3-Chip Camera Head with optical zoom\",\"32-inch Medical Anti-Glare IPS Monitor (3840x2160)\",\"45 Litres\\/min Automatic Gas Insufflator with pre-heating\",\"300W Medical Cold LED Illuminator (6000K daylight balance)\",\"Synchronized Digital Video & Still Capture to USB 3.0\"]', '3', '1', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');
INSERT INTO `products` (`id`, `company_id`, `title`, `slug`, `sku`, `image`, `gallery`, `short_description`, `description`, `key_features`, `order`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES ('4', '3', 'Modular Multi-Parameter Patient Monitor (12.1\" TFT)', 'modular-multi-parameter-patient-monitor', 'MDT-MPM-1200', 'assets/img/shop/shop-04.jpg', NULL, 'Comprehensive bedside critical care monitor for real-time tracking of ECG, SpO2, NIBP, Respiration, and Temp.', 'The MDT-MPM-1200 offers reliable physiological tracking across ICUs, OTs, and recovery wards. Built-in pacemaker detection and defibrillator synchronization safeguard patient security during high-risk cardiac interventions.', '[\"12.1-inch High-Resolution Anti-Glare Color TFT Display\",\"7-Lead ECG Waveform Synchronized Display with Arrhythmia analysis\",\"Digital Masimo \\/ Nellcor SpO2 sensor compatibility\",\"High-Frequency Electrosurgery and Defibrillation protection\",\"Built-in Rechargeable Lithium Battery (4+ hours continuous operation)\"]', '4', '1', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');
INSERT INTO `products` (`id`, `company_id`, `title`, `slug`, `sku`, `image`, `gallery`, `short_description`, `description`, `key_features`, `order`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES ('5', '6', 'Microprocessor Electrosurgical Diathermy Generator (400W)', 'microprocessor-electrosurgical-diathermy-generator', 'MIC-ESU-400', 'assets/img/shop/shop-05.jpg', NULL, 'Precision monopolar and bipolar electrosurgical generator designed for minimal tissue carbonization and rapid hemostasis.', 'With instant tissue impedance feedback, this unit dynamically modulates power output across micro-seconds, ensuring clean cut margins and effective coagulation without thermal spread.', '[\"400 Watts Monopolar Output with Pure, Blend, and Spray modes\",\"Bipolar Standard & Force Coagulation with Auto-Stop sensor\",\"REM (Return Electrode Monitoring) contact security safety system\",\"10 User-Customizable Specialty Programs memory\",\"Waterproof Double-Foot Pedal with safety guard\"]', '5', '1', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');
INSERT INTO `products` (`id`, `company_id`, `title`, `slug`, `sku`, `image`, `gallery`, `short_description`, `description`, `key_features`, `order`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES ('6', '4', 'Digital High-Resolution Mammography & Radiology Suite', 'digital-high-resolution-mammography-suite', 'SC-MAMMO-800', 'assets/img/shop/shop-06.jpg', NULL, 'Low-dose full-field digital mammography system with gentle motorized compression and dual focal spot clarity.', 'Empowers diagnostic radiology centers with supreme spatial resolution for early-stage microcalcification detection. Ergonomic breast positioning gantry ensures patient relaxation throughout examinations.', '[\"Amorphous Silicon Flat Panel Detector with 85\\u00b5m pixel pitch\",\"Automated Micro-Dose Exposure Control (AEC)\",\"Smart Motorized Compression with soft-release technology\",\"Dual High-Resolution 5MP Medical Diagnostic Review Screens\",\"Full DICOM 3.0 PACS connectivity and worklist management\"]', '6', '1', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');
INSERT INTO `products` (`id`, `company_id`, `title`, `slug`, `sku`, `image`, `gallery`, `short_description`, `description`, `key_features`, `order`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES ('7', '3', 'Critical Care Intensive ICU Mechanical Ventilator', 'critical-care-intensive-icu-mechanical-ventilator', 'MDT-VENT-ICU', 'assets/img/shop/shop-07.jpg', NULL, 'Adaptive lung-protective ICU ventilator accommodating adult, pediatric, and neonatal respiratory support.', 'Engineered for complex respiratory failure cases with built-in high-performance ultra-quiet turbine technology, eliminating the requirement for external hospital wall compressed air lines.', '[\"15-inch Touchscreen with configurable pressure\\/volume loops\",\"Invasive (VCV, PCV, SIMV, PRVC) & Non-Invasive (NIV\\/CPAP) modes\",\"Integrated High-Performance Silent Turbine drive\",\"Lung Recruitment maneuvers and automated P\\/V Tool\",\"Dual Oxygen Sensor with automated calibration\"]', '7', '1', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');
INSERT INTO `products` (`id`, `company_id`, `title`, `slug`, `sku`, `image`, `gallery`, `short_description`, `description`, `key_features`, `order`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES ('8', '4', 'Digital Mobile Surgical C-Arm Fluoroscopy Imaging System', 'digital-mobile-surgical-c-arm-fluoroscopy-system', 'SC-CARM-900', 'assets/img/shop/shop-08.jpg', NULL, 'High-frequency mobile C-Arm fluoroscopy for orthopedic, vascular, and traumatology surgical theaters.', 'Offers broad orbital rotation, generous free space, and low radiation scatter. Dual high-luminance anti-glare screens display live fluoroscopy and reference roadmaps simultaneously.', '[\"High-Frequency 40kHz 5kW Rotating Anode X-ray generator\",\"9-inch Triple-Field High DQE Image Intensifier\",\"Pulsed Fluoroscopy mode reducing radiation exposure up to 70%\",\"Laser Position Cross-Marker for accurate beam centering\",\"Integrated Memory for 100,000+ DICOM frames with cine playback\"]', '8', '1', '1', '2026-09-03 19:40:22', '2026-09-03 19:40:22');


-- --------------------------------------------------------
-- Table structure for `services`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Medical',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `steps_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `steps_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_1_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_1_points` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_2_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_2_points` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_3_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_3_points` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_4_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_4_points` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bottom_link_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bottom_link_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `services`
INSERT INTO `services` (`id`, `title`, `slug`, `category`, `icon`, `image`, `banner_image`, `banner_subtitle`, `image_2`, `process_title`, `short_description`, `description`, `features`, `steps_title`, `steps_description`, `step_1_title`, `step_1_points`, `step_2_title`, `step_2_points`, `step_3_title`, `step_3_points`, `step_4_title`, `step_4_points`, `research_title`, `research_description`, `research_image`, `bottom_link_text`, `bottom_link_url`, `is_featured`, `is_active`, `order`, `created_at`, `updated_at`) VALUES ('1', 'Medical Equipment & ICU Systems', 'medical-equipment-icu-systems', 'Critical Care', 'flaticon-hemoglobin-test-meter', 'assets/img/services/services-thumb-01.jpg', NULL, NULL, NULL, 'Medical Equipment & ICU Systems - Overview & Specifications', 'High-precision patient monitors, ventilators, anesthesia workstations, and infusion pumps for acute care units.', 'We supply top-tier intensive care unit (ICU) and operation theatre equipment designed to assist healthcare teams during critical procedures with maximum reliability.', 'Certified OEM medical equipment with official manufacturer warranty\r\nOn-site calibration, turnkey clinical integration, and staff operation training\r\n24/7 biomedical emergency hotline and guaranteed genuine spare parts\r\nFull compliance with international CE, FDA, and ISO healthcare safety benchmarks', '4 Simple Steps to Implementation', 'Streamlined procurement, rapid delivery, and certified biomedical installation, delivering complete healthcare projects with minimal turnaround time and total precision.', 'Step 01', 'Consultation and Needs Assessment\r\nClinical Setup Planning', 'Step 02', 'Turnkey Delivery and Placement\r\nBiomedical Precision Calibration', 'Step 03', 'Staff Clinical Operations Training\r\nPreventative Maintenance Contracts', 'Step 04', '24/7 Technical Support\r\nEmergency Spare Parts Dispatch', 'Our Research and Clinical Verification', 'Every piece of medical equipment and clinical service provided by Innotech undergoes rigorous testing and biomedical calibration to guarantee peak diagnostic accuracy and patient safety.', NULL, 'Inquire About This Service', '/contact', '1', '1', '1', '2026-08-31 19:06:08', '2026-09-03 06:52:08');
INSERT INTO `services` (`id`, `title`, `slug`, `category`, `icon`, `image`, `banner_image`, `banner_subtitle`, `image_2`, `process_title`, `short_description`, `description`, `features`, `steps_title`, `steps_description`, `step_1_title`, `step_1_points`, `step_2_title`, `step_2_points`, `step_3_title`, `step_3_points`, `step_4_title`, `step_4_points`, `research_title`, `research_description`, `research_image`, `bottom_link_text`, `bottom_link_url`, `is_featured`, `is_active`, `order`, `created_at`, `updated_at`) VALUES ('2', 'Diagnostic & Laboratory Instruments', 'diagnostic-laboratory-instruments', 'Diagnostics', 'flaticon-blood-test', 'assets/img/services/services-thumb-02.jpg', NULL, NULL, NULL, 'Diagnostic & Laboratory Instruments - Overview & Specifications', 'Automated hematology analyzers, clinical chemistry, immunology systems, and molecular diagnostics.', 'Our clinical diagnostic portfolio provides high-throughput, accurate results for modern pathology labs and hospital diagnostic departments.', 'Certified OEM medical equipment with official manufacturer warranty\nOn-site calibration, turnkey clinical integration, and staff operation training\n24/7 biomedical emergency hotline and guaranteed genuine spare parts\nFull compliance with international CE, FDA, and ISO healthcare safety benchmarks', '4 Simple Steps to Implementation', 'Streamlined procurement, rapid delivery, and certified biomedical installation, delivering complete healthcare projects with minimal turnaround time and total precision.', 'Step 01', 'Consultation and Needs Assessment\nClinical Setup Planning', 'Step 02', 'Turnkey Delivery and Placement\nBiomedical Precision Calibration', 'Step 03', 'Staff Clinical Operations Training\nPreventative Maintenance Contracts', 'Step 04', '24/7 Technical Support\nEmergency Spare Parts Dispatch', 'Our Research and Clinical Verification', 'Every piece of medical equipment and clinical service provided by Innotech undergoes rigorous testing and biomedical calibration to guarantee peak diagnostic accuracy and patient safety.', NULL, 'Inquire About This Service', '/contact', '1', '1', '2', '2026-08-31 19:06:08', '2026-09-01 18:51:21');
INSERT INTO `services` (`id`, `title`, `slug`, `category`, `icon`, `image`, `banner_image`, `banner_subtitle`, `image_2`, `process_title`, `short_description`, `description`, `features`, `steps_title`, `steps_description`, `step_1_title`, `step_1_points`, `step_2_title`, `step_2_points`, `step_3_title`, `step_3_points`, `step_4_title`, `step_4_points`, `research_title`, `research_description`, `research_image`, `bottom_link_text`, `bottom_link_url`, `is_featured`, `is_active`, `order`, `created_at`, `updated_at`) VALUES ('3', 'Hospital Consumables & Disposables', 'hospital-consumables-disposables', 'Consumables', 'flaticon-biochemistry', 'assets/img/services/services-thumb-03.jpg', NULL, NULL, NULL, 'Hospital Consumables & Disposables - Overview & Specifications', 'Surgical gloves, IV sets, sterilization packs, surgical drapes, syringes, and clinical disposables.', 'Ensuring seamless day-to-day hospital operations with high-quality sterile medical consumables adhering to international ISO standards.', 'Certified OEM medical equipment with official manufacturer warranty\nOn-site calibration, turnkey clinical integration, and staff operation training\n24/7 biomedical emergency hotline and guaranteed genuine spare parts\nFull compliance with international CE, FDA, and ISO healthcare safety benchmarks', '4 Simple Steps to Implementation', 'Streamlined procurement, rapid delivery, and certified biomedical installation, delivering complete healthcare projects with minimal turnaround time and total precision.', 'Step 01', 'Consultation and Needs Assessment\nClinical Setup Planning', 'Step 02', 'Turnkey Delivery and Placement\nBiomedical Precision Calibration', 'Step 03', 'Staff Clinical Operations Training\nPreventative Maintenance Contracts', 'Step 04', '24/7 Technical Support\nEmergency Spare Parts Dispatch', 'Our Research and Clinical Verification', 'Every piece of medical equipment and clinical service provided by Innotech undergoes rigorous testing and biomedical calibration to guarantee peak diagnostic accuracy and patient safety.', NULL, 'Inquire About This Service', '/contact', '1', '1', '3', '2026-08-31 19:06:08', '2026-09-01 18:51:21');
INSERT INTO `services` (`id`, `title`, `slug`, `category`, `icon`, `image`, `banner_image`, `banner_subtitle`, `image_2`, `process_title`, `short_description`, `description`, `features`, `steps_title`, `steps_description`, `step_1_title`, `step_1_points`, `step_2_title`, `step_2_points`, `step_3_title`, `step_3_points`, `step_4_title`, `step_4_points`, `research_title`, `research_description`, `research_image`, `bottom_link_text`, `bottom_link_url`, `is_featured`, `is_active`, `order`, `created_at`, `updated_at`) VALUES ('4', 'Turnkey Healthcare Projects', 'turnkey-healthcare-projects', 'Turnkey Solutions', 'flaticon-ct-scan', 'assets/img/services/services-thumb-01.jpg', NULL, NULL, NULL, 'Turnkey Healthcare Projects - Overview & Specifications', 'Complete hospital setup consulting, layout planning, medical gas pipeline systems, and OT modular design.', 'From architectural planning of modular operation theatres to medical gas systems, we deliver end-to-end turnkey hospital setups.', 'Certified OEM medical equipment with official manufacturer warranty\nOn-site calibration, turnkey clinical integration, and staff operation training\n24/7 biomedical emergency hotline and guaranteed genuine spare parts\nFull compliance with international CE, FDA, and ISO healthcare safety benchmarks', '4 Simple Steps to Implementation', 'Streamlined procurement, rapid delivery, and certified biomedical installation, delivering complete healthcare projects with minimal turnaround time and total precision.', 'Step 01', 'Consultation and Needs Assessment\nClinical Setup Planning', 'Step 02', 'Turnkey Delivery and Placement\nBiomedical Precision Calibration', 'Step 03', 'Staff Clinical Operations Training\nPreventative Maintenance Contracts', 'Step 04', '24/7 Technical Support\nEmergency Spare Parts Dispatch', 'Our Research and Clinical Verification', 'Every piece of medical equipment and clinical service provided by Innotech undergoes rigorous testing and biomedical calibration to guarantee peak diagnostic accuracy and patient safety.', NULL, 'Inquire About This Service', '/contact', '1', '1', '4', '2026-08-31 19:06:08', '2026-09-01 18:51:21');
INSERT INTO `services` (`id`, `title`, `slug`, `category`, `icon`, `image`, `banner_image`, `banner_subtitle`, `image_2`, `process_title`, `short_description`, `description`, `features`, `steps_title`, `steps_description`, `step_1_title`, `step_1_points`, `step_2_title`, `step_2_points`, `step_3_title`, `step_3_points`, `step_4_title`, `step_4_points`, `research_title`, `research_description`, `research_image`, `bottom_link_text`, `bottom_link_url`, `is_featured`, `is_active`, `order`, `created_at`, `updated_at`) VALUES ('5', 'Biomedical Maintenance & Calibration', 'biomedical-maintenance-calibration', 'Engineering & Support', 'flaticon-dna', 'assets/img/services/services-thumb-02.jpg', NULL, NULL, NULL, 'Biomedical Maintenance & Calibration - Overview & Specifications', 'Certified biomedical engineers providing preventative maintenance, calibration, repairs, and genuine spare parts.', 'Ensure maximum uptime of critical medical machinery with our certified preventative maintenance contracts and genuine OEM components.', 'Certified OEM medical equipment with official manufacturer warranty\nOn-site calibration, turnkey clinical integration, and staff operation training\n24/7 biomedical emergency hotline and guaranteed genuine spare parts\nFull compliance with international CE, FDA, and ISO healthcare safety benchmarks', '4 Simple Steps to Implementation', 'Streamlined procurement, rapid delivery, and certified biomedical installation, delivering complete healthcare projects with minimal turnaround time and total precision.', 'Step 01', 'Consultation and Needs Assessment\nClinical Setup Planning', 'Step 02', 'Turnkey Delivery and Placement\nBiomedical Precision Calibration', 'Step 03', 'Staff Clinical Operations Training\nPreventative Maintenance Contracts', 'Step 04', '24/7 Technical Support\nEmergency Spare Parts Dispatch', 'Our Research and Clinical Verification', 'Every piece of medical equipment and clinical service provided by Innotech undergoes rigorous testing and biomedical calibration to guarantee peak diagnostic accuracy and patient safety.', NULL, 'Inquire About This Service', '/contact', '1', '1', '5', '2026-08-31 19:06:08', '2026-09-01 18:51:21');
INSERT INTO `services` (`id`, `title`, `slug`, `category`, `icon`, `image`, `banner_image`, `banner_subtitle`, `image_2`, `process_title`, `short_description`, `description`, `features`, `steps_title`, `steps_description`, `step_1_title`, `step_1_points`, `step_2_title`, `step_2_points`, `step_3_title`, `step_3_points`, `step_4_title`, `step_4_points`, `research_title`, `research_description`, `research_image`, `bottom_link_text`, `bottom_link_url`, `is_featured`, `is_active`, `order`, `created_at`, `updated_at`) VALUES ('6', 'Radiology & Imaging Solutions', 'radiology-imaging-solutions', 'Imaging', 'flaticon-microscope', 'assets/img/services/services-thumb-03.jpg', NULL, NULL, NULL, 'Radiology & Imaging Solutions - Overview & Specifications', 'Ultrasound scanners, digital radiography systems, C-arms, and radiation protection equipment.', 'Cutting-edge ultrasound and digital imaging technologies delivering crystal-clear diagnostics for radiology suites.', 'Certified OEM medical equipment with official manufacturer warranty\nOn-site calibration, turnkey clinical integration, and staff operation training\n24/7 biomedical emergency hotline and guaranteed genuine spare parts\nFull compliance with international CE, FDA, and ISO healthcare safety benchmarks', '4 Simple Steps to Implementation', 'Streamlined procurement, rapid delivery, and certified biomedical installation, delivering complete healthcare projects with minimal turnaround time and total precision.', 'Step 01', 'Consultation and Needs Assessment\nClinical Setup Planning', 'Step 02', 'Turnkey Delivery and Placement\nBiomedical Precision Calibration', 'Step 03', 'Staff Clinical Operations Training\nPreventative Maintenance Contracts', 'Step 04', '24/7 Technical Support\nEmergency Spare Parts Dispatch', 'Our Research and Clinical Verification', 'Every piece of medical equipment and clinical service provided by Innotech undergoes rigorous testing and biomedical calibration to guarantee peak diagnostic accuracy and patient safety.', NULL, 'Inquire About This Service', '/contact', '1', '1', '6', '2026-08-31 19:06:08', '2026-09-01 18:51:21');


-- --------------------------------------------------------
-- Table structure for `sessions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `sessions`
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('pPxvEoRr6WZxSYbHc0b5HHoHG7dqLBdmw2yUkLrK', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMnVNTVJVSlRxV3V5OTU5UXdNVGlnRVh1TmVlOWRWZ0dWMUNpYUQwOCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo5NzoiaHR0cDovL2xvY2FsaG9zdC9pbm5vdGVjaC9jaGF0L3BvbGw/bGFzdF9pZD0zNiZzZXNzaW9uX3Rva2VuPXRaT0hmSjZCbk5Pa2JPSXN5eE93UUpDQkRYa2tBUTdiVnpPSiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', '1788382410');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('ybCpAmftTrmT49rZNt7zSr7hBzkkMyizboWrAh24', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ3NNZFdVSkZkY0dUTkdLZ25tRTVJOUtIbzFkZzVBR1ZlaUdzQWo3MiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo3MzoiaHR0cDovL2xvY2FsaG9zdC9pbm5vdGVjaC9hZG1pbi9ub3RpZmljYXRpb25zL2NoZWNrP2xhc3RfY2hlY2s9MTc4ODQyNDIxNyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', '1788424223');


-- --------------------------------------------------------
-- Table structure for `settings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `settings`
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('1', 'site_title', 'INNOTECH MEDICAL PVT LTD', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:06:08');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('2', 'site_tagline', 'Innovating Health Care with Advance Technologies', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('3', 'helpdesk_phone', '+92 331 6699992', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('4', 'emergency_phone', '+92 300 1234567', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:35:27');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('5', 'support_email', 'info@innotechmed.com', 'general', 'text', '2026-08-31 19:06:08', '2026-09-03 19:59:28');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('6', 'sales_email', 'sales@innotechmedical.com', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:35:27');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('7', 'office_address', '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('8', 'working_hours', 'Office Hours: 10AM - 6PM', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('9', 'logo_path', 'uploads/sections/1788288342_708_logo.png', 'general', 'text', '2026-08-31 19:06:08', '2026-09-01 18:45:42');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('10', 'favicon_path', 'assets/img/logo/favicon.png', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('11', 'facebook_url', 'https://facebook.com', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('12', 'twitter_url', 'https://twitter.com', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('13', 'linkedin_url', 'https://linkedin.com', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('14', 'instagram_url', 'https://instagram.com', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:35:27');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('15', 'youtube_url', 'https://youtube.com', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('16', 'about_badge', 'Welcome to Innotech Medical Pvt Ltd', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('17', 'about_heading', 'Innovating Healthcare with Advance Technologies', 'general', 'text', '2026-08-31 19:06:08', '2026-09-01 19:33:56');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('18', 'about_description', 'Innotech Medical Pvt Ltd is Established & Reputable distributor of top-quality medical equipment across Pakistan. From state-of-the-art Medical Devices and Surgical Disposable solutions to comprehensive turnkey hospital projects, our commitment goes beyond equipment distribution. We provide end-to-end technical support, regulatory compliance, and seamless integration, ensuring that healthcare providers across the nation have access to reliable, cutting-edge medical technologies.', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('19', 'about_experience_years', '7', 'general', 'text', '2026-08-31 19:06:08', '2026-09-01 19:33:56');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('20', 'stat_clients_count', '1492', 'general', 'text', '2026-08-31 19:06:08', '2026-09-01 19:44:52');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('21', 'stat_clients_label', 'Laboratories in 100+ states', 'general', 'text', '2026-08-31 19:06:08', '2026-09-01 19:44:52');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('22', 'stat_devices_count', '152', 'general', 'text', '2026-08-31 19:06:08', '2026-09-01 19:44:52');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('23', 'stat_devices_label', 'Laboratory specialists', 'general', 'text', '2026-08-31 19:06:08', '2026-09-01 19:44:52');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('24', 'stat_engineers_count', '1022', 'general', 'text', '2026-08-31 19:06:08', '2026-09-01 19:44:52');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('25', 'stat_engineers_label', 'Material collection points', 'general', 'text', '2026-08-31 19:06:08', '2026-09-01 19:44:52');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('26', 'stat_support_count', '24332', 'general', 'text', '2026-08-31 19:06:08', '2026-09-01 19:44:52');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('27', 'stat_support_label', 'Patients diagnosed in 2022', 'general', 'text', '2026-08-31 19:06:08', '2026-09-01 19:44:52');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('28', 'footer_about', 'Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical.', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('29', 'copyright_text', '© Copyright ©2026 - 2027 INNOTECH MEDICAL Pvt Ltd. All Rights Reserved', 'general', 'text', '2026-08-31 19:06:08', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('30', 'section_header_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('31', 'section_banner_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:54:05');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('32', 'section_services_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-09-03 06:52:09');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('33', 'section_about_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('34', 'section_counter_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('35', 'section_gallery_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('36', 'section_choose_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('37', 'section_appointment_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('38', 'section_team_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('39', 'section_testimonial_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-09-01 19:39:29');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('40', 'section_brand_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('41', 'section_cta_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('42', 'section_blog_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('43', 'section_footer_enabled', '1', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('44', 'banner_badge', 'Welcome to Innotech Medical Pvt Ltd', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('45', 'banner_title', 'Innovating Health Care with Advance Technologies', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('46', 'banner_description', 'Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical, we are dedicated to bridging the gap between world-class medical innovation and Pakistan’s healthcare sector. As a leading provider of advanced biomedical technologies, diagnostic systems, and specialized clinical equipment, we empower healthcare institutions to deliver accurate diagnoses and superior patient care.', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('47', 'banner_btn_text', 'Contact with Us', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('48', 'banner_btn_link', '/contact', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 20:24:57');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('49', 'banner_btn2_text', 'About us', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('50', 'banner_btn2_link', '/about', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('51', 'banner_video_url', 'https://www.youtube.com/watch?v=d8w5SICzzxc', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('52', 'banner_feature_1', '100% Customer Satisfaction', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:36:19');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('53', 'banner_feature_2', 'Help and Acess is Our Mission', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:36:19');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('54', 'banner_feature_3', '100% Quality Laboratory service', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:36:19');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('55', 'services_subtitle', 'our Services', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('56', 'services_title', 'Service Area', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('57', 'services_search_placeholder', 'What are you looking for?', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('58', 'about_experience_label', 'Years of Experience', 'general', 'text', '2026-08-31 19:29:37', '2026-09-01 19:33:56');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('59', 'about_italic_text', '—Empowering hospitals, diagnostic labs, and surgical suites with world-class technology and end-to-end engineering support.', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('60', 'about_point_1', 'Critical Care & ICU Equipment', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('61', 'about_point_2', 'Advanced Diagnostic & Lab Instruments', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('62', 'about_point_3', 'Operating Room & General Medical Solutions', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('63', 'about_point_4', 'Turnkey Projects & Technical Support', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('64', 'counter_1_number', '1492', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('65', 'counter_1_title', 'Laboratories in 100+ states', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('66', 'counter_2_number', '152', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('67', 'counter_2_title', 'Laboratory specialists', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('68', 'counter_3_number', '1022', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('69', 'counter_3_title', 'Material collection points', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('70', 'counter_4_number', '24332', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('71', 'counter_4_title', 'Patients diagnosed in 2022', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('72', 'gallery_subtitle', 'Work Gallery', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('73', 'gallery_title', 'INNOTECH Gallery', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('74', 'gallery_btn_text', 'Explore More', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('75', 'gallery_btn_link', '/gallery', 'general', 'text', '2026-08-31 19:29:37', '2026-09-01 19:01:01');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('76', 'choose_subtitle', 'Our Specialists', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('77', 'choose_title', 'Why Choose Us', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('78', 'choose_card1_title', 'Global Standards & Quality', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('79', 'choose_card1_desc', 'Delivering FDA, CE, and ISO certified medical equipment from global principal brands, ensuring maximum clinical accuracy and patient safety.', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('80', 'choose_card2_title', 'Swift Turnkey Delivery', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('81', 'choose_card2_desc', 'Streamlined procurement and rapid installation, delivering complete healthcare projects with minimal turnaround time and total precision.', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('82', 'choose_card3_title', '24/7 Emergency Support', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('83', 'choose_card3_desc', 'Round-the-clock technical coverage and rapid dispatch troubleshooting to eliminate critical equipment downtime in ICUs and OTs.', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('84', 'choose_card4_title', 'Certified Biomedical Experts', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('85', 'choose_card4_desc', 'Backed by OEM-trained engineers executing precision calibration, complex repairs, and proactive maintenance to global standards.', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('86', 'choose_bottom_text', 'Scientific Research Laboratories:', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('87', 'choose_bottom_btn_text', 'Contact Us', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('88', 'choose_bottom_btn_link', '/contact', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('89', 'appointment_title', 'GET IN TOUCH WITH US', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('90', 'appointment_phone', '+92 331 6699992', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('91', 'appointment_subtitle', '24/7 Emergency Service', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('92', 'team_subtitle', 'Specialists Team', 'general', 'text', '2026-08-31 19:29:37', '2026-09-01 19:26:56');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('93', 'team_title', 'Meet Our Specialists', 'general', 'text', '2026-08-31 19:29:37', '2026-09-01 19:26:56');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('94', 'testimonial_subtitle', 'Testimonial', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('95', 'testimonial_title', 'Customer Feedback', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('96', 'cta_title', 'Looking for a best lebatory Service', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('97', 'cta_phone', '+92 331 6699992', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('98', 'cta_btn_text', 'Call :', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('99', 'blog_subtitle', 'Waht’s New', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('100', 'blog_title', 'Blog & Article', 'general', 'text', '2026-08-31 19:29:37', '2026-08-31 19:29:37');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('101', 'banner_image', 'uploads/sections/1788207663_529_banner-01.png', 'general', 'text', '2026-08-31 19:57:56', '2026-08-31 20:21:04');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('102', 'banner_video_type', 'url', 'general', 'text', '2026-08-31 20:24:56', '2026-08-31 20:24:56');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('103', 'team_banner_title', 'Our Specialists & Healthcare Experts', 'general', 'text', '2026-09-01 19:23:44', '2026-09-01 19:26:56');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('104', 'team_banner_subtitle', 'Specialists', 'general', 'text', '2026-09-01 19:23:44', '2026-09-01 19:26:56');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('105', 'about_step_1_title', 'Clinical Consultation & Proposal', 'general', 'text', '2026-09-01 19:33:55', '2026-09-01 19:33:55');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('106', 'about_mission_title', 'Our Mission is to Give You Always the Best Clinical Results.', 'general', 'text', '2026-09-01 19:33:55', '2026-09-01 19:33:55');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('107', 'about_why_1_title', 'Global Standards &Quality', 'general', 'text', '2026-09-01 19:33:55', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('108', 'about_image', 'assets/img/about/about-bg-01.png', 'general', 'text', '2026-09-01 19:44:52', '2026-09-02 19:21:48');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('109', 'about_shape_1', 'assets/img/about/about-bg-05.jpg', 'general', 'text', '2026-09-01 19:44:52', '2026-09-01 19:44:52');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('110', 'about_shape_2', 'assets/img/about/about-bg-06.jpg', 'general', 'text', '2026-09-01 19:44:52', '2026-09-01 19:44:52');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('111', 'about_link_text', 'Read our Mission & Vision', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('112', 'about_process_desc', 'Your trusted partner for medical equipment procurement, turnkey installations, and technical integration across Pakistan.', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('113', 'about_step_1_desc', 'Understanding facility requirements to recommend compliant, cost-effective medical equipment solutions.', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('114', 'about_step_2_title', 'Seamless Deployment', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('115', 'about_step_2_desc', 'Rapid procurement, physical installation, and precise site calibration by certified biomedical engineers.', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('116', 'about_step_3_title', 'Integration & Support', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('117', 'about_step_3_desc', 'Comprehensive staff application training alongside 24/7 technical support and routine maintenance.', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('118', 'about_mission_desc', 'To enhance the quality of healthcare across Pakistan by delivering state-of-the-art medical devices, advanced diagnostic technologies, and uncompromised technical support to hospitals and laboratories.', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('119', 'about_value_title', 'Trusted by Leading Clinical Facilities', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('120', 'about_value_desc', 'To become Pakistan’s premier and most trusted B2B healthcare partner, driving innovation in biomedical engineering and empowering institutions with futuristic medical solutions.', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('121', 'about_choose_subtitle', 'Our Specialists', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('122', 'about_choose_title', 'Why Choose Us', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('123', 'about_why_1_desc', 'Delivering FDA, CE, and ISO certified medical equipment from global principal brands, ensuring maximum clinical accuracy and patient safety.', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('124', 'about_why_2_title', 'Swift Turnkey Delivery', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('125', 'about_why_2_desc', 'Streamlined procurement and rapid installation, delivering complete healthcare projects with minimal turnaround time and total precision.', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('126', 'about_why_3_title', '24/7 Emergency Support', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('127', 'about_why_3_desc', 'Round-the-clock technical coverage and rapid dispatch troubleshooting to eliminate critical equipment downtime in ICUs and OTs.', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('128', 'about_why_4_title', 'Certified Biomedical Experts', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('129', 'about_why_4_desc', 'Backed by OEM-trained engineers executing precision calibration, complex repairs, and proactive maintenance to global standards.', 'general', 'text', '2026-09-01 19:46:20', '2026-09-01 19:46:20');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('130', 'partners_title', 'Our Trust Partners', 'general', 'text', '2026-09-01 20:25:55', '2026-09-01 20:25:55');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('131', 'partners_subtitle', 'Global Collaborations', 'general', 'text', '2026-09-01 20:25:55', '2026-09-01 20:25:55');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('132', 'about_mission_img1', 'uploads/sections/1788376225_512_tab-thumb-03.jpg', 'general', 'text', '2026-09-02 19:09:39', '2026-09-02 19:10:25');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('133', 'sidebar_mission', 'Our mission is to ensure the generation of accurate and precise findings.', 'general', 'text', '2026-09-02 20:13:38', '2026-09-02 20:13:38');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('134', 'sidebar_contact_title', 'Contact Us', 'general', 'text', '2026-09-02 20:13:38', '2026-09-02 20:13:38');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('135', 'sidebar_newsletter_enabled', '1', 'general', 'text', '2026-09-02 20:13:38', '2026-09-02 20:14:15');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('136', 'sidebar_newsletter_title', 'Get Update', 'general', 'text', '2026-09-02 20:13:38', '2026-09-02 20:13:38');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('137', 'sidebar_gallery_enabled', '0', 'general', 'text', '2026-09-02 20:13:38', '2026-09-02 20:13:38');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('138', 'sidebar_gallery_title', 'Check Instagram Post', 'general', 'text', '2026-09-02 20:13:38', '2026-09-02 20:13:38');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('139', 'services_why_choose_subtitle', 'Why Choose Us', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('140', 'services_why_choose_title', 'Biomedical Excellence & Reliability', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:30:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('141', 'services_why_1_icon', 'flaticon-microscope', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('142', 'services_why_1_title', 'High Quality Equipment', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('143', 'services_why_1_desc', 'ISO 13485 and CE certified biomedical systems built for accuracy.', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('144', 'services_why_2_icon', 'flaticon-thinking', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('145', 'services_why_2_title', 'Rapid Field Response', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('146', 'services_why_2_desc', 'Immediate calibration & servicing for critical care hospital wards.', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('147', 'services_why_3_icon', 'flaticon-24-hours-1', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('148', 'services_why_3_title', '24/7 Biomedical Support', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('149', 'services_why_3_desc', 'Dedicated clinical support desk with round-the-clock availability.', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('150', 'services_why_4_icon', 'flaticon-team', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('151', 'services_why_4_title', 'Certified Expert Team', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('152', 'services_why_4_desc', 'Factory-certified biomedical engineers and hospital project managers.', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('153', 'services_why_banner_text', 'Healthcare Infrastructure & Turnkey Hospital Engineering :', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('154', 'services_why_banner_btn_text', 'Request Consultation', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('155', 'services_why_banner_btn_url', '/contact', 'general', 'text', '2026-09-03 07:29:02', '2026-09-03 07:29:02');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('156', 'about_banner_title', 'About us', 'general', 'text', '2026-09-03 07:41:50', '2026-09-03 07:41:50');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('157', 'about_banner_subtitle', 'Innotech : About Us', 'general', 'text', '2026-09-03 07:41:50', '2026-09-03 07:41:50');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('158', 'gallery_banner_title', 'Work Gallery', 'general', 'text', '2026-09-03 07:41:50', '2026-09-03 07:41:50');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('159', 'gallery_banner_subtitle', 'Innotech : Gallery', 'general', 'text', '2026-09-03 07:41:50', '2026-09-03 07:41:50');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('160', 'contact_banner_title', 'Contact us', 'general', 'text', '2026-09-03 07:41:50', '2026-09-03 07:41:50');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('161', 'contact_banner_subtitle', 'Innotech : Contact', 'general', 'text', '2026-09-03 07:41:50', '2026-09-03 07:41:50');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('162', 'blog_banner_title', 'Blog & Medical Research', 'general', 'text', '2026-09-03 07:41:50', '2026-09-03 07:41:50');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('163', 'blog_banner_subtitle', 'Innotech : Blog Details', 'general', 'text', '2026-09-03 07:41:50', '2026-09-03 07:41:50');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('164', 'tab', 'seo', 'general', 'text', '2026-09-03 07:41:50', '2026-09-03 07:51:31');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('165', 'seo_meta_title', 'INNOTECH MEDICAL PVT LTD | Advanced Healthcare & Biomedical Solutions', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('166', 'seo_title_separator', '|', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('167', 'seo_meta_tagline', 'Innovating Healthcare With Advanced Technologies', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('168', 'seo_meta_description', 'Leading provider of hospital medical equipment, clinical laboratory analyzers, ICU monitoring systems, and accredited biomedical calibration services in Pakistan.', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('169', 'seo_meta_keywords', 'medical equipment, biomedical engineering, ICU monitors, laboratory calibration, hospital supply, surgical instruments, Pakistan healthcare, ISO 13485, CE certified', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('170', 'seo_meta_robots', 'index, follow', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('171', 'seo_og_image', 'assets/img/logo/logo.png', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('172', 'seo_twitter_card', 'summary_large_image', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('173', 'seo_twitter_handle', '@InnotechMedical', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('174', 'seo_google_verification', NULL, 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:51:31');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('175', 'seo_google_analytics', NULL, 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:51:31');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('176', 'seo_google_tag_manager', NULL, 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:51:31');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('177', 'seo_header_scripts', NULL, 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:51:31');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('178', 'seo_footer_scripts', NULL, 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:51:31');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('179', 'seo_schema_type', 'MedicalBusiness', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('180', 'seo_schema_specialty', 'Biomedical Engineering & Medical Equipment Supply', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('181', 'seo_org_address', 'Lahore, Punjab, Pakistan', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('182', 'seo_org_phone', '+92 331 6699992', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('183', 'seo_org_email', 'info@innotechmedical.pk', 'general', 'text', '2026-09-03 07:44:44', '2026-09-03 07:44:44');


-- --------------------------------------------------------
-- Table structure for `sliders`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sliders`;
CREATE TABLE `sliders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `badge` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btn_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Discover More',
  `btn_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '/services',
  `btn_secondary_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Contact Us',
  `btn_secondary_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '/contact',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `sliders`
INSERT INTO `sliders` (`id`, `badge`, `title`, `subtitle`, `btn_text`, `btn_link`, `btn_secondary_text`, `btn_secondary_link`, `image`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'WELCOME TO INNOTECH MEDICAL', 'Advanced Medical & Diagnostic Technologies', 'Empowering hospitals, diagnostic centers, and healthcare professionals with world-class clinical solutions.', 'Explore Our Services', '#services-section', 'Get In Touch', '#contact-section', 'assets/img/slider/slider-bg-1.jpg', '1', '1', '2026-08-31 19:06:08', '2026-08-31 19:06:08');
INSERT INTO `sliders` (`id`, `badge`, `title`, `subtitle`, `btn_text`, `btn_link`, `btn_secondary_text`, `btn_secondary_link`, `image`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'CERTIFIED HEALTHCARE SOLUTIONS', 'Precision ICU, Surgical & Lab Equipment', 'Reliable turnkey installations, 24/7 calibration, maintenance, and clinical support across the country.', 'View Solutions', '#services-section', 'Contact Helpdesk', '#contact-section', 'assets/img/slider/slider-bg-2.jpg', '2', '1', '2026-08-31 19:06:08', '2026-08-31 19:06:08');


-- --------------------------------------------------------
-- Table structure for `team_members`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `team_members`;
CREATE TABLE `team_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expertise` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personal_experience` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `education` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `awards` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pinterest_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skype_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `team_members`
INSERT INTO `team_members` (`id`, `name`, `slug`, `designation`, `expertise`, `experience`, `email`, `phone`, `bio`, `personal_experience`, `skills`, `education`, `awards`, `image`, `youtube_url`, `twitter_url`, `facebook_url`, `instagram_url`, `pinterest_url`, `skype_url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Cameron Williamson', 'cameron-williamson', 'Chief Biomedical Engineer', 'Diagnostic & Radiology Systems', '15+ Years', 'cameron.williamson@innotechmedical.com', '+92 331 6699992', 'Providing insight-driven biomedical transformations, turnkey diagnostic installations, and clinical laboratory calibrations across tertiary hospitals.', 'Over the past 15 years, Cameron has spearheaded the installation and maintenance of cutting-edge clinical radiology systems, MRI suites, and diagnostic equipment in major healthcare centers throughout Pakistan and the region.\n\nHis expertise encompasses stringent ISO quality compliance, predictive maintenance protocols, and ensuring 99.8% equipment uptime in high-volume intensive care units.', 'Diagnostic Imaging Calibration\nBiomedical Equipment Integration\nRadiation Safety Protocols\nTurnkey Hospital Equipment Planning\nICU & CCU Life Support Systems\nPreventive Maintenance Schedules', 'Ph.D. in Biomedical Engineering, University of Technology\nM.Sc. in Clinical Medical Instrumentation\nCertified Clinical Engineer (CCE) Certification\nAdvanced Diagnostic Systems Specialist Diploma', 'Healthcare Engineering Excellence Award 2023\nNational Biomedical Innovator of the Year\nBest Clinical Equipment Project Delivery\nCertified Quality Master in Healthcare Logistics', 'assets/img/team/team-thumb-05.png', NULL, 'https://twitter.com', 'https://facebook.com', 'https://instagram.com', 'https://linkedin.com', NULL, '1', '1', '2026-08-31 19:29:08', '2026-09-01 19:47:51');
INSERT INTO `team_members` (`id`, `name`, `slug`, `designation`, `expertise`, `experience`, `email`, `phone`, `bio`, `personal_experience`, `skills`, `education`, `awards`, `image`, `youtube_url`, `twitter_url`, `facebook_url`, `instagram_url`, `pinterest_url`, `skype_url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Savannah Nguyen', 'savannah-nguyen', 'Senior Critical Care Specialist', 'ICU & Anesthesia Systems', '12+ Years', 'savannah.nguyen@innotechmedical.com', '+92 331 6699992', 'Specializing in anesthesia delivery workstations, ventilator integration, and real-time hemodynamic monitoring networks.', 'Savannah brings comprehensive experience in mission-critical medical infrastructure. She leads our technical evaluation team for operating theater setups, ensuring zero downtime for critical life-support apparatus.\n\nHer collaborative work with surgical departments has set new benchmarks in patient safety and medical precision.', 'Anesthesia Workstation Setup\nVentilator Calibration & Safety\nCardiac Monitoring Networks\nSurgical Theater Ergonomics\nBiomedical Gas Systems', 'M.Sc. in Biomedical Instrumentation\nB.E. in Electronics & Medical Engineering\nFellow of Clinical Engineering Academy', 'Critical Care Innovation Award\nExcellence in Medical Device Training\nOutstanding Service in Emergency Healthcare', 'assets/img/team/team-thumb-06.png', NULL, 'https://twitter.com', 'https://facebook.com', 'https://instagram.com', 'https://linkedin.com', NULL, '2', '1', '2026-08-31 19:29:08', '2026-09-01 19:47:51');
INSERT INTO `team_members` (`id`, `name`, `slug`, `designation`, `expertise`, `experience`, `email`, `phone`, `bio`, `personal_experience`, `skills`, `education`, `awards`, `image`, `youtube_url`, `twitter_url`, `facebook_url`, `instagram_url`, `pinterest_url`, `skype_url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Darlene Robertson', 'darlene-robertson', 'Clinical Pathology Lead', 'Biochemical Analyzers & PCR Labs', '10+ Years', 'darlene.robertson@innotechmedical.com', '+92 331 6699992', 'Expert in automated chemistry analyzers, molecular diagnostic systems, and laboratory automation workflows.', 'Darlene has overseen the deployment of state-of-the-art biochemistry and hematology analyzers across top reference laboratories. Her deep knowledge of assay precision and quality assurance protocols ensures reliable laboratory outcomes.', 'Clinical Pathology Automation\nPCR & Molecular Lab Design\nReagent Optimization Protocols\nLaboratory Information System (LIS) Integration\nGood Laboratory Practice (GLP) Auditing', 'M.S. in Clinical Laboratory Science\nB.Sc. in Medical Technology\nCertified Laboratory Quality Specialist', 'Laboratory Quality Excellence Award\nInnovator in Molecular Diagnostics\nHealthcare Standards Recognition', 'assets/img/team/team-thumb-07.png', NULL, 'https://twitter.com', 'https://facebook.com', 'https://instagram.com', 'https://linkedin.com', NULL, '3', '1', '2026-08-31 19:29:08', '2026-09-01 19:47:51');
INSERT INTO `team_members` (`id`, `name`, `slug`, `designation`, `expertise`, `experience`, `email`, `phone`, `bio`, `personal_experience`, `skills`, `education`, `awards`, `image`, `youtube_url`, `twitter_url`, `facebook_url`, `instagram_url`, `pinterest_url`, `skype_url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('4', 'Jhon Methweu', 'jhon-methweu', 'Radiologist Specialist', NULL, NULL, NULL, NULL, 'Providing insight-driven transformation to investment banks, wealth and asset mana, exchanges, Finance', NULL, NULL, NULL, NULL, 'assets/img/team/team-thumb-10.jpg', NULL, 'https://twitter.com', 'https://facebook.com', 'https://instagram.com', 'https://linkedin.com', NULL, '4', '1', '2026-08-31 19:29:08', '2026-09-01 19:47:51');
INSERT INTO `team_members` (`id`, `name`, `slug`, `designation`, `expertise`, `experience`, `email`, `phone`, `bio`, `personal_experience`, `skills`, `education`, `awards`, `image`, `youtube_url`, `twitter_url`, `facebook_url`, `instagram_url`, `pinterest_url`, `skype_url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('5', 'Marvin McKinney', 'marvin-mckinney', 'Lead Medical Solutions Consultant', 'Healthcare Infrastructure & Tenders', '14+ Years', 'marvin.mckinney@innotechmedical.com', '+92 331 6699992', 'Advising hospital boards, government healthcare authorities, and private diagnostic centers on comprehensive equipment procurement.', 'Marvin acts as the bridge between clinical requirements and engineering execution. He has consulted on over 100 major hospital expansion projects across Pakistan.', 'Hospital Turnkey Project Planning\nHealthcare Logistics & Sourcing\nMedical Device Regulation\nTechnical Tender Management\nClient Support & Post-Install Audits', 'MBA in Healthcare Management\nB.S. in Biomedical Technology\nCertified Project Management Professional (PMP)', 'Healthcare Consultant of the Year\nProject Leadership Excellence Award\nStrategic Procurement Master', 'assets/img/team/team-thumb-08.png', NULL, 'https://twitter.com', 'https://facebook.com', 'https://instagram.com', 'https://linkedin.com', NULL, '4', '1', '2026-09-01 19:16:50', '2026-09-01 19:47:51');
INSERT INTO `team_members` (`id`, `name`, `slug`, `designation`, `expertise`, `experience`, `email`, `phone`, `bio`, `personal_experience`, `skills`, `education`, `awards`, `image`, `youtube_url`, `twitter_url`, `facebook_url`, `instagram_url`, `pinterest_url`, `skype_url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('6', 'Muhammad Usama hameed', 'muhammad-usama-hameed', 'Head Founder', 'systems', '17', 'creativecoder93@gmail.com', '+923472663843', 'hello world', 'hello world 2', 'hello world 3', 'hello world 4', 'hello world 5', 'uploads/team/1788376767_230_chatgpt-image-aug-16-2026-03-42-09-pm.png', NULL, 'https://www.facebook.com/', 'https://www.facebook.com/', 'https://www.facebook.com/', 'https://www.facebook.com/', NULL, '1', '1', '2026-09-02 19:18:31', '2026-09-02 19:19:27');


-- --------------------------------------------------------
-- Table structure for `testimonials`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hospital` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `testimonials`
INSERT INTO `testimonials` (`id`, `name`, `designation`, `hospital`, `content`, `rating`, `avatar`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Dr. Farhan Siddiqui', 'Head of Cardiology & ICU', 'National Medical Complex', 'Innotech Medical supplied and configured our entire critical care wing. Their technical team demonstrated exceptional precision, responsiveness, and prompt after-sales support.', '5', 'assets/img/icon/testi-ava-01.jpg', '1', '1', '2026-08-31 19:06:08', '2026-09-01 20:05:03');
INSERT INTO `testimonials` (`id`, `name`, `designation`, `hospital`, `content`, `rating`, `avatar`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Dr. Ayesha Tariq', 'Director of Pathology', 'City Diagnostic Institute', 'The automated chemistry and hematology systems installed by Innotech have drastically reduced our turnaround times while maintaining unmatched diagnostic accuracy.', '5', 'assets/img/icon/testi-ava-02.jpg', '2', '1', '2026-08-31 19:06:08', '2026-09-01 20:05:03');
INSERT INTO `testimonials` (`id`, `name`, `designation`, `hospital`, `content`, `rating`, `avatar`, `order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Engr. Kamran Raza', 'Chief Biomedical Officer', 'Apex Healthcare Network', 'Their 24/7 biomedical maintenance response is the best in the industry. Equipment downtime has reduced to near zero across our regional facilities.', '5', 'assets/img/icon/testi-ava-03.jpg', '3', '1', '2026-08-31 19:06:08', '2026-09-01 20:05:03');


-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Innotech Admin', 'admin@innotech.com', '2026-08-31 19:06:08', '$2y$12$GWee4usrQDgNPOmth7dsL.FqzcP7bevd.PR1Ksk.pn2ElCxlC9NqO', 'IdI1NNmFe6bJVxGe8MXiN1KbrJhIq11XM3dGjnfjnVp1kps3lrhvOSji6OZI', '2026-08-31 19:06:08', '2026-08-31 19:06:08');


-- --------------------------------------------------------
-- Table structure for `visitor_logs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `visitor_logs`;
CREATE TABLE `visitor_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visit_date` date NOT NULL,
  `device_type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Desktop',
  `browser` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_seconds` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitor_logs_ip_address_index` (`ip_address`),
  KEY `visitor_logs_session_id_index` (`session_id`),
  KEY `visitor_logs_visit_date_index` (`visit_date`),
  KEY `visitor_logs_page_url_index` (`page_url`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `visitor_logs`
INSERT INTO `visitor_logs` (`id`, `ip_address`, `session_id`, `visit_date`, `device_type`, `browser`, `platform`, `page_url`, `page_title`, `duration_seconds`, `created_at`, `updated_at`) VALUES ('1', '::1', 'ruLgweisa9mB2ljC28iJpFDJis2auj3QzOaR6pvs', '2026-09-03', 'Desktop', 'Chrome', 'Windows', '/innotech/contact', 'Contact Us | 24/7 Clinical Equipment Support - INNOTECH MEDICAL PVT LTD', '0', '2026-09-03 08:32:09', '2026-09-03 08:32:09');
INSERT INTO `visitor_logs` (`id`, `ip_address`, `session_id`, `visit_date`, `device_type`, `browser`, `platform`, `page_url`, `page_title`, `duration_seconds`, `created_at`, `updated_at`) VALUES ('2', '::1', 'ruLgweisa9mB2ljC28iJpFDJis2auj3QzOaR6pvs', '2026-09-03', 'Desktop', 'Chrome', 'Windows', '/innotech/', 'INNOTECH MEDICAL PVT LTD', '0', '2026-09-03 08:32:15', '2026-09-03 08:32:15');
INSERT INTO `visitor_logs` (`id`, `ip_address`, `session_id`, `visit_date`, `device_type`, `browser`, `platform`, `page_url`, `page_title`, `duration_seconds`, `created_at`, `updated_at`) VALUES ('3', '::1', 'L97By40ayFZOpzZKx4ykivgwSsquixptdBvl3h18', '2026-09-03', 'Desktop', 'Chrome', 'Windows', '/innotech/public/products', 'Medical Products & Equipment | INNOTECH MEDICAL PVT LTD', '0', '2026-09-03 19:46:38', '2026-09-03 19:46:38');
INSERT INTO `visitor_logs` (`id`, `ip_address`, `session_id`, `visit_date`, `device_type`, `browser`, `platform`, `page_url`, `page_title`, `duration_seconds`, `created_at`, `updated_at`) VALUES ('4', '::1', '3tOhiQfHkJhezWmBWKUuodbn7MTnMnLy4irJx3yn', '2026-09-03', 'Desktop', 'Chrome', 'Windows', '/innotech/products', 'Medical Products & Equipment | INNOTECH MEDICAL PVT LTD', '0', '2026-09-03 19:47:21', '2026-09-03 19:47:21');
INSERT INTO `visitor_logs` (`id`, `ip_address`, `session_id`, `visit_date`, `device_type`, `browser`, `platform`, `page_url`, `page_title`, `duration_seconds`, `created_at`, `updated_at`) VALUES ('5', '::1', '3tOhiQfHkJhezWmBWKUuodbn7MTnMnLy4irJx3yn', '2026-09-03', 'Desktop', 'Chrome', 'Windows', '/innotech/products/elektro-mag-m-308-infant-radiant-warmer-unit-tft', 'Elektro-mag M 308 Infant Radiant Warmer Unit (TFT) | Elektro-mag - INNOTECH MEDICAL PVT LTD', '0', '2026-09-03 19:48:01', '2026-09-03 19:48:01');
INSERT INTO `visitor_logs` (`id`, `ip_address`, `session_id`, `visit_date`, `device_type`, `browser`, `platform`, `page_url`, `page_title`, `duration_seconds`, `created_at`, `updated_at`) VALUES ('6', '::1', 'L97By40ayFZOpzZKx4ykivgwSsquixptdBvl3h18', '2026-09-03', 'Desktop', 'Chrome', 'Windows', '/innotech/public/products/elektro-mag-m-308-infant-radiant-warmer-unit-tft', 'Elektro-mag M 308 Infant Radiant Warmer Unit (TFT) | Elektro-mag - INNOTECH MEDICAL PVT LTD', '0', '2026-09-03 19:50:53', '2026-09-03 19:50:53');
INSERT INTO `visitor_logs` (`id`, `ip_address`, `session_id`, `visit_date`, `device_type`, `browser`, `platform`, `page_url`, `page_title`, `duration_seconds`, `created_at`, `updated_at`) VALUES ('7', '::1', '3tOhiQfHkJhezWmBWKUuodbn7MTnMnLy4irJx3yn', '2026-09-03', 'Desktop', 'Chrome', 'Windows', '/innotech/about', 'About Us | Medical Engineering Excellence - INNOTECH MEDICAL PVT LTD', '0', '2026-09-03 19:55:25', '2026-09-03 19:55:25');
INSERT INTO `visitor_logs` (`id`, `ip_address`, `session_id`, `visit_date`, `device_type`, `browser`, `platform`, `page_url`, `page_title`, `duration_seconds`, `created_at`, `updated_at`) VALUES ('8', '::1', '3tOhiQfHkJhezWmBWKUuodbn7MTnMnLy4irJx3yn', '2026-09-03', 'Desktop', 'Chrome', 'Windows', '/innotech/blog', 'Blog & Medical Research | Healthcare Articles - INNOTECH MEDICAL PVT LTD', '0', '2026-09-03 19:56:41', '2026-09-03 19:56:41');
INSERT INTO `visitor_logs` (`id`, `ip_address`, `session_id`, `visit_date`, `device_type`, `browser`, `platform`, `page_url`, `page_title`, `duration_seconds`, `created_at`, `updated_at`) VALUES ('9', '::1', '3tOhiQfHkJhezWmBWKUuodbn7MTnMnLy4irJx3yn', '2026-09-03', 'Desktop', 'Chrome', 'Windows', '/innotech/blog/transforming-critical-care-modern-advancements-in-icu-monitoring', 'Transforming Critical Care: Modern Advancements in ICU Monitoring | Clinical Research & Insights - INNOTECH MEDICAL PVT LTD', '0', '2026-09-03 19:56:47', '2026-09-03 19:56:47');
INSERT INTO `visitor_logs` (`id`, `ip_address`, `session_id`, `visit_date`, `device_type`, `browser`, `platform`, `page_url`, `page_title`, `duration_seconds`, `created_at`, `updated_at`) VALUES ('10', '::1', '3tOhiQfHkJhezWmBWKUuodbn7MTnMnLy4irJx3yn', '2026-09-03', 'Desktop', 'Chrome', 'Windows', '/innotech/products/greater-scooping-at-ease-endoscopic-system', 'GREATER SCOOPING AT EASE Endoscopic System | Lynmou Surgical - INNOTECH MEDICAL PVT LTD', '0', '2026-09-03 20:16:19', '2026-09-03 20:16:19');

SET FOREIGN_KEY_CHECKS=1;
