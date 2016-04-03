--
-- Estructura de tabla para la tabla `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `link` text COLLATE utf8_spanish2_ci NOT NULL,
  `image` mediumtext COLLATE utf8_spanish2_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

--
-- Estructura de tabla para la tabla `estatus`
--

CREATE TABLE IF NOT EXISTS `estatus` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `estatus`
--

INSERT INTO `estatus` (`id`, `name`) VALUES
(1, 'Activo'),
(2, 'Inactivo'),
(3, 'Eliminado');

--
-- Estructura de tabla para la tabla `perfils`
--

CREATE TABLE IF NOT EXISTS `perfils` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `perfils`
--

INSERT INTO `perfils` (`id`, `name`) VALUES
(1, 'ROLE_Programador'),
(2, 'ROLE_Administrador'),
(3, 'ROLE_Usuario');

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `perfil_id` int(255) NOT NULL,
  `estatus_id` int(255) NOT NULL,
  `name` text COLLATE utf8_spanish2_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `perfil_id` (`perfil_id`),
  KEY `estatus_id` (`estatus_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=3;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `perfil_id`, `estatus_id`, `name`, `username`, `password`, `email`, `created`, `updated`) VALUES
(1, 1, 1, 'Nombre Apellido', 'programador', 's3klBI9L0n1wd6SSVhtYkSZiQDSk0lcqSwKxFpCY2LZxDolCRIPPt6Qy1l51xxGa5kIPhaboFM2hYGkFd4CQhA==', 'programador@example.com', '2014-02-22 22:31:00', '2014-07-29 04:35:54'),
(2, 2, 1, 'Nombre Apellido', 'administrador', 'nhDr7OyKlXQju+Ge/WKGrPQ9lPBSUFfpK+B1xqx/+8zLZqRNX0+5G1zBQklXUFy86lCpkAofsExlXiorUcKSNQ==', 'administrador@example.com', '2014-06-14 16:03:50', '2015-03-27 01:58:56'),
(3, 3, 1, 'Nombre Apellido', 'usuario', 'nhDr7OyKlXQju+Ge/WKGrPQ9lPBSUFfpK+B1xqx/+8zLZqRNX0+5G1zBQklXUFy86lCpkAofsExlXiorUcKSNQ==', 'usuario@example.com', '2014-06-14 16:03:50', '2015-03-27 01:58:56');;