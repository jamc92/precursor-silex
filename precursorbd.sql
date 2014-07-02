-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 02-07-2014 a las 18:34:27
-- Versión del servidor: 5.6.16
-- Versión de PHP: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `precursorbd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo`
--

CREATE TABLE IF NOT EXISTS `articulo` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de cada registro',
  `id_autor` int(255) NOT NULL COMMENT 'Referencia al autor del artículo',
  `id_categoria` int(11) NOT NULL COMMENT 'Referencia a la categoría del artículo',
  `titulo` varchar(255) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Título del artículo',
  `contenido` text COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Contenido que lleva el artículo',
  `fecha_pub` datetime NOT NULL COMMENT 'Fecha de publicación del artículo',
  `creado` datetime NOT NULL COMMENT 'Fecha cuando se crea el registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha cuando se modifica el registro',
  PRIMARY KEY (`id`),
  KEY `id_autor` (`id_autor`),
  KEY `id_categoria` (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Datos de los artículos del periódico' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_etiquetas`
--

CREATE TABLE IF NOT EXISTS `articulos_etiquetas` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_articulo` int(255) NOT NULL COMMENT 'Índice que hace relación a la tabla artículos',
  `id_etiqueta` int(11) NOT NULL COMMENT 'Índice que hace relación a la tabla etiquetas',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_articulo` (`id_articulo`,`id_etiqueta`),
  KEY `id_etiqueta` (`id_etiqueta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de cada registro',
  `id_categoria` int(255) DEFAULT NULL COMMENT 'Referencia a la categoría perteneciente',
  `nombre` varchar(255) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Nombre que describe la categoría',
  `creado` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de modificación del registro',
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE IF NOT EXISTS `comentario` (
  `id_articulo` int(255) NOT NULL COMMENT 'Índice que hace relación con la tabla de artículos',
  `id_autor` int(255) NOT NULL COMMENT 'Índice que hace relación con la tabla de usuarios',
  `asunto` varchar(255) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Asunto del comentario',
  `contenido` text COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Contenido del comentario. Puede ser HTML',
  `fecha` datetime NOT NULL COMMENT 'Fecha del comentario',
  PRIMARY KEY (`id_articulo`),
  KEY `id_autor` (`id_autor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiqueta`
--

CREATE TABLE IF NOT EXISTS `etiqueta` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único para cada registro',
  `nombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Nombre que describe la etiqueta',
  `creado` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `etiqueta`
--

INSERT INTO `etiqueta` (`id`, `nombre`, `creado`, `modificado`) VALUES
(1, 'Etiquetaasdasd', '2014-07-02 11:49:11', '2014-07-02 16:20:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único para cada registro',
  `nombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Nombre que describe el perfil',
  `creado` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id`, `nombre`, `creado`, `modificado`) VALUES
(1, 'Administrador', '2014-07-02 10:01:29', '2014-07-02 14:59:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único para cada registro',
  `id_perfil` int(11) NOT NULL COMMENT 'Índice para relación con tabla perfiles',
  `nombre` varchar(255) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Nombre y apellido',
  `correo` varchar(255) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Correo electrónico',
  `alias` varchar(32) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Alias para inicio de sesión',
  `clave` varchar(32) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Clave para inicio de sesión',
  `creado` datetime NOT NULL COMMENT 'Fecha de la creación del registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de la modificación del registro',
  PRIMARY KEY (`id`),
  KEY `id_perfil` (`id_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD CONSTRAINT `articulo_ibfk_2` FOREIGN KEY (`id_autor`) REFERENCES `usuario` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `articulo_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `articulos_etiquetas`
--
ALTER TABLE `articulos_etiquetas`
  ADD CONSTRAINT `articulos_etiquetas_ibfk_1` FOREIGN KEY (`id_etiqueta`) REFERENCES `etiqueta` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `articulos_etiquetas_ibfk_2` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `categoria_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `comentario_ibfk_3` FOREIGN KEY (`id_autor`) REFERENCES `usuario` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id`) ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
