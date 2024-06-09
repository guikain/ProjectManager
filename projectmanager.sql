-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 09, 2024 at 09:56 PM
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
-- Database: `projectmanager`
--

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
  `data_fim` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projetos`
--

INSERT INTO `projetos` (`id`, `nome`, `prioridade`, `dificuldade`, `data_inicio`, `prazo`, `data_fim`, `status`) VALUES
(1, 'Trabalho de PHP', 5, 5, '2024-06-09', '2024-06-09', '2024-06-09', 'FINALIZADO'),
(2, 'Trabalho de C#', 3, 4, '2024-06-09', '2024-06-29', '-', 'AGUARDANDO'),
(3, 'Trabalho de Java', 5, 5, '2024-06-09', '2024-06-21', '-', 'EM ANDAMENTO');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `groupID`, `nome`, `sobrenome`, `data_nascimento`, `cpf`, `ddd`, `telefone`, `username`, `pass`) VALUES
(2, 2, 'Guilherme', 'Stempniak', '1997-07-21', '10297503900', 41, '991051033', 'guiniak', '$argon2i$v=19$m=65536,t=4,p=1$bFNYYWk0MkZ1cVdKbU4zSA$cLQ2+n93zbgkcm+xFONtuGgfKPM+/zBssVPzMAzy0tg');

-- --------------------------------------------------------

--
-- Table structure for table `vinculo`
--

DROP TABLE IF EXISTS `vinculo`;
CREATE TABLE IF NOT EXISTS `vinculo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user_fk` int NOT NULL,
  `id_projeto_fk` int NOT NULL,
  `checkin` datetime NOT NULL,
  `checkout` datetime NOT NULL,
  `status` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `joined` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vinculo`
--

INSERT INTO `vinculo` (`id`, `id_user_fk`, `id_projeto_fk`, `checkin`, `checkout`, `status`, `descricao`, `joined`) VALUES
(25, 2, 1, '2024-06-09 21:51:06', '2024-06-09 21:51:06', 'FINALIZADO', '', 1),
(26, 2, 1, '2024-06-09 21:51:06', '2024-06-09 21:51:23', 'FINALIZADO', 'trabalho concluido', 0),
(21, 2, 1, '2024-06-09 21:42:07', '2024-06-09 21:42:07', 'FINALIZADO', '', 1),
(22, 2, 1, '2024-06-09 21:42:07', '2024-06-09 21:42:12', 'FINALIZADO', 'xD', 0),
(23, 2, 1, '2024-06-09 21:42:24', '2024-06-09 21:42:24', 'FINALIZADO', '', 1),
(24, 2, 1, '2024-06-09 21:42:24', '2024-06-09 21:43:55', 'FINALIZADO', 'show', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
