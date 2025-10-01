-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2025 at 02:32 PM
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
-- Database: `news_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'تكنولوجيا'),
(2, 'رياضة'),
(3, 'سياسي');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `category_id` int(11) NOT NULL,
  `details` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `category_id`, `details`, `image_path`, `user_id`, `is_deleted`) VALUES
(1, 'إطلاق نسخة جديدة من تطبيق واتساب', 1, 'أعلنت شركة واتساب عن تحديث جديد للتطبيق يضيف ميزة تسجيل الدخول على أكثر من جهاز في نفس الوقت، مع تحسينات في سرعة الأداء وحماية الخصوصية. التحديث سيصل تدريجيًا لجميع المستخدمين حول العالم خلال الأيام القادمة.', NULL, 1, 0),
(2, 'إطلاق نسخة جديدة من تطبيق واتساب', 1, 'أعلنت شركة واتساب عن تحديث جديد للتطبيق يضيف ميزة تسجيل الدخول على أكثر من جهاز في نفس الوقت، مع تحسينات في سرعة الأداء وحماية الخصوصية. التحديث سيصل تدريجيًا لجميع المستخدمين حول العالم خلال الأيام القادمة.', NULL, 1, 1),
(3, 'فوز برشلونة على ريال مدريد في مباراة ودية', 2, 'فاز فريق برشلونة على ريال مدريد بنتيجة 2-1 في مباراة ودية أقيمت مساء أمس. وقدّم اللاعبون أداءً جيدًا وسط حضور جماهيري كبير.', NULL, 1, 0),
(4, 'خبر للتجريب تعديل', 1, 'تجريب لحذف الخبر', NULL, 1, 1),
(5, 'الإعلان عن وقف الحرب في غزة', 3, 'أُعلن مساء اليوم عن التوصل إلى اتفاق يقضي بـ وقف الحرب في قطاع غزة اعتبارًا من منتصف الليل. ويشمل الاتفاق فتح المعابر الحدودية بشكل تدريجي، ليس فقط لإدخال المساعدات الإنسانية والمواد الطبية، بل أيضًا لتمكين حركة السفر والتنقل من وإلى القطاع.\r\n\r\nوأكدت مصادر دبلوماسية أن هذا الاتفاق جاء بعد وساطات إقليمية ودولية مكثفة، ويُتوقع أن يسهم في تهدئة الأوضاع وتهيئة الأجواء لمباحثات سياسية خلال الأيام المقبلة، إضافة إلى تخفيف المعاناة الإنسانية عبر تسهيل حركة المساعدات والمسافرين.', '/web2-practical/news_app/uploads/img_1759318692_4155.jpg', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`) VALUES
(1, 'sana', 'sana@gmail.com', '$2y$10$LihlF7Dq1xILALJLuMQ/sezQX4xkh6z7yUVsU3OVPeAN.kXJqQQ.C'),
(2, 'ali', 'ali@gmail.com', '$2y$10$FPpVeuDJImseY111ZmECLu1reL4GBMjA5UbDeFTIFGsuKJg7VqYdK'),
(3, 'omar', 'omar@gmail.com', '$2y$10$K8jp/JMHNiReZli8.ssNT.kuUlv7fb2pZgg3bskHQcXDPt5Y6mSxu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_news_category` (`category_id`),
  ADD KEY `fk_news_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_news_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
