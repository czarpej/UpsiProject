-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 05 Lis 2018, 18:50
-- Wersja serwera: 10.1.21-MariaDB
-- Wersja PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `upsi`
--
  CREATE DATABASE IF NOT EXISTS `upsi`;
-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `actual_test`
--

CREATE TABLE `actual_test` (
  `id` int(11) NOT NULL,
  `exam_type` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `test_type` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `count_question` int(11) NOT NULL,
  `time_on_question` int(11) NOT NULL,
  `id_class` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `extra_points` float NOT NULL,
  `multipler_points` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `actual_test`
--

INSERT INTO `actual_test` (`id`, `exam_type`, `test_type`, `count_question`, `time_on_question`, `id_class`, `extra_points`, `multipler_points`) VALUES
(1, 'all', 'all', 30, 50, '3', 0, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `archive_results`
--

CREATE TABLE `archive_results` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `exam_category` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `comment` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `score` float NOT NULL,
  `mark` float NOT NULL,
  `count_question` int(11) NOT NULL,
  `extra_points` float NOT NULL,
  `multipler_points` float NOT NULL,
  `date` date NOT NULL,
  `grade_2` float NOT NULL,
  `grade_3` float NOT NULL,
  `grade_4` float NOT NULL,
  `grade_5` float NOT NULL,
  `grade_6` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `archive_results`
--

INSERT INTO `archive_results` (`id`, `id_users`, `exam_category`, `comment`, `score`, `mark`, `count_question`, `extra_points`, `multipler_points`, `date`, `grade_2`, `grade_3`, `grade_4`, `grade_5`, `grade_6`) VALUES
(6, 6, 'tur', 'cja', 7, 7, 7, 7, 7, '2018-08-02', 7, 7, 7, 7, 7),
(8, 8, 'kebap', 'rollo', 17, 17, 17, 17, 17, '2018-08-17', 17, 17, 17, 17, 17),
(9, 9, 'tur', 'fur', 0, 0, 0, 0, 0, '2018-08-01', 0, 0, 0, 0, 0),
(10, 9, 'tur', 'mur', 4, 4, 4, 4, 4, '2018-08-04', 4, 4, 4, 4, 4),
(11, 9, 'her', 'mer', 2, 2, 2, 2, 2, '2018-08-02', 2, 2, 2, 2, 2),
(12, 11, 'maszyny', 'rolnicze', 2, 2, 2, 2, 2, '2018-08-02', 2, 2, 2, 2, 2),
(13, 11, 'rozrzutnik', 'gnoju', 5, 5, 55, 5, 5, '2018-08-05', 5, 5, 5, 5, 5),
(14, 33, 'czu', 'bek', 1, 1, 1, 1, 1, '2018-08-16', 1, 1, 1, 1, 100),
(15, 33, 'ye', 'ti', 5, 5, 5, 5, 5, '2018-08-05', 5, 5, 5, 5, 5),
(16, 34, 'wina', 'robaka', 3, 3, 3, 3, 3, '2018-08-03', 3, 3, 3, 3, 3),
(17, 35, 'robak', 'len', 7, 7, 7, 7, 7, '2018-08-07', 7, 7, 7, 7, 7),
(18, 35, '8', '8', 8, 8, 8, 8, 8, '2018-08-08', 8, 8, 8, 8, 8),
(19, 36, 'nadal', 'czubek', 2, 2, 2, 2, 2, '2018-08-02', 2, 2, 2, 2, 2),
(20, 36, 'kiedy', 'czubek', 0, 0, 0, 0, 0, '2018-08-22', 0, 0, 0, 0, 0),
(21, 37, 'Super Combo', 'Super Combo', 0, 1, 5, 0, 1, '2018-08-08', 50, 60, 70, 85, 95),
(22, 37, 'E12', 'Budowa komputera', 33.3333, 1, 3, 0, 1, '2018-08-08', 50, 60, 70, 85, 95),
(59, 44, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(60, 44, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(61, 44, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(62, 44, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(63, 44, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(64, 44, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(65, 44, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(66, 45, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(67, 45, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(68, 45, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(69, 45, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(70, 45, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(71, 45, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(72, 45, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(73, 46, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(74, 46, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(75, 46, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(76, 46, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(77, 46, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(78, 46, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(79, 46, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(80, 47, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(81, 47, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(82, 47, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(83, 47, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(84, 47, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(85, 47, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(86, 47, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(87, 48, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(88, 48, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(89, 48, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(90, 48, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(91, 48, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(92, 48, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(93, 48, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(94, 49, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(95, 49, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(96, 49, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(97, 49, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(98, 49, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(99, 49, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(100, 49, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(101, 50, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(102, 50, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(103, 50, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(104, 50, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(105, 50, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(106, 50, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(107, 50, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(108, 51, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(109, 51, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(110, 51, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(111, 51, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(112, 51, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(113, 51, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(114, 51, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(115, 52, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(116, 52, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(117, 52, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(118, 52, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(119, 52, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(120, 52, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(121, 52, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(122, 53, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(123, 53, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(124, 53, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(125, 53, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(126, 53, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(127, 53, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(128, 53, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(129, 54, 'E14', 'C++', 63.5, 3, 7, 0, 1, '2018-05-09', 0, 0, 0, 0, 0),
(130, 54, 'Super Combo', 'Super Combo', 83.3333, 4, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(131, 54, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(132, 54, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(133, 54, 'Super Combo', 'Super Combo', 33.3333, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(134, 54, 'Super Combo', 'Super Combo', 50, 2, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(135, 54, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(136, 54, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(137, 54, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(138, 54, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(139, 54, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(140, 54, 'E14', 'Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(141, 54, 'E12', 'Budowa komputera', 33.3333, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(142, 54, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(143, 54, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-25', 0, 0, 0, 0, 0),
(144, 54, 'E14', 'Grafika', 100, 6, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(145, 54, 'E14', 'Grafika', 33.3333, 2, 1, 0, 1, '2018-08-03', 30, 50, 65, 80, 95),
(146, 54, 'E12', 'Napędy optyczne i dyski twarde', 50, 3, 1, 0, 1, '2018-08-06', 30, 50, 65, 80, 95),
(147, 55, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(148, 55, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(149, 55, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(150, 55, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(151, 55, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(152, 55, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(153, 55, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(154, 56, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(155, 56, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(156, 56, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(157, 56, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(158, 56, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(159, 56, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(160, 56, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(161, 57, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(162, 57, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(163, 57, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(164, 57, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(165, 57, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(166, 57, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(167, 57, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(168, 58, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(169, 58, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(170, 58, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(171, 58, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(172, 58, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(173, 58, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(174, 58, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(175, 59, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(176, 59, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(177, 59, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(178, 59, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(179, 59, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(180, 59, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(181, 59, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(182, 60, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(183, 60, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(184, 60, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(185, 60, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(186, 60, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(187, 60, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(188, 60, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(189, 61, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(190, 61, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(191, 61, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(192, 61, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(193, 61, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(194, 61, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(195, 61, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(196, 62, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(197, 62, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(198, 62, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(199, 62, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(200, 62, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(201, 62, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(202, 62, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(203, 63, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(204, 63, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(205, 63, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(206, 63, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(207, 63, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(208, 63, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(209, 63, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(210, 64, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(211, 64, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(212, 64, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(213, 64, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(214, 64, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(215, 64, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(216, 64, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(217, 65, 'E14', 'C++', 63.5, 3, 7, 0, 1, '2018-05-09', 0, 0, 0, 0, 0),
(218, 65, 'Super Combo', 'Super Combo', 83.3333, 4, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(219, 65, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(220, 65, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(221, 65, 'Super Combo', 'Super Combo', 33.3333, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(222, 65, 'Super Combo', 'Super Combo', 50, 2, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(223, 65, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(224, 65, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(225, 65, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(226, 65, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(227, 65, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(228, 65, 'E14', 'Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(229, 65, 'E12', 'Budowa komputera', 33.3333, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(230, 65, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(231, 65, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-25', 0, 0, 0, 0, 0),
(232, 65, 'E14', 'Grafika', 100, 6, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(233, 65, 'E14', 'Grafika', 33.3333, 2, 1, 0, 1, '2018-08-03', 30, 50, 65, 80, 95),
(234, 65, 'E12', 'Napędy optyczne i dyski twarde', 50, 3, 1, 0, 1, '2018-08-06', 30, 50, 65, 80, 95),
(235, 66, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(236, 66, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(237, 66, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(238, 66, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(239, 66, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(240, 66, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(241, 66, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(242, 67, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(243, 67, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(244, 67, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(245, 67, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(246, 67, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(247, 67, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(248, 67, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(249, 68, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(250, 68, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(251, 68, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(252, 68, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(253, 68, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(254, 68, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(255, 68, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(256, 69, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(257, 69, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(258, 69, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(259, 69, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(260, 69, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(261, 69, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(262, 69, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(263, 70, 'E14', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(264, 70, 'E12', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(265, 70, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(266, 70, 'E12', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(267, 70, 'E12', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(268, 70, 'E12', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(269, 70, 'E12', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(270, 71, 'E14', 'C++', 63.5, 3, 7, 0, 1, '2018-05-09', 0, 0, 0, 0, 0),
(271, 71, 'Super Combo', 'Super Combo', 83.3333, 4, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(272, 71, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(273, 71, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(274, 71, 'Super Combo', 'Super Combo', 33.3333, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(275, 71, 'Super Combo', 'Super Combo', 50, 2, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(276, 71, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(277, 71, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(278, 71, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(279, 71, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(280, 71, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(281, 71, 'E14', 'Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(282, 71, 'E12', 'Budowa komputera', 33.3333, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(283, 71, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(284, 71, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-25', 0, 0, 0, 0, 0),
(285, 71, 'E14', 'Grafika', 100, 6, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(286, 71, 'E14', 'Grafika', 33.3333, 2, 1, 0, 1, '2018-08-03', 30, 50, 65, 80, 95),
(287, 71, 'E12', 'Napędy optyczne i dyski twarde', 50, 3, 1, 0, 1, '2018-08-06', 30, 50, 65, 80, 95),
(288, 72, 'E14', 'C++', 63.5, 3, 7, 0, 1, '2018-05-09', 0, 0, 0, 0, 0),
(289, 72, 'Super Combo', 'Super Combo', 83.3333, 4, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(290, 72, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(291, 72, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(292, 72, 'Super Combo', 'Super Combo', 33.3333, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(293, 72, 'Super Combo', 'Super Combo', 50, 2, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(294, 72, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(295, 72, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(296, 72, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(297, 72, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(298, 72, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(299, 72, 'E14', 'Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(300, 72, 'E12', 'Budowa komputera', 33.3333, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(301, 72, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(302, 72, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-25', 0, 0, 0, 0, 0),
(303, 72, 'E14', 'Grafika', 100, 6, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(304, 72, 'E14', 'Grafika', 33.3333, 2, 1, 0, 1, '2018-08-03', 30, 50, 65, 80, 95),
(305, 72, 'E12', 'Napędy optyczne i dyski twarde', 50, 3, 1, 0, 1, '2018-08-06', 30, 50, 65, 80, 95),
(306, 73, 'E14', 'C++', 63.5, 3, 7, 0, 1, '2018-05-09', 0, 0, 0, 0, 0),
(307, 73, 'Super Combo', 'Super Combo', 83.3333, 4, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(308, 73, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(309, 73, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(310, 73, 'Super Combo', 'Super Combo', 33.3333, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(311, 73, 'Super Combo', 'Super Combo', 50, 2, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(312, 73, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(313, 73, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(314, 73, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(315, 73, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(316, 73, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(317, 73, 'E14', 'Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(318, 73, 'E12', 'Budowa komputera', 33.3333, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(319, 73, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(320, 73, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-25', 0, 0, 0, 0, 0),
(321, 73, 'E14', 'Grafika', 100, 6, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(322, 73, 'E14', 'Grafika', 33.3333, 2, 1, 0, 1, '2018-08-03', 30, 50, 65, 80, 95),
(323, 73, 'E12', 'Napędy optyczne i dyski twarde', 50, 3, 1, 0, 1, '2018-08-06', 30, 50, 65, 80, 95),
(324, 74, 'E14', 'C++', 63.5, 3, 7, 0, 1, '2018-05-09', 0, 0, 0, 0, 0),
(325, 74, 'Super Combo', 'Super Combo', 83.3333, 4, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(326, 74, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-19', 0, 0, 0, 0, 0),
(327, 74, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(328, 74, 'Super Combo', 'Super Combo', 33.3333, 1, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(329, 74, 'Super Combo', 'Super Combo', 50, 2, 7, 0, 1, '2018-06-20', 0, 0, 0, 0, 0),
(330, 74, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(331, 74, 'Super Combo', 'Super Combo', -16.6667, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(332, 74, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-23', 0, 0, 0, 0, 0),
(333, 74, 'Super Combo', 'Super Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(334, 74, 'Super Combo', 'Super Combo', -5.5556, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(335, 74, 'E14', 'Combo', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(336, 74, 'E12', 'Budowa komputera', 33.3333, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(337, 74, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-24', 0, 0, 0, 0, 0),
(338, 74, 'E12', 'Budowa komputera', 0, 1, 7, 0, 1, '2018-06-25', 0, 0, 0, 0, 0),
(339, 74, 'E14', 'Grafika', 100, 6, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(340, 74, 'E14', 'Grafika', 33.3333, 2, 1, 0, 1, '2018-08-03', 30, 50, 65, 80, 95),
(341, 74, 'E12', 'Napędy optyczne i dyski twarde', 50, 3, 1, 0, 1, '2018-08-06', 30, 50, 65, 80, 95),
(342, 76, '', 'Grafika', -100, 1, 1, 0, 1, '2018-06-29', 0, 0, 0, 0, 0),
(343, 76, '', 'Systemy operacyjne', -66.6667, 1, 1, 0, 2, '2018-06-30', 0, 0, 0, 0, 0),
(344, 76, '', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(345, 76, '', 'Systemy operacyjne', 33.3333, 1, 1, 0, 1, '2018-07-12', 0, 0, 0, 0, 0),
(346, 76, '', 'Systemy operacyjne', 0, 1, 1, 0, 1, '2018-07-13', 0, 0, 0, 0, 0),
(347, 76, '', 'Systemy operacyjne', -33.3333, 1, 1, 0, 1, '2018-07-14', 0, 0, 0, 0, 0),
(348, 76, '', 'Systemy operacyjne', 66.6667, 3, 1, 0, 1, '2018-07-15', 0, 0, 0, 0, 0),
(349, 76, '', 'Napędy optyczne i dyski twarde', 0, 6, 1, 0, 1, '2018-08-11', 0, 0, 0, 0, 0),
(350, 76, '', 'Budowa komputera', 33.3333, 6, 3, 0, 1, '2018-08-11', 0, 0, 0, 0, 0),
(351, 76, '', 'Budowa komputera', -11.1111, 1, 3, 0, 1, '2018-08-11', 0, 0, 0, 0, 0),
(352, 76, '', 'Budowa komputera', 0, 1, 3, 0, 1, '2018-08-11', 0, 0, 0, 0, 0),
(353, 77, '3', '3', 3, 3, 3, 3, 3, '2018-08-03', 3, 3, 3, 3, 3),
(354, 93, '5', '5', 5, 5, 5, 5, 5, '2018-08-05', 5, 5, 5, 5, 5),
(355, 93, '25', '25', 25, 25, 25, 25, 25, '2018-08-25', 25, 252, 25, 252, 25),
(356, 94, '6', '6', 6, 6, 6, 6, 6, '2018-08-06', 6, 6, 6, 6, 6),
(357, 102, '8', 'cos', 23, 42, 21, 24, 42, '2018-08-08', 1, 1, 1, 1, 1),
(358, 103, 'E12', 'Napędy optyczne i dyski twarde', 0, 1, 1, 0, 1, '2018-08-24', 50, 60, 75, 90, 100),
(359, 103, 'E12', 'Napędy optyczne i dyski twarde', 50, 2, 1, 0, 1, '2018-08-24', 50, 60, 75, 90, 100);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `archive_users`
--

CREATE TABLE `archive_users` (
  `id` int(11) NOT NULL,
  `login` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `class` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `year` varchar(10) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `archive_users`
--

INSERT INTO `archive_users` (`id`, `login`, `class`, `year`) VALUES
(2, 'bolczak', '3', ''),
(3, 'bolczak', '3', ''),
(4, 'pedal', '2', ''),
(5, 'pedal', '2', ''),
(6, 'turek', '2', ''),
(7, 'turek', '1', ''),
(8, 'turek', '2', ''),
(9, 'turek', '2', ''),
(10, 'turek', '2', ''),
(11, 'czubek', '2', ''),
(12, 'czubek', '2', ''),
(13, 'czubek', '2', ''),
(14, 'czubek', '2', ''),
(15, 'czubek', '2', ''),
(16, 'czubek', '2', ''),
(17, 'czubek', '2', ''),
(18, 'czubek', '2', ''),
(19, 'czubek', '2', ''),
(20, 'czubek', '2', ''),
(21, 'czubek', '2', ''),
(22, 'czubek', '2', ''),
(23, 'czubek', '2', ''),
(24, 'czubek', '2', ''),
(25, 'czubek', '2', ''),
(26, 'czubek', '2', ''),
(27, 'czubek', '2', ''),
(28, 'czubek', '2', ''),
(29, 'czubek', '2', ''),
(30, 'czubek', '2', ''),
(31, 'czubek', '2', ''),
(32, 'czubek', '2', ''),
(33, 'czubek', '2', ''),
(34, 'czubek', '2', ''),
(35, 'czubek', '2', ''),
(36, 'czubek', '2', ''),
(37, 'kowalski', '3', ''),
(38, 'solecki', '4', ''),
(39, 'solecki', '4', ''),
(40, 'solecki', '4', ''),
(41, 'solecki', '4', ''),
(42, 'solecki', '4', ''),
(43, 'solecki', '4', ''),
(44, 'solecki', '4', ''),
(45, 'solecki', '4', ''),
(46, 'solecki', '4', ''),
(47, 'solecki', '4', ''),
(48, 'solecki', '4', ''),
(49, 'solecki', '4', ''),
(50, 'solecki', '4', ''),
(51, 'solecki', '4', ''),
(52, 'solecki', '4', ''),
(53, 'solecki', '4', ''),
(54, 'czarnota', '4', ''),
(55, 'solecki', '4', ''),
(56, 'solecki', '4', ''),
(57, 'solecki', '4', ''),
(58, 'solecki', '4', ''),
(59, 'solecki', '4', ''),
(60, 'solecki', '4', ''),
(61, 'solecki', '4', ''),
(62, 'solecki', '4', ''),
(63, 'solecki', '4', ''),
(64, 'solecki', '4', ''),
(65, 'czarnota', '4', ''),
(66, 'solecki', '4', ''),
(67, 'solecki', '4', ''),
(68, 'solecki', '4', ''),
(69, 'solecki', '4', ''),
(70, 'solecki', '4', ''),
(71, 'czarnota', '4', ''),
(72, 'czarnota', '4', ''),
(73, 'czarnota', '4', ''),
(74, 'czarnota', '4', ''),
(75, 'kudzia', '4', ''),
(76, 'solecki', '4', ''),
(77, 'brucki', '0', ''),
(78, 'budak', '4', ''),
(79, 'muca', '0', ''),
(80, 'jakubowski', '4', ''),
(81, 'muca', '4', ''),
(82, 'peczkis', '4', ''),
(83, 'peczkis', '4', ''),
(84, 'ktos', '4', ''),
(85, 'test', '4', ''),
(86, 'test', '4', ''),
(87, 'test', '4', ''),
(88, 'test', '4', ''),
(89, 'test', '4', ''),
(90, 'test', '4', ''),
(91, 'test', '4', ''),
(92, 'test', '0', ''),
(93, 'ktos', '2', ''),
(94, 'cos', '2', ''),
(95, 'gawlik', '3TI', '2016-2019'),
(96, 'gawlik', '3TI', '2016-2019'),
(97, 'gawlik', '3TI', '2016-2019'),
(98, 'gawlik', '3TI', '2016-2019'),
(99, 'gawlik', '3TI', '-3'),
(100, 'logik', '2TI', '2016-2018'),
(101, 'jedrzęjczak', '3MO', '2015-2018'),
(102, '2', '2TE', '2013-2015'),
(103, 'olek', '2TE', '2013-2015'),
(104, 'olek', '2TE', '2013-2015'),
(105, '2', '2TE', '2013-2015'),
(106, 'olek', '2TE', '2013-2015'),
(107, 'olek', '2TE', '2013-2015'),
(108, 'gurek', '2TE', '2013-2015'),
(111, 'dupek', '2TMR', '2014-2016');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `section` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `year_started` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `classes`
--

INSERT INTO `classes` (`id`, `class`, `section`, `year_started`) VALUES
(2, 71, 'admin', 1948),
(3, 3, 'TI', 2018),
(5, 1, 'Administratorzy', 2018);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `grade_norm`
--

CREATE TABLE `grade_norm` (
  `id_norm` int(11) NOT NULL,
  `grade_2` float NOT NULL,
  `grade_3` float NOT NULL,
  `grade_4` float NOT NULL,
  `grade_5` float NOT NULL,
  `grade_6` float NOT NULL,
  `id_class` varchar(30) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `grade_norm`
--

INSERT INTO `grade_norm` (`id_norm`, `grade_2`, `grade_3`, `grade_4`, `grade_5`, `grade_6`, `id_class`) VALUES
(1, 30, 50, 65, 80, 95, '1'),
(2, 50, 60, 75, 90, 100, '4'),
(3, 50, 60, 75, 90, 100, '7'),
(4, 50, 60, 75, 90, 100, '3');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `question`
--

CREATE TABLE `question` (
  `id_question` int(11) NOT NULL,
  `exam_category` varchar(10) COLLATE utf8_polish_ci NOT NULL,
  `test_category` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `question` text COLLATE utf8_polish_ci NOT NULL,
  `image` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `ans_a` text COLLATE utf8_polish_ci NOT NULL,
  `ans_b` text COLLATE utf8_polish_ci NOT NULL,
  `ans_c` text COLLATE utf8_polish_ci NOT NULL,
  `ans_d` text COLLATE utf8_polish_ci NOT NULL,
  `ans_good` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `question`
--

INSERT INTO `question` (`id_question`, `exam_category`, `test_category`, `question`, `image`, `ans_a`, `ans_b`, `ans_c`, `ans_d`, `ans_good`) VALUES
(3, 'E14', 'Wiedza ogólna', 'Dziedziczenie w programowaniu obiektowym pozwala na', '', 'łączenie obiektów', 'kopiowanie cech jednego obiektu do innego', 'usunięcie z istniejącej klasy zbędnych elementów.', 'tworzenie nowej klasy na podstawie jednej lub kilku już istniejących klas', 'd'),
(4, 'E12', 'Budowa komputera', 'Złącze AGP służy do podłączenia', '', 'szybkich pamięci dyskowych', 'urządzeń wejścia/wyjścia', 'kart graficznych', 'modemu', 'c, a, c, d, a, c, d'),
(5, 'E12', 'Budowa komputera', 'Do którego wyprowadzenia należy podłączyć głośniki aktywne w karcie dźwiękowej, której schemat funkcjonalny przedstawia rysunek?', 'karta-dzwiekowa4.jpg', 'Mic in', 'Line in', 'Line out', 'Speaker out', 'c'),
(8, 'E12', 'Budowa komputera', 'Do którego wyprowadzenia należy podłączyć głośniki aktywne w karcie dźwiękowej, której schemat funkcjonalny przedstawia rysunek?', '', 'Mic in', 'Line in', 'Line out', 'Speaker out', 'c'),
(9, 'E12', 'Systemy operacyjne', 'Co oznacza zwrot \"wykonanie backupu systemu\"?', '', 'Zamknięcie systemu', 'Ponowne uruchomienie systemu', 'Wykonanie aktualizacji systemu', 'Wykonanie kopii zapasowej systemu', 'd'),
(10, 'E12', 'Napędy optyczne i dyski twarde', 'Jaka jest maksymalna prędkość odczytu płyt CD-R w napędzie oznaczonym x48?', '', '480 kB/s', '4800 kB/s', '7200 kB/s', '10000 kB/s', 'c, d'),
(11, 'E14', 'Grafika', 'Jakiej liczbie kolorów odpowiada kolor zakodowany na 16 bitach?', '', '16 kolorom', 'około 64 tysiącom kolorów', 'około 65 tysiącom kolorów', 'około 16 milionom kolorów', 'c'),
(17, 'E14', 'Grafika', 'sdfdsf', 'sod2-concept-5.jpg', 'dsf', 'as', 'gdf', 'fgiu', 'b,c,'),
(18, 'E12', 'Budowa komputera', 'fghg', 'wallpaper.jpg', 'sdfds', 'dsfdsf', 'sdfsd', 'fsdf', 'b,c,'),
(19, 'E12', 'Budowa komputera', 'sdfdsf', '23659803_633979323659792_1744921926_o.png', 'sdfds', 'sdfsdf', 'sdf', 'sdf', 'a,'),
(20, 'E12', 'Budowa komputera', 'sdfsdf', '14 (68).jpg', 'sdg', 'hsd', 'srg', 'sfhr', 'a,b,c,'),
(24, 'E14', 'Grafika', 'fdhdfh', '23659803_633979323659792_1744921926_o.png', 'fjfgk', 'khk', 'gkhg', '7glk', 'b,c,'),
(28, 'E12', 'Systemy operacyjne', 'sdgdsg', '14 (68).jpg', 'gsrh', 'shr', 'hsr', 'hsr', 'c,'),
(29, 'E12', 'Budowa komputera', 'dsgs', '14 (68).jpg', 'sdg', 'gsrghs', 'hsrhs', 'srhrs', 'a,'),
(31, 'E12', 'new_material', 'sfhfsh', '', 'rshsr', 'hdth', 'hsths', 'bdtsbs', 'b,'),
(32, 'E12', 'new_material', 'dfhdt', '', 'dtjdt', 'fykfy', 'fykfy', 'fykjfy', 'd,'),
(33, 'E12', 'Systemy operacyjne', 'sfhfsh', '', 'drhdh', 'dhfdh', 'dhfdh', 'hdtjdtj', 'b,'),
(34, 'E14', 'Grafika', 'sdgsdg', '34103318_741701852887538_4349836048667246592_n.png', 'sgsd', 'shsh', 'dtkj', 'fyk', 'a,'),
(35, 'E12', 'new_material', 'ssdhsdh', '', 'dfhfdh', 'djtj', 'jdtj', 'fjydtjd', 'b,'),
(36, 'E12', 'Monitory i matryce', 'sdgsdg', '', 'sfhsfh', 'hdrhfd', 'hfdhd', 'hdfh', 'b,c,'),
(37, 'E12', 'Urządzenia wejścia wyjścia', 'fdhfdh', '34119098_405427279865873_4845801438737072128_n.jpg', 'dsgdsg', 'gfdhdf', 'dfhfdh', 'dfhdfh', 'a,'),
(39, 'E12', 'Urządzenia wejścia wyjścia', 'hfdhfdh', '34119098_405427279865873_4845801438737072128_n.jpg', 'dfhdf', 'dfhfdh', 'dfjhfdj', 'dfhdf', 'b,c,d,'),
(40, 'E14', 'Grafika', 'dfjdfj', '', 'fkg', 'fykfy', 'fjft', 'tjd', 'a,'),
(41, 'E12', 'Systemy operacyjne', 'dsgdsh', '23659803_633979323659792_1744921926_o.png', 'sfhfs', 'dfh', 'djdf', 'cgj', 'a,'),
(42, 'E13', 'Warstwy sieciowe', 'Która warstwa modelu ISO/OSI jest związana z protokołem IP?', '', 'Sieciowa', 'Fizyczna', 'Transportowa', 'Łącza danych', 'a,'),
(43, 'E12', 'Budowa komputera', 'zs', '', 'odp_a', 'odp_b', 'odp_c', 'odp_d', 'b,'),
(44, 'E12', 'Monitory i matryce', 'fgjgfj', '5h45744h45d5878j6t.png', 'gfjgf', 'fgjfg', 'gfjgf', 'fgjkfg', 'a,'),
(45, 'E14', 'dsg', 'sfhdsh', '<img src=img/img_to_question/>', 'sdhds', 'sd', 'shdh', 'jgfjgf', 'a,'),
(46, 'E12', 'new_material', 'fdgdfgfd', 'GlHQ5vO.jpg', 'dfgfd', 'fdgdf', 'dfgfdg', 'dfgfdg', 'b,'),
(47, 'E13', 'Wiedza ogólna', 'Jaką ilość rzeczywistych danych można przesłać w czasie 1 s przez łącze synchroniczne o przepustowości 512 kbps, bez sprzętowej i programowej kompresji?', '', 'Około 5 kB', 'Około 55 kB', 'Ponad 64 kB', 'Ponad 500 kB', 'b,');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `results`
--

CREATE TABLE `results` (
  `id_results` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `exam_category` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `comment` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `score` float NOT NULL,
  `mark` float NOT NULL,
  `count_question` int(11) NOT NULL,
  `extra_points` float NOT NULL,
  `multipler_points` float NOT NULL,
  `date` date NOT NULL,
  `grade_2` float NOT NULL,
  `grade_3` float NOT NULL,
  `grade_4` float NOT NULL,
  `grade_5` float NOT NULL,
  `grade_6` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `results`
--

INSERT INTO `results` (`id_results`, `id_users`, `exam_category`, `comment`, `score`, `mark`, `count_question`, `extra_points`, `multipler_points`, `date`, `grade_2`, `grade_3`, `grade_4`, `grade_5`, `grade_6`) VALUES
(3, 14, 'E12', 'Monitory i matryce', 0, 1, 2, 0, 1, '2018-10-05', 50, 58, 74, 90, 100),
(4, 14, 'E12', 'Monitory i matryce', 66.6667, 3, 2, 0, 1, '2018-10-05', 50, 58, 74, 90, 100),
(5, 14, 'E12', 'Combo', 0, 1, 20, 0, 1, '2018-10-05', 50, 58, 74, 90, 100),
(6, 14, 'Super Combo', 'Super Combo', -1.4368, 1, 29, 0, 1, '2018-10-05', 50, 58, 74, 90, 100),
(7, 14, 'Super Combo', 'Super Combo', 0, 1, 30, 0, 1, '2018-10-21', 50, 60, 75, 90, 100),
(8, 14, 'Super Combo', 'Super Combo', 0, 1, 30, 0, 1, '2018-10-22', 50, 60, 75, 90, 100),
(9, 14, 'Super Combo', 'Super Combo', -0.5556, 1, 30, 0, 1, '2018-10-22', 50, 60, 75, 90, 100),
(10, 14, 'Super Combo', 'Super Combo', 0, 1, 30, 0, 1, '2018-11-01', 50, 60, 75, 90, 100),
(13, 18, 'Super Combo', 'Super Combo', -6.6667, 1, 30, 0, 1, '2018-11-02', 50, 60, 75, 90, 100);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `review` text COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `reviews`
--

INSERT INTO `reviews` (`id`, `id_users`, `review`, `date`) VALUES
(2, 14, 'No to moja opinia (jako pierwsza) to utworzyć możliwość przeglądania kodu źródłowego projektu dla innych - w celach naukowych oczywiście. No i w sumie to możliwość wrzucania swoich własnych lub przerobionych konkretnych stron.\nJeszcze jedno - przeglądania tych wszystkich uwag na kontach admina i wrzuconych plików.', '2018-10-09 06:20:29'),
(3, 14, 'Kolejna opinia - a może by tak te opcje linki do stron wsparcia dać w osobny dział w belce nawigacyjnej, zamiast, żeby były one w działaniach?', '2018-10-11 13:36:36'),
(4, 14, 'trzecia opinia', '2018-10-11 18:50:46'),
(5, 14, 'trzecia opinia', '2018-10-14 11:24:29'),
(6, 14, 'następna opinia', '2018-10-15 14:37:31'),
(7, 14, '@czarnota, nie zgadzam się z twoją opinią, nic nie zawiera pożytecznego ani nie znosi', '2018-10-16 16:49:44'),
(8, 2, 'Prosimy bez kłótni, zaproponowane zmiany zostały pozytywnie rozpatrzone i są właśnie implementowane. Niedługo zostaną one wprowadzone do obiegu.', '2018-10-22 18:26:07'),
(9, 6, 'Jak widzicie poprawiona odświeżona wersja już funkcjonuje. Czekamy na kolejne propozycje usprawnień :)', '2018-10-22 18:29:23'),
(10, 15, 'Wypowiedź Robaka: \"To wina Boczka\".', '2018-11-02 10:55:28');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` text COLLATE utf8_polish_ci NOT NULL,
  `haslo` text COLLATE utf8_polish_ci NOT NULL,
  `id_klasy` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `freezing` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `haslo`, `id_klasy`, `freezing`) VALUES
(2, 'admin', '$2y$10$j5QLZNmQfkdH6r4wHR9CWuAOnk8ZZKmeHR6QRh/MYYEqGLU.y0kOi', '2', 0),
(14, 'czarnota', '', '3', 0),
(15, 'robak', '', '3', 0),
(16, 'michal', '', '5', 1),
(18, 'solecki', '', '5', 0),
(19, 'budak', '', '5', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `actual_test`
--
ALTER TABLE `actual_test`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class` (`id_class`),
  ADD KEY `class_2` (`id_class`),
  ADD KEY `exam_type` (`exam_type`),
  ADD KEY `test_type` (`test_type`);

--
-- Indexes for table `archive_results`
--
ALTER TABLE `archive_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_users` (`id_users`);

--
-- Indexes for table `archive_users`
--
ALTER TABLE `archive_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grade_norm`
--
ALTER TABLE `grade_norm`
  ADD PRIMARY KEY (`id_norm`),
  ADD KEY `class` (`id_class`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id_question`,`exam_category`,`test_category`),
  ADD KEY `test_category` (`test_category`),
  ADD KEY `exam_category` (`exam_category`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id_results`),
  ADD KEY `id_users` (`id_users`),
  ADD KEY `exam_category` (`exam_category`),
  ADD KEY `test_category` (`comment`),
  ADD KEY `exam_category_2` (`exam_category`),
  ADD KEY `test_category_2` (`comment`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klasa` (`id_klasy`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `actual_test`
--
ALTER TABLE `actual_test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT dla tabeli `archive_results`
--
ALTER TABLE `archive_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;
--
-- AUTO_INCREMENT dla tabeli `archive_users`
--
ALTER TABLE `archive_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT dla tabeli `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT dla tabeli `grade_norm`
--
ALTER TABLE `grade_norm`
  MODIFY `id_norm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `question`
--
ALTER TABLE `question`
  MODIFY `id_question` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT dla tabeli `results`
--
ALTER TABLE `results`
  MODIFY `id_results` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT dla tabeli `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `archive_results`
--
ALTER TABLE `archive_results`
  ADD CONSTRAINT `archive_results_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `archive_users` (`id`);

--
-- Ograniczenia dla tabeli `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
