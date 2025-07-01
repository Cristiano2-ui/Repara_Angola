-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 01:06 AM
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
-- Database: `sr`
--

-- --------------------------------------------------------

--
-- Table structure for table `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id` bigint(20) NOT NULL,
  `empresa_id` bigint(20) NOT NULL,
  `cidadao_id` bigint(20) NOT NULL,
  `nota` int(11) DEFAULT NULL CHECK (`nota` >= 1 and `nota` <= 5),
  `comentario` text DEFAULT NULL,
  `data_avaliacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historico_precos`
--

CREATE TABLE `historico_precos` (
  `id` bigint(20) NOT NULL,
  `tipo_material` varchar(100) NOT NULL,
  `preco_por_kg_kz` decimal(10,2) NOT NULL,
  `data_registro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `negociacoes`
--

CREATE TABLE `negociacoes` (
  `id` bigint(20) NOT NULL,
  `resíduo_id` bigint(20) NOT NULL,
  `empresa_id` bigint(20) NOT NULL,
  `valor_estimado_kz` decimal(10,2) DEFAULT NULL,
  `valor_final_kz` decimal(10,2) DEFAULT NULL,
  `status` enum('pendente','confirmada','cancelada') DEFAULT 'pendente',
  `data_negociacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `mensagem` text NOT NULL,
  `lida` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesagens`
--

CREATE TABLE `pesagens` (
  `id` bigint(20) NOT NULL,
  `negociacao_id` bigint(20) NOT NULL,
  `peso_real_kg` decimal(10,2) NOT NULL,
  `valor_final_calculado` decimal(10,2) DEFAULT NULL,
  `data_pesagem` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `residuos`
--

CREATE TABLE `residuos` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `tipo_material` varchar(100) NOT NULL,
  `estado_conservacao` varchar(100) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `localizacao` text DEFAULT NULL,
  `quantidade_estimada_kg` decimal(10,2) DEFAULT NULL,
  `status` enum('disponivel','negociando','coletado') DEFAULT 'disponivel',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo_usuario` enum('empresa','cidadao') NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `localizacao` text DEFAULT NULL,
  `verificado` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id` (`empresa_id`),
  ADD KEY `cidadao_id` (`cidadao_id`);

--
-- Indexes for table `historico_precos`
--
ALTER TABLE `historico_precos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `negociacoes`
--
ALTER TABLE `negociacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resíduo_id` (`resíduo_id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Indexes for table `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pesagens`
--
ALTER TABLE `pesagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `negociacao_id` (`negociacao_id`);

--
-- Indexes for table `residuos`
--
ALTER TABLE `residuos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `historico_precos`
--
ALTER TABLE `historico_precos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `negociacoes`
--
ALTER TABLE `negociacoes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesagens`
--
ALTER TABLE `pesagens`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `residuos`
--
ALTER TABLE `residuos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `avaliacoes_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avaliacoes_ibfk_2` FOREIGN KEY (`cidadao_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `negociacoes`
--
ALTER TABLE `negociacoes`
  ADD CONSTRAINT `negociacoes_ibfk_1` FOREIGN KEY (`resíduo_id`) REFERENCES `residuos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `negociacoes_ibfk_2` FOREIGN KEY (`empresa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesagens`
--
ALTER TABLE `pesagens`
  ADD CONSTRAINT `pesagens_ibfk_1` FOREIGN KEY (`negociacao_id`) REFERENCES `negociacoes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `residuos`
--
ALTER TABLE `residuos`
  ADD CONSTRAINT `residuos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
