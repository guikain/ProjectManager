-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 28, 2024 at 10:59 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projmgr`
--

-- --------------------------------------------------------

--
-- Table structure for table `participa`
--

DROP TABLE IF EXISTS `participa`;
CREATE TABLE IF NOT EXISTS `participa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `if_user_fk` int NOT NULL,
  `id_projeto_fk` int NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `descricao` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projetos`
--

DROP TABLE IF EXISTS `projetos`;
CREATE TABLE IF NOT EXISTS `projetos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(20) NOT NULL,
  `prioridade` int NOT NULL,
  `dificuldade` int NOT NULL,
  `data_inicio` date NOT NULL,
  `prazo` date NOT NULL,
  `data_fim` varchar(8) NOT NULL,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projetos`
--

INSERT INTO `projetos` (`id`, `nome`, `prioridade`, `dificuldade`, `data_inicio`, `prazo`, `data_fim`, `status`) VALUES
(1, 'Trabalho de PHP', 5, 5, '2024-05-27', '2024-07-21', '-', 'EM ANDAMENTO');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `groupID` int NOT NULL,
  `nome` varchar(20) NOT NULL,
  `sobrenome` varchar(120) NOT NULL,
  `data_nascimento` date NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `ddd` int NOT NULL,
  `telefone` varchar(9) NOT NULL,
  `username` varchar(30) NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `groupID`, `nome`, `sobrenome`, `data_nascimento`, `cpf`, `ddd`, `telefone`, `username`, `pass`) VALUES
(1, 2, 'Guilherme', 'Stempniak', '1997-07-21', '10297503900', 41, '991051033', 'guiniak', '$argon2i$v=19$m=65536,t=4,p=1$UnRzM0hwU3p5Q1paNVZvbA$RvvVqIF+DVgzMDMXJ0z8PyqIXPZaDmj2xgXwUakM04w'),
(3, 1, 'Illio', 'Deus', '1998-06-12', '00000000001', 41, '991051034', 'illiodeus', '$argon2i$v=19$m=65536,t=4,p=1$cmhXdnFLTEJjWDBMZjYuag$qZu6U44MRElXPxF9XU9VF4iy52hUhZms1XWZHAYYuvs');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
