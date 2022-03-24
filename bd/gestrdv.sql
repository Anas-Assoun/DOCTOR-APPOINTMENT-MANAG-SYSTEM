-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2022 at 08:57 AM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestrdv`
--

-- --------------------------------------------------------

--
-- Table structure for table `table_admin`
--

CREATE TABLE `table_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `admin_password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `admin_nom` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `hopital_nom` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `hopital_addresse` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `hopital_contact_no` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `hopital_logo` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `table_admin`
--

INSERT INTO `table_admin` (`admin_id`, `admin_email`, `admin_password`, `admin_nom`, `hopital_nom`, `hopital_addresse`, `hopital_contact_no`, `hopital_logo`) VALUES
(1, 'admin1@gmail.com', 'password', 'ADMIN', 'Hopital CHIFAA', '115, bd Zerktouni, Casablanca', '+212541287410', '../images/hopital_logo.png'),
(2, 'johnsmith@gmail.com', 'password', 'ADMIN', 'Hopital ACHIFAA', 'hay edakhla khouribga', '212542515236', '../images/94129982.png');

-- --------------------------------------------------------

--
-- Table structure for table `table_horaire`
--

CREATE TABLE `table_horaire` (
  `horaire_id` int(11) NOT NULL,
  `medecin_id` int(11) NOT NULL,
  `horaire_date` date NOT NULL,
  `horaire_jour` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') COLLATE utf8_unicode_ci NOT NULL,
  `horaire_debut` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `horaire_fin` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `duree_consultation` int(5) NOT NULL,
  `horaire_status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `table_horaire`
--

INSERT INTO `table_horaire` (`horaire_id`, `medecin_id`, `horaire_date`, `horaire_jour`, `horaire_debut`, `horaire_fin`, `duree_consultation`, `horaire_status`) VALUES
(2, 1, '2021-02-19', '', '09:00', '14:00', 15, 'Active'),
(3, 2, '2021-02-19', '', '09:00', '12:00', 15, 'Active'),
(4, 5, '2021-02-19', '', '10:00', '14:00', 10, 'Active'),
(5, 3, '2021-02-19', '', '13:00', '17:00', 20, 'Active'),
(6, 4, '2021-02-19', '', '15:00', '18:00', 5, 'Active'),
(7, 5, '2021-02-22', '', '18:00', '20:00', 10, 'Active'),
(8, 2, '2021-02-24', '', '09:30', '12:30', 10, 'Active'),
(9, 5, '2021-02-24', '', '11:00', '15:00', 10, 'Active'),
(10, 1, '2021-02-24', '', '12:00', '15:00', 10, 'Active'),
(11, 3, '2021-02-24', '', '14:00', '17:00', 15, 'Active'),
(12, 4, '2021-02-24', '', '16:00', '20:00', 10, 'Active'),
(13, 6, '2021-02-24', '', '15:30', '18:30', 10, 'Active'),
(14, 6, '2021-02-25', '', '10:00', '13:30', 10, 'Active'),
(15, 5, '2021-06-19', '', '07:48', '10:48', 20, 'Active'),
(16, 6, '2021-06-12', '', '09:43', '19:43', 20, 'Active'),
(17, 2, '2021-06-16', '', '00:50', '18:50', 30, 'Active'),
(18, 2, '2021-06-16', 'Wednesday', '07:00', '19:00', 15, 'Active'),
(19, 6, '2021-06-19', 'Saturday', '05:00', '15:00', 10, 'Active'),
(20, 3, '2021-06-11', 'Friday', '12:00', '15:00', 15, 'Active'),
(21, 4, '2021-07-05', 'Monday', '13:00', '20:00', 30, 'Active'),
(22, 5, '2021-06-11', 'Friday', '03:07', '08:06', 35, 'Active'),
(23, 10, '2021-06-12', 'Saturday', '08:00', '16:00', 20, 'Active'),
(24, 14, '2021-06-13', 'Sunday', '08:00', '17:00', 45, 'Active'),
(25, 9, '2021-06-14', 'Monday', '08:00', '18:00', 25, 'Active'),
(27, 15, '2021-06-12', 'Saturday', '09:00', '17:00', 5, 'Active'),
(28, 12, '2021-06-19', 'Saturday', '06:00', '17:30', 25, 'Active'),
(29, 8, '2021-06-16', 'Wednesday', '07:00', '16:20', 30, 'Active'),
(30, 12, '2021-06-13', 'Sunday', '09:00', '18:00', 20, 'Active'),
(31, 12, '2021-06-14', 'Monday', '08:00', '17:00', 10, 'Active'),
(32, 12, '2021-06-15', 'Tuesday', '07:00', '16:00', 15, 'Active'),
(34, 12, '2021-06-18', 'Friday', '09:30', '17:00', 20, 'Active'),
(35, 8, '2021-07-08', 'Thursday', '01:49', '14:49', 25, 'Active'),
(36, 12, '2021-07-14', 'Wednesday', '08:15', '18:10', 25, 'Active'),
(37, 8, '2021-07-17', 'Saturday', '06:00', '18:10', 20, 'Active'),
(38, 8, '2021-07-09', 'Friday', '05:19', '17:30', 30, 'Active'),
(39, 12, '2021-07-11', 'Sunday', '07:15', '18:00', 30, 'Active'),
(40, 9, '2021-07-13', 'Tuesday', '05:19', '10:19', 20, 'Active'),
(41, 10, '2021-07-10', 'Saturday', '09:20', '17:05', 5, 'Inactive'),
(43, 13, '2021-07-14', 'Wednesday', '05:20', '18:20', 30, 'Active'),
(44, 13, '2021-07-16', 'Friday', '06:52', '15:52', 20, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `table_medecin`
--

CREATE TABLE `table_medecin` (
  `medecin_id` int(11) NOT NULL,
  `medecin_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `medecin_password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `medecin_nom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `medecin_image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `medecin_tel` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `medecin_addresse` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `medecin_dateNaiss` date NOT NULL,
  `medecin_experience` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `medecin_specialite` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `medecin_status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL,
  `medecin_date_ajout` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `table_medecin`
--

INSERT INTO `table_medecin` (`medecin_id`, `medecin_email`, `medecin_password`, `medecin_nom`, `medecin_image`, `medecin_tel`, `medecin_addresse`, `medecin_dateNaiss`, `medecin_experience`, `medecin_specialite`, `medecin_status`, `medecin_date_ajout`) VALUES
(8, 'mohamedchahbi@gmail.com', 'password', 'Mohamed Chahbi', '../images/1399196650.jpg', '0658969632', 'NR 58 Hay El Hassani, Khouribga', '1959-04-16', '20 ans', 'La pédiatrie', 'Active', '2021-06-11 03:39:32'),
(9, 'samiradoukali@gmail.com', 'password', 'Samira DOUKALI', '../images/906623257.jpg', '0685969632', 'Rue el qods, casablanca', '1966-09-08', '18 ans', 'La podologie', 'Active', '2021-06-11 15:51:43'),
(10, 'adilelkhou@gmail.com', 'password', 'Adil ELKHOU', '../images/1432128681.jpg', '0658969632', 'Boulevard Zerktouni , Casablanca', '1981-06-01', '12 ans', 'La radiothérapie', 'Active', '2021-06-11 15:51:43'),
(11, 'issambari@gmail.com', 'password', 'Issam BARI', '../images/1633630940.jpg', '0632129714', '25 Rue marrakech, KHouribga', '1951-08-15', '25 ans', 'La radiologie', 'Active', '2021-06-11 15:55:06'),
(12, 'aminaelkhayat@gmail.com', 'password', 'Amina EL KHAYAT', '../images/513709281.jpg', '0632129258', 'Hay nouara, Berrechid', '1971-08-01', '15 ans', 'La médecine générale', 'Active', '2021-06-11 15:55:06'),
(13, 'ilyasalaoui@gmail.com', 'password', 'Ilyas ALAOUI', '../images/1913054130.jpg', '0661589878', '36 Rue agdal, Rabat', '1949-03-29', '17 ans', 'La chirurgie cardiaque', 'Active', '2021-06-11 16:13:00'),
(14, 'mehdibenatia@gmail.com', 'password', 'Mehdi BENATIA', '../images/258790273.jpg', '0658965863', '45 rue massira, Tanger', '1958-08-02', '30 ans', 'La médecine d\'urgence', 'Active', '2021-06-11 16:15:04');

-- --------------------------------------------------------

--
-- Table structure for table `table_patient`
--

CREATE TABLE `table_patient` (
  `patient_id` int(11) NOT NULL,
  `patient_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `patient_password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `patient_prenom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `patient_nom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `patient_dateNaiss` date NOT NULL,
  `patient_sexe` enum('Homme','Femme') COLLATE utf8_unicode_ci NOT NULL,
  `patient_addresse` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `patient_tel` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `patient_situation_fam` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `patient_date_ajout` datetime NOT NULL,
  `patient_verification_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email_verification` enum('Non','Oui') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `table_patient`
--

INSERT INTO `table_patient` (`patient_id`, `patient_email`, `patient_password`, `patient_prenom`, `patient_nom`, `patient_dateNaiss`, `patient_sexe`, `patient_addresse`, `patient_tel`, `patient_situation_fam`, `patient_date_ajout`, `patient_verification_code`, `email_verification`) VALUES
(7, 'aminetazi@gmail.com', 'password', 'Amine', 'Tazi', '1986-02-06', 'Homme', 'Rue mouahidine Sbata, Casablanca', '0658569625', 'Marié', '2021-06-11 03:26:56', 'ookoiohyyeehe25688556', 'Oui'),
(9, 'youssefchamekh@gmail.com', 'password', 'Youssef', 'CHAMEKH', '1999-08-01', 'Homme', '14 Hay el hassani, KHOURIBGA', '0658989874', 'Marié', '2021-06-11 16:19:55', 'ooijhyutuytftyuitftuy', 'Oui'),
(10, 'othmaneteffahi@gmai.com', 'password', 'Othmane', 'TEFFAHI', '2000-05-02', 'Homme', '25 rue massira, Beni mellal', '0687471240', 'Célibataire', '2021-06-11 16:28:57', 'dthgththdru2546666hrth', 'Oui'),
(12, 'safaasoufiani@gmail.com', 'password', 'Safaa', 'SOUFIANI', '1998-06-07', 'Femme', 'Boulevard Saada, Tetouan', '0689897415', 'Marié', '2021-06-11 16:33:22', 'yjhtyjytujyutjyu354654yj', 'Oui'),
(13, 'redaachiq@gmail.com', 'password', 'Reda', 'ACHIQ', '1990-08-03', 'Homme', 'Boulevard ANOUARE, Rabat', '0689898741', 'Marié', '2021-06-11 16:33:22', 'yjhtyjytujyutjyhuku4654yj', 'Oui'),
(14, 'yassineassoun@gmail.com', 'password', 'Yassine', 'ASSOUN', '1996-08-07', 'Homme', 'Rue Zohour, Casablanca', '0689896978', 'célibataire', '2021-06-11 16:33:22', 'yjhtyjytujyutjyu354654yj', 'Oui'),
(16, 'hamiddaoudi@gmail.com', '123456', 'Hamid', 'DAOUDI', '1980-07-10', 'Homme', 'hyhyhh(h', '0658987841', 'Célibataire', '2021-07-09 16:26:56', 'fa6250a4b7e672dc5a053bfb01619161', 'Oui');

-- --------------------------------------------------------

--
-- Table structure for table `table_rdv`
--

CREATE TABLE `table_rdv` (
  `rdv_id` int(11) NOT NULL,
  `medecin_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `horaire_id` int(11) NOT NULL,
  `num_rdv` int(11) NOT NULL,
  `motif_rdv` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `heure_rdv` time NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `patient_presence` enum('Non','Oui') COLLATE utf8_unicode_ci NOT NULL,
  `medecin_comment` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `table_rdv`
--

INSERT INTO `table_rdv` (`rdv_id`, `medecin_id`, `patient_id`, `horaire_id`, `num_rdv`, `motif_rdv`, `heure_rdv`, `status`, `patient_presence`, `medecin_comment`) VALUES
(3, 1, 3, 2, 1000, 'Pain in Stomach', '09:00:00', 'Annulé', 'Non', ''),
(4, 1, 3, 2, 1001, 'Paint in stomach', '09:00:00', 'Réservé', 'Non', ''),
(5, 1, 4, 2, 1002, 'For Delivery', '09:30:00', 'Terminé', 'Oui', 'She gave birth to boy baby.'),
(6, 5, 3, 7, 1003, 'Fever and cold.', '18:00:00', 'En cours', 'Oui', ''),
(7, 6, 5, 13, 1004, 'Pain in Stomach.', '15:30:00', 'Terminé', 'Oui', 'Acidity Problem. '),
(8, 10, 9, 21, 1005, 'Mal a la tete', '09:30:00', 'Réservé', 'Non', ''),
(9, 11, 11, 22, 1006, 'Mal a la tete', '09:30:00', 'Réservé', 'Non', ''),
(10, 14, 8, 21, 1007, 'Mal a la tete', '10:00:00', 'Réservé', 'Non', ''),
(11, 9, 9, 22, 1008, 'Mal a la tete', '13:30:00', 'Annulé', 'Non', ''),
(12, 8, 9, 20, 1009, 'Mal a la tete', '11:30:00', 'Réservé', 'Non', ''),
(13, 14, 9, 0, 1010, 'TEST MOTIF', '10:30:00', 'Réservé', 'Non', ''),
(14, 14, 9, 23, 1010, 'TEST MOTIF', '10:30:00', 'Réservé', 'Non', ''),
(15, 14, 11, 23, 1011, 'TEST ', '12:00:00', 'Annulé', 'Non', 'Annulé'),
(16, 14, 13, 24, 1012, 'TEST MOTIF', '08:15:00', 'Réservé', 'Non', ''),
(17, 14, 14, 24, 1013, 'TEST MOTIF', '11:00:00', 'Terminé', 'Non', ''),
(18, 14, 9, 25, 1015, 'TEST MOTIF', '09:40:00', 'Réservé', 'Non', ''),
(19, 14, 10, 26, 1016, 'TEST MOTIF', '11:20:00', 'En cours', 'Oui', ''),
(20, 15, 7, 27, 1017, 'TEST MOTIF', '12:30:00', 'Réservé', 'Non', ''),
(21, 13, 14, 28, 1018, 'TEST MOTIF', '08:15:00', 'Terminé', 'Non', ''),
(24, 8, 9, 35, 1021, 'hyhyyhyhy', '01:49:00', 'Annulé', 'Non', ''),
(26, 12, 9, 36, 1023, 'tgtgtgtgtg', '08:15:00', 'Réservé', 'Non', ''),
(27, 12, 10, 36, 1024, 'ededdeded', '08:35:00', 'Réservé', 'Non', ''),
(28, 8, 10, 35, 1025, 'kikkiki', '02:39:00', 'Réservé', 'Non', ''),
(29, 8, 9, 37, 1026, 'ujujyujyuju', '06:00:00', 'En cours', 'Oui', ''),
(30, 8, 10, 37, 1027, 'rgerrt', '06:20:00', 'Réservé', 'Non', ''),
(31, 8, 10, 38, 1028, 'dfdfgedrfg', '05:19:00', 'Réservé', 'Non', ''),
(32, 9, 9, 40, 1029, 'Mal à la tete', '05:19:00', 'Réservé', 'Non', ''),
(33, 13, 9, 43, 1030, 'Mal à la tete', '05:20:00', 'Terminé', 'Oui', 'hhytrtgsegserg'),
(34, 13, 10, 44, 1031, 'mal à la tete', '06:52:00', 'En cours', 'Oui', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `table_admin`
--
ALTER TABLE `table_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `table_horaire`
--
ALTER TABLE `table_horaire`
  ADD PRIMARY KEY (`horaire_id`);

--
-- Indexes for table `table_medecin`
--
ALTER TABLE `table_medecin`
  ADD PRIMARY KEY (`medecin_id`);

--
-- Indexes for table `table_patient`
--
ALTER TABLE `table_patient`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `table_rdv`
--
ALTER TABLE `table_rdv`
  ADD PRIMARY KEY (`rdv_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `table_admin`
--
ALTER TABLE `table_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `table_horaire`
--
ALTER TABLE `table_horaire`
  MODIFY `horaire_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `table_medecin`
--
ALTER TABLE `table_medecin`
  MODIFY `medecin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `table_patient`
--
ALTER TABLE `table_patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `table_rdv`
--
ALTER TABLE `table_rdv`
  MODIFY `rdv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
