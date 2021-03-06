-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 19, 2014 at 09:05 PM
-- Server version: 5.5.38
-- PHP Version: 5.4.4-14+deb7u14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `precursorbd`
--

-- --------------------------------------------------------

--
-- Table structure for table `articulo`
--

CREATE TABLE IF NOT EXISTS `articulo` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de cada registro',
  `id_autor` int(255) NOT NULL COMMENT 'Referencia al autor del artículo',
  `id_categoria` int(11) NOT NULL COMMENT 'Referencia a la categoría del artículo',
  `imagen` text COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Link de la imagen destacada del articulo',
  `titulo` varchar(255) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Título del artículo',
  `descripcion` varchar(100) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Descripción corta del artículo',
  `contenido` text COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Contenido que lleva el artículo',
  `fecha_pub` datetime NOT NULL COMMENT 'Fecha de publicación del artículo',
  `creado` datetime NOT NULL COMMENT 'Fecha cuando se crea el registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha cuando se modifica el registro',
  PRIMARY KEY (`id`),
  KEY `id_autor` (`id_autor`),
  KEY `id_categoria` (`id_categoria`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Datos de los artículos del periódico' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `articulo`
--

INSERT INTO `articulo` (`id`, `id_autor`, `id_categoria`, `imagen`, `titulo`, `descripcion`, `contenido`, `fecha_pub`, `creado`, `modificado`) VALUES
(1, 2, 3, 'http://precursor.esy.es/web/resources/uploads/05-10-2014/universidad en transformacion.png', 'UNIVERSIDAD EN TRANSFORMACION', 'el CUFM impulsa la transformación universitaria', '<p>Estudiantes, profesores y trabajadores del colegio universitario &ldquo;Francisco de Miranda&rdquo;, conjuntamente con integrantes de otras instituciones del pa&iacute;s, impulsan la transformaci&oacute;n universitaria para el socialismo. Trabajan igualmente, para que la universidad se vista de pueblo y responda a las urgencias y necesidades planteadas en estos momentos de revoluci&oacute;n. Para llevar adelante esta acci&oacute;n, que cambiara para siempre las estructuras de estos centros educativos, el CUFM se enlaza con misiones, colectivos organizados y comunidades en los cuales se entiende que la propuesta de una sociedad de justicia e igualdad, necesita de la formaci&oacute;n de nuevos ciudadanos.&nbsp;</p>', '2014-10-05 19:10:47', '2014-10-05 19:10:47', '2014-10-05 19:03:46'),
(2, 2, 4, 'http://precursor.esy.es/web/resources/uploads/05-10-2014/plan rector garantiza la formacion humanistica integral del hombre nuevo.png', 'PLAN RECTOR GARANTIZA LA FORMACIÓN HUMANÍSTICA INTEGRAL DEL HOMBRE NUEVO', 'Materiales para el Analisis', '<p>El plan rector del Colegio Universitario &ldquo;Francisco de Miranda&rdquo; es el compendio de gu&iacute;as a seguir para alcanzar la formaci&oacute;n human&iacute;stica integral del hombre nuevo del siglo XXI, y garantizar la &oacute;ptima ejecuci&oacute;n de las actividades Acad&eacute;micas-Administrativas, con la participaci&oacute;n, protagonismo de la comunidad en un ambiente propicio, con el prop&oacute;sito de generar aportes de soluciones a los procesos de transformaci&oacute;n del pa&iacute;s, contribuyendo a mejorar la calidad de vida. El documento que se gener&oacute; es el resultado de distintas reuniones y mesas de trabajo en las que participaron la comunidad universitaria y comunidad vecinal, considerando el basamento legal nacional&nbsp; y los lineamientos emanados del Ministerio del Poder Popular para la Educaci&oacute;n Universitaria, nuestra casa rectora logrando plasmar, el pensamiento filos&oacute;fico institucional como base para el desarrollo de acciones que conlleven al alcance de las metas y objetivos institucionales.</p>', '2014-10-05 23:10:22', '2014-10-05 23:10:22', '2014-10-07 03:09:38'),
(3, 2, 5, 'http://precursor.esy.es/web/resources/uploads/05-10-2014/estudiantes del cufm presentaron proyectos en beneficio de las comunidades 1.png', 'ESTUDIANTES DEL CUFM PRESENTARON PROYECTOS EN BENEFICIO DE COMUNIDADES', 'Cufm en Dimension', '<p>Durante la primera jornada de divulgaci&oacute;n de proyectos Socio-Comunitarios y Socio-Productivos del PNF, realizada en nuestra casa de estudios, se presentaron los trabajos realizados por los estudiantes en las comunidades de esta capital. La actividad fue organizada por los integrantes de la primera promoci&oacute;n de Ingenieros en Inform&aacute;tica y Licenciados en Administraci&oacute;n que egresan de esta universidad. Tomaron como base su tesis de grado, los problemas y necesidades sociales que est&aacute;n presentes en las barriadas y urbanizaciones; para apoyar las tareas que emprenden los pobladores en procura de mejores niveles de vida. Los proyectos que presentaron los 67 nuevos Ingenieros en Inform&aacute;tica, y 47 licenciados en Administraci&oacute;n, se corresponden con el nuevo enfoque y practica educativa, materializados a trav&eacute;s de los Programas Nacionales de Formaci&oacute;n (PNF), que posibilitan la vinculaci&oacute;n entre la universidad y el pueblo, en aspectos productivos y comunitarios que requieren transformaci&oacute;n. Estas jornadas fueron instaladas por la profesora Judith Salgado, directora del CUFM; la profesora Marisela de Eustache, Subdirectora Acad&eacute;mica; y la profesora Elia Oliveros, Coordinadora General de los Proyectos.</p>', '2014-10-05 23:10:19', '2014-10-05 23:10:19', '2014-10-05 23:52:17'),
(4, 2, 3, 'http://precursor.esy.es/web/resources/uploads/07-10-2014/EL CUFM AL CUMPLIR 37 AÑOS FORTALECE LA TRANSFORMACION UNIVERSITARIA PARA EL SOCIALISMO 6.png', 'EL CUFM AL CUMPLIR 37 AÑOS FORTALECE LA TRANSFORMACIÓN UNIVERSITARIA PARA EL SOCIALISMO', 'Semana Aniversario 37', '<p>Entre el 21 y 27 de febrero, el Colegio Universitario &ldquo;Francisco de Miranda&rdquo; celebro su semana aniversario, con un conjunto de actividades acad&eacute;micas, culturales y deportivas, en las cuales participaron vecinos y colectivos organizados de la parroquia Altagracia.</p>\r\n\r\n<p>Temas como el de la Ley de universidades, el cambio clim&aacute;tico, la defensor&iacute;a y la violencia estudiantil; la vida de Miranda, y otros temas de inter&eacute;s fueron expuestos por investigadores historiadores y docentes en los espacios de este centro educativo que presta servicio a miles de estudiantes del &aacute;rea metropolitana, Aragua, Vargas y Miranda, principalmente. Pero hay un t&oacute;pico relevante en esta comunidad: la transformaci&oacute;n universitaria para el socialismo. Pues, sus autoridades trabajan hoy en sinton&iacute;a con el proceso revolucionario que vive el pa&iacute;s, y pone en pr&aacute;ctica una educaci&oacute;n con valores de justicia, igualdad, solidaridad y car&aacute;cter antiimperialista, que posibilite la formaci&oacute;n de ciudadanos responsables y comprometidos con los cambios sociales. La Divisi&oacute;n de Interacci&oacute;n Social, que dirige el profesor Manuel Fari&ntilde;as, estructuro un programa contentivo de jornadas de salud, torneos deportivos, exposiciones de libros, bautizos de libros, bautizos de revistas literarias, expo ciencia y tecnolog&iacute;a, artesan&iacute;a gastronom&iacute;a y otros eventos donde estudiantes, profesores trabajadores y vecinos compartieron experiencias y recibieron informaci&oacute;n sobre estos aspectos importantes de la educaci&oacute;n universitaria. Fue un momento de encuentro y alegr&iacute;a en nuestra instituci&oacute;n.</p>\r\n\r\n<p>Los espacios de nuestra instituci&oacute;n se llenaron de alegr&iacute;a. Miembros de la comunidad participaron de manera entusiasta en los diferentes eventos. La participaci&oacute;n estudiantil fue notoria y plena de colorido. La celebraci&oacute;n de los 37 a&ntilde;os de nuestra instituci&oacute;n constituyo un encuentro enriquecedor para todos.</p>', '2014-10-07 01:10:26', '2014-10-07 01:10:26', '2014-10-07 01:59:16'),
(5, 2, 6, 'http://precursor.esy.es/web/resources/uploads/07-10-2014/EL DEPARTAMENTO DE SERVICIO COMUNITARIO CUMPLIO 4 AÑOS VINCULANDO A LOS ESTUDIANTES CON LAS COMUNIDADES DE CARACAS.png', 'EL DEPARTAMENTO DE SERVICIO COMUNITARIO CUMPLIO CON 4 AÑOS VINCULANDO A LOS ESTUDIANTES CON LAS COMUNIDADES DE CARACAS', 'Vinculación Comunitaria', '<p>El departamento de servicio comunitario ha consolidado los vinculos entre esta institucion y los sectores populares de Caracas. Los actos de celebracion del IV aniversario, se realizaron los dias 1 y 2 de abril, con la participacion de la comunidad universitaria y vecinos de esta casa de estudios.</p>\r\n\r\n<p>La profesora judith salgado, directora del CUFM, se&ntilde;alo: &ldquo;estoy muy contenta porque los estudiantes han realizado un gran esfuezo acercandose a muchas parroquias de Caracas, como la Vega, El Paraiso, San Agustin, 23 de Enero, Altagracia, entre otras. Los jovenes que prestan esta labor, comprenden que el servicio comunitario no es una obligacion, algo que establece la ley, sino un acto que se hace con respeto y solidaridad.&rdquo; Por su parte, el profesor Carlos Quintana, jefe de la depencia que realiza esta accion social, manifesto que la incorporacion del servicio comunitario en las comunidades, posibilita que los futuros jovenes profesionales, fortalezcan su sensibilidad social y se comprometen con lo que esta ocurriendo en los sectores populares.</p>', '2014-10-07 02:10:49', '2014-10-07 02:10:49', '2014-10-07 02:15:48'),
(6, 2, 1, 'http://precursor.esy.es/web/resources/uploads/07-10-2014/HOY MÁS QUE NUNCA AMÉRICA.png', 'HOY MAS QUE NUNCA AMERICA', 'Enlace Colectivo', '<p><strong>&ldquo;La conservaci&oacute;n de los derechos naturales, y, sobre todo, de la libertad de las personas y seguidos de los bienes, es incuestionablemente la piedra fundamental de toda sociedad humana, bajo cualquier forma pol&iacute;tica en que esta sea organizada.&rdquo;</strong></p>\r\n\r\n<p>&ldquo;No es casual que la irritaci&oacute;n del imperio sea por una acci&oacute;n de solidaridad internacional. La oligarqu&iacute;a conspira contra el internacionalismo revolucionario, saben que no hay triunfo consolidado si no es internacionalista, por eso acusan, deforman la solidaridad con naciones hermanas. Por eso los lacayos de la oposici&oacute;n tienen como uno de sus objetivos centrales la solidaridad internacional, la atacan, intentan sembrar odio entre hermanos, ego&iacute;smos. El ataque que hoy sufrimos es por nuestras relaciones con Ir&aacute;n, pero en el fondo es por nuestra hermandad con Cuba, ese es el verdadero objetivo de los gringos, romper la unidad continental en torno al socialismo, intentan asesinar el ejemplo&rdquo;. DEBATE SOCIALISTA</p>', '2014-10-07 02:10:53', '2014-10-07 02:10:53', '2014-10-07 02:37:51');

-- --------------------------------------------------------

--
-- Table structure for table `articulos_etiquetas`
--

CREATE TABLE IF NOT EXISTS `articulos_etiquetas` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_articulo` int(255) NOT NULL COMMENT 'Índice que hace relación a la tabla artículos',
  `id_etiqueta` int(11) NOT NULL COMMENT 'Índice que hace relación a la tabla etiquetas',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_articulo` (`id_articulo`,`id_etiqueta`),
  KEY `id_etiqueta` (`id_etiqueta`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `articulos_etiquetas`
--

INSERT INTO `articulos_etiquetas` (`id`, `id_articulo`, `id_etiqueta`) VALUES
(1, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de cada registro',
  `id_categoria` int(255) DEFAULT NULL COMMENT 'Referencia a la categoría perteneciente',
  `nombre` varchar(255) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Nombre que describe la categoría',
  `creado` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de modificación del registro',
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`id_categoria`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `categoria`
--

INSERT INTO `categoria` (`id`, `id_categoria`, `nombre`, `creado`, `modificado`) VALUES
(1, NULL, 'Categoría principal', '2014-07-03 13:08:06', '2014-07-03 17:38:06'),
(5, 1, 'Proyecto', '2014-10-05 23:10:13', '2014-10-05 23:49:11'),
(3, 1, 'Universidad', '2014-10-05 18:10:08', '2014-10-05 18:48:07'),
(4, 1, 'Analisis', '2014-10-05 23:10:16', '2014-10-05 23:48:06'),
(6, 1, 'Servicio Comunitario', '2014-10-07 02:10:46', '2014-10-07 02:09:45'),
(7, 1, 'Frases Celebres', '2014-10-07 02:10:31', '2014-10-07 02:33:30');

-- --------------------------------------------------------

--
-- Table structure for table `comentario`
--

CREATE TABLE IF NOT EXISTS `comentario` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de cada registro',
  `id_articulo` int(255) NOT NULL COMMENT 'Índice que hace relación con la tabla de artículos',
  `id_autor` int(255) NOT NULL COMMENT 'Índice que hace relación con la tabla de usuarios',
  `contenido` text COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Contenido del comentario. Puede ser HTML',
  `fecha` datetime NOT NULL COMMENT 'Fecha del comentario',
  PRIMARY KEY (`id`),
  KEY `id_autor` (`id_autor`),
  KEY `id_articulo` (`id_articulo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `comentario`
--

INSERT INTO `comentario` (`id`, `id_articulo`, `id_autor`, `contenido`, `fecha`) VALUES
(1, 1, 3, 'Oflxlx', '2014-10-08 14:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `etiqueta`
--

CREATE TABLE IF NOT EXISTS `etiqueta` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único para cada registro',
  `nombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Nombre que describe la etiqueta',
  `creado` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `etiqueta`
--

INSERT INTO `etiqueta` (`id`, `nombre`, `creado`, `modificado`) VALUES
(7, 'Servicio Comunitario', '2014-10-07 02:10:16', '2014-10-07 02:10:15'),
(2, 'Transformacion Universitaria', '2014-10-05 18:10:24', '2014-10-05 18:50:23'),
(3, 'colegio universitario', '2014-10-05 23:10:25', '2014-10-05 23:22:23'),
(4, 'CUFM', '2014-10-05 23:10:33', '2014-10-05 23:22:31'),
(5, 'Estudiantes', '2014-10-05 23:10:51', '2014-10-05 23:46:49'),
(6, 'Proyectos comunitarios', '2014-10-05 23:10:21', '2014-10-05 23:47:20'),
(8, 'Pensamientos', '2014-10-07 02:10:45', '2014-10-07 02:33:44'),
(9, 'Frases Celebres', '2014-10-07 02:10:59', '2014-10-07 02:33:58');

-- --------------------------------------------------------

--
-- Table structure for table `imagen`
--

CREATE TABLE IF NOT EXISTS `imagen` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de cada registro',
  `nombre` varchar(255) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Nombre de la imagen',
  `link` text COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Dirección URL de la imagen',
  `imagen` mediumtext COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Imagen transformada para el navegador',
  `fuente_autor` varchar(255) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Autor o fuente de la Imagen',
  `creado` datetime NOT NULL COMMENT 'Fecha cuando se crea el registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha cuando se modifica el registro',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Imágenes del periódico' AUTO_INCREMENT=9 ;

--
-- Dumping data for table `imagen`
--

INSERT INTO `imagen` (`id`, `nombre`, `link`, `imagen`, `fuente_autor`, `creado`, `modificado`) VALUES
(6, 'EL CUFM AL CUMPLIR 37 AÑOS FORTALECE LA TRANSFORMACION UNIVERSITARIA PARA EL SOCIALISMO.png', 'http://precursor.esy.es/web/resources/uploads/07-10-2014/EL CUFM AL CUMPLIR 37 AÑOS FORTALECE LA TRANSFORMACION UNIVERSITARIA PARA EL SOCIALISMO 6.png', '', '', '2014-10-07 01:10:25', '2014-10-19 23:40:52'),
(3, 'universidad en transformacion.png', 'http://precursor.esy.es/web/resources/uploads/05-10-2014/universidad en transformacion.png', '', '', '2014-10-05 18:10:57', '2014-10-05 18:58:55'),
(4, 'plan rector garantiza la formacion humanistica integral del hombre nuevo.png', 'http://precursor.esy.es/web/resources/uploads/05-10-2014/plan rector garantiza la formacion humanistica integral del hombre nuevo.png', '', '', '2014-10-05 23:10:41', '2014-10-05 23:31:39'),
(5, 'estudiantes del cufm presentaron proyectos en beneficio de las comunidades 1.png', 'http://precursor.esy.es/web/resources/uploads/05-10-2014/estudiantes del cufm presentaron proyectos en beneficio de las comunidades 1.png', '', '', '2014-10-05 23:10:13', '2014-10-05 23:50:11'),
(7, 'EL DEPARTAMENTO DE SERVICIO COMUNITARIO CUMPLIO 4 AÑOS VINCULANDO A LOS ESTUDIANTES CON LAS COMUNIDADES DE CARACAS.png', 'http://precursor.esy.es/web/resources/uploads/07-10-2014/EL DEPARTAMENTO DE SERVICIO COMUNITARIO CUMPLIO 4 AÑOS VINCULANDO A LOS ESTUDIANTES CON LAS COMUNIDADES DE CARACAS.png', '', '', '2014-10-07 02:10:47', '2014-10-07 02:08:46'),
(8, 'HOY MÁS QUE NUNCA AMÉRICA.png', 'http://precursor.esy.es/web/resources/uploads/07-10-2014/HOY MÁS QUE NUNCA AMÉRICA.png', '', '', '2014-10-07 02:10:27', '2014-10-07 02:29:26');

-- --------------------------------------------------------

--
-- Table structure for table `opcion`
--

CREATE TABLE IF NOT EXISTS `opcion` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de cada registro',
  `tipo` varchar(100) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Identificador para el tipo de opción',
  `nombre` varchar(100) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Nombre único para el valor de la opción',
  `valor` text COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Valor de cualquier tipo para la opción',
  `creado` datetime NOT NULL COMMENT 'Fecha cuando se crea el registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha cuando se modifica el registro',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `opcion`
--

INSERT INTO `opcion` (`id`, `tipo`, `nombre`, `valor`, `creado`, `modificado`) VALUES
(1, 'js', 'menu', '[{"texto":"adsadsa","link":"http:\\/\\/link","id":""},{"texto":"adsadsa","link":"http:\\/\\/link","id":""}]', '0000-00-00 00:00:00', '2014-10-20 00:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único para cada registro',
  `nombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Nombre que describe el perfil',
  `creado` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `perfil`
--

INSERT INTO `perfil` (`id`, `nombre`, `creado`, `modificado`) VALUES
(1, 'ROLE_SUPER_ADMIN', '2014-07-02 10:01:29', '2014-07-07 00:45:12'),
(2, 'ROLE_ADMIN', '2014-07-03 13:07:32', '2014-07-07 01:01:32'),
(3, 'ROLE_USER', '2014-07-06 20:31:46', '2014-07-07 01:01:46');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único para cada registro',
  `id_perfil` int(11) NOT NULL COMMENT 'Índice para relación con tabla perfiles',
  `nombre` varchar(255) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Nombre y apellido',
  `correo` varchar(255) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Correo electrónico',
  `alias` varchar(32) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Alias para inicio de sesión',
  `clave` varchar(88) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Clave para inicio de sesión',
  `creado` datetime NOT NULL COMMENT 'Fecha de la creación del registro',
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de la modificación del registro',
  PRIMARY KEY (`id`),
  KEY `id_perfil` (`id_perfil`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id`, `id_perfil`, `nombre`, `correo`, `alias`, `clave`, `creado`, `modificado`) VALUES
(1, 1, 'Ramón Serrano', 'ramon.calle.88@gmail.com', 'RamEduard', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', '2014-07-03 13:11:27', '2014-07-03 18:56:35'),
(2, 1, 'Javier Madrid', 'javiermadrid19@hotmail.com', 'jamc92', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', '2014-07-03 13:12:15', '2014-07-03 19:02:24'),
(3, 1, 'Sander Rodríguez', 'sander@gmail.com', 'sander', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', '2014-07-03 13:15:08', '2014-07-03 19:02:30'),
(4, 2, 'Administrador', 'admin@precursor', 'admin', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', '2014-07-14 15:30:14', '2014-07-14 20:00:24'),
(5, 3, 'Usuario', 'usuario@precursor', 'usuario', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', '2014-07-03 13:16:05', '2014-07-14 19:59:31'),
(9, 4, 'Ramón', 'ramon_calle-88@hotmail.com', 'admin1', 'nhDr7OyKlXQju+Ge/WKGrPQ9lPBSUFfpK+B1xqx/+8zLZqRNX0+5G1zBQklXUFy86lCpkAofsExlXiorUcKSNQ==', '0000-00-00 00:00:00', '2014-10-12 08:23:22');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
