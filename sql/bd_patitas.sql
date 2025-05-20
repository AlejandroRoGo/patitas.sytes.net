-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-05-2025 a las 10:13:52
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_patitas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `estado` enum('activo','convertido','abandonado') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id_carrito`, `id_usuario`, `fecha_creacion`, `estado`) VALUES
(1, 1, '2025-05-15 17:47:29', 'activo'),
(2, 2, '2025-05-15 17:47:29', 'activo'),
(3, 3, '2025-05-15 17:47:29', 'activo'),
(4, 4, '2025-05-15 20:01:44', 'activo'),
(5, 5, '2025-05-15 22:30:04', 'convertido'),
(6, 5, '2025-05-15 22:34:51', 'convertido'),
(7, 5, '2025-05-15 22:41:27', 'activo'),
(8, 6, '2025-05-16 15:51:58', 'convertido'),
(9, 6, '2025-05-16 16:56:53', 'activo'),
(10, 7, '2025-05-16 17:42:48', 'activo'),
(11, 9, '2025-05-17 12:51:07', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_detalle`
--

CREATE TABLE `carrito_detalle` (
  `id_carrito_detalle` int(11) NOT NULL,
  `id_carrito` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carrito_detalle`
--

INSERT INTO `carrito_detalle` (`id_carrito_detalle`, `id_carrito`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 2, 19.95),
(2, 1, 4, 1, 12.99),
(3, 2, 8, 1, 18.60),
(4, 2, 11, 6, 2.95),
(5, 3, 13, 1, 8.99),
(6, 3, 17, 2, 12.50),
(7, 2, 3, 1, 3.99),
(21, 9, 15, 2, 6.75),
(22, 9, 21, 16, 3.99),
(23, 9, 5, 3, 34.95),
(24, 9, 16, 1, 7.50),
(29, 4, 2, 1, 21.50),
(30, 4, 12, 1, 3.50),
(31, 10, 12, 4, 3.50),
(32, 11, 21, 1, 3.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagenes` varchar(100) NOT NULL DEFAULT 'img.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`, `imagenes`) VALUES
(1, 'Perros', 'Productos para perros', 'perro.jpg'),
(2, 'Gatos', 'Productos para gatos', 'gato.jpg'),
(3, 'Otras Mascotas', 'Productos para otras mascotas (aves, roedores, reptiles)', 'Otras.jpg'),
(4, 'Cuidado e Higiene', 'Productos de higiene para mascotas', 'higiene.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones_envio`
--

CREATE TABLE `direcciones_envio` (
  `id_direccion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `telefono_envio` varchar(20) DEFAULT NULL,
  `alias` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `direcciones_envio`
--

INSERT INTO `direcciones_envio` (`id_direccion`, `id_usuario`, `direccion`, `ciudad`, `provincia`, `codigo_postal`, `pais`, `telefono_envio`, `alias`) VALUES
(1, 1, 'Calle Mayor 12', 'Madrid', 'Madrid', '28001', 'España', '611223344', 'Casa'),
(2, 2, 'Avda. del Parque 4', 'Valencia', 'Valencia', '46008', 'España', '633445566', 'Piso'),
(3, 3, 'C/ Libertad 22', 'Sevilla', 'Sevilla', '41001', 'España', '644556677', 'Trabajo'),
(4, 5, 'Madrid', 'Madri', 'Madrid', '28038', 'España', '654123789', 'Casa'),
(5, 6, 'adsf', 'asdf', 'asdf', 'asdf', 'asdf', 'asdf', 'asdf'),
(6, 7, 'LKJHGFD', 'ÑLKJHGF', '´ÑLKJHG', '´ÑLKJHG', '-.,MNB', 'LKJHGF', 'LKJHG'),
(7, 9, 'Ff', 'Ggg', 'Ff', '28008', 'España', '618353645', 'Casa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id_metodo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo` enum('tarjeta','paypal','transferencia') NOT NULL,
  `titular` varchar(100) DEFAULT NULL,
  `numero_tarjeta` varchar(20) DEFAULT NULL,
  `caducidad` varchar(7) DEFAULT NULL,
  `paypal_email` varchar(150) DEFAULT NULL,
  `iban` varchar(34) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `fecha_agregado` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id_metodo`, `id_usuario`, `tipo`, `titular`, `numero_tarjeta`, `caducidad`, `paypal_email`, `iban`, `token`, `fecha_agregado`) VALUES
(1, 1, 'tarjeta', 'Alejandro Romero', '1234', '12/2027', NULL, NULL, 'tok_alej_1234', '2025-05-15 17:47:29'),
(2, 2, 'paypal', NULL, NULL, NULL, 'lucia@paypal.com', NULL, 'tok_lucia_ppal', '2025-05-15 17:47:29'),
(3, 3, 'transferencia', NULL, NULL, NULL, NULL, 'ES9820385778983000760236', 'tok_pedro_iban', '2025-05-15 17:47:29'),
(4, 5, 'tarjeta', 'Yo', '12343453456456745674', '03/04', NULL, NULL, 'tok_68264f10a47c12.69865245', '2025-05-15 22:31:12'),
(5, 6, 'tarjeta', 'asdfasdf', '123456789', '11/25', NULL, NULL, 'tok_682742f7cd3743.90754807', '2025-05-16 15:51:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_direccion` int(11) NOT NULL,
  `id_metodo` int(11) DEFAULT NULL,
  `fecha_pedido` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','pagado','enviado','entregado','cancelado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_usuario`, `id_direccion`, `id_metodo`, `fecha_pedido`, `total`, `estado`) VALUES
(1, 1, 1, 1, '2025-05-15 17:47:29', 52.89, 'pagado'),
(2, 2, 2, 2, '2025-05-15 17:47:29', 27.45, 'pendiente'),
(3, 5, 4, 4, '2025-05-15 22:31:25', 195.56, 'pendiente'),
(4, 5, 4, 4, '2025-05-15 22:35:05', 6.99, 'pendiente'),
(5, 6, 5, 5, '2025-05-16 15:52:40', 60.75, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

CREATE TABLE `pedido_detalle` (
  `id_pedido_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedido_detalle`
--

INSERT INTO `pedido_detalle` (`id_pedido_detalle`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 2, 19.95),
(2, 1, 4, 1, 12.99),
(3, 2, 8, 1, 18.60),
(4, 2, 11, 3, 2.95),
(5, 3, 19, 1, 8.99),
(6, 3, 5, 1, 34.95),
(7, 3, 21, 38, 3.99),
(8, 4, 20, 1, 6.99),
(9, 5, 15, 9, 6.75);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `id_categoria`, `imagen`) VALUES
(1, 'Croquetas ecológicas de pollo', 'Croquetas saludables para perros de todas las edades', 19.95, 30, 1, 'img1.jpg'),
(2, 'Croquetas ecológicas de cordero', 'Croquetas naturales sin aditivos', 21.50, 20, 1, 'img2.jpg'),
(3, 'Snack hueso vegetal', 'Hueso masticable de origen vegetal', 3.99, 50, 1, 'img3.jpg'),
(4, 'Correa de materiales reciclados', 'Correa ecológica de 1,2 m para perros', 12.99, 15, 1, 'img4.jpg'),
(5, 'Cama algodón orgánico', 'Cama para perro tamaño mediano', 34.95, 10, 1, 'img5.jpg'),
(6, 'Pelota de caucho natural', 'Juguete ecológico y resistente', 6.99, 40, 1, 'img6.jpg'),
(7, 'Pienso ecológico pollo', 'Pienso premium sin conservantes', 17.80, 25, 2, 'img7.jpg'),
(8, 'Pienso ecológico salmón', 'Pienso equilibrado con omega 3', 18.60, 18, 2, 'img8.jpg'),
(9, 'Premio pasta de malta', 'Ayuda a la digestión y bolas de pelo', 4.50, 35, 2, 'img9.jpg'),
(10, 'Rascador cartón reciclado', 'Rascador ecológico tamaño estándar', 14.99, 9, 2, 'img10.jpg'),
(11, 'Ratón de tela reciclada', 'Juguete ligero y divertido', 2.95, 25, 2, 'img11.jpg'),
(12, 'Pelota de lana natural', 'Juguete sostenible para gatos', 3.50, 20, 2, 'img12.jpg'),
(13, 'Heno ecológico para conejos', 'Alimento natural para pequeños mamíferos', 8.99, 12, 3, 'img13.jpg'),
(14, 'Snack natural de frutas', 'Mezcla de frutas deshidratadas para roedores', 5.25, 16, 3, 'img14.jpg'),
(15, 'Comida ecológica para aves', 'Mezcla de semillas ecológicas', 6.75, 18, 3, 'img15.jpg'),
(16, 'Juguete de madera para aves', 'Juguete colgante de madera reciclada', 7.50, 10, 3, 'img16.jpg'),
(17, 'Sustrato ecológico para reptiles', 'Sustrato vegetal para terrarios', 12.50, 8, 3, 'img17.jpg'),
(18, 'Champú ecológico para perros', 'Sin parabenos ni sulfatos', 9.99, 30, 4, 'img18.jpg'),
(19, 'Champú ecológico para gatos', 'Fórmula suave para gatos', 8.99, 25, 4, 'img19.jpg'),
(20, 'Cepillo de bambú', 'Cepillo sostenible para el pelaje', 6.99, 22, 4, 'img20.jpg'),
(21, 'Bolsas biodegradables para excrementos', 'Pack de 60 bolsas', 3.99, 40, 4, 'img21.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` datetime DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellidos`, `email`, `password`, `telefono`, `fecha_nacimiento`, `fecha_registro`) VALUES
(1, 'Alejandro', 'Romero', 'alejandro@correo.com', '$2y$10$A0CeRfHCgnlMOYDirlBt0uy.vHnI5npkmo.GYGDKx7z6s9zMNMowq', '611223344', NULL, '2025-05-15 17:47:29'),
(2, 'Lucía', 'Martínez', 'lucia@correo.com', '$2y$10$A0CeRfHCgnlMOYDirlBt0uy.vHnI5npkmo.GYGDKx7z6s9zMNMowq', '633445566', '2025-05-08 00:00:00', '2025-05-15 17:47:29'),
(3, 'Pedro', 'López', 'pedro@correo.com', '$2y$10$A0CeRfHCgnlMOYDirlBt0uy.vHnI5npkmo.GYGDKx7z6s9zMNMowq', '644556677', NULL, '2025-05-15 17:47:29'),
(4, 'alejandro', 'romero', 'alex@correo.es', '$2y$10$A0CeRfHCgnlMOYDirlBt0uy.vHnI5npkmo.GYGDKx7z6s9zMNMowq', '682123123', '2003-02-01 00:00:00', '2025-05-15 19:08:50'),
(5, 'Fer', 'fer', 'fer@correo.es', '$2y$10$ynfCEGzO7u.uVzcJPNlvk.GrktfNE1prEj4wZyLLdZhRLzVcs26MW', '123456789', '3123-03-12 00:00:00', '2025-05-15 22:29:59'),
(6, 'Guiler', 'Erencia', 'guille@correo.es', '$2y$10$OG/Oq2NHOzyl5.kohx.AT.ggQAqCYHr0j7TRSaVSX6gxsbxRy8pam', '123456789', '2025-05-07 00:00:00', '2025-05-16 15:50:08'),
(7, 'Enrique', 'AMldoando', 'enrique.maldonado@gmail.com', '$2y$10$JCxqIPMhPn4S1.Zn./EjrejG/gPw4S0Y.M54n1YnjgCsorI1PLiay', 'comemeelhuevo', '2026-12-30 00:00:00', '2025-05-16 17:39:55'),
(8, 'Guille2', 'Lamejorweb', 'guille@mail.com', '$2y$10$wQceaaQPBg67uMbrSTL9.uGXiGJG/hLXtgizKJBxZn3DSXvl8ZsRe', '', '0000-00-00 00:00:00', '2025-05-16 18:19:24'),
(9, 'Pepito', 'The', 'pepitorhe@gmail.con', '$2y$10$xjW/zqrifKfu8VMicQe66uXEZyxO0iXn4r.sy9ihfSmWfytwJvwgO', '654321789', '2025-05-05 00:00:00', '2025-05-17 12:50:58');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  ADD PRIMARY KEY (`id_carrito_detalle`),
  ADD KEY `id_carrito` (`id_carrito`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `direcciones_envio`
--
ALTER TABLE `direcciones_envio`
  ADD PRIMARY KEY (`id_direccion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id_metodo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_direccion` (`id_direccion`),
  ADD KEY `id_metodo` (`id_metodo`);

--
-- Indices de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD PRIMARY KEY (`id_pedido_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  MODIFY `id_carrito_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `direcciones_envio`
--
ALTER TABLE `direcciones_envio`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  MODIFY `id_pedido_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  ADD CONSTRAINT `carrito_detalle_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id_carrito`),
  ADD CONSTRAINT `carrito_detalle_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `direcciones_envio`
--
ALTER TABLE `direcciones_envio`
  ADD CONSTRAINT `direcciones_envio_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD CONSTRAINT `metodos_pago_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_direccion`) REFERENCES `direcciones_envio` (`id_direccion`),
  ADD CONSTRAINT `pedidos_ibfk_3` FOREIGN KEY (`id_metodo`) REFERENCES `metodos_pago` (`id_metodo`);

--
-- Filtros para la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD CONSTRAINT `pedido_detalle_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `pedido_detalle_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
