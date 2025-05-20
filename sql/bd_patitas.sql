-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-05-2025 a las 17:47:44
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
(3, 3, '2025-05-15 17:47:29', 'activo');

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
(4, 2, 11, 3, 2.95),
(5, 3, 13, 1, 8.99),
(6, 3, 17, 2, 12.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Perros', 'Productos para perros'),
(2, 'Gatos', 'Productos para gatos'),
(3, 'Otras Mascotas', 'Productos para otras mascotas (aves, roedores, reptiles)'),
(4, 'Cuidado e Higiene', 'Productos de higiene para mascotas');

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
(2, 2, 'Avda. del Parque 4', 'Valencia', 'Valencia', '46007', 'España', '633445566', 'Piso'),
(3, 3, 'C/ Libertad 22', 'Sevilla', 'Sevilla', '41001', 'España', '644556677', 'Trabajo');

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
(3, 3, 'transferencia', NULL, NULL, NULL, NULL, 'ES9820385778983000760236', 'tok_pedro_iban', '2025-05-15 17:47:29');

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
(2, 2, 2, 2, '2025-05-15 17:47:29', 27.45, 'pendiente');

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
(4, 2, 11, 3, 2.95);

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
(1, 'Croquetas ecológicas de pollo', 'Croquetas saludables para perros de todas las edades', 19.95, 30, 1, 'img.jpg'),
(2, 'Croquetas ecológicas de cordero', 'Croquetas naturales sin aditivos', 21.50, 20, 1, 'img.jpg'),
(3, 'Snack hueso vegetal', 'Hueso masticable de origen vegetal', 3.99, 50, 1, 'img.jpg'),
(4, 'Correa de materiales reciclados', 'Correa ecológica de 1,2 m para perros', 12.99, 15, 1, 'img.jpg'),
(5, 'Cama algodón orgánico', 'Cama para perro tamaño mediano', 34.95, 10, 1, 'img.jpg'),
(6, 'Pelota de caucho natural', 'Juguete ecológico y resistente', 6.99, 40, 1, 'img.jpg'),
(7, 'Pienso ecológico pollo', 'Pienso premium sin conservantes', 17.80, 25, 2, 'img.jpg'),
(8, 'Pienso ecológico salmón', 'Pienso equilibrado con omega 3', 18.60, 18, 2, 'img.jpg'),
(9, 'Premio pasta de malta', 'Ayuda a la digestión y bolas de pelo', 4.50, 35, 2, 'img.jpg'),
(10, 'Rascador cartón reciclado', 'Rascador ecológico tamaño estándar', 14.99, 9, 2, 'img.jpg'),
(11, 'Ratón de tela reciclada', 'Juguete ligero y divertido', 2.95, 25, 2, 'img.jpg'),
(12, 'Pelota de lana natural', 'Juguete sostenible para gatos', 3.50, 20, 2, 'img.jpg'),
(13, 'Heno ecológico para conejos', 'Alimento natural para pequeños mamíferos', 8.99, 12, 3, 'img.jpg'),
(14, 'Snack natural de frutas', 'Mezcla de frutas deshidratadas para roedores', 5.25, 16, 3, 'img.jpg'),
(15, 'Comida ecológica para aves', 'Mezcla de semillas ecológicas', 6.75, 18, 3, 'img.jpg'),
(16, 'Juguete de madera para aves', 'Juguete colgante de madera reciclada', 7.50, 10, 3, 'img.jpg'),
(17, 'Sustrato ecológico para reptiles', 'Sustrato vegetal para terrarios', 12.50, 8, 3, 'img.jpg'),
(18, 'Champú ecológico para perros', 'Sin parabenos ni sulfatos', 9.99, 30, 4, 'img.jpg'),
(19, 'Champú ecológico para gatos', 'Fórmula suave para gatos', 8.99, 25, 4, 'img.jpg'),
(20, 'Cepillo de bambú', 'Cepillo sostenible para el pelaje', 6.99, 22, 4, 'img.jpg'),
(21, 'Bolsas biodegradables para excrementos', 'Pack de 60 bolsas', 3.99, 40, 4, 'img.jpg');

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
(1, 'Alejandro', 'Romero', 'alejandro@correo.com', 'clavehash', '611223344', NULL, '2025-05-15 17:47:29'),
(2, 'Lucía', 'Martínez', 'lucia@correo.com', 'clavehash', '633445566', NULL, '2025-05-15 17:47:29'),
(3, 'Pedro', 'López', 'pedro@correo.com', 'clavehash', '644556677', NULL, '2025-05-15 17:47:29');

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
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  MODIFY `id_carrito_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `direcciones_envio`
--
ALTER TABLE `direcciones_envio`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  MODIFY `id_pedido_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
