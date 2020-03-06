-- MySQL dump 10.13  Distrib 5.7.9, for osx10.9 (x86_64)
--
-- Host: localhost    Database: sgaf1
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.9-MariaDB

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
-- Table structure for table `acertos`
--

DROP TABLE IF EXISTS `acertos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acertos` (
  `ace_codigo` bigint(18) NOT NULL AUTO_INCREMENT,
  `ace_data` date NOT NULL,
  `ace_hora` time NOT NULL,
  `ace_supervisor` int(11) NOT NULL,
  `ace_fornecedor` int(11) NOT NULL,
  `ace_valorbruto` float NOT NULL,
  `ace_valortaxas` float NOT NULL,
  `ace_valorpendente` float NOT NULL,
  `ace_valorpendenteanterior` float NOT NULL,
  `ace_valortotal` float NOT NULL,
  `ace_valorpago` float NOT NULL,
  `ace_trocodevolvido` float NOT NULL,
  `ace_quiosque` bigint(18) NOT NULL,
  PRIMARY KEY (`ace_codigo`),
  KEY `ace_quiosque` (`ace_quiosque`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acertos`
--

LOCK TABLES `acertos` WRITE;
/*!40000 ALTER TABLE `acertos` DISABLE KEYS */;
/*!40000 ALTER TABLE `acertos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acertos_taxas`
--

DROP TABLE IF EXISTS `acertos_taxas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acertos_taxas` (
  `acetax_acerto` bigint(18) NOT NULL,
  `acetax_taxa` smallint(4) NOT NULL,
  `acetax_referencia` float NOT NULL,
  `acetax_valor` float NOT NULL,
  PRIMARY KEY (`acetax_acerto`,`acetax_taxa`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acertos_taxas`
--

LOCK TABLES `acertos_taxas` WRITE;
/*!40000 ALTER TABLE `acertos_taxas` DISABLE KEYS */;
/*!40000 ALTER TABLE `acertos_taxas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cidades`
--

DROP TABLE IF EXISTS `cidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cidades` (
  `cid_codigo` smallint(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `cid_estado` tinyint(2) unsigned zerofill NOT NULL DEFAULT '00',
  `cid_nome` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid_codigo`),
  KEY `cid_estado` (`cid_estado`)
) ENGINE=MyISAM AUTO_INCREMENT=9751 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cidades`
--

LOCK TABLES `cidades` WRITE;
/*!40000 ALTER TABLE `cidades` DISABLE KEYS */;
INSERT INTO `cidades` VALUES (9719,07,'Brasília'),(9718,23,'Santa Maria'),(9717,24,'Florianópolis'),(9716,23,'Porto Alegre'),(9715,23,'Salvador das Missões'),(9722,23,'Marcelino Ramos'),(9723,23,'Erechim'),(9724,23,'Rio Grande'),(9727,30,'La Plata'),(9729,30,'Buenos Aires'),(9730,26,'São Paulo'),(9743,23,'Roque Gonzales'),(9733,23,'São Paulo Das Missões'),(9734,23,'Ivoti'),(9737,23,'Dois Irmãos'),(9739,41,'La Paz'),(9740,42,'Morioka'),(9741,23,'Campina das Missões'),(9742,23,'Cerro Largo'),(9744,23,'São Pedro do Butiá'),(9745,23,'Cândido Godói'),(9747,23,'São Luiz Gonzaga'),(9748,23,'Porto Xavier'),(9749,23,'Santo Ângelo'),(9750,23,'Santa Rosa');
/*!40000 ALTER TABLE `cidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cooperativas`
--

DROP TABLE IF EXISTS `cooperativas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cooperativas` (
  `coo_codigo` smallint(4) NOT NULL AUTO_INCREMENT,
  `coo_nomecompleto` varchar(70) NOT NULL,
  `coo_abreviacao` varchar(30) DEFAULT NULL,
  `coo_presidente` int(11) NOT NULL,
  PRIMARY KEY (`coo_codigo`),
  KEY `coo_presidente` (`coo_presidente`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cooperativas`
--

LOCK TABLES `cooperativas` WRITE;
/*!40000 ALTER TABLE `cooperativas` DISABLE KEYS */;
/*!40000 ALTER TABLE `cooperativas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entradas`
--

DROP TABLE IF EXISTS `entradas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entradas` (
  `ent_codigo` bigint(20) NOT NULL AUTO_INCREMENT,
  `ent_quiosque` bigint(20) NOT NULL,
  `ent_fornecedor` bigint(20) NOT NULL,
  `ent_supervisor` bigint(20) NOT NULL,
  `ent_datacadastro` date NOT NULL,
  `ent_horacadastro` time NOT NULL,
  `ent_tipo` bigint(20) NOT NULL,
  `ent_status` tinyint(4) NOT NULL,
  `ent_valortotal` float DEFAULT NULL,
  PRIMARY KEY (`ent_codigo`),
  KEY `ent_tipo` (`ent_tipo`),
  KEY `ent_fornecedor` (`ent_fornecedor`),
  KEY `ent_vendedor` (`ent_supervisor`),
  KEY `ent_quiosque` (`ent_quiosque`),
  KEY `ent_status` (`ent_status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entradas`
--

LOCK TABLES `entradas` WRITE;
/*!40000 ALTER TABLE `entradas` DISABLE KEYS */;
/*!40000 ALTER TABLE `entradas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entradas_produtos`
--

DROP TABLE IF EXISTS `entradas_produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entradas_produtos` (
  `entpro_entrada` bigint(20) NOT NULL,
  `entpro_numero` smallint(4) NOT NULL AUTO_INCREMENT,
  `entpro_produto` bigint(20) NOT NULL,
  `entpro_quantidade` float NOT NULL,
  `entpro_valorunitario` float NOT NULL,
  `entpro_validade` date DEFAULT NULL,
  `entpro_local` varchar(40) DEFAULT NULL,
  `entpro_valtot` float NOT NULL,
  `entpro_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`entpro_entrada`,`entpro_numero`),
  KEY `entpro_entrada` (`entpro_entrada`),
  KEY `entpro_produto` (`entpro_produto`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entradas_produtos`
--

LOCK TABLES `entradas_produtos` WRITE;
/*!40000 ALTER TABLE `entradas_produtos` DISABLE KEYS */;
/*!40000 ALTER TABLE `entradas_produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entradas_tipo`
--

DROP TABLE IF EXISTS `entradas_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entradas_tipo` (
  `enttip_codigo` tinyint(4) NOT NULL AUTO_INCREMENT,
  `enttip_nome` varchar(70) NOT NULL,
  `enttip_descricao` text NOT NULL,
  PRIMARY KEY (`enttip_codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entradas_tipo`
--

LOCK TABLES `entradas_tipo` WRITE;
/*!40000 ALTER TABLE `entradas_tipo` DISABLE KEYS */;
INSERT INTO `entradas_tipo` VALUES (1,'Normal',''),(2,'Doação','Para produtos que são doados ao quiosque'),(3,'Ajuste','');
/*!40000 ALTER TABLE `entradas_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estados`
--

DROP TABLE IF EXISTS `estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estados` (
  `est_codigo` tinyint(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `est_sigla` varchar(10) NOT NULL DEFAULT '',
  `est_pais` smallint(4) NOT NULL,
  `est_nome` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`est_codigo`),
  KEY `est_pais` (`est_pais`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estados`
--

LOCK TABLES `estados` WRITE;
/*!40000 ALTER TABLE `estados` DISABLE KEYS */;
INSERT INTO `estados` VALUES (01,'AC',1,'Acre'),(02,'AL',1,'Alagoas'),(03,'AM',1,'Amazonas'),(38,'BN',9,'Beni'),(05,'BA',1,'Bahia'),(06,'CE',1,'Ceará'),(07,'DF',1,'Distrito Federal'),(08,'ES',1,'Espírito Santo'),(09,'GO',1,'Goiás'),(10,'MA',1,'Maranhão'),(11,'MG',1,'Minas Gerais'),(12,'MS',1,'Mato Grosso do Sul'),(13,'MT',1,'Mato Grosso'),(14,'PA',1,'Pará'),(15,'PB',1,'Paraíba'),(16,'PE',1,'Pernambuco'),(17,'PI',1,'Piauí'),(18,'PR',1,'Paraná'),(19,'RJ',1,'Rio de Janeiro'),(20,'RN',1,'Rio Grande do Norte'),(21,'RO',1,'Rondônia'),(22,'RR',1,'Roraima'),(23,'RS',1,'Rio Grande do Sul'),(24,'SC',1,'Santa Catarina'),(25,'SE',1,'Sergipe'),(26,'SP',1,'São Paulo'),(27,'TO',1,'Tocantins'),(34,'MS',2,'Missiones'),(39,'CO',9,'Cochabamba'),(30,'PB',2,'Província De Buenos '),(40,'PE',9,'Peni'),(41,'LP',9,'La Paz Estado'),(42,'IW',10,'Iwate');
/*!40000 ALTER TABLE `estados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estoque`
--

DROP TABLE IF EXISTS `estoque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estoque` (
  `etq_quiosque` tinyint(2) NOT NULL,
  `etq_produto` bigint(20) NOT NULL,
  `etq_fornecedor` int(11) NOT NULL,
  `etq_lote` bigint(20) NOT NULL,
  `etq_quantidade` float NOT NULL,
  `etq_valorunitario` float DEFAULT NULL,
  `etq_validade` date DEFAULT NULL,
  PRIMARY KEY (`etq_quiosque`,`etq_produto`,`etq_fornecedor`,`etq_lote`),
  KEY `etqpro_quiosque` (`etq_quiosque`),
  KEY `etqpro_produto` (`etq_produto`),
  KEY `etqpro_fornecedor` (`etq_fornecedor`),
  KEY `etq_lote` (`etq_lote`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estoque`
--

LOCK TABLES `estoque` WRITE;
/*!40000 ALTER TABLE `estoque` DISABLE KEYS */;
/*!40000 ALTER TABLE `estoque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_permissoes`
--

DROP TABLE IF EXISTS `grupo_permissoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupo_permissoes` (
  `gruper_codigo` tinyint(2) NOT NULL AUTO_INCREMENT,
  `gruper_nome` varchar(70) NOT NULL,
  `gruper_cooperativa_ver` tinyint(1) NOT NULL,
  `gruper_cooperativa_cadastrar` tinyint(1) NOT NULL,
  `gruper_cooperativa_editar` tinyint(1) NOT NULL,
  `gruper_cooperativa_excluir` tinyint(1) NOT NULL,
  `gruper_quiosque_ver` tinyint(1) NOT NULL,
  `gruper_quiosque_cadastrar` tinyint(1) NOT NULL,
  `gruper_quiosque_editar` tinyint(1) NOT NULL,
  `gruper_quiosque_excluir` tinyint(1) NOT NULL,
  `gruper_quiosque_definirsupervisores` tinyint(1) NOT NULL,
  `gruper_quiosque_definirvendedores` tinyint(1) NOT NULL,
  `gruper_quiosque_versupervisores` tinyint(1) NOT NULL,
  `gruper_quiosque_vervendedores` tinyint(1) NOT NULL,
  `gruper_quiosque_vertaxas` tinyint(1) NOT NULL,
  `gruper_quiosque_definircooperativa` tinyint(1) NOT NULL,
  `gruper_pessoas_alterar_cooperativa` tinyint(1) NOT NULL,
  `gruper_pessoas_cadastrar` tinyint(1) NOT NULL,
  `gruper_pessoas_cadastrar_administradores` tinyint(1) NOT NULL,
  `gruper_pessoas_cadastrar_presidentes` tinyint(1) NOT NULL,
  `gruper_pessoas_cadastrar_supervisores` tinyint(1) NOT NULL,
  `gruper_pessoas_cadastrar_vendedores` tinyint(1) NOT NULL,
  `gruper_pessoas_cadastrar_fornecedores` tinyint(1) NOT NULL,
  `gruper_pessoas_cadastrar_consumidores` tinyint(1) NOT NULL,
  `gruper_pessoas_excluir` tinyint(1) NOT NULL,
  `gruper_pessoas_ver` tinyint(1) NOT NULL,
  `gruper_pessoas_ver_presidentes` tinyint(1) NOT NULL,
  `gruper_pessoas_ver_supervisores` tinyint(1) NOT NULL,
  `gruper_pessoas_ver_vendedores` tinyint(1) NOT NULL,
  `gruper_pessoas_ver_fornecedores` tinyint(1) NOT NULL,
  `gruper_pessoas_ver_consumidores` tinyint(1) NOT NULL,
  `gruper_pessoas_ver_administradores` tinyint(1) NOT NULL,
  `gruper_pessoas_criarusuarios` tinyint(1) NOT NULL,
  `gruper_pessoas_definir_grupo_administradores` tinyint(1) NOT NULL,
  `gruper_pessoas_definir_grupo_presidentes` tinyint(1) NOT NULL,
  `gruper_pessoas_definir_grupo_supervisores` tinyint(1) NOT NULL,
  `gruper_pessoas_definir_grupo_vendedores` tinyint(1) NOT NULL,
  `gruper_pessoas_definir_grupo_fornecedores` tinyint(1) NOT NULL,
  `gruper_pessoas_definir_grupo_consumidores` tinyint(1) NOT NULL,
  `gruper_pessoas_definir_quiosqueusuario` tinyint(1) NOT NULL,
  `gruper_produtos_ver` tinyint(1) NOT NULL,
  `gruper_produtos_cadastrar` tinyint(1) NOT NULL,
  `gruper_produtos_editar` tinyint(1) NOT NULL,
  `gruper_produtos_excluir` tinyint(1) NOT NULL,
  `gruper_paises_ver` tinyint(1) NOT NULL,
  `gruper_paises_cadastrar` tinyint(1) NOT NULL,
  `gruper_paises_editar` tinyint(1) NOT NULL,
  `gruper_paises_excluir` tinyint(1) NOT NULL,
  `gruper_estados_ver` tinyint(1) NOT NULL,
  `gruper_estados_cadastrar` tinyint(1) NOT NULL,
  `gruper_estados_editar` tinyint(1) NOT NULL,
  `gruper_estados_excluir` tinyint(1) NOT NULL,
  `gruper_cidades_ver` tinyint(1) NOT NULL,
  `gruper_cidades_cadastrar` tinyint(1) NOT NULL,
  `gruper_cidades_editar` tinyint(1) NOT NULL,
  `gruper_cidades_excluir` tinyint(1) NOT NULL,
  `gruper_categorias_ver` tinyint(1) NOT NULL,
  `gruper_categorias_cadastrar` tinyint(1) NOT NULL,
  `gruper_categorias_editar` tinyint(1) NOT NULL,
  `gruper_categorias_excluir` tinyint(1) NOT NULL,
  `gruper_tipocontagem_ver` tinyint(1) NOT NULL,
  `gruper_tipocontagem_cadastrar` tinyint(1) NOT NULL,
  `gruper_tipocontagem_editar` tinyint(1) NOT NULL,
  `gruper_tipocontagem_excluir` tinyint(1) NOT NULL,
  `gruper_estoque_ver` tinyint(1) NOT NULL,
  `gruper_estoque_qtdide_definir` tinyint(1) NOT NULL,
  `gruper_entradas_ver` tinyint(1) NOT NULL,
  `gruper_entradas_cadastrar` tinyint(1) NOT NULL,
  `gruper_entradas_editar` tinyint(1) NOT NULL,
  `gruper_entradas_excluir` tinyint(1) NOT NULL,
  `gruper_entradas_etiquetas` tinyint(1) NOT NULL,
  `gruper_entradas_cancelar` tinyint(1) NOT NULL,
  `gruper_saidas_ver` tinyint(1) NOT NULL,
  `gruper_saidas_cadastrar` tinyint(1) NOT NULL,
  `gruper_saidas_excluir` tinyint(1) NOT NULL,
  `gruper_saidas_editar` tinyint(1) NOT NULL,
  `gruper_saidas_cadastrar_devolucao` tinyint(1) NOT NULL,
  `gruper_saidas_editar_devolucao` tinyint(1) NOT NULL,
  `gruper_saidas_excluir_devolucao` tinyint(1) NOT NULL,
  `gruper_saidas_ver_devolucao` tinyint(1) NOT NULL,
  `gruper_relatorios_ver` tinyint(1) NOT NULL,
  `gruper_relatorios_cadastrar` tinyint(1) NOT NULL,
  `gruper_relatorios_editar` tinyint(1) NOT NULL,
  `gruper_relatorios_excluir` tinyint(1) NOT NULL,
  `gruper_acertos_cadastrar` tinyint(1) NOT NULL,
  `gruper_acertos_editar` tinyint(1) NOT NULL,
  `gruper_acertos_excluir` tinyint(1) NOT NULL,
  `gruper_acertos_ver` tinyint(1) NOT NULL,
  `gruper_taxas_cadastrar` tinyint(1) NOT NULL,
  `gruper_taxas_editar` tinyint(1) NOT NULL,
  `gruper_taxas_excluir` tinyint(1) NOT NULL,
  `gruper_taxas_ver` tinyint(1) NOT NULL,
  `gruper_taxas_aplicar` tinyint(1) NOT NULL,
  PRIMARY KEY (`gruper_codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_permissoes`
--

LOCK TABLES `grupo_permissoes` WRITE;
/*!40000 ALTER TABLE `grupo_permissoes` DISABLE KEYS */;
INSERT INTO `grupo_permissoes` VALUES (1,'Administrador',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,0,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),(2,'Presidente',0,0,0,0,1,1,1,1,1,1,1,1,1,0,0,1,0,0,1,1,1,1,1,1,0,1,1,1,1,0,1,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,1,0,0,0,1,0,1,0,0,1,1,0,0,0,1,0,0,0,0,0,0,1,1,1,1,1,1),(3,'Supervisor',0,0,0,0,1,0,0,0,0,0,1,1,1,0,0,1,0,0,0,1,1,1,1,1,0,0,1,1,1,0,1,0,0,0,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,0,0,0,1,0),(4,'Vendedor',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,1,1,1,0,0,0,0,1,0,0,0,0,0,0,0,1,0,1,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1,1,1,1,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0),(5,'Fornecedor',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0),(7,'Root',1,1,1,1,1,1,1,1,0,0,0,0,0,1,1,1,1,0,0,0,0,0,1,1,0,0,0,0,0,1,1,1,0,0,0,0,0,1,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `grupo_permissoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mestre_pessoas_tipo`
--

DROP TABLE IF EXISTS `mestre_pessoas_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mestre_pessoas_tipo` (
  `mespestip_pessoa` int(11) NOT NULL,
  `mespestip_tipo` tinyint(2) NOT NULL,
  PRIMARY KEY (`mespestip_pessoa`,`mespestip_tipo`),
  KEY `mespestip_pessoa` (`mespestip_pessoa`),
  KEY `mespestip_tipo` (`mespestip_tipo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mestre_pessoas_tipo`
--

LOCK TABLES `mestre_pessoas_tipo` WRITE;
/*!40000 ALTER TABLE `mestre_pessoas_tipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `mestre_pessoas_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paises`
--

DROP TABLE IF EXISTS `paises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paises` (
  `pai_codigo` smallint(6) NOT NULL AUTO_INCREMENT,
  `pai_sigla` char(3) NOT NULL,
  `pai_nome` varchar(50) NOT NULL,
  PRIMARY KEY (`pai_codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paises`
--

LOCK TABLES `paises` WRITE;
/*!40000 ALTER TABLE `paises` DISABLE KEYS */;
INSERT INTO `paises` VALUES (1,'BR','Brasil'),(2,'ARG','Argentina'),(3,'CHL','Chile'),(5,'PY','Paraguai'),(9,'BL','Bolívia'),(10,'JP','Japão');
/*!40000 ALTER TABLE `paises` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pessoas`
--

DROP TABLE IF EXISTS `pessoas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pessoas` (
  `pes_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `pes_id` int(8) DEFAULT NULL,
  `pes_nome` varchar(70) NOT NULL,
  `pes_cidade` mediumint(11) NOT NULL,
  `pes_cep` varchar(9) DEFAULT NULL,
  `pes_bairro` varchar(70) DEFAULT NULL,
  `pes_vila` varchar(70) DEFAULT NULL,
  `pes_endereco` varchar(70) DEFAULT NULL,
  `pes_complemento` varchar(70) DEFAULT NULL,
  `pes_referencia` varchar(70) DEFAULT NULL,
  `pes_numero` varchar(11) DEFAULT NULL,
  `pes_fone1` varchar(13) DEFAULT NULL,
  `pes_fone2` varchar(13) DEFAULT NULL,
  `pes_email` varchar(70) DEFAULT NULL,
  `pes_datacadastro` date DEFAULT NULL,
  `pes_horacadastro` time DEFAULT NULL,
  `pes_dataedicao` date DEFAULT NULL,
  `pes_horaedicao` time DEFAULT NULL,
  `pes_obs` text,
  `pes_chat` varchar(70) DEFAULT NULL,
  `pes_cooperativa` tinyint(2) NOT NULL,
  `pes_possuiacesso` tinyint(1) NOT NULL,
  `pes_senha` text,
  `pes_grupopermissoes` tinyint(2) DEFAULT NULL,
  `pes_quiosqueusuario` bigint(18) DEFAULT NULL,
  PRIMARY KEY (`pes_codigo`),
  KEY `usu_cidade` (`pes_cidade`),
  KEY `pes_quiosque` (`pes_cooperativa`),
  KEY `pes_quiosqueusuario` (`pes_quiosqueusuario`)
) ENGINE=MyISAM AUTO_INCREMENT=267 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pessoas`
--

LOCK TABLES `pessoas` WRITE;
/*!40000 ALTER TABLE `pessoas` DISABLE KEYS */;
INSERT INTO `pessoas` VALUES (1,1,'Usuário Root',0,'','','','','','','','','','','2012-03-27','06:50:12','2012-03-27','06:51:08','Usuário padrão do sistema, não pode ser excluído nunca.','',0,1,'4b0daceccf8aef63c93aca5d5b228d31',7,0);
/*!40000 ALTER TABLE `pessoas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pessoas_tipo`
--

DROP TABLE IF EXISTS `pessoas_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pessoas_tipo` (
  `pestip_codigo` tinyint(2) NOT NULL AUTO_INCREMENT,
  `pestip_nome` varchar(30) NOT NULL,
  PRIMARY KEY (`pestip_codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pessoas_tipo`
--

LOCK TABLES `pessoas_tipo` WRITE;
/*!40000 ALTER TABLE `pessoas_tipo` DISABLE KEYS */;
INSERT INTO `pessoas_tipo` VALUES (1,'Administrador'),(4,'Vendedor'),(5,'Fornecedor'),(6,'Consumidor'),(2,'Presidente'),(3,'Supervisor');
/*!40000 ALTER TABLE `pessoas_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produtos` (
  `pro_codigo` bigint(20) NOT NULL AUTO_INCREMENT,
  `pro_nome` varchar(70) NOT NULL,
  `pro_tipocontagem` tinyint(4) NOT NULL,
  `pro_categoria` mediumint(11) NOT NULL,
  `pro_datacriacao` date NOT NULL,
  `pro_horacriacao` time NOT NULL,
  `pro_dataedicao` date DEFAULT NULL,
  `pro_horaedicao` time DEFAULT NULL,
  `pro_descricao` text,
  `pro_obs` text,
  `pro_cooperativa` smallint(4) NOT NULL,
  `pro_estoqueminimo` float DEFAULT NULL,
  PRIMARY KEY (`pro_codigo`),
  KEY `pro_categoria` (`pro_categoria`),
  KEY `pro_tipocontagem` (`pro_tipocontagem`),
  KEY `pro_quiosque` (`pro_cooperativa`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos_categorias`
--

DROP TABLE IF EXISTS `produtos_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produtos_categorias` (
  `cat_codigo` mediumint(9) NOT NULL AUTO_INCREMENT,
  `cat_nome` varchar(70) NOT NULL,
  `cat_cooperativa` smallint(4) NOT NULL,
  `cat_obs` text,
  PRIMARY KEY (`cat_codigo`),
  KEY `cat_quiosque` (`cat_cooperativa`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos_categorias`
--

LOCK TABLES `produtos_categorias` WRITE;
/*!40000 ALTER TABLE `produtos_categorias` DISABLE KEYS */;
/*!40000 ALTER TABLE `produtos_categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos_tipo`
--

DROP TABLE IF EXISTS `produtos_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produtos_tipo` (
  `protip_codigo` tinyint(4) NOT NULL AUTO_INCREMENT,
  `protip_nome` varchar(70) NOT NULL,
  `protip_sigla` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`protip_codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos_tipo`
--

LOCK TABLES `produtos_tipo` WRITE;
/*!40000 ALTER TABLE `produtos_tipo` DISABLE KEYS */;
INSERT INTO `produtos_tipo` VALUES (1,'Unidade(s)','un.'),(2,'Quilo(s)','kg.');
/*!40000 ALTER TABLE `produtos_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quantidade_ideal`
--

DROP TABLE IF EXISTS `quantidade_ideal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quantidade_ideal` (
  `qtdide_quiosque` smallint(4) NOT NULL,
  `qtdide_produto` mediumint(6) NOT NULL,
  `qtdide_quantidade` float NOT NULL,
  PRIMARY KEY (`qtdide_quiosque`,`qtdide_produto`),
  KEY `qtdide_quiosque` (`qtdide_quiosque`),
  KEY `qtdide_produto` (`qtdide_produto`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quantidade_ideal`
--

LOCK TABLES `quantidade_ideal` WRITE;
/*!40000 ALTER TABLE `quantidade_ideal` DISABLE KEYS */;
/*!40000 ALTER TABLE `quantidade_ideal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiosques`
--

DROP TABLE IF EXISTS `quiosques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiosques` (
  `qui_codigo` bigint(18) NOT NULL AUTO_INCREMENT,
  `qui_nome` varchar(70) NOT NULL,
  `qui_cidade` int(11) NOT NULL,
  `qui_cep` varchar(9) DEFAULT NULL,
  `qui_bairro` varchar(70) DEFAULT NULL,
  `qui_vila` varchar(70) DEFAULT NULL,
  `qui_endereco` varchar(70) DEFAULT NULL,
  `qui_numero` varchar(10) DEFAULT NULL,
  `qui_complemento` varchar(70) DEFAULT NULL,
  `qui_referencia` varchar(70) DEFAULT NULL,
  `qui_fone1` varchar(13) DEFAULT NULL,
  `qui_fone2` varchar(13) NOT NULL,
  `qui_email` varchar(70) DEFAULT NULL,
  `qui_obs` text,
  `qui_datacadastro` date NOT NULL,
  `qui_horacadastro` time NOT NULL,
  `qui_dataedicao` date DEFAULT NULL,
  `qui_horaedicao` time DEFAULT NULL,
  `qui_cooperativa` int(8) NOT NULL,
  `qui_usuario` int(11) NOT NULL,
  `qui_disponivelnobusca` tinyint(4) NOT NULL,
  PRIMARY KEY (`qui_codigo`),
  KEY `qui_cidade` (`qui_cidade`),
  KEY `qui_cooperativa` (`qui_cooperativa`),
  KEY `qui_usuario` (`qui_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiosques`
--

LOCK TABLES `quiosques` WRITE;
/*!40000 ALTER TABLE `quiosques` DISABLE KEYS */;
/*!40000 ALTER TABLE `quiosques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiosques_supervisores`
--

DROP TABLE IF EXISTS `quiosques_supervisores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiosques_supervisores` (
  `quisup_quiosque` tinyint(2) NOT NULL,
  `quisup_supervisor` int(11) NOT NULL,
  `quisup_datafuncao` date DEFAULT NULL,
  PRIMARY KEY (`quisup_quiosque`,`quisup_supervisor`),
  KEY `quisup_quiosque` (`quisup_quiosque`),
  KEY `quisup_supervisor` (`quisup_supervisor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiosques_supervisores`
--

LOCK TABLES `quiosques_supervisores` WRITE;
/*!40000 ALTER TABLE `quiosques_supervisores` DISABLE KEYS */;
/*!40000 ALTER TABLE `quiosques_supervisores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiosques_taxas`
--

DROP TABLE IF EXISTS `quiosques_taxas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiosques_taxas` (
  `quitax_quiosque` bigint(18) NOT NULL,
  `quitax_taxa` smallint(4) NOT NULL,
  `quitax_valor` float NOT NULL,
  PRIMARY KEY (`quitax_quiosque`,`quitax_taxa`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiosques_taxas`
--

LOCK TABLES `quiosques_taxas` WRITE;
/*!40000 ALTER TABLE `quiosques_taxas` DISABLE KEYS */;
/*!40000 ALTER TABLE `quiosques_taxas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiosques_vendedores`
--

DROP TABLE IF EXISTS `quiosques_vendedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiosques_vendedores` (
  `quiven_quiosque` tinyint(4) NOT NULL,
  `quiven_vendedor` bigint(20) NOT NULL,
  `quiven_datafuncao` date DEFAULT NULL,
  PRIMARY KEY (`quiven_quiosque`,`quiven_vendedor`),
  KEY `ven_vendedor` (`quiven_vendedor`),
  KEY `ven_quiosque` (`quiven_quiosque`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiosques_vendedores`
--

LOCK TABLES `quiosques_vendedores` WRITE;
/*!40000 ALTER TABLE `quiosques_vendedores` DISABLE KEYS */;
/*!40000 ALTER TABLE `quiosques_vendedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relatorios`
--

DROP TABLE IF EXISTS `relatorios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relatorios` (
  `rel_codigo` bigint(20) NOT NULL AUTO_INCREMENT,
  `rel_nome` varchar(100) NOT NULL,
  `rel_descricao` text,
  `rel_datacadastro` date NOT NULL,
  `rel_horacadastro` time NOT NULL,
  PRIMARY KEY (`rel_codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relatorios`
--

LOCK TABLES `relatorios` WRITE;
/*!40000 ALTER TABLE `relatorios` DISABLE KEYS */;
INSERT INTO `relatorios` VALUES (1,'Vendas por Fornecedor (resumido)','Este relatório mostra o valor total das vendas de todos os produtos pertencentes a um ou mais fornecedores em um período específico','2012-07-04','22:13:25'),(12,'Vendas por Produto (resumido)','','2012-07-31','07:31:56');
/*!40000 ALTER TABLE `relatorios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relatorios_permissao`
--

DROP TABLE IF EXISTS `relatorios_permissao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relatorios_permissao` (
  `relper_relatorio` bigint(20) NOT NULL,
  `relper_grupo` tinyint(2) NOT NULL,
  PRIMARY KEY (`relper_relatorio`,`relper_grupo`),
  KEY `relper_relatorio` (`relper_relatorio`),
  KEY `relper_grupo` (`relper_grupo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relatorios_permissao`
--

LOCK TABLES `relatorios_permissao` WRITE;
/*!40000 ALTER TABLE `relatorios_permissao` DISABLE KEYS */;
INSERT INTO `relatorios_permissao` VALUES (1,3),(1,5),(12,2),(12,3);
/*!40000 ALTER TABLE `relatorios_permissao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saidas`
--

DROP TABLE IF EXISTS `saidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saidas` (
  `sai_codigo` bigint(20) NOT NULL AUTO_INCREMENT,
  `sai_quiosque` bigint(20) NOT NULL,
  `sai_vendedor` bigint(20) NOT NULL,
  `sai_consumidor` bigint(20) NOT NULL,
  `sai_tipo` tinyint(4) NOT NULL,
  `sai_saidajustificada` tinyint(4) DEFAULT NULL,
  `sai_descricao` text,
  `sai_datacadastro` date NOT NULL,
  `sai_horacadastro` time NOT NULL,
  `sai_status` tinyint(1) NOT NULL,
  `sai_totalbruto` float DEFAULT NULL,
  `sai_descontopercentual` float DEFAULT NULL,
  `sai_descontovalor` float DEFAULT NULL,
  `sai_totalcomdesconto` float DEFAULT NULL,
  `sai_valorecebido` float DEFAULT NULL,
  `sai_troco` float DEFAULT NULL,
  `sai_trocodevolvido` float DEFAULT NULL,
  `sai_descontoforcado` float DEFAULT NULL,
  `sai_acrescimoforcado` float DEFAULT NULL,
  `sai_totalliquido` float NOT NULL,
  PRIMARY KEY (`sai_codigo`),
  KEY `sai_quiosque` (`sai_quiosque`),
  KEY `sai_vendedor` (`sai_vendedor`),
  KEY `sai_cliente` (`sai_consumidor`),
  KEY `sai_tipo` (`sai_tipo`),
  KEY `sai_justificativa` (`sai_saidajustificada`),
  KEY `sai_status` (`sai_status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saidas`
--

LOCK TABLES `saidas` WRITE;
/*!40000 ALTER TABLE `saidas` DISABLE KEYS */;
/*!40000 ALTER TABLE `saidas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saidas_motivo`
--

DROP TABLE IF EXISTS `saidas_motivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saidas_motivo` (
  `saimot_codigo` tinyint(4) NOT NULL AUTO_INCREMENT,
  `saimot_nome` varchar(70) NOT NULL,
  PRIMARY KEY (`saimot_codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saidas_motivo`
--

LOCK TABLES `saidas_motivo` WRITE;
/*!40000 ALTER TABLE `saidas_motivo` DISABLE KEYS */;
INSERT INTO `saidas_motivo` VALUES (1,'Vencimento'),(2,'Fornecedor Pediu'),(3,'Extravio'),(4,'Outro'),(5,'Ajuste');
/*!40000 ALTER TABLE `saidas_motivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saidas_produtos`
--

DROP TABLE IF EXISTS `saidas_produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saidas_produtos` (
  `saipro_saida` bigint(20) NOT NULL,
  `saipro_codigo` bigint(20) NOT NULL AUTO_INCREMENT,
  `saipro_lote` bigint(20) NOT NULL,
  `saipro_produto` bigint(20) NOT NULL,
  `saipro_quantidade` float NOT NULL,
  `saipro_valorunitario` float NOT NULL,
  `saipro_valortotal` float NOT NULL,
  `saipro_acertado` bigint(20) NOT NULL,
  PRIMARY KEY (`saipro_saida`,`saipro_codigo`),
  KEY `saipro_lote` (`saipro_lote`),
  KEY `saipro_produto` (`saipro_produto`),
  KEY `saipro_saida` (`saipro_saida`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saidas_produtos`
--

LOCK TABLES `saidas_produtos` WRITE;
/*!40000 ALTER TABLE `saidas_produtos` DISABLE KEYS */;
/*!40000 ALTER TABLE `saidas_produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saidas_tipo`
--

DROP TABLE IF EXISTS `saidas_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saidas_tipo` (
  `saitip_codigo` tinyint(4) NOT NULL AUTO_INCREMENT,
  `saitip_nome` varchar(70) NOT NULL,
  PRIMARY KEY (`saitip_codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saidas_tipo`
--

LOCK TABLES `saidas_tipo` WRITE;
/*!40000 ALTER TABLE `saidas_tipo` DISABLE KEYS */;
INSERT INTO `saidas_tipo` VALUES (1,'Venda'),(3,'Devolução');
/*!40000 ALTER TABLE `saidas_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `sta_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `sta_nome` varchar(70) NOT NULL,
  PRIMARY KEY (`sta_codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'Válido'),(2,'Incompleto');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taxas`
--

DROP TABLE IF EXISTS `taxas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taxas` (
  `tax_codigo` smallint(4) NOT NULL AUTO_INCREMENT,
  `tax_nome` varchar(70) NOT NULL,
  `tax_descricao` text,
  `tax_cooperativa` smallint(4) DEFAULT NULL,
  `tax_quiosque` bigint(18) DEFAULT NULL,
  PRIMARY KEY (`tax_codigo`),
  KEY `tax_cooperativa` (`tax_cooperativa`,`tax_quiosque`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxas`
--

LOCK TABLES `taxas` WRITE;
/*!40000 ALTER TABLE `taxas` DISABLE KEYS */;
/*!40000 ALTER TABLE `taxas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-01-26 15:17:26
