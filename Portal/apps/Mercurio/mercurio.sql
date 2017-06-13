-- MySQL dump 10.13  Distrib 5.5.52, for Linux (x86_64)
--
-- Host: localhost    Database: mercurio_comfahuila
-- ------------------------------------------------------
-- Server version	5.5.52-cll

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `basica01`
--

DROP TABLE IF EXISTS `basica01`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basica01` (
  `codare` char(2) NOT NULL,
  `detalle` char(30) NOT NULL,
  PRIMARY KEY (`codare`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `basica02`
--

DROP TABLE IF EXISTS `basica02`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basica02` (
  `usuario` int(11) NOT NULL,
  `codare` char(2) NOT NULL,
  `email` char(40) NOT NULL,
  `celular` char(10) NOT NULL,
  `telefono` char(13) NOT NULL,
  `ext` char(5) NOT NULL,
  `foto` char(20) DEFAULT NULL,
  PRIMARY KEY (`usuario`),
  KEY `fk_basica01_gener02_idx` (`usuario`),
  KEY `fk_basica02_basica011_idx` (`codare`),
  CONSTRAINT `fk_basica01_gener02` FOREIGN KEY (`usuario`) REFERENCES `gener02` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_basica02_basica011` FOREIGN KEY (`codare`) REFERENCES `basica01` (`codare`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `basica03`
--

DROP TABLE IF EXISTS `basica03`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basica03` (
  `usuario` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `titulo` char(20) NOT NULL,
  `nota` char(250) NOT NULL,
  PRIMARY KEY (`usuario`,`numero`),
  KEY `fk_basica03_gener021_idx` (`usuario`),
  CONSTRAINT `fk_basica03_gener021` FOREIGN KEY (`usuario`) REFERENCES `gener02` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `basica04`
--

DROP TABLE IF EXISTS `basica04`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basica04` (
  `numero` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` char(8) NOT NULL,
  `nota` char(250) DEFAULT NULL,
  PRIMARY KEY (`numero`),
  KEY `fk_basica04_gener021_idx` (`usuario`),
  CONSTRAINT `fk_basica04_gener021` FOREIGN KEY (`usuario`) REFERENCES `gener02` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `basica05`
--

DROP TABLE IF EXISTS `basica05`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basica05` (
  `numero` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `estado` enum('D','R') NOT NULL,
  PRIMARY KEY (`numero`,`usuario`),
  KEY `fk_basica05_basica041_idx` (`numero`),
  KEY `fk_basica05_gener021_idx` (`usuario`),
  CONSTRAINT `basica05_ibfk_1` FOREIGN KEY (`numero`) REFERENCES `basica04` (`numero`),
  CONSTRAINT `fk_basica05_gener021` FOREIGN KEY (`usuario`) REFERENCES `gener02` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `basica06`
--

DROP TABLE IF EXISTS `basica06`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basica06` (
  `numero` int(11) NOT NULL,
  `detalle` char(150) NOT NULL,
  `lugar` char(45) NOT NULL,
  `fecini` date NOT NULL,
  `fecfin` date NOT NULL,
  `horini` char(8) NOT NULL,
  `horfin` char(8) NOT NULL,
  `obliga` enum('S','N') NOT NULL,
  `nota` char(200) DEFAULT NULL,
  PRIMARY KEY (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gener02`
--

DROP TABLE IF EXISTS `gener02`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gener02` (
  `usuario` int(11) NOT NULL AUTO_INCREMENT,
  `cedtra` char(13) NOT NULL,
  `nombre` char(35) NOT NULL,
  `tipfun` char(4) NOT NULL,
  `estado` enum('A','I') NOT NULL,
  `clave` char(40) NOT NULL,
  `feccla` date NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `tipfun` (`tipfun`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gener07`
--

DROP TABLE IF EXISTS `gener07`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gener07` (
  `coddep` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `detdep` char(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`coddep`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gener08`
--

DROP TABLE IF EXISTS `gener08`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gener08` (
  `codciu` char(5) NOT NULL,
  `detciu` char(30) NOT NULL,
  `numpob` int(11) NOT NULL,
  PRIMARY KEY (`codciu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gener17`
--

DROP TABLE IF EXISTS `gener17`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gener17` (
  `codsex` char(1) NOT NULL,
  `detsex` char(10) DEFAULT NULL,
  PRIMARY KEY (`codsex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gener18`
--

DROP TABLE IF EXISTS `gener18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gener18` (
  `coddoc` char(1) NOT NULL,
  `detdoc` char(20) NOT NULL,
  PRIMARY KEY (`coddoc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gener21`
--

DROP TABLE IF EXISTS `gener21`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gener21` (
  `tipfun` char(4) NOT NULL,
  `detalle` char(30) NOT NULL,
  PRIMARY KEY (`tipfun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gener28`
--

DROP TABLE IF EXISTS `gener28`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gener28` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codapl` char(2) NOT NULL,
  `role` char(4) NOT NULL,
  `resource` char(30) NOT NULL,
  `action` char(30) NOT NULL,
  `allow` char(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio01`
--

DROP TABLE IF EXISTS `mercurio01`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio01` (
  `codapl` char(2) NOT NULL,
  `control` enum('S','N') NOT NULL,
  `audtra` enum('S','N') NOT NULL,
  `email` char(45) DEFAULT NULL,
  `clave` char(20) DEFAULT NULL,
  `desema` char(40) NOT NULL,
  PRIMARY KEY (`codapl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio02`
--

DROP TABLE IF EXISTS `mercurio02`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio02` (
  `codcaj` char(6) NOT NULL,
  `nit` char(13) NOT NULL,
  `razsoc` char(60) NOT NULL,
  `sigla` char(30) NOT NULL,
  `email` char(90) NOT NULL,
  `direccion` char(90) NOT NULL,
  `telefono` char(20) NOT NULL,
  `codciu` char(5) NOT NULL,
  `nomarc` char(45) NOT NULL,
  `estado` enum('A','I') NOT NULL DEFAULT 'A',
  `pagweb` char(45) NOT NULL,
  `pagfac` char(45) DEFAULT NULL,
  `pagtwi` char(45) DEFAULT NULL,
  `pagyou` char(45) DEFAULT NULL,
  PRIMARY KEY (`codcaj`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio03`
--

DROP TABLE IF EXISTS `mercurio03`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio03` (
  `codfir` char(3) NOT NULL,
  `detalle` varchar(45) NOT NULL,
  PRIMARY KEY (`codfir`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio04`
--

DROP TABLE IF EXISTS `mercurio04`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio04` (
  `codpub` int(11) NOT NULL,
  `titulo` char(45) NOT NULL,
  `imagen` char(45) NOT NULL,
  `nota` char(45) NOT NULL,
  `enlace` char(45) NOT NULL,
  PRIMARY KEY (`codpub`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio05`
--

DROP TABLE IF EXISTS `mercurio05`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio05` (
  `codcaj` char(6) NOT NULL,
  `codfir` char(3) NOT NULL,
  `cedula` char(13) NOT NULL,
  `nombre` char(60) NOT NULL,
  `cargo` char(60) NOT NULL,
  `nomimg` char(30) DEFAULT NULL,
  `email` char(60) DEFAULT NULL,
  PRIMARY KEY (`codcaj`,`codfir`),
  KEY `fk_mercurio03_has_mercurio04_mercurio031_idx` (`codfir`),
  KEY `fk_mercurio05_mercurio021_idx` (`codcaj`),
  CONSTRAINT `fk_mercurio03_has_mercurio04_mercurio031` FOREIGN KEY (`codfir`) REFERENCES `mercurio03` (`codfir`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio05_mercurio021` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio06`
--

DROP TABLE IF EXISTS `mercurio06`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio06` (
  `tipo` char(2) NOT NULL,
  `detalle` char(40) NOT NULL,
  PRIMARY KEY (`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio07`
--

DROP TABLE IF EXISTS `mercurio07`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio07` (
  `codcaj` char(6) NOT NULL,
  `tipo` char(2) NOT NULL,
  `coddoc` char(1) NOT NULL,
  `documento` char(14) NOT NULL,
  `nombre` char(90) DEFAULT NULL,
  `email` char(60) DEFAULT NULL,
  `clave` char(10) NOT NULL,
  `feccla` date NOT NULL,
  `autoriza` enum('S','N') NOT NULL,
  `agencia` char(1) DEFAULT NULL,
  `fecreg` date DEFAULT NULL,
  PRIMARY KEY (`codcaj`,`tipo`,`documento`),
  KEY `fk_mercurio05_mercurio061_idx` (`tipo`),
  KEY `fk_mercurio07_mercurio021_idx` (`codcaj`),
  CONSTRAINT `fk_mercurio05_mercurio061` FOREIGN KEY (`tipo`) REFERENCES `mercurio06` (`tipo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio07_mercurio021` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio08`
--

DROP TABLE IF EXISTS `mercurio08`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio08` (
  `codare` char(2) NOT NULL,
  `detalle` char(45) NOT NULL,
  PRIMARY KEY (`codare`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio09`
--

DROP TABLE IF EXISTS `mercurio09`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio09` (
  `codpub` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `codcaj` char(6) NOT NULL,
  PRIMARY KEY (`codpub`,`numero`,`codcaj`),
  KEY `fk_mercurio09_mercurio021_idx` (`codcaj`),
  KEY `fk_mercurio09_mercurio171_idx` (`codpub`,`numero`),
  CONSTRAINT `fk_mercurio09_mercurio021` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio09_mercurio171` FOREIGN KEY (`codpub`, `numero`) REFERENCES `mercurio17` (`codpub`, `numero`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio10`
--

DROP TABLE IF EXISTS `mercurio10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio10` (
  `codcaj` char(6) NOT NULL,
  `codare` char(2) NOT NULL,
  `codope` char(3) NOT NULL,
  `tipo` enum('1','2','3','4') NOT NULL COMMENT '1->Consulta\n2->Solo Archivos\n3-> descarga Formulario llena y envia archivo\n4->Captura,Descarga Archivo y archivos\n',
  `email` char(45) DEFAULT NULL,
  `estado` enum('A','I') NOT NULL,
  PRIMARY KEY (`codcaj`,`codare`,`codope`),
  KEY `fk_mercurio10_mercurio111_idx` (`codare`,`codope`),
  KEY `fk_mercurio10_mercurio021_idx` (`codcaj`),
  CONSTRAINT `fk_mercurio10_mercurio021` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio10_mercurio111` FOREIGN KEY (`codare`, `codope`) REFERENCES `mercurio11` (`codare`, `codope`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio11`
--

DROP TABLE IF EXISTS `mercurio11`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio11` (
  `codare` char(2) NOT NULL,
  `codope` char(3) NOT NULL,
  `detalle` char(50) NOT NULL,
  `tipo` char(2) NOT NULL,
  `mandoc` enum('S','N') NOT NULL,
  `url` char(45) NOT NULL,
  `webser` char(35) NOT NULL,
  `nota` char(200) DEFAULT NULL,
  `orden` int(11) NOT NULL,
  PRIMARY KEY (`codare`,`codope`),
  KEY `fk_mercurio11_mercurio061_idx` (`tipo`),
  KEY `fk_mercurio11_mercurio081_idx` (`codare`),
  CONSTRAINT `fk_mercurio11_mercurio061` FOREIGN KEY (`tipo`) REFERENCES `mercurio06` (`tipo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio11_mercurio081` FOREIGN KEY (`codare`) REFERENCES `mercurio08` (`codare`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio12`
--

DROP TABLE IF EXISTS `mercurio12`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio12` (
  `coddoc` int(11) NOT NULL,
  `detalle` char(60) NOT NULL,
  PRIMARY KEY (`coddoc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio13`
--

DROP TABLE IF EXISTS `mercurio13`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio13` (
  `codcaj` char(6) NOT NULL,
  `codare` char(2) NOT NULL,
  `codope` char(3) NOT NULL,
  `coddoc` int(11) NOT NULL,
  `archivo` longblob,
  `obliga` enum('S','N') NOT NULL,
  PRIMARY KEY (`codcaj`,`codare`,`codope`,`coddoc`),
  KEY `fk_mercurio13_mercurio121_idx` (`coddoc`),
  KEY `fk_mercurio13_mercurio101_idx` (`codcaj`,`codare`,`codope`),
  CONSTRAINT `fk_mercurio13_mercurio101` FOREIGN KEY (`codcaj`, `codare`, `codope`) REFERENCES `mercurio10` (`codcaj`, `codare`, `codope`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio13_mercurio121` FOREIGN KEY (`coddoc`) REFERENCES `mercurio12` (`coddoc`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio14`
--

DROP TABLE IF EXISTS `mercurio14`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio14` (
  `numero` int(11) NOT NULL,
  `codcaj` char(6) NOT NULL,
  `codare` char(2) NOT NULL,
  `codope` char(3) NOT NULL,
  `tipo` char(2) NOT NULL,
  `documento` char(14) NOT NULL,
  `nota` char(250) DEFAULT NULL,
  `estado` enum('A','V','R') NOT NULL,
  PRIMARY KEY (`numero`),
  KEY `fk_mercurio14_mercurio101_idx` (`codcaj`,`codare`,`codope`),
  KEY `fk_table1_mercurio071` (`codcaj`,`tipo`,`documento`),
  CONSTRAINT `fk_mercurio14_mercurio101` FOREIGN KEY (`codcaj`, `codare`, `codope`) REFERENCES `mercurio10` (`codcaj`, `codare`, `codope`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_mercurio071` FOREIGN KEY (`codcaj`, `tipo`, `documento`) REFERENCES `mercurio07` (`codcaj`, `tipo`, `documento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio15`
--

DROP TABLE IF EXISTS `mercurio15`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio15` (
  `numero` int(11) NOT NULL,
  `coddoc` int(11) NOT NULL,
  `nomarc` char(150) DEFAULT NULL,
  PRIMARY KEY (`numero`,`coddoc`),
  KEY `fk_mercurio15_mercurio141_idx` (`numero`),
  KEY `fk_mercurio15_mercurio121_idx` (`coddoc`),
  CONSTRAINT `fk_mercurio15_mercurio121` FOREIGN KEY (`coddoc`) REFERENCES `mercurio12` (`coddoc`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio15_mercurio141` FOREIGN KEY (`numero`) REFERENCES `mercurio14` (`numero`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio16`
--

DROP TABLE IF EXISTS `mercurio16`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio16` (
  `numero` int(11) NOT NULL,
  `numcon` int(11) NOT NULL,
  `nota` varchar(45) DEFAULT NULL,
  `nomarc` char(150) DEFAULT NULL,
  PRIMARY KEY (`numero`,`numcon`),
  CONSTRAINT `fk_mercurio16_mercurio141` FOREIGN KEY (`numero`) REFERENCES `mercurio14` (`numero`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio17`
--

DROP TABLE IF EXISTS `mercurio17`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio17` (
  `codpub` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `nivel` int(11) NOT NULL,
  `fecini` date NOT NULL,
  `fecfin` date NOT NULL,
  `estado` enum('A','I') NOT NULL,
  PRIMARY KEY (`codpub`,`numero`),
  CONSTRAINT `fk_mercurio17_mercurio041` FOREIGN KEY (`codpub`) REFERENCES `mercurio04` (`codpub`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio18`
--

DROP TABLE IF EXISTS `mercurio18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio18` (
  `codigo` char(2) NOT NULL,
  `detalle` char(60) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio19`
--

DROP TABLE IF EXISTS `mercurio19`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio19` (
  `codcaj` char(6) NOT NULL,
  `tipo` char(2) NOT NULL,
  `coddoc` char(1) NOT NULL,
  `documento` char(14) NOT NULL,
  `codigo` char(2) NOT NULL,
  `respuesta` char(60) NOT NULL,
  PRIMARY KEY (`codcaj`,`tipo`,`documento`,`codigo`),
  KEY `fk_mercurio19_codigo_idx` (`codigo`),
  CONSTRAINT `mercurio19_ibfk_1` FOREIGN KEY (`codcaj`, `tipo`, `documento`) REFERENCES `mercurio07` (`codcaj`, `tipo`, `documento`),
  CONSTRAINT `fk_mercurio19_codigo` FOREIGN KEY (`codigo`) REFERENCES `mercurio18` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio20`
--

DROP TABLE IF EXISTS `mercurio20`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio20` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codcaj` char(6) DEFAULT NULL,
  `tipo` char(2) DEFAULT NULL,
  `documento` char(14) DEFAULT NULL,
  `ip` char(20) NOT NULL,
  `fecha` date NOT NULL,
  `hora` char(8) NOT NULL,
  `controlador` char(40) NOT NULL,
  `accion` char(40) NOT NULL,
  `nota` char(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mercurio20_07_idx` (`tipo`,`codcaj`,`documento`)
) ENGINE=InnoDB AUTO_INCREMENT=43508 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio21`
--

DROP TABLE IF EXISTS `mercurio21`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio21` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codcaj` char(6) DEFAULT NULL,
  `tipo` char(2) DEFAULT NULL,
  `documento` char(14) DEFAULT NULL,
  `controlador` char(40) NOT NULL,
  `accion` char(40) NOT NULL,
  `ip` char(15) NOT NULL,
  `fecha` date NOT NULL,
  `hora` char(5) NOT NULL,
  `valor` int(11) NOT NULL,
  `nota` char(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mercurio20_mercurio071_idx` (`codcaj`,`tipo`,`documento`),
  KEY `fk_mercurio21_mercurio251_idx` (`controlador`,`accion`),
  CONSTRAINT `fk_mercurio20_mercurio0710` FOREIGN KEY (`codcaj`, `tipo`, `documento`) REFERENCES `mercurio07` (`codcaj`, `tipo`, `documento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio21_mercurio251` FOREIGN KEY (`controlador`, `accion`) REFERENCES `mercurio25` (`controlador`, `accion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=983 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio22`
--

DROP TABLE IF EXISTS `mercurio22`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio22` (
  `codcaj` char(6) NOT NULL,
  `tipo` char(2) NOT NULL,
  `coddoc` char(1) NOT NULL,
  `documento` char(14) NOT NULL,
  `ip` char(20) NOT NULL,
  PRIMARY KEY (`codcaj`,`tipo`,`documento`,`ip`),
  CONSTRAINT `mercurio22_ibfk_1` FOREIGN KEY (`codcaj`, `tipo`, `documento`) REFERENCES `mercurio07` (`codcaj`, `tipo`, `documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio23`
--

DROP TABLE IF EXISTS `mercurio23`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio23` (
  `codcat` char(2) NOT NULL,
  `detalle` char(45) NOT NULL,
  PRIMARY KEY (`codcat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio24`
--

DROP TABLE IF EXISTS `mercurio24`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio24` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codcaj` char(6) NOT NULL,
  `tipo` char(2) NOT NULL,
  `documento` char(14) NOT NULL,
  `codcat` char(2) NOT NULL,
  `fecini` date NOT NULL,
  `fecfin` date DEFAULT NULL,
  `titulo` char(45) NOT NULL,
  `descripcion` char(250) NOT NULL,
  `estado` enum('P','A','X') NOT NULL,
  `fecest` date DEFAULT NULL,
  `motivo` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mercurio24_mercurio071_idx` (`codcaj`,`tipo`,`documento`),
  KEY `fk_mercurio24_mercurio231_idx` (`codcat`),
  CONSTRAINT `fk_mercurio24_mercurio071` FOREIGN KEY (`codcaj`, `tipo`, `documento`) REFERENCES `mercurio07` (`codcaj`, `tipo`, `documento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio24_mercurio231` FOREIGN KEY (`codcat`) REFERENCES `mercurio23` (`codcat`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio25`
--

DROP TABLE IF EXISTS `mercurio25`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio25` (
  `controlador` char(40) NOT NULL,
  `accion` char(40) NOT NULL,
  `detalle` char(45) NOT NULL,
  `valor` decimal(12,2) NOT NULL,
  PRIMARY KEY (`controlador`,`accion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio26`
--

DROP TABLE IF EXISTS `mercurio26`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio26` (
  `numero` int(11) NOT NULL,
  `tipo` enum('1','2') NOT NULL,
  `estado` enum('A','I') NOT NULL,
  `codcaj` char(6) DEFAULT NULL,
  `nomimg` char(45) NOT NULL,
  PRIMARY KEY (`numero`),
  KEY `fk_mercurio26_mercurio021` (`codcaj`),
  CONSTRAINT `fk_mercurio26_mercurio021` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio27`
--

DROP TABLE IF EXISTS `mercurio27`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio27` (
  `nivel` int(11) NOT NULL,
  `detalle` char(45) NOT NULL,
  `tipo` enum('1','2') NOT NULL,
  `numero` int(11) NOT NULL,
  `valor` int(11) NOT NULL,
  PRIMARY KEY (`nivel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio28`
--

DROP TABLE IF EXISTS `mercurio28`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio28` (
  `tipo` enum('E','T','C') NOT NULL,
  `campo` char(20) NOT NULL,
  `detalle` char(45) NOT NULL,
  `orden` int(11) NOT NULL,
  PRIMARY KEY (`tipo`,`campo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio29`
--

DROP TABLE IF EXISTS `mercurio29`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio29` (
  `numero` int(11) NOT NULL AUTO_INCREMENT,
  `codcaj` char(6) NOT NULL,
  `titulo` char(60) NOT NULL,
  `descripcion` char(255) NOT NULL,
  `estado` enum('A','I') NOT NULL,
  PRIMARY KEY (`numero`),
  KEY `fk_mercurio29_mercurio021_idx` (`codcaj`),
  CONSTRAINT `fk_mercurio29_mercurio021` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio30`
--

DROP TABLE IF EXISTS `mercurio30`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio30` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` int(11) NOT NULL,
  `codcaj` char(6) NOT NULL,
  `nit` char(14) NOT NULL,
  `razsoc` char(45) NOT NULL,
  `sigla` char(45) DEFAULT NULL,
  `digver` char(1) DEFAULT NULL,
  `coddoc` char(1) DEFAULT NULL,
  `codciu` char(5) NOT NULL,
  `barrio` char(8) DEFAULT NULL,
  `ciucor` char(5) DEFAULT NULL,
  `barcor` char(8) DEFAULT NULL,
  `direccion` char(45) NOT NULL,
  `telefono` char(13) NOT NULL,
  `fax` char(13) DEFAULT NULL,
  `email` char(45) NOT NULL,
  `pagweb` char(45) DEFAULT NULL,
  `feccon` date DEFAULT NULL,
  `codact` int(11) NOT NULL,
  `objemp` char(5) DEFAULT NULL,
  `tottra` int(11) NOT NULL,
  `totnom` int(11) NOT NULL,
  `cedrep` char(13) NOT NULL,
  `docrep` char(1) DEFAULT NULL,
  `nomrep` char(45) NOT NULL,
  `estado` enum('P','A','X') NOT NULL,
  `motivo` char(100) DEFAULT NULL,
  `fecest` date DEFAULT NULL,
  `tipsoc` char(3) NOT NULL,
  `calemp` enum('E','F','P','D','N') NOT NULL,
  `codzon` char(9) DEFAULT NULL,
  `zonsuc` char(9) DEFAULT NULL,
  `dirsuc` char(40) DEFAULT NULL,
  `emailsuc` char(80) DEFAULT NULL,
  `telsuc` char(20) DEFAULT NULL,
  `faxsuc` char(13) DEFAULT NULL,
  `nomsub` char(40) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `agencia` int(11) NOT NULL,
  `docadm` char(1) DEFAULT NULL,
  `cedadm` char(13) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mercurio30_mercurio211_idx` (`log`),
  KEY `fk_mercurio30_mercurio021_idx` (`codcaj`),
  KEY `agencia` (`agencia`),
  CONSTRAINT `fk_mercurio30_mercurio021` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio30_mercurio211` FOREIGN KEY (`log`) REFERENCES `mercurio21` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio31`
--

DROP TABLE IF EXISTS `mercurio31`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio31` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` int(11) NOT NULL,
  `codcaj` char(6) NOT NULL,
  `nit` char(14) NOT NULL,
  `cedtra` char(14) NOT NULL,
  `coddoc` char(1) NOT NULL,
  `priape` char(20) NOT NULL,
  `segape` char(20) DEFAULT NULL,
  `prinom` char(20) NOT NULL,
  `segnom` char(20) DEFAULT NULL,
  `fecnac` date NOT NULL,
  `ciunac` char(5) NOT NULL,
  `sexo` char(1) NOT NULL,
  `estciv` char(2) NOT NULL,
  `cabhog` enum('S','N') NOT NULL,
  `codciu` char(5) DEFAULT NULL,
  `codzon` char(9) DEFAULT NULL,
  `direccion` char(45) NOT NULL,
  `barrio` int(4) NOT NULL,
  `telefono` char(13) NOT NULL,
  `celular` char(20) DEFAULT NULL,
  `fax` char(13) DEFAULT NULL,
  `email` char(45) NOT NULL,
  `fecing` date NOT NULL,
  `salario` int(11) NOT NULL,
  `captra` enum('N','I') DEFAULT NULL,
  `tipdis` char(2) DEFAULT NULL,
  `nivedu` char(3) DEFAULT NULL,
  `rural` enum('S','N') NOT NULL,
  `horas` int(11) NOT NULL,
  `tipcon` enum('F','I') NOT NULL,
  `vivienda` char(4) NOT NULL,
  `autoriza` enum('S','N') DEFAULT NULL,
  `estado` enum('P','A','X') NOT NULL,
  `motivo` char(100) DEFAULT NULL,
  `fecest` date DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `tipafi` char(2) NOT NULL,
  `agencia` int(11) NOT NULL,
  `profesion` char(9) DEFAULT NULL,
  `cargo` char(9) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mercurio31_mercurio211_idx` (`log`),
  KEY `fk_mercurio31_mercurio021_idx` (`codcaj`),
  KEY `agencia` (`agencia`),
  CONSTRAINT `fk_mercurio31_mercurio021` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio31_mercurio211` FOREIGN KEY (`log`) REFERENCES `mercurio21` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio32`
--

DROP TABLE IF EXISTS `mercurio32`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio32` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` int(11) NOT NULL,
  `codcaj` char(6) NOT NULL,
  `cedtra` char(14) NOT NULL,
  `cajcon` char(6) DEFAULT NULL,
  `cedcon` char(14) NOT NULL,
  `coddoc` char(1) NOT NULL,
  `priape` char(20) NOT NULL,
  `segape` char(20) DEFAULT NULL,
  `prinom` char(20) NOT NULL,
  `segnom` char(20) DEFAULT NULL,
  `fecnac` date NOT NULL,
  `ciunac` char(5) NOT NULL,
  `sexo` char(1) NOT NULL,
  `estciv` char(2) NOT NULL,
  `comper` enum('S','N') NOT NULL,
  `ciures` char(5) DEFAULT NULL,
  `codzon` char(9) DEFAULT NULL,
  `tipviv` char(2) DEFAULT NULL,
  `direccion` char(45) NOT NULL,
  `barrio` char(8) DEFAULT NULL,
  `telefono` char(13) NOT NULL,
  `celular` char(10) DEFAULT NULL,
  `email` char(45) NOT NULL,
  `nivedu` char(3) DEFAULT NULL,
  `fecing` date NOT NULL,
  `codocu` char(5) DEFAULT NULL,
  `salario` int(11) DEFAULT NULL,
  `estado` enum('P','A','X') NOT NULL,
  `motivo` char(100) DEFAULT NULL,
  `fecest` date DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mercurio32_mercurio211_idx` (`log`),
  KEY `fk_mercurio32_mercurio021_idx` (`codcaj`),
  CONSTRAINT `fk_mercurio32_mercurio021` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio32_mercurio211` FOREIGN KEY (`log`) REFERENCES `mercurio21` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio33`
--

DROP TABLE IF EXISTS `mercurio33`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio33` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` int(11) NOT NULL,
  `codcaj` char(6) NOT NULL,
  `tipo` enum('E','T','C') NOT NULL,
  `documento` char(14) NOT NULL,
  `campo` char(20) NOT NULL,
  `valor` char(100) NOT NULL,
  `estado` enum('P','A','X') NOT NULL,
  `motivo` char(100) DEFAULT NULL,
  `fecest` date DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mercurio36_mercurio201_idx` (`tipo`,`campo`),
  KEY `fk_mercurio36_mercurio211_idx` (`log`),
  KEY `fk_mercurio33_mercurio021_idx` (`codcaj`),
  CONSTRAINT `fk_mercurio33_mercurio021` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio36_mercurio201` FOREIGN KEY (`tipo`, `campo`) REFERENCES `mercurio28` (`tipo`, `campo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio36_mercurio211` FOREIGN KEY (`log`) REFERENCES `mercurio21` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=563 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio34`
--

DROP TABLE IF EXISTS `mercurio34`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio34` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` int(11) NOT NULL,
  `codcaj` char(6) NOT NULL,
  `cedtra` char(14) NOT NULL,
  `documento` char(14) NOT NULL,
  `cedcon` char(14) DEFAULT NULL,
  `coddoc` char(1) NOT NULL,
  `priape` char(20) NOT NULL,
  `segape` char(20) DEFAULT NULL,
  `prinom` char(20) NOT NULL,
  `segnom` char(20) DEFAULT NULL,
  `fecnac` date NOT NULL,
  `ciunac` char(5) NOT NULL,
  `sexo` char(1) NOT NULL,
  `parent` char(13) NOT NULL,
  `huerfano` enum('0','1','2') NOT NULL,
  `tiphij` enum('0','1','2') DEFAULT NULL,
  `nivedu` char(3) DEFAULT NULL,
  `captra` enum('N','I') DEFAULT NULL,
  `tipdis` char(2) DEFAULT NULL,
  `calendario` enum('A','B','N') NOT NULL,
  `estado` enum('P','A','X') NOT NULL,
  `motivo` char(100) DEFAULT NULL,
  `fecest` date DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mercurio34_mercurio211_idx` (`log`),
  KEY `fk_mercurio34_mercurio021_idx` (`codcaj`),
  CONSTRAINT `fk_mercurio34_mercurio021` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mercurio34_mercurio211` FOREIGN KEY (`log`) REFERENCES `mercurio21` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio35`
--

DROP TABLE IF EXISTS `mercurio35`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio35` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` int(11) DEFAULT NULL,
  `nit` varchar(14) NOT NULL,
  `cedtra` char(14) NOT NULL,
  `nomtra` char(90) DEFAULT NULL,
  `codest` varchar(4) NOT NULL,
  `fecret` date NOT NULL,
  `nota` char(250) NOT NULL,
  `estado` char(2) DEFAULT NULL,
  `fecest` date DEFAULT NULL,
  `motivo` char(255) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `nomarc` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log` (`log`),
  CONSTRAINT `mercurio35_ibfk_1` FOREIGN KEY (`log`) REFERENCES `mercurio21` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio36`
--

DROP TABLE IF EXISTS `mercurio36`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio36` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cedtra` char(14) NOT NULL,
  `codest` char(2) NOT NULL,
  `fecret` date NOT NULL,
  `nota` char(250) NOT NULL,
  `estado` char(2) DEFAULT NULL,
  `fecest` date DEFAULT NULL,
  `motivo` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio37`
--

DROP TABLE IF EXISTS `mercurio37`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio37` (
  `numero` int(11) NOT NULL,
  `coddoc` int(11) NOT NULL,
  `nomarc` char(255) NOT NULL,
  PRIMARY KEY (`numero`,`coddoc`),
  KEY `coddoc` (`coddoc`),
  CONSTRAINT `mercurio37_ibfk_1` FOREIGN KEY (`numero`) REFERENCES `mercurio30` (`id`),
  CONSTRAINT `mercurio37_ibfk_2` FOREIGN KEY (`coddoc`) REFERENCES `mercurio12` (`coddoc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio38`
--

DROP TABLE IF EXISTS `mercurio38`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio38` (
  `numero` int(11) NOT NULL,
  `coddoc` int(11) NOT NULL,
  `nomarc` char(255) NOT NULL,
  PRIMARY KEY (`numero`,`coddoc`),
  KEY `coddoc` (`coddoc`),
  CONSTRAINT `mercurio38_ibfk_1` FOREIGN KEY (`numero`) REFERENCES `mercurio31` (`id`),
  CONSTRAINT `mercurio38_ibfk_2` FOREIGN KEY (`coddoc`) REFERENCES `mercurio12` (`coddoc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio39`
--

DROP TABLE IF EXISTS `mercurio39`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio39` (
  `numero` int(11) NOT NULL,
  `coddoc` int(11) NOT NULL,
  `nomarc` char(255) NOT NULL,
  PRIMARY KEY (`numero`,`coddoc`),
  KEY `coddoc` (`coddoc`),
  CONSTRAINT `mercurio39_ibfk_1` FOREIGN KEY (`numero`) REFERENCES `mercurio32` (`id`),
  CONSTRAINT `mercurio39_ibfk_2` FOREIGN KEY (`coddoc`) REFERENCES `mercurio12` (`coddoc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio40`
--

DROP TABLE IF EXISTS `mercurio40`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio40` (
  `numero` int(11) NOT NULL,
  `coddoc` int(11) NOT NULL,
  `nomarc` char(255) NOT NULL,
  PRIMARY KEY (`numero`,`coddoc`),
  KEY `coddoc` (`coddoc`),
  CONSTRAINT `mercurio40_ibfk_1` FOREIGN KEY (`numero`) REFERENCES `mercurio34` (`id`),
  CONSTRAINT `mercurio40_ibfk_2` FOREIGN KEY (`coddoc`) REFERENCES `mercurio12` (`coddoc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio41`
--

DROP TABLE IF EXISTS `mercurio41`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio41` (
  `codcat` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`codcat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio42`
--

DROP TABLE IF EXISTS `mercurio42`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio42` (
  `codban` int(11) NOT NULL,
  `codcat` int(11) NOT NULL,
  `codcaj` char(6) NOT NULL,
  `nomarc` varchar(255) NOT NULL,
  `estado` enum('A','I') NOT NULL,
  PRIMARY KEY (`codban`),
  KEY `codcat` (`codcat`),
  KEY `codcaj` (`codcaj`),
  CONSTRAINT `mercurio42_ibfk_1` FOREIGN KEY (`codcat`) REFERENCES `mercurio41` (`codcat`),
  CONSTRAINT `mercurio42_ibfk_2` FOREIGN KEY (`codcaj`) REFERENCES `mercurio02` (`codcaj`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio43`
--

DROP TABLE IF EXISTS `mercurio43`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio43` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` int(11) NOT NULL,
  `codcaj` char(6) NOT NULL,
  `documento` char(14) NOT NULL,
  `campo` char(10) NOT NULL,
  `valor` char(100) NOT NULL,
  `estado` enum('P','A','X') NOT NULL,
  `motivo` char(100) DEFAULT NULL,
  `fecest` date DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `nomarc` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=213 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio44`
--

DROP TABLE IF EXISTS `mercurio44`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio44` (
  `usuario` int(11) NOT NULL,
  `codare` char(2) NOT NULL,
  `codope` char(3) NOT NULL,
  `orden` int(11) NOT NULL,
  `estado` enum('A','I') NOT NULL,
  PRIMARY KEY (`usuario`,`codare`,`codope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio45`
--

DROP TABLE IF EXISTS `mercurio45`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio45` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` int(11) NOT NULL,
  `codcaj` char(6) NOT NULL,
  `documento` char(14) NOT NULL,
  `nomben` char(90) DEFAULT NULL,
  `codben` char(14) DEFAULT NULL,
  `codcer` char(4) NOT NULL,
  `estado` char(1) NOT NULL,
  `usuario` int(11) NOT NULL,
  `motivo` char(100) DEFAULT NULL,
  `fecest` date DEFAULT NULL,
  `nomarc` char(100) NOT NULL,
  `periodo` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio46`
--

DROP TABLE IF EXISTS `mercurio46`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio46` (
  `log` int(11) NOT NULL,
  `coddoc` char(2) NOT NULL,
  `documento` char(13) NOT NULL,
  `priape` char(30) NOT NULL,
  `segape` char(30) DEFAULT NULL,
  `prinom` char(30) NOT NULL,
  `segnom` char(30) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `fecnac` date DEFAULT NULL,
  `tipper` char(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio47`
--

DROP TABLE IF EXISTS `mercurio47`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio47` (
  `numero` int(11) NOT NULL,
  `coddoc` int(11) NOT NULL,
  `nomarc` char(255) NOT NULL,
  PRIMARY KEY (`numero`,`coddoc`),
  CONSTRAINT `mercurio47_ibfk_1` FOREIGN KEY (`numero`) REFERENCES `mercurio43` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mercurio48`
--

DROP TABLE IF EXISTS `mercurio48`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mercurio48` (
  `codare` char(2) NOT NULL,
  `codope` char(3) NOT NULL,
  `tipfun` char(4) NOT NULL,
  `orden` int(11) NOT NULL,
  PRIMARY KEY (`codare`,`codope`,`tipfun`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migra079`
--

DROP TABLE IF EXISTS `migra079`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migra079` (
  `idciiu` int(11) DEFAULT NULL,
  `seccion` char(2) DEFAULT NULL,
  `division` varchar(2) DEFAULT NULL,
  `grupo` varchar(3) DEFAULT NULL,
  `clase` varchar(4) DEFAULT NULL,
  `descripcion` char(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migra087`
--

DROP TABLE IF EXISTS `migra087`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migra087` (
  `codbar` char(8) NOT NULL DEFAULT '',
  `codzon` char(13) DEFAULT NULL,
  `detalle` char(30) DEFAULT NULL,
  PRIMARY KEY (`codbar`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migra089`
--

DROP TABLE IF EXISTS `migra089`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migra089` (
  `coddep` char(2) NOT NULL DEFAULT '',
  `codciu` char(6) NOT NULL DEFAULT '',
  `codzon` char(9) NOT NULL DEFAULT '',
  `detdep` char(30) DEFAULT NULL,
  `detciu` char(30) DEFAULT NULL,
  `detzon` char(30) DEFAULT NULL,
  `pais` char(2) DEFAULT NULL,
  PRIMARY KEY (`coddep`,`codciu`,`codzon`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migra090`
--

DROP TABLE IF EXISTS `migra090`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migra090` (
  `iddefinicion` int(11) NOT NULL,
  `definicion` char(50) DEFAULT NULL,
  `concepto` char(50) DEFAULT NULL,
  `fechacreacion` date DEFAULT NULL,
  `usuario` char(6) DEFAULT NULL,
  PRIMARY KEY (`iddefinicion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migra091`
--

DROP TABLE IF EXISTS `migra091`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migra091` (
  `iddetalledef` int(11) NOT NULL,
  `iddefinicion` int(11) NOT NULL,
  `codigo` char(11) DEFAULT NULL,
  `detalledefinicion` char(50) DEFAULT NULL,
  `concepto` char(50) DEFAULT NULL,
  `fechacreacion` date DEFAULT NULL,
  `usuario` char(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-11-28 11:43:07
