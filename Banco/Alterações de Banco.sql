# Espaço reservado para incluir as alterações de banco de dados necessárias #

# Baixe a versão 3.1.1. A partir desta estrutura pode executar os scripts abaixo para adaptar para a versão desejada

# Versão 3.1.2


INSERT INTO `produtos_tipo` (`protip_codigo`, `protip_nome`, `protip_sigla`) VALUES ('3', 'Litro(s)', 'lt.');

CREATE TABLE `caixas_tipo` (
  `caitip_codigo` INT NOT NULL,
  `caitip_nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`caitip_codigo`));

INSERT INTO `caixas_tipo` (`caitip_codigo`, `caitip_nome`) VALUES ('1', 'Entrada');
INSERT INTO `caixas_tipo` (`caitip_codigo`, `caitip_nome`) VALUES ('2', 'Saida');


CREATE TABLE `caixas_entradassaidas` (
  `caientsai_id` INT(11) NOT NULL AUTO_INCREMENT,
  `caientsai_tipo` TINYINT(2) NOT NULL,
  `caientsai_valor` FLOAT NOT NULL,
  `caientsai_datacadastro` DATETIME NOT NULL,
  `caientsai_descricao` VARCHAR(60) NULL,
  `caientsai_areceber` TINYINT(2) NOT NULL,
  `caientsai_venda` BIGINT(20) NULL,
  `caientsai_usuarioquecadastrou` INT(11) NOT NULL,
  PRIMARY KEY (`caientsai_id`));

ALTER TABLE `caixas_entradassaidas` 
ADD COLUMN `caientsai_numerooperacao` INT(11) NOT NULL AFTER `caientsai_usuarioquecadastrou`;

ALTER TABLE `caixas_entradassaidas` 
CHANGE COLUMN `caientsai_areceber` `caientsai_areceber` TINYINT(2) NULL ;

ALTER TABLE `caixas_operacoes` 
CHANGE COLUMN `caiopo_saldovendas` `caiopo_saldovendas` FLOAT NULL DEFAULT NULL AFTER `caiopo_valorinicial`,
CHANGE COLUMN `caiopo_valorfinal` `caiopo_valorfinal` FLOAT NULL DEFAULT NULL AFTER `caiopo_totaltroco`,
ADD COLUMN `caiopo_totalbruto` FLOAT NULL AFTER `caiopo_diferenca`,
ADD COLUMN `caiopo_liquido` FLOAT NULL AFTER `caiopo_totalbruto`,
ADD COLUMN `caiopo_liquidosemcartao` FLOAT NULL AFTER `caiopo_liquido`,
ADD COLUMN `caiopo_liquidocartao` FLOAT NULL AFTER `caiopo_liquidosemcartao`,
ADD COLUMN `caiopo_entradastotal` FLOAT NULL AFTER `caiopo_liquidocartao`,
ADD COLUMN `caiopo_saidastotal` FLOAT NULL AFTER `caiopo_entradastotal`,
ADD COLUMN `caiopo_saldoentradassaidas` FLOAT NULL AFTER `caiopo_saidastotal`,
ADD COLUMN `caiopo_totaldescontovendas` FLOAT NULL AFTER `caiopo_saldoentradassaidas`;

ALTER TABLE `caixas_operacoes` 
ADD COLUMN `caiopo_valoresperado` FLOAT NULL AFTER `caiopo_totaldescontovendas`;

ALTER TABLE `caixas_operacoes` 
ADD COLUMN `caiopo_supervisor` INT(11) NULL AFTER `caiopo_valoresperado`;

CREATE TABLE `produtos_porcoes` (
  `propor_codigo` INT NOT NULL AUTO_INCREMENT,
  `propor_produto` BIGINT(20) NOT NULL,
  `propor_quantidade` FLOAT NOT NULL,
  `propor_usuarioquecadastrou` INT(11) NOT NULL,
  `propor_quiosquequecadastrou` INT(11) NOT NULL,
  `propor_datacadastro` DATETIME NOT NULL,
  PRIMARY KEY (`propor_codigo`));

ALTER TABLE `produtos_porcoes` 
ADD COLUMN `propor_nome` VARCHAR(45) NOT NULL AFTER `propor_produto`;

ALTER TABLE `produtos_porcoes` 
DROP COLUMN `propor_quantidade_custo`,
ADD COLUMN `propor_valuniref` FLOAT NULL AFTER `propor_datacadastro`;


ALTER TABLE `saidas_produtos` 
ADD COLUMN `saipro_porcao` INT NULL AFTER `saipro_fechado`,
ADD COLUMN `saipro_porcao_quantidade` FLOAT NULL AFTER `saipro_porcao`;

-- Fim da versão 3.4.1

-- Inicio versão 3.4.1+

ALTER TABLE `pessoas` 
CHANGE COLUMN `pes_cidade` `pes_cidade` MEDIUMINT(11) NULL ;

ALTER TABLE `pessoas` 
ADD COLUMN `pes_datanascimento` DATE NULL AFTER `pes_quiosquequecadastrou`;

UPDATE `grupo_permissoes` SET `gruper_quiosque_definirsupervisores`='1' WHERE `gruper_codigo`='3';

INSERT INTO `pessoas_categoria` (`pescat_codigo`, `pescat_nome`) VALUES ('7', 'Fábrica');


UPDATE `pessoas_tipo` SET `pestip_nome`='Gestor' WHERE `pestip_codigo`='2';

UPDATE `grupo_permissoes` SET `gruper_pessoas_cadastrar_gestores`='1', `gruper_pessoas_cadastrar_supervisores`='1', `gruper_pessoas_ver_gestores`='1', `gruper_pessoas_definir_grupo_gestores`='0' WHERE `gruper_codigo`='3';


ALTER TABLE `produtos` 
ADD COLUMN `pro_podesersubproduto` INT(1) NOT NULL DEFAULT 0 AFTER `pro_quiosquequecadastrou`;

CREATE TABLE `produtos_subproduto` (
  `prosub_produto` int(11) NOT NULL,
  `prosub_subproduto` int(11) NOT NULL,
  `prosub_numero` int(11) NOT NULL,
  `prosub_quantidade` float NOT NULL,
  `prosub_usuarioquecadastrou` int(11) NOT NULL,
  `prosub_datacadastro` datetime NOT NULL,
  PRIMARY KEY (`prosub_produto`,`prosub_subproduto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `entradas_produtos` (
  `entpro_entrada` bigint(20) NOT NULL,
  `entpro_numero` smallint(4) NOT NULL AUTO_INCREMENT,
  `entpro_produto` bigint(20) NOT NULL,
  `entpro_quantidade` float NOT NULL,
  `entpro_valorunitario` float NOT NULL,
  `entpro_validade` date DEFAULT NULL,
  `entpro_local` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entpro_valtot` float NOT NULL,
  `entpro_status` tinyint(1) NOT NULL,
  `entpro_valunicusto` float DEFAULT NULL,
  `entpro_valtotcusto` float DEFAULT NULL,
  `entpro_retiradodoestoquesubprodutos` tinyint(1) NOT NULL DEFAULT '0',
  `entpro_temsubprodutos` tinyint(1) NOT NULL,
  PRIMARY KEY (`entpro_entrada`,`entpro_numero`),
  KEY `entpro_entrada` (`entpro_entrada`),
  KEY `entpro_produto` (`entpro_produto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `entradas_produtos` 
ADD COLUMN `entpro_retiradodoestoque` TINYINT(1) NOT NULL DEFAULT 0 AFTER `entpro_valtotcusto`;

ALTER TABLE `entradas_produtos` 
ADD COLUMN `entpro_desejaretirarsubprodutos` TINYINT(1) NULL DEFAULT NULL AFTER `entpro_retiradodoestoquesubprodutos`;
ALTER TABLE `entradas_produtos` 
CHANGE COLUMN `entpro_desejaretirarsubprodutos` `entpro_temsubprodutos` TINYINT(1) NOT NULL ;

ALTER TABLE `saidas` 
ADD COLUMN `sai_id` INT(11) NULL AFTER `sai_usuarioquecadastrou`;

UPDATE `grupo_permissoes` SET `gruper_pessoas_cadastrar_consumidores`='1' WHERE `gruper_codigo`='7';

INSERT INTO `status` (`sta_codigo`, `sta_nome`) VALUES ('3', 'Cancelado');

UPDATE `agape`.`configuracoes` SET `cnf_versao`='v3.5b' WHERE `cnf_codigo`='1';
