-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-05-2024 a las 01:51:06
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectofinal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilidades`
--

CREATE TABLE `habilidades` (
  `habilidad_id` int(11) NOT NULL,
  `nombreHabilidad` varchar(200) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` enum('Tecnología y electrónica','Salud y bienestar','Arte y creatividad','Educación y aprendizaje','Negocios y finanzas','Comunicación y relaciones interpersonales','Hogar y estilo de vida','Deportes y actividad física','Desarrollo personal y bienestar emocional','Idiomas') DEFAULT NULL,
  `tipoIntercambio` enum('enseñar','aprender') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `habilidades`
--

INSERT INTO `habilidades` (`habilidad_id`, `nombreHabilidad`, `descripcion`, `categoria`, `tipoIntercambio`) VALUES
(15, 'Desarrollo web', 'Desarrollo de aplicaciones web utilizando tecnologías como HTML, CSS, JavaScript, etc.', 'Tecnología y electrónica', 'aprender'),
(16, 'Diseño gráfico', 'Creación de elementos visuales atractivos utilizando herramientas de diseño como Photoshop, Illustrator, etc.', 'Arte y creatividad', 'enseñar'),
(17, 'Inglés', 'Aprendizaje del idioma inglés para mejorar la comunicación y las oportunidades laborales.', 'Idiomas', 'aprender'),
(18, 'Yoga', 'Práctica de ejercicios de yoga para mejorar la flexibilidad, el equilibrio y la relajación.', 'Salud y bienestar', 'aprender'),
(19, 'Gestión financiera personal', 'Planificación financiera personal para alcanzar metas financieras y mantener la estabilidad económica.', 'Negocios y finanzas', 'enseñar'),
(20, 'Comunicación efectiva', 'Desarrollo de habilidades de comunicación verbal y no verbal para expresarse con claridad y persuasión.', 'Comunicación y relaciones interpersonales', 'aprender'),
(21, 'Cocina italiana', 'Aprendizaje de recetas y técnicas de cocina italiana para preparar platos deliciosos y auténticos.', 'Hogar y estilo de vida', 'aprender'),
(22, 'Jardinería básica', 'Conocimientos básicos sobre cuidado de plantas y jardinería para mantener un jardín saludable y hermoso.', 'Hogar y estilo de vida', 'aprender'),
(23, 'Entrenamiento de fútbol', 'Enseñanza de técnicas de fútbol, tácticas de juego y habilidades físicas a jugadores jóvenes.', 'Deportes y actividad física', 'enseñar'),
(24, 'Mindfulness', 'Práctica de mindfulness y meditación para cultivar la atención plena y reducir el estrés.', 'Desarrollo personal y bienestar emocional', 'aprender'),
(25, 'Cocina japonesa', 'Aprendizaje de recetas y técnicas de cocina japonesa para preparar platos deliciosos y auténticos.', 'Hogar y estilo de vida', 'enseñar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilidad_usuario`
--

CREATE TABLE `habilidad_usuario` (
  `hu_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `habilidad_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `habilidad_usuario`
--

INSERT INTO `habilidad_usuario` (`hu_id`, `usuario_id`, `habilidad_id`) VALUES
(1, 5, 17),
(2, 6, 21),
(3, 7, 19),
(4, 8, 15),
(5, 9, 23),
(12, 9, 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intercambios`
--

CREATE TABLE `intercambios` (
  `intercambio_id` int(11) NOT NULL,
  `us_ofertante` int(11) DEFAULT NULL,
  `us_demandante` int(11) DEFAULT NULL,
  `hab_ofrecida` int(11) DEFAULT NULL,
  `hab_requerida` int(11) DEFAULT NULL,
  `estado` enum('pendiente','en curso','completado','cancelado') DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_final` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `mensaje_id` int(11) NOT NULL,
  `exchange_id` int(11) DEFAULT NULL,
  `emisor` int(11) DEFAULT NULL,
  `receptor` int(11) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(255) NOT NULL,
  `edad_usuario` varchar(50) DEFAULT NULL,
  `ubicacion_usuario` varchar(255) DEFAULT NULL,
  `correo_solicitante` varchar(255) NOT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `correo` varchar(200) DEFAULT NULL,
  `contrasena` varchar(200) DEFAULT NULL,
  `ubicacion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `edad`, `correo`, `contrasena`, `ubicacion`) VALUES
(5, 'Aitana', 24, 'aitana@gmail.com', '$2y$10$5fLeNjLObYwIbsah7TU7yeK4BxqrSEC1iGmP9aAdMk6.jiM8xmdBG', 'Valencia'),
(6, 'Lucas', 22, 'lucas@gmail.com', '$2y$10$GPFF5G83xLYjVwVm04h1suR4pAyJXsPE9s.FJ.UDB2eic5x9yuSwW', 'Alicante'),
(7, 'Marta', 26, 'marta@gmail.com', '$2y$10$CiXGhAKi85fQ1rRH42ooDuDqfU.5gebAQ/CJP3sNYj..cDyV03nYa', 'Zaragoza'),
(8, 'Juan', 28, 'juan@gmail.com', '$2y$10$AwMqGDG9I1hjzwwGjwU0pOdSObpcGxDwfUq8jvb9FpiMDekWSZX4W', 'Galicia'),
(9, 'Sara', 25, 'sara@gmail.com', '$2y$10$IT5AoQB72FYC.YAR8PZqZepj7ohBjXbRUJ.4i2Clar3RHLMZgVFfW', 'Madrid');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoraciones`
--

CREATE TABLE `valoraciones` (
  `valoracion_id` int(11) NOT NULL,
  `exchange_id` int(11) DEFAULT NULL,
  `us_evaluador` int(11) DEFAULT NULL,
  `us_evaluado` int(11) DEFAULT NULL,
  `puntuacion` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  ADD PRIMARY KEY (`habilidad_id`);

--
-- Indices de la tabla `habilidad_usuario`
--
ALTER TABLE `habilidad_usuario`
  ADD PRIMARY KEY (`hu_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_habilidad_usuario_habilidades` (`habilidad_id`);

--
-- Indices de la tabla `intercambios`
--
ALTER TABLE `intercambios`
  ADD PRIMARY KEY (`intercambio_id`),
  ADD KEY `us_ofertante` (`us_ofertante`),
  ADD KEY `us_demandante` (`us_demandante`),
  ADD KEY `hab_ofrecida` (`hab_ofrecida`),
  ADD KEY `hab_requerida` (`hab_requerida`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`mensaje_id`),
  ADD KEY `exchange_id` (`exchange_id`),
  ADD KEY `emisor` (`emisor`),
  ADD KEY `receptor` (`receptor`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`);

--
-- Indices de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD PRIMARY KEY (`valoracion_id`),
  ADD KEY `exchange_id` (`exchange_id`),
  ADD KEY `us_evaluador` (`us_evaluador`),
  ADD KEY `us_evaluado` (`us_evaluado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  MODIFY `habilidad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `habilidad_usuario`
--
ALTER TABLE `habilidad_usuario`
  MODIFY `hu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `intercambios`
--
ALTER TABLE `intercambios`
  MODIFY `intercambio_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `mensaje_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  MODIFY `valoracion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `habilidad_usuario`
--
ALTER TABLE `habilidad_usuario`
  ADD CONSTRAINT `fk_habilidad_usuario_habilidades` FOREIGN KEY (`habilidad_id`) REFERENCES `habilidades` (`habilidad_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `habilidad_usuario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `habilidad_usuario_ibfk_2` FOREIGN KEY (`habilidad_id`) REFERENCES `habilidades` (`habilidad_id`);

--
-- Filtros para la tabla `intercambios`
--
ALTER TABLE `intercambios`
  ADD CONSTRAINT `intercambios_ibfk_1` FOREIGN KEY (`us_ofertante`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `intercambios_ibfk_2` FOREIGN KEY (`us_demandante`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `intercambios_ibfk_3` FOREIGN KEY (`hab_ofrecida`) REFERENCES `habilidades` (`habilidad_id`),
  ADD CONSTRAINT `intercambios_ibfk_4` FOREIGN KEY (`hab_requerida`) REFERENCES `habilidades` (`habilidad_id`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`exchange_id`) REFERENCES `intercambios` (`intercambio_id`),
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`emisor`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `mensajes_ibfk_3` FOREIGN KEY (`receptor`) REFERENCES `usuarios` (`usuario_id`);

--
-- Filtros para la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD CONSTRAINT `valoraciones_ibfk_1` FOREIGN KEY (`exchange_id`) REFERENCES `intercambios` (`intercambio_id`),
  ADD CONSTRAINT `valoraciones_ibfk_2` FOREIGN KEY (`us_evaluador`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `valoraciones_ibfk_3` FOREIGN KEY (`us_evaluado`) REFERENCES `usuarios` (`usuario_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
