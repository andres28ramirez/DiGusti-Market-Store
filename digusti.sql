-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2019 a las 18:54:39
-- Versión del servidor: 10.1.9-MariaDB
-- Versión de PHP: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `digusti`
--
CREATE DATABASE IF NOT EXISTS `digusti` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `digusti`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `actualizar`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar` (IN `campo` VARCHAR(20), IN `valor` VARCHAR(100), IN `idd` INT)  BEGIN
    	CASE campo
        	WHEN 'Correo' THEN
    			UPDATE usuario SET Correo = valor WHERE ID = idd;
            WHEN 'Pass' THEN
            	UPDATE usuario SET Pass = valor WHERE ID = idd;
            WHEN 'Nombre' THEN
            	UPDATE dato_usuario SET Nombre = valor WHERE ID = idd;
            WHEN 'Apellido' THEN
            	UPDATE dato_usuario SET Apellido = valor WHERE ID = idd;
    		WHEN 'Ciudad' THEN
            	UPDATE dato_usuario SET Ciudad = valor WHERE ID = idd;
            WHEN 'Direccion' THEN
            	UPDATE dato_usuario SET Direccion = valor WHERE ID = idd;
        END CASE;
    END$$

DROP PROCEDURE IF EXISTS `u_envios`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `u_envios` (IN `estad` VARCHAR(50), IN `descripcion` VARCHAR(255), IN `factura` INT)  UPDATE ENVIOS SET estado=estad, movimiento=descripcion,fecha=now() WHERE C_Factura=factura$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `bodega`
--
DROP VIEW IF EXISTS `bodega`;
CREATE TABLE `bodega` (
`Codigo` int(11)
,`Nombre` varchar(50)
,`Categoria` varchar(30)
,`Precio` int(11)
,`Imagen` varchar(250)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `carne`
--
DROP VIEW IF EXISTS `carne`;
CREATE TABLE `carne` (
`Codigo` int(11)
,`Nombre` varchar(50)
,`Categoria` varchar(30)
,`Precio` int(11)
,`Imagen` varchar(250)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

DROP TABLE IF EXISTS `carrito`;
CREATE TABLE `carrito` (
  `Id` int(11) NOT NULL COMMENT 'Campo que guardara el ID del usuario que realizo la compra',
  `Codigo` int(11) NOT NULL COMMENT 'Código del producto que seleccionado el usuario para su compra',
  `Cantidad` int(11) NOT NULL COMMENT 'Cantidad que del producto que el usuario desee comprar'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `charcuteria`
--
DROP VIEW IF EXISTS `charcuteria`;
CREATE TABLE `charcuteria` (
`Codigo` int(11)
,`Nombre` varchar(50)
,`Categoria` varchar(30)
,`Precio` int(11)
,`Imagen` varchar(250)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dato_usuario`
--

DROP TABLE IF EXISTS `dato_usuario`;
CREATE TABLE `dato_usuario` (
  `ID` int(100) NOT NULL COMMENT 'ID único del usuario que esta ligado al campo ID de la tabla Usuario',
  `Nombre` varchar(30) DEFAULT NULL COMMENT 'Nombre del usuario',
  `Apellido` varchar(30) DEFAULT NULL COMMENT 'Apellido del usuario',
  `Ci` varchar(10) DEFAULT NULL COMMENT 'C.i. del usuario',
  `Direccion` varchar(250) DEFAULT NULL COMMENT 'Dirección del usuario',
  `Ciudad` varchar(100) DEFAULT NULL COMMENT 'Ciudad del usuario'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `dato_usuario`
--

INSERT INTO `dato_usuario` (`ID`, `Nombre`, `Apellido`, `Ci`, `Direccion`, `Ciudad`) VALUES
(19, 'Andres', 'Ramirez', '24877448', 'Calle San Martín', 'Maneiro'),
(20, 'Gilberto', 'Mendoza', '9148869', 'Calle San Martín, Urb. Colinas del Paraiso', 'Maneiro'),
(21, 'Mercedes', 'Duque', '1903453', 'Calle San Martin', 'Maneiro');

--
-- Disparadores `dato_usuario`
--
DROP TRIGGER IF EXISTS `dato_usuario_bd`;
DELIMITER $$
CREATE TRIGGER `dato_usuario_bd` BEFORE DELETE ON `dato_usuario` FOR EACH ROW delete from usuario where id=old.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envios`
--

DROP TABLE IF EXISTS `envios`;
CREATE TABLE `envios` (
  `C_Factura` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` varchar(30) DEFAULT NULL,
  `movimiento` varchar(255) DEFAULT NULL,
  `tipo` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `envios`
--

INSERT INTO `envios` (`C_Factura`, `fecha`, `estado`, `movimiento`, `tipo`) VALUES
(15, '2019-07-03', 'En espera de su busqueda', 'Empaquetados y Listos', 'Delivery'),
(16, '2018-06-25', 'Procesando Compra.', 'Seleccionando los Productos.', 'Retiro Presencial'),
(18, '2018-07-01', 'Empacando Productos', 'En bolsitas', 'Retiro Presencial'),
(19, '2018-07-02', 'Procesando Compra.', 'Seleccionando los Productos.', 'Retiro Presencial'),
(23, '2019-07-02', 'En espera de su busqueda', 'empaquetado', 'Retiro Presencial'),
(24, '2019-07-01', 'Entregado', 'Finalizado', 'Retiro Presencial'),
(25, '2019-07-02', 'Entregado', 'Listo y Recibido', 'Delivery'),
(26, '2019-07-03', 'Procesando Compra.', 'Seleccionando los Productos.', 'Delivery');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

DROP TABLE IF EXISTS `factura`;
CREATE TABLE `factura` (
  `C_Factura` int(11) NOT NULL COMMENT 'Factura con código único auto incrementado',
  `Id` int(11) NOT NULL COMMENT 'Id del usuario',
  `Modalidad` varchar(30) NOT NULL COMMENT 'Forma de retiro(Delivery o presencial) ',
  `Monto` int(11) NOT NULL COMMENT 'Monto total de la factura',
  `Cancelacion` varchar(30) NOT NULL COMMENT 'Tipo de pago del usuario (Efectivo o Crédito)',
  `Fecha` varchar(20) NOT NULL COMMENT 'Fecha de la compra realizada',
  `ver` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`C_Factura`, `Id`, `Modalidad`, `Monto`, `Cancelacion`, `Fecha`, `ver`) VALUES
(16, 1, 'Transferencia', 1000000, 'Retiro Presencial', '26-06-2018', 1),
(13, 1, 'Crédito', 1080000, 'Retiro Presencial', '17-06-2018', 1),
(3, 1, 'Transferencia', 2500000, 'Retiro Presencial', '16-03-2018', 1),
(4, 2, 'Crédito', 5250000, 'Retiro Presencial', '16-03-2018', 1),
(5, 2, 'Transferencia', 5551000, 'Delivery', '16-03-2018', 1),
(6, 2, 'Crédito', 3234460, 'Delivery', '18-03-2018', 1),
(7, 8, 'Transferencia', 1025000, 'Delivery', '20-03-2018', 1),
(8, 9, 'Transferencia', 225000, 'Delivery', '21-03-2018', 1),
(9, 2, 'Crédito', 300000, 'Delivery', '28-03-2018', 1),
(10, 1, 'Crédito', 2050000, 'Retiro Presencial', '27-05-2018', 1),
(11, 1, 'Transferencia', 557600, 'Retiro Presencial', '06-06-2018', 1),
(25, 19, 'Transferencia', 2550000, 'Delivery', '02-07-2019', 1),
(15, 1, 'Transferencia', 80000, 'Delivery', '25-06-2018', 1),
(24, 19, 'Crédito', 757600, 'Retiro Presencial', '02-07-2019', 1),
(18, 19, 'Crédito', 1157600, 'Retiro Presencial', '01-07-2018', 1),
(19, 19, 'Crédito', 5100000, 'Retiro Presencial', '03-07-2018', 1),
(20, 19, 'Crédito', 600000, 'Retiro Presencial', '03-07-2018', 2),
(21, 19, 'Crédito', 600000, 'Retiro Presencial', '03-07-2018', 1),
(22, 19, 'Crédito', 600000, 'Retiro Presencial', '03-07-2018', 2),
(23, 19, 'Crédito', 557600, 'Retiro Presencial', '29-06-2019', 1),
(26, 19, 'Crédito', 637600, 'Delivery', '03-07-2019', 1);

--
-- Disparadores `factura`
--
DROP TRIGGER IF EXISTS `factura_ai`;
DELIMITER $$
CREATE TRIGGER `factura_ai` AFTER INSERT ON `factura` FOR EACH ROW insert into envios (C_Factura, estado, movimiento, Tipo, Fecha) values (new.C_Factura, "Procesando Compra.", "Seleccionando los Productos.", new.Cancelacion, now())
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `fruta`
--
DROP VIEW IF EXISTS `fruta`;
CREATE TABLE `fruta` (
`Codigo` int(11)
,`Nombre` varchar(50)
,`Categoria` varchar(30)
,`Precio` int(11)
,`Imagen` varchar(250)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden`
--

DROP TABLE IF EXISTS `orden`;
CREATE TABLE `orden` (
  `C_Factura` int(11) NOT NULL COMMENT 'Código de la factura',
  `Codigo` int(11) NOT NULL COMMENT 'Código del producto',
  `Cantidad` int(11) NOT NULL COMMENT 'Cantidad del producto comprado',
  `Nombre` varchar(255) NOT NULL,
  `Precio` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `orden`
--

INSERT INTO `orden` (`C_Factura`, `Codigo`, `Cantidad`, `Nombre`, `Precio`) VALUES
(1, 10, 1, 'Pollo', 650000),
(1, 3, 1, 'Queso Mozzarella', 650000),
(2, 6, 1, 'Cerveza Zulia', 25000),
(2, 11, 1, 'Chocolate Savoy', 200000),
(2, 5, 1, 'Lechuga', 150000),
(2, 4, 1, 'Pan de Hamburguesa', 600000),
(3, 1, 1, 'Morcilla Carupanera', 550000),
(3, 3, 3, 'Queso Mozzarella', 650000),
(4, 6, 4, 'Cerveza Zulia', 25000),
(4, 5, 5, 'Lechuga', 150000),
(4, 2, 1, 'Queso Cheddar', 300000),
(4, 4, 5, 'Pan de Hamburguesa', 600000),
(4, 1, 2, 'Morcilla Carupanera', 550000),
(5, 12, 2, 'Chuleta Ahumada de Cerdo', 850000),
(5, 15, 5, 'Parchita', 320000),
(5, 8, 2, 'Fresa', 125500),
(5, 5, 1, 'Lechuga', 150000),
(5, 6, 6, 'Cerveza Zulia', 25000),
(5, 1, 2, 'Morcilla Carupanera', 550000),
(5, 4, 1, 'Pan de Hamburguesa', 600000),
(6, 8, 1, 'Fresa', 125500),
(6, 18, 1, 'Whiskey (Old Par)', 1500000),
(6, 4, 1, 'Pan de Hamburguesa', 600000),
(6, 16, 1, 'Pasta Seca', 258960),
(6, 11, 1, 'Chocolate Savoy', 200000),
(6, 1, 1, 'Morcilla Carupanera', 550000),
(7, 6, 1, 'Cerveza Zulia', 25000),
(7, 11, 1, 'Chocolate Savoy', 200000),
(7, 10, 1, 'Pollo', 650000),
(7, 5, 1, 'Lechuga', 150000),
(8, 6, 1, 'Cerveza Zulia', 25000),
(8, 11, 1, 'Chocolate Savoy', 200000),
(9, 11, 1, 'Chocolate Savoy', 200000),
(9, 6, 4, 'Cerveza Zulia', 25000),
(10, 12, 1, 'Chuleta Ahumada de Cerdo', 850000),
(10, 1, 1, 'Morcilla Carupanera', 550000),
(10, 3, 1, 'Queso Mozzarella', 650000),
(11, 33, 1, 'Cebolla', 557600),
(12, 26, 1, 'Carne Molida', 1000000),
(13, 26, 1, 'Carne Molida', 1000000),
(13, 17, 1, 'Cerveza Solera Verde', 80000),
(24, 33, 1, 'Cebolla', 557600),
(15, 17, 1, 'Cerveza Solera Verde', 80000),
(16, 26, 1, 'Carne Molida', 1000000),
(24, 11, 1, 'Chocolate Savoy', 200000),
(18, 33, 1, 'Cebolla', 557600),
(18, 4, 1, 'Pan de Hamburguesa', 600000),
(19, 35, 1, 'Torta Negra', 4500000),
(19, 4, 1, 'Pan de Hamburguesa', 600000),
(23, 33, 1, 'Cebolla', 557600),
(25, 12, 3, 'Chuleta Ahumada de Cerdo', 850000),
(26, 17, 1, 'Cerveza Solera Verde', 80000),
(26, 33, 1, 'Cebolla', 557600);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `panaderia`
--
DROP VIEW IF EXISTS `panaderia`;
CREATE TABLE `panaderia` (
`Codigo` int(11)
,`Nombre` varchar(50)
,`Categoria` varchar(30)
,`Precio` int(11)
,`Imagen` varchar(250)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `Codigo` int(11) NOT NULL COMMENT 'Código de producto único, auto-incrementado al introducir un producto nuevo',
  `Nombre` varchar(50) DEFAULT NULL COMMENT 'Nombre del producto',
  `Categoria` varchar(30) DEFAULT NULL COMMENT 'Categoría del producto',
  `Precio` int(11) DEFAULT NULL COMMENT 'Precio del producto',
  `Imagen` varchar(250) NOT NULL COMMENT 'Dirección de la carpeta donde se encuentra guardada la imagen y su nombre correspondiente'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`Codigo`, `Nombre`, `Categoria`, `Precio`, `Imagen`) VALUES
(2, 'Queso Cheddar', 'Charcuteria', 300000, '/AlCampo/Alimentos_img/cheddar.jpg'),
(3, 'Queso Mozzarella', 'Charcuteria', 650000, '/AlCampo/Alimentos_img/mozzarella.jpg'),
(44, 'Jamon de Pavo', 'Charcuteria', 40000, '/AlCampo/Alimentos_img/jamon.jpg'),
(6, 'Cerveza Zulia', 'Bodega', 25000, '/AlCampo/Alimentos_img/zuliaverde.jpg'),
(7, 'Mandarina', 'Fruta', 40000, '/AlCampo/Alimentos_img/mandarina.jpg'),
(8, 'Fresa', 'Fruta', 125500, '/AlCampo/Alimentos_img/fresa.jpeg'),
(10, 'Pollo', 'Carne', 650000, '/AlCampo/Alimentos_img/pollo.jpg'),
(11, 'Chocolate Savoy', 'Bodega', 200000, '/AlCampo/Alimentos_img/chocolatesavoy.jpg'),
(12, 'Chuleta Ahumada de Cerdo', 'Carne', 850000, '/AlCampo/Alimentos_img/chuleta.jpg'),
(13, 'Pechuga de Pollo', 'Carne', 850000, '/AlCampo/Alimentos_img/pechuga.jpg'),
(14, 'Pirulin', 'Bodega', 548598, '/AlCampo/Alimentos_img/pirulin.jpg'),
(42, 'Limon', 'Fruta', 54000, '/AlCampo/Alimentos_img/limon.jpg'),
(17, 'Cerveza Solera Verde', 'Bodega', 80000, '/AlCampo/Alimentos_img/soleraverde.jpg'),
(18, 'Whiskey (Old Par)', 'Bodega', 1500000, '/AlCampo/Alimentos_img/oldpar.jpg'),
(39, 'Manzana Roja', 'Fruta', 1800000, '/AlCampo/Alimentos_img/manzanaroja.jpg'),
(24, 'Uvas', 'Fruta', 150000, '/AlCampo/Alimentos_img/uvas.jpg'),
(23, 'Galleta Susy ', 'Bodega', 120000, '/AlCampo/Alimentos_img/susy.jpg'),
(25, 'Solomo', 'Carne', 1750000, '/AlCampo/Alimentos_img/solomo.jpg'),
(26, 'Carne Molida', 'Carne', 1000000, '/AlCampo/Alimentos_img/molida.jpg'),
(27, 'Muslos', 'Carne', 750000, '/AlCampo/Alimentos_img/muslos.jpg'),
(30, 'Salchichas', 'Charcuteria', 1000000, '/AlCampo/Alimentos_img/salchichas.jpg'),
(31, 'Queso Blanco', 'Charcuteria', 600000, '/AlCampo/Alimentos_img/blanco.jpg'),
(32, 'Tomate', 'Verdura', 850000, '/AlCampo/Alimentos_img/tomate.jpg'),
(33, 'Cebolla', 'Verdura', 557600, '/AlCampo/Alimentos_img/cebolla.jpg'),
(34, 'Zanahoria', 'Verdura', 325000, '/AlCampo/Alimentos_img/zanahoria.jpg'),
(35, 'Torta Negra', 'Panaderia', 4500000, '/AlCampo/Alimentos_img/tortanegra.jpg'),
(36, 'Pan Canilla', 'Panaderia', 200000, '/AlCampo/Alimentos_img/pan.jpg'),
(43, 'Huevos', 'Carne', 400000, '/AlCampo/Alimentos_img/huevos-gallina.jpg'),
(40, 'Pimentón Rojo', 'Verdura', 650000, '/AlCampo/Alimentos_img/pimenton.jpg');

--
-- Disparadores `productos`
--
DROP TRIGGER IF EXISTS `productos_ai`;
DELIMITER $$
CREATE TRIGGER `productos_ai` AFTER INSERT ON `productos` FOR EACH ROW insert into reg_productos (Codigo, Nombre_n, Categoria_n, Precio_n, Accion, Fecha) VALUES (new.Codigo, new.Nombre, new.Categoria, new.Precio, "Agregado", now())
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `productos_bd`;
DELIMITER $$
CREATE TRIGGER `productos_bd` BEFORE DELETE ON `productos` FOR EACH ROW insert into reg_productos (Codigo, Nombre_v, Categoria_v, Precio_v, Accion, Fecha) VALUES (old.Codigo, old.Nombre, old.Categoria, old.Precio, "Borrado", now())
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `productos_bu`;
DELIMITER $$
CREATE TRIGGER `productos_bu` BEFORE UPDATE ON `productos` FOR EACH ROW insert into reg_productos (Codigo, Nombre_n, Categoria_n, Precio_n, Accion, Fecha, Nombre_v, Categoria_v, Precio_v) VALUES (new.Codigo, new.Nombre, new.Categoria, new.Precio, "Actualizado", now(),  old.Nombre, old.Categoria, old.Precio)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reg_productos`
--

DROP TABLE IF EXISTS `reg_productos`;
CREATE TABLE `reg_productos` (
  `Codigo` int(11) NOT NULL,
  `Nombre_n` varchar(50) DEFAULT NULL,
  `Categoria_n` varchar(30) DEFAULT NULL,
  `Precio_n` int(11) DEFAULT NULL,
  `Accion` varchar(20) DEFAULT NULL,
  `Nombre_v` varchar(50) DEFAULT NULL,
  `Categoria_v` varchar(30) DEFAULT NULL,
  `Precio_v` int(11) DEFAULT NULL,
  `Fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `reg_productos`
--

INSERT INTO `reg_productos` (`Codigo`, `Nombre_n`, `Categoria_n`, `Precio_n`, `Accion`, `Nombre_v`, `Categoria_v`, `Precio_v`, `Fecha`) VALUES
(1, 'Morcilla Carupanera', 'Carne', 550000, 'Actualizado', 'Morcilla Carupanera', 'Carne', 450000, '2018-06-24'),
(4, 'Pan de Hamburguesa', 'Panaderia', 600000, 'Actualizado', 'Pan de Hamburguesa', 'Panaderia', 300000, '2018-06-24'),
(5, 'Lechuga', 'Verdura', 150000, 'Actualizado', 'Lechuga', 'Verdura', 80000, '2018-06-24'),
(11, 'Chocolate Savoy', 'Bodega', 200000, 'Actualizado', 'Chocolate Savoy', 'Bodega', 250000, '2018-06-25'),
(38, 'Manzana Verde', 'Fruta', 2000000, 'Agregado', NULL, NULL, NULL, '2018-06-22'),
(39, 'Manzana Roja', 'Fruta', 1800000, 'Agregado', NULL, NULL, NULL, '2018-06-22'),
(40, 'Pimentón Rojo', 'Verdura', 650000, 'Agregado', NULL, NULL, NULL, '2018-06-24'),
(3, 'Queso Mozzarella', 'Charcuetria', 650000, 'Actualizado', 'Queso Mozzarella', 'Charcuteria', 650000, '2018-11-18'),
(1, NULL, NULL, NULL, 'Borrado', 'Morcilla Carupanera', 'Carne', 550000, '2018-11-18'),
(41, 'Carne', 'Charcuteria', 36547, 'Agregado', NULL, NULL, NULL, '2018-11-19'),
(41, NULL, NULL, NULL, 'Borrado', 'Carne', 'Charcuteria', 36547, '2018-11-19'),
(3, 'Queso Mozzarella', 'Charcuetria', 650000, 'Actualizado', 'Queso Mozzarella', 'Charcuetria', 650000, '2019-06-30'),
(3, 'Queso Mozzarella', 'Charcuetria', 1650000, 'Actualizado', 'Queso Mozzarella', 'Charcuetria', 650000, '2019-06-30'),
(3, 'Queso Mozzarella', 'Charcuetria', 1650000, 'Actualizado', 'Queso Mozzarella', 'Charcuetria', 1650000, '2019-06-30'),
(3, 'Queso Mozzarella', 'Charcuteria', 1650000, 'Actualizado', 'Queso Mozzarella', 'Charcuetria', 1650000, '2019-06-30'),
(3, 'Queso Mozzarella', 'Charcuteria', 1650000, 'Actualizado', 'Queso Mozzarella', 'Charcuteria', 1650000, '2019-06-30'),
(33, 'Cebolla', 'Verdura', 557600, 'Actualizado', 'Cebolla', 'Verdura', 557600, '2019-06-30'),
(3, 'Queso Mozzarella', 'Charcuteria', 1650000, 'Actualizado', 'Queso Mozzarella', 'Charcuteria', 1650000, '2019-06-30'),
(3, 'Queso Mozzarella', 'Charcuteria', 1650000, 'Actualizado', 'Queso Mozzarella', 'Charcuteria', 1650000, '2019-06-30'),
(3, 'Queso Mozzarella', 'Charcuteria', 650000, 'Actualizado', 'Queso Mozzarella', 'Charcuteria', 1650000, '2019-06-30'),
(5, 'Lechuga', 'Verdura', 150000, 'Actualizado', 'Lechuga', 'Verdura', 150000, '2019-07-01'),
(5, 'Lechuga', 'Verdura', 150000, 'Actualizado', 'Lechuga', 'Verdura', 150000, '2019-07-01'),
(5, NULL, NULL, NULL, 'Borrado', 'Lechuga', 'Verdura', 150000, '2019-07-01'),
(9, NULL, NULL, NULL, 'Borrado', 'Limon', 'Fruta', 35250, '2019-07-01'),
(38, 'Manzana Verde', 'Fruta', 2000000, 'Actualizado', 'Manzana Verde', 'Fruta', 2000000, '2019-07-01'),
(38, NULL, NULL, NULL, 'Borrado', 'Manzana Verde', 'Fruta', 2000000, '2019-07-01'),
(2, 'Queso Cheddar', 'Charcuteria', 500000, 'Actualizado', 'Queso Cheddar', 'Charcuteria', 300000, '2019-07-01'),
(2, 'Queso Cheddar', 'Charcuteria', 300000, 'Actualizado', 'Queso Cheddar', 'Charcuteria', 500000, '2019-07-01'),
(2, 'Queso Cheddar23', 'Panaderia', 500000, 'Actualizado', 'Queso Cheddar', 'Charcuteria', 300000, '2019-07-01'),
(2, 'Queso Cheddar', 'Charcuteria', 300000, 'Actualizado', 'Queso Cheddar23', 'Panaderia', 500000, '2019-07-01'),
(6, 'Cerveza Zulia 25', 'Bodega', 25000, 'Actualizado', 'Cerveza Zulia', 'Bodega', 25000, '2019-07-01'),
(6, 'Cerveza Zulia', 'Bodega', 25000, 'Actualizado', 'Cerveza Zulia 25', 'Bodega', 25000, '2019-07-01'),
(16, NULL, NULL, NULL, 'Borrado', 'Pasta Seca', 'Panaderia', 258960, '2019-07-01'),
(4, 'Pan de Hamburguesa', 'Panaderia', 700000, 'Actualizado', 'Pan de Hamburguesa', 'Panaderia', 600000, '2019-07-01'),
(24, 'Uvas', 'Fruta', 150000, 'Actualizado', 'Uvas', 'Fruta', 100000, '2019-07-01'),
(2, 'Queso Cheddar', 'Charcuteria', 300000, 'Actualizado', 'Queso Cheddar', 'Charcuteria', 300000, '2019-07-01'),
(34, 'Zanahoria', 'Verdura', 325000, 'Actualizado', 'Zanahoria', 'Verdura', 325000, '2019-07-01'),
(34, 'Zanahoria', 'Verdura', 325000, 'Actualizado', 'Zanahoria', 'Verdura', 325000, '2019-07-01'),
(35, 'Torta Negra', 'Panaderia', 4500000, 'Actualizado', 'Torta Negra', 'Panaderia', 4500000, '2019-07-01'),
(35, 'Torta Negra', 'Panaderia', 4500000, 'Actualizado', 'Torta Negra', 'Panaderia', 4500000, '2019-07-01'),
(36, 'Pan Canilla', 'Panaderia', 200000, 'Actualizado', 'Pan Canilla', 'Panaderia', 200000, '2019-07-01'),
(36, 'Pan Canilla', 'Panaderia', 200000, 'Actualizado', 'Pan Canilla', 'Panaderia', 200000, '2019-07-01'),
(6, 'Cerveza Zulia', 'Bodega', 25000, 'Actualizado', 'Cerveza Zulia', 'Bodega', 25000, '2019-07-01'),
(24, 'Uvas', 'Fruta', 150000, 'Actualizado', 'Uvas', 'Fruta', 150000, '2019-07-01'),
(32, 'Tomate', 'Verdura', 850000, 'Actualizado', 'Tomate', 'Verdura', 850000, '2019-07-01'),
(23, 'Galleta Susy ', 'Bodega', 120000, 'Actualizado', 'Galleta Susy ', 'Bodega', 120000, '2019-07-01'),
(25, 'Solomo', 'Carne', 1750000, 'Actualizado', 'Solomo', 'Carne', 1750000, '2019-07-01'),
(17, 'Cerveza Solera Verde', 'Bodega', 80000, 'Actualizado', 'Cerveza Solera Verde', 'Bodega', 80000, '2019-07-01'),
(30, 'Salchichas', 'Charcuteria', 1000000, 'Actualizado', 'Salchichas', 'Charcuteria', 1000000, '2019-07-01'),
(31, 'Queso Blanco', 'Charcuteria', 600000, 'Actualizado', 'Queso Blanco', 'Charcuteria', 600000, '2019-07-01'),
(10, 'Pollo', 'Carne', 650000, 'Actualizado', 'Pollo', 'Carne', 650000, '2019-07-01'),
(14, 'Pirulin', 'Bodega', 548598, 'Actualizado', 'Pirulin', 'Bodega', 548598, '2019-07-01'),
(40, 'Pimentón Rojo', 'Verdura', 650000, 'Actualizado', 'Pimentón Rojo', 'Verdura', 650000, '2019-07-01'),
(13, 'Pechuga de Pollo', 'Carne', 850000, 'Actualizado', 'Pechuga de Pollo', 'Carne', 850000, '2019-07-01'),
(4, 'Pan de Hamburguesa', 'Panaderia', 700000, 'Actualizado', 'Pan de Hamburguesa', 'Panaderia', 700000, '2019-07-01'),
(18, 'Whiskey (Old Par)', 'Bodega', 1500000, 'Actualizado', 'Whiskey (Old Par)', 'Bodega', 1500000, '2019-07-01'),
(27, 'Muslos', 'Carne', 750000, 'Actualizado', 'Muslos', 'Carne', 750000, '2019-07-01'),
(26, 'Carne Molida', 'Carne', 1000000, 'Actualizado', 'Carne Molida', 'Carne', 1000000, '2019-07-01'),
(39, 'Manzana Roja', 'Fruta', 1800000, 'Actualizado', 'Manzana Roja', 'Fruta', 1800000, '2019-07-01'),
(7, 'Mandarina', 'Fruta', 40000, 'Actualizado', 'Mandarina', 'Fruta', 40000, '2019-07-01'),
(8, 'Fresa', 'Fruta', 125500, 'Actualizado', 'Fresa', 'Fruta', 125500, '2019-07-01'),
(12, 'Chuleta Ahumada de Cerdo', 'Carne', 850000, 'Actualizado', 'Chuleta Ahumada de Cerdo', 'Carne', 850000, '2019-07-01'),
(11, 'Chocolate Savoy', 'Bodega', 200000, 'Actualizado', 'Chocolate Savoy', 'Bodega', 200000, '2019-07-01'),
(42, 'Limon', 'Fruta', 54000, 'Agregado', NULL, NULL, NULL, '2019-07-01'),
(43, 'Huevos', 'Carne', 400000, 'Agregado', NULL, NULL, NULL, '2019-07-02'),
(4, NULL, NULL, NULL, 'Borrado', 'Pan de Hamburguesa', 'Panaderia', 700000, '2019-07-03'),
(44, 'Jamon de Pavo', 'Charcuteria', 40000, 'Agregado', NULL, NULL, NULL, '2019-07-03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `ID` int(100) NOT NULL COMMENT 'Le asigna al usuario una ID automáticamente al registrarse el usuario y lo guarda ',
  `Correo` varchar(100) DEFAULT NULL COMMENT 'Correo que utilizara el usuario para poder ingresar al sistema',
  `Pass` varchar(250) DEFAULT NULL COMMENT 'Contraseña que necesita el usuario para ingresar al sistema',
  `Tipo_Usuario` int(1) DEFAULT '2' COMMENT 'Tipo de usuario, 1: Administrador, 2: Normales (Se le asigna automáticamente al usuario el tipo 2, solo el administrador de la BDD puede cambiar el tipo)'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID`, `Correo`, `Pass`, `Tipo_Usuario`) VALUES
(19, 'admin@admin.com', '$2y$10$bgq3OgaJtns5ffN2crcm1.14.LcXYwp2XV5AdweekXZgdL2SliSh2', 0),
(20, 'andresramirez2025@gmail.com', '$2y$10$FAiGzn.lZiWG2mvciM7zC.Buej491PCEEBuReEO3r.o7Dsk6LIUty', 1),
(21, 'mcaripe15@gmail.com', '$2y$10$wXvP1aDYUh96qYRm71hLY.zoszP.GLemY10duAn0Qorgie.PZehhS', 2);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `verdura`
--
DROP VIEW IF EXISTS `verdura`;
CREATE TABLE `verdura` (
`Codigo` int(11)
,`Nombre` varchar(50)
,`Categoria` varchar(30)
,`Precio` int(11)
,`Imagen` varchar(250)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `bodega`
--
DROP TABLE IF EXISTS `bodega`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bodega`  AS  select `productos`.`Codigo` AS `Codigo`,`productos`.`Nombre` AS `Nombre`,`productos`.`Categoria` AS `Categoria`,`productos`.`Precio` AS `Precio`,`productos`.`Imagen` AS `Imagen` from `productos` where (`productos`.`Categoria` = 'Bodega') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `carne`
--
DROP TABLE IF EXISTS `carne`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `carne`  AS  select `productos`.`Codigo` AS `Codigo`,`productos`.`Nombre` AS `Nombre`,`productos`.`Categoria` AS `Categoria`,`productos`.`Precio` AS `Precio`,`productos`.`Imagen` AS `Imagen` from `productos` where (`productos`.`Categoria` = 'Carne') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `charcuteria`
--
DROP TABLE IF EXISTS `charcuteria`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `charcuteria`  AS  select `productos`.`Codigo` AS `Codigo`,`productos`.`Nombre` AS `Nombre`,`productos`.`Categoria` AS `Categoria`,`productos`.`Precio` AS `Precio`,`productos`.`Imagen` AS `Imagen` from `productos` where (`productos`.`Categoria` = 'Charcuteria') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `fruta`
--
DROP TABLE IF EXISTS `fruta`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `fruta`  AS  select `productos`.`Codigo` AS `Codigo`,`productos`.`Nombre` AS `Nombre`,`productos`.`Categoria` AS `Categoria`,`productos`.`Precio` AS `Precio`,`productos`.`Imagen` AS `Imagen` from `productos` where (`productos`.`Categoria` = 'Fruta') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `panaderia`
--
DROP TABLE IF EXISTS `panaderia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `panaderia`  AS  select `productos`.`Codigo` AS `Codigo`,`productos`.`Nombre` AS `Nombre`,`productos`.`Categoria` AS `Categoria`,`productos`.`Precio` AS `Precio`,`productos`.`Imagen` AS `Imagen` from `productos` where (`productos`.`Categoria` = 'Panaderia') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `verdura`
--
DROP TABLE IF EXISTS `verdura`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `verdura`  AS  select `productos`.`Codigo` AS `Codigo`,`productos`.`Nombre` AS `Nombre`,`productos`.`Categoria` AS `Categoria`,`productos`.`Precio` AS `Precio`,`productos`.`Imagen` AS `Imagen` from `productos` where (`productos`.`Categoria` = 'Verdura') ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `dato_usuario`
--
ALTER TABLE `dato_usuario`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`C_Factura`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`Codigo`),
  ADD UNIQUE KEY `Codigo` (`Codigo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `dato_usuario`
--
ALTER TABLE `dato_usuario`
  MODIFY `ID` int(100) NOT NULL AUTO_INCREMENT COMMENT 'ID único del usuario que esta ligado al campo ID de la tabla Usuario', AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `C_Factura` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Factura con código único auto incrementado', AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código de producto único, auto-incrementado al introducir un producto nuevo', AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID` int(100) NOT NULL AUTO_INCREMENT COMMENT 'Le asigna al usuario una ID automáticamente al registrarse el usuario y lo guarda ', AUTO_INCREMENT=24;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
