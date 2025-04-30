-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2024 a las 17:57:56
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `papeleria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_venta`
--

CREATE TABLE `detalles_venta` (
  `id_detalle` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_venta`
--

INSERT INTO `detalles_venta` (`id_detalle`, `id_venta`, `id_producto`, `cantidad`, `subtotal`) VALUES
(2, 5, 1, 1, 5.00),
(3, 6, 2, 1, 45.00),
(4, 7, 3, 1, 20.00),
(5, 9, 4, 1, 15.00),
(6, 10, 3, 1, 20.00),
(7, 11, 2, 1, 45.00),
(8, 12, 1, 1, 5.00),
(9, 13, 3, 1, 20.00),
(10, 14, 1, 1, 5.00),
(11, 14, 2, 1, 45.00),
(12, 15, 6, 1, 10.00),
(13, 16, 5, 1, 127.50),
(14, 17, 2, 1, 42.75),
(15, 18, 6, 1, 10.00),
(16, 19, 3, 1, 20.00),
(17, 20, 1, 1, 5.00),
(18, 20, 2, 1, 45.00),
(19, 21, 1, 1, 5.00),
(20, 21, 2, 1, 45.00),
(21, 22, 7, 1, 85.50),
(22, 23, 7, 1, 85.50),
(23, 24, 5, 1, 127.50),
(24, 25, 5, 1, 127.50),
(25, 26, 2, 1, 45.00),
(26, 26, 2, 1, 45.00),
(27, 27, 3, 1, 20.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `categoria` varchar(255) NOT NULL,
  `fecha_agregado` datetime DEFAULT current_timestamp(),
  `id_promocion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `categoria`, `fecha_agregado`, `id_promocion`) VALUES
(1, 'Mapa', NULL, 5.00, 3, '', '2024-11-09 11:14:56', NULL),
(2, 'Libreta', NULL, 45.00, 5, '', '2024-11-11 18:01:47', 5),
(3, 'Lápiz', NULL, 20.00, 3, '', '2024-11-11 18:02:02', NULL),
(4, 'Boligrafo', NULL, 15.00, 3, '', '2024-11-16 20:21:02', 6),
(5, 'Diccionario', NULL, 150.00, 7, '', '2024-11-19 16:41:31', 4),
(6, 'Goma', NULL, 10.00, 2, '', '2024-11-23 10:14:04', NULL),
(7, 'Paquete de 100 hojas blancas', NULL, 90.00, 4, '', '2024-11-23 19:31:29', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones`
--

CREATE TABLE `promociones` (
  `id_promocion` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `descuento` decimal(5,2) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promociones`
--

INSERT INTO `promociones` (`id_promocion`, `producto_id`, `nombre`, `descripcion`, `descuento`, `fecha_inicio`, `fecha_fin`, `activo`) VALUES
(4, 5, '', NULL, 15.00, '2024-11-23 00:00:00', '2024-11-25 00:00:00', 1),
(5, 2, '', NULL, 5.00, '2024-11-23 00:00:00', '2024-11-28 00:00:00', 0),
(6, 4, '', NULL, 5.00, '2024-11-23 00:00:00', '2024-11-24 00:00:00', 0),
(7, 7, '', NULL, 5.00, '2024-11-23 00:00:00', '2024-11-24 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `contacto` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre`, `contacto`, `fecha_creacion`) VALUES
(2, 'scribe', 'scribe@scribe.com', '2024-11-09 19:56:23'),
(3, 'pelikan', 'pelikan@pelikan.com', '2024-11-12 00:04:04'),
(4, 'Paper Mate', 'papermate@papermate.com', '2024-11-18 20:44:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`, `descripcion`) VALUES
(1, 'admin', 'Administrador del sistema, con acceso completo a todas las funcionalidades.'),
(2, 'trabajador', 'Trabajador encargado de ventas y atención al cliente.'),
(3, 'vendedor', 'Vendedor encargado de procesar ventas y cobros.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `usuario`, `password_hash`, `id_rol`, `fecha_registro`, `estado`) VALUES
(1, 'Luis Ramos', 'luis@gmail.com', 'LuisR', '$2y$10$qxjwzzYdN4WwcgTyOcgM0uFdJfMdM8OXmrODaFcVpcJs2cxzTd/g2', 1, '2024-11-08 12:56:24', 'activo'),
(2, 'Juan Perez', 'juan@gmail.com', 'JuanP', '$2y$10$9TnlKvdJnZWX91P1SS3UWOPsDIzxpVQSFqDgmGJm.gPWI/O0j7yK2', 2, '2024-11-08 13:10:26', 'activo'),
(3, 'Fernando Garcia Rios', 'fernando@gmail.com', 'FernandoGR', '$2y$10$K9zckD3f/0JL2BlXjIDpFOcv2jhOOgwT3aU7jgf9b/wdtP1WFlGOu', 3, '2024-11-09 10:47:00', 'activo'),
(4, 'Fatima Rios Dominguez', 'fatimarios@correo.com', 'FatimaRD', '$2y$10$wxWfDgnFANf3ikDpau.Hs.paGzAr.V6cyeNRA9RgTC7uMgkWKBeKa', 1, '2024-11-11 18:03:00', 'activo'),
(6, 'Leonardo Domingo', 'leonardo@gmail.com', 'LeonardoD', '$2y$10$m.nCU1XupNnvScndrk9mp.VtAddzAEsVv4qYYjOnkiC1kb.IeAcTW', 3, '2024-11-18 17:47:52', 'activo'),
(7, 'Uriel Palma', 'uriel@gmail.com', 'UrielP', '$2y$10$/VP5ijv4pIJAaNgSLRgMQu29j.be6XdihlFILpolqImBIhrkKEj0i', 2, '2024-11-18 17:48:52', 'activo'),
(8, 'Bernardo Roman', 'bernardo@gmail.com', 'BernardoR', '$2y$10$nQwCfXz0kMbc3ISSfUUvr.exyZOYl9nSaIrwgGH.p9Ws6kiZ9Dcxm', 1, '2024-11-18 17:49:35', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `fecha_venta` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','spei') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `fecha_venta`, `total`, `metodo_pago`) VALUES
(5, '2024-11-09 17:18:34', 5.00, 'efectivo'),
(6, '2024-11-11 18:05:27', 45.00, 'efectivo'),
(7, '2024-11-11 18:14:14', 20.00, 'tarjeta'),
(8, '2024-11-11 18:31:49', 0.00, 'efectivo'),
(9, '2024-11-17 19:16:22', 15.00, 'efectivo'),
(10, '2024-11-17 19:16:57', 20.00, 'efectivo'),
(11, '2024-11-18 17:02:29', 45.00, 'efectivo'),
(12, '2024-11-18 17:57:26', 5.00, 'efectivo'),
(13, '2024-11-18 17:58:13', 20.00, 'efectivo'),
(14, '2024-11-23 10:09:04', 50.00, 'efectivo'),
(15, '2024-11-23 10:15:11', 10.00, 'efectivo'),
(16, '2024-11-23 12:42:55', 127.50, 'efectivo'),
(17, '2024-11-23 15:41:23', 42.75, 'tarjeta'),
(18, '2024-11-23 18:21:30', 10.00, 'efectivo'),
(19, '2024-11-23 18:35:30', 20.00, 'efectivo'),
(20, '2024-11-23 20:21:12', 50.00, 'efectivo'),
(21, '2024-11-23 20:28:35', 50.00, 'efectivo'),
(22, '2024-11-23 20:34:32', 85.50, 'efectivo'),
(23, '2024-11-23 22:28:53', 85.50, 'efectivo'),
(24, '2024-11-24 09:56:21', 127.50, 'efectivo'),
(25, '2024-11-24 10:02:48', 127.50, 'efectivo'),
(26, '2024-11-24 10:18:58', 45.00, 'efectivo'),
(27, '2024-11-24 10:51:49', 20.00, 'efectivo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_producto_promocion` (`id_promocion`);

--
-- Indices de la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id_promocion`),
  ADD KEY `fk_producto_id` (`producto_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id_promocion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD CONSTRAINT `detalles_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`),
  ADD CONSTRAINT `detalles_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_producto_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promociones` (`id_promocion`);

--
-- Filtros para la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD CONSTRAINT `fk_producto_id` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
