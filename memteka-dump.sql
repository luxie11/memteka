-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2019 m. Kov 11 d. 16:51
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `memteka`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `kategorijos`
--

CREATE TABLE `kategorijos` (
  `pavadinimas` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sukurta duomenų kopija lentelei `kategorijos`
--

INSERT INTO `kategorijos` (`pavadinimas`) VALUES
('Linksmiausi'),
('Sportas');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `komentarai`
--

CREATE TABLE `komentarai` (
  `id` int(10) NOT NULL,
  `vardas` varchar(32) CHARACTER SET latin1 NOT NULL,
  `komentaras` varchar(255) CHARACTER SET latin1 NOT NULL,
  `ip` varchar(20) CHARACTER SET latin1 NOT NULL,
  `data` datetime NOT NULL,
  `fk_memo_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sukurta duomenų kopija lentelei `komentarai`
--

INSERT INTO `komentarai` (`id`, `vardas`, `komentaras`, `ip`, `data`, `fk_memo_id`) VALUES
(1, 'Petras', 'Mano pirmasis komentaras', '127.0.0.1', '2019-03-06 21:00:25', 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `memai`
--

CREATE TABLE `memai` (
  `id` int(10) NOT NULL,
  `pavadinimas` varchar(255) CHARACTER SET latin1 NOT NULL,
  `nuoroda` varchar(255) CHARACTER SET latin1 NOT NULL,
  `tasku_kiekis` int(10) NOT NULL,
  `komentaru_kiekis` int(10) NOT NULL,
  `data` datetime NOT NULL,
  `fk_vartotojo_vardas` varchar(20) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sukurta duomenų kopija lentelei `memai`
--

INSERT INTO `memai` (`id`, `pavadinimas`, `nuoroda`, `tasku_kiekis`, `komentaru_kiekis`, `data`, `fk_vartotojo_vardas`) VALUES
(1, 'Pirmasis memas', 'images/memas.jpg', 0, 1, '2019-03-06 20:15:24', 'admin'),
(2, 'Bychu gvardija', 'images/memas2.jpg', 6669, 6669, '2019-06-06 19:12:12', 'admin'),
(3, 'Veikia memas kazkoks hrien znajet koks sene su akiniais', 'images/memas3.jpg', 69, 6669, '2019-01-05 12:30:14', 'admin');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `memai_kategorijos`
--

CREATE TABLE `memai_kategorijos` (
  `fk_kategorijos_pavadinimas` varchar(255) CHARACTER SET latin1 NOT NULL,
  `fk_memo_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sukurta duomenų kopija lentelei `memai_kategorijos`
--

INSERT INTO `memai_kategorijos` (`fk_kategorijos_pavadinimas`, `fk_memo_id`) VALUES
('Sportas', 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `vartotojai`
--

CREATE TABLE `vartotojai` (
  `vartotojo_vardas` varchar(20) CHARACTER SET latin1 NOT NULL,
  `slaptazodis` varchar(32) CHARACTER SET latin1 NOT NULL,
  `paskutinis_prisijungimas` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sukurta duomenų kopija lentelei `vartotojai`
--

INSERT INTO `vartotojai` (`vartotojo_vardas`, `slaptazodis`, `paskutinis_prisijungimas`) VALUES
('admin', 'admin', '2019-03-06 20:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategorijos`
--
ALTER TABLE `kategorijos`
  ADD PRIMARY KEY (`pavadinimas`);

--
-- Indexes for table `komentarai`
--
ALTER TABLE `komentarai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkc_komentaro_memas` (`fk_memo_id`);

--
-- Indexes for table `memai`
--
ALTER TABLE `memai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkc_mema_ikele` (`fk_vartotojo_vardas`);

--
-- Indexes for table `memai_kategorijos`
--
ALTER TABLE `memai_kategorijos`
  ADD KEY `fkc_memai_kategorijos_kategorijos_pavadinimas` (`fk_kategorijos_pavadinimas`),
  ADD KEY `fkc_memai_kategorijos_memo_id` (`fk_memo_id`);

--
-- Indexes for table `vartotojai`
--
ALTER TABLE `vartotojai`
  ADD PRIMARY KEY (`vartotojo_vardas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `komentarai`
--
ALTER TABLE `komentarai`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `memai`
--
ALTER TABLE `memai`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Apribojimai eksportuotom lentelėm
--

--
-- Apribojimai lentelei `komentarai`
--
ALTER TABLE `komentarai`
  ADD CONSTRAINT `fkc_komentaro_memas` FOREIGN KEY (`fk_memo_id`) REFERENCES `memai` (`id`);

--
-- Apribojimai lentelei `memai`
--
ALTER TABLE `memai`
  ADD CONSTRAINT `fkc_mema_ikele` FOREIGN KEY (`fk_vartotojo_vardas`) REFERENCES `vartotojai` (`vartotojo_vardas`);

--
-- Apribojimai lentelei `memai_kategorijos`
--
ALTER TABLE `memai_kategorijos`
  ADD CONSTRAINT `fkc_memai_kategorijos_kategorijos_pavadinimas` FOREIGN KEY (`fk_kategorijos_pavadinimas`) REFERENCES `kategorijos` (`pavadinimas`),
  ADD CONSTRAINT `fkc_memai_kategorijos_memo_id` FOREIGN KEY (`fk_memo_id`) REFERENCES `memai` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
