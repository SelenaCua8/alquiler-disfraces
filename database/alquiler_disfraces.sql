-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 09:11 PM
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
-- Database: `alquiler_disfraces`
--

-- --------------------------------------------------------

--
-- Table structure for table `disfraces`
--

CREATE TABLE `disfraces` (
  `id_disfraces` int(11) NOT NULL,
  `nombre_disfraz` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `imagen` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `disfraces`
--

INSERT INTO `disfraces` (`id_disfraces`, `nombre_disfraz`, `tipo`, `imagen`) VALUES
(1, 'Disfraz Bowling', 'otros', 'bowling.jpg'),
(2, 'Disfraz Conos', 'otros', 'conos.jpg'),
(3, 'Disfraz Calendario Jesús', 'religioso', 'calendario_jesus.jpg'),
(4, 'Disfraz Patty y Selma', 'personajes', 'patty_selma.jpg'),
(5, 'Disfraz de Terror', 'terror', 'terror.jpg'),
(6, 'Disfraz Trapos de Piso', 'otros', 'trapos_piso.jpg'),
(7, 'Disfraz Ovejas', 'animales', 'ovejas.jpg'),
(8, 'Disfraz Teletubbies', 'personajes', 'teletubbies.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `contraseña`, `tipo`) VALUES
(1, 'Selena', 'selena.cuadra@davinci.edu.ar', '123456789', 'cliente'),
(2, 'Paula', 'paula.giaimo@davinci.edu.ar', '123123123', 'administrador'),
(3, 'Juan', 'juan.perez@davinci.edu.ar', '111111111', 'empleado');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `disfraces`
--
ALTER TABLE `disfraces`
  ADD PRIMARY KEY (`id_disfraces`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `disfraces`
--
ALTER TABLE `disfraces`
  MODIFY `id_disfraces` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
