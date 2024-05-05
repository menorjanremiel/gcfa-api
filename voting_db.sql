-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2022 at 04:20 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voting_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `adminid_fld` int(6) NOT NULL,
  `adminuser_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `adminpass_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `adminfname_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `adminmname_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `adminlname_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `adminext_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `admindept_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `adminpos_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `adminhead_fld` int(11) NOT NULL,
  `admintoken_fld` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`adminid_fld`, `adminuser_fld`, `adminpass_fld`, `adminfname_fld`, `adminmname_fld`, `adminlname_fld`, `adminext_fld`, `admindept_fld`, `adminpos_fld`, `adminhead_fld`, `admintoken_fld`) VALUES
(1, 'voting.main@gordoncollege.edu.ph', '$2y$10$YTk0YzIxN2ZlNjg1MmJhN.9TCqbG2h6gfxTe44xTVFMqClafIUPea', 'Administrator', '', 'Head', '', 'CCS', 'Head Admin', 1, 'eyJ0eXAiOiJQV0EiLCJhbGciOiJIUzI1NiIsInZlciI6IjEuMC4wIiwiZGV2IjoiQlNJVCAzQSJ9.eyJ1aWQiOjEsInVuIjoidm90aW5nLm1haW5AZ29yZG9uY29sbGVnZS5lZHUucGgiLCJpYnkiOiJCU0lUIDNBIiwiaWUiOiIyMDE4MTEwMjhAZ29yZG9uY29sbGVnZS5lZHUucGgiLCJpZGF0ZSI6eyJkYXRlIjoiMjAyMi0wOC0yOCAyMjozMToxOC43NTYxMjEiLCJ0aW1lem9uZV90eXBlIjozLCJ0aW1lem9uZSI6IkFzaWFcL01hbmlsYSJ9LCJleHAiOjE2NjIzMDE4Nzh9.YmE4OWI0OWQwOWZkNDY3ZmFiNjA3MTc2OWRiMDIxNTYxM2I0YWY1MWNkNDVmMGQ1Y2U5ODAwYzM5MTc5MDI4Mw'),
(7, '201811028@gordoncollege.edu.ph', '$2y$10$NjI1ZjU1NzZiNjg3Mjk4YOmEVZTfV.UZspt7h0E6G4CUtxETDOU7S', 'Simon Gerard', 'Eugenio', 'Granil', '', 'CCS', 'Member', 0, 'eyJ0eXAiOiJQV0EiLCJhbGciOiJIUzI1NiIsInZlciI6IjEuMC4wIiwiZGV2IjoiQlNJVCAzQSJ9.eyJ1aWQiOjEsInVuIjoidm90aW5nLm1haW5AZ29yZG9uY29sbGVnZS5lZHUucGgiLCJpYnkiOiJCU0lUIDNBIiwiaWUiOiIyMDE4MTEwMjhAZ29yZG9uY29sbGVnZS5lZHUucGgiLCJpZGF0ZSI6eyJkYXRlIjoiMjAyMi0wOC0yOCAyMjozMToxOC43NTYxMjEiLCJ0aW1lem9uZV90eXBlIjozLCJ0aW1lem9uZSI6IkFzaWFcL01hbmlsYSJ9LCJleHAiOjE2NjIzMDE4Nzh9.YmE4OWI0OWQwOWZkNDY3ZmFiNjA3MTc2OWRiMDIxNTYxM2I0YWY1MWNkNDVmMGQ1Y2U5ODAwYzM5MTc5MDI4Mw');

-- --------------------------------------------------------

--
-- Table structure for table `candidate_tbl`
--

CREATE TABLE `candidate_tbl` (
  `candidateid_fld` int(11) NOT NULL,
  `envid_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `posid_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `studno_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `partylist_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `candidatedept_fld` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `env_tbl`
--

CREATE TABLE `env_tbl` (
  `envid_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `adminid_fld` int(11) NOT NULL,
  `envname_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `envdate_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `envdept_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `view_fld` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `position_tbl`
--

CREATE TABLE `position_tbl` (
  `posid_fld` int(11) NOT NULL,
  `envid_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `posname_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `poscode_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `postype_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `numOfVotes_fld` int(11) NOT NULL,
  `posdept_fld` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `studno_fld` int(11) NOT NULL,
  `studpass_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `studfname_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `studmname_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `studlname_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `studextension_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `studdept_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `studprog_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `studtoken_fld` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`studno_fld`, `studpass_fld`, `studfname_fld`, `studmname_fld`, `studlname_fld`, `studextension_fld`, `studdept_fld`, `studprog_fld`, `studtoken_fld`) VALUES
(201811028, '$2y$10$YzhkNjQ4NDgwZWYxNzgxYegFPwq.w1OeewOdI4JDtbgmSWFiFZzGC', 'Simon Gerard', 'Eugenio', 'Granil', '', 'CCS', 'BSIT', ''),
(201910813, '$2y$10$NDBmNTVhMTYxM2YxNDk0MeztSJsfGFFsFhtp9KA98OccKXs48JPje', 'John Paul', 'Angcot', 'Gingpis', '', 'CCS', 'BSIT', ''),
(201911086, '$2y$10$M2RmNjJjNjc1OTY0NTMwNun1xqhPmbSzakkrl9N8eWTUKWgFyGisW', 'Neil John', 'Santiago', 'Bitangcol', '', 'CCS', 'BSIT', ''),
(201820031, '$2y$10$ZGM4MDgwZmM3ZjVmZTRkMuodQJyVnUbO.PpSg.ovyXvUbdCUjJo8S', 'Renzo', 'Guile', 'Santos', '', 'CCS', 'BSIT', ''),
(202211022, '$2y$10$YmYyODc0MjMzZDE4NmQzNus5Q/pUmWYRAKsBfht6ysDxyTUfPmERm', 'Robin', '.', 'Padilla', '', 'CEAS', 'BSEd', ''),
(202311258, '$2y$10$NzcyOTUxNzg5OTYyYzczM.xZNNUjrE7D/Ld0yoizY1zW7/VCQlfwC', 'Harry', 'Spoux', 'Roque', '', 'CEAS', 'BPEd', ''),
(202344588, '$2y$10$MWRiZjQ1NDA2MTJmNDkxMOEt34vQfInYMghAI5K8P0aUb.hg8EE9u', 'Jam', '.', 'Magno', '', 'CEAS', 'BAComm', ''),
(202205092, '$2y$10$NjBmZWQyMDZmNjg3YzI4Nug1Z1s0EdwPYCEs2d5yRmjK7rY4nXqZa', 'Maria Leonor', 'Gerona', 'Robredo', '', 'CEAS', 'BSEd', '');

-- --------------------------------------------------------

--
-- Table structure for table `vote_candidate_tbl`
--

CREATE TABLE `vote_candidate_tbl` (
  `voteid_fld` int(11) NOT NULL,
  `studno_fld` int(11) NOT NULL,
  `envid_fld` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `votedata_fld` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`adminid_fld`);

--
-- Indexes for table `candidate_tbl`
--
ALTER TABLE `candidate_tbl`
  ADD PRIMARY KEY (`candidateid_fld`);

--
-- Indexes for table `position_tbl`
--
ALTER TABLE `position_tbl`
  ADD PRIMARY KEY (`posid_fld`);

--
-- Indexes for table `vote_candidate_tbl`
--
ALTER TABLE `vote_candidate_tbl`
  ADD PRIMARY KEY (`voteid_fld`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `adminid_fld` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `candidate_tbl`
--
ALTER TABLE `candidate_tbl`
  MODIFY `candidateid_fld` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `position_tbl`
--
ALTER TABLE `position_tbl`
  MODIFY `posid_fld` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `vote_candidate_tbl`
--
ALTER TABLE `vote_candidate_tbl`
  MODIFY `voteid_fld` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
