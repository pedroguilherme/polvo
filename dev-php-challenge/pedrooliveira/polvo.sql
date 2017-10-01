-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 01-Out-2017 às 22:29
-- Versão do servidor: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `polvo`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL,
  `id_carrinho` int(11) DEFAULT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `carrinho`
--

INSERT INTO `carrinho` (`id`, `id_carrinho`, `id_produto`, `quantidade`) VALUES
(11, 7, 1, 2),
(19, 17, 5, 2),
(20, 20, 1, 1),
(21, 21, 1, 2),
(22, 22, 1, 1),
(23, 22, 5, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `id_carrinho` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valor_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `pedido`
--

INSERT INTO `pedido` (`id`, `id_carrinho`, `data`, `valor_total`) VALUES
(1, 7, '2017-09-30 17:00:04', '7979.98'),
(2, 17, '2017-10-01 17:03:16', '6319.98'),
(3, 17, '2017-10-01 17:06:09', '6319.98'),
(4, 20, '2017-10-01 17:07:16', '3889.99'),
(5, 21, '2017-10-01 17:09:18', '3889.99'),
(6, 21, '2017-10-01 17:12:50', '7779.98'),
(7, 21, '2017-10-01 17:16:18', '7779.98'),
(8, 22, '2017-10-01 17:17:16', '7049.98');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `id` int(11) NOT NULL,
  `sku_id` varchar(50) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `preco_venda` decimal(10,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`id`, `sku_id`, `nome`, `descricao`, `preco_venda`, `status`) VALUES
(1, '132248809', 'iPhone 7 256GB Prateado Desbloqueado IOS 10', 'O iPhone 7 melhora consideravelmente as partes mais importantes da sua experiência com o iPhone. Ele traz um conjunto de câmeras completamente novo, o melhor desempenho e a maior duração de bateria até hoje, sistema de alto-falantes estéreo e tela com mais brilho e cores. Além de ser resistente à água e respingos. É poderoso em cada detalhe. E poderoso como um todo.', '3889.99', 1),
(3, NULL, 'Smart TV LED 55\" LG Ultra HD 4K 55UJ6545 com Conversor Digital 4HDMI 2 USB Painel Ips Hdr e Magic Mobile Connection', 'O painel IPS 4K oferece excelente qualidade de imagem com 4x mais resolução que o Full HD. Com essa função você tem os mínimos detalhes captando cada cena da maneira mais fiel possível. O conteúdo como ele deve ser visto.', '3289.99', 0),
(5, '132295314', 'Notebook Dell Inspiron i15-5566-A50B Intel Core i7 8GB 1TB Tela LED 15.6\" Windows 10 - Branco', 'Com o Inspiron 15 5000, você terá uma nova perspectiva sobre como um Notebook poderá te ajudar, seja no trabalho, estudo ou entretenimento. Além de um design moderno e diferenciado, este modelo conta com uma tela de 15 polegadas em Alta Definição (1366 x 768) e um teclado confortável com Alpha numérico, possui a 7ª Geração de Processadores Intel Core i7, Placa de Vídeo Intel HD Graphics 620 para rodar softwares, jogos ou vídeos muito mais rápido e fluido. Memória de 8GB, Armazenamento de 1TB e Windows 10 para uma experiência aprimorada. Total conectividade com as principais funcionalidades como um Leitor de Cartão SD, HDMI e USB 3.0.', '3159.99', 1),
(6, NULL, 'Teste Produto', 'Testando 1234', '1908.63', 0),
(7, NULL, 'Nome do produto', 'Testeeeeee', '12355.34', 0),
(8, NULL, 'dsadasd', 'asdasd', '1935.00', 0),
(10, NULL, 'sdasdasd', 'asdasdasd', '1234.00', 0),
(12, NULL, 'asdasdas', 'aaaaa', '1234.00', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nome` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `login`, `senha`, `nome`) VALUES
(1, 'polvo.challenge', '$2y$10$/SEjFchCDQ8KSXEleG0sPOzATGswhXbWW.1Je6VhywWyVNR2FX1qa', 'Grupo Polvo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carrinho_ibfk_1` (`id_carrinho`),
  ADD KEY `carrinho_ibfk_2` (`id_produto`);

--
-- Indexes for table `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_carrinho` (`id_carrinho`);

--
-- Indexes for table `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku_id` (`sku_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `produto`
--
ALTER TABLE `produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_carrinho`) REFERENCES `carrinho` (`id_carrinho`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
