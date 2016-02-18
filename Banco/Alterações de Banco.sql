# Espaço reservado para incluir as alterações de banco de dados necessárias #

# Baixe a versão 3.1.1. A partir desta estrutura pode executar os scripts abaixo para adaptar para a versão desejada

# Versão 3.1.2


INSERT INTO `coopaf`.`produtos_tipo` (`protip_codigo`, `protip_nome`, `protip_sigla`) VALUES ('3', 'Litro(s)', 'lt.');

CREATE TABLE `sgaf`.`caixas_tipo` (
  `caitip_codigo` INT NOT NULL,
  `caitip_nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`caitip_codigo`));

INSERT INTO `sgaf`.`caixas_tipo` (`caitip_codigo`, `caitip_nome`) VALUES ('1', 'Entrada');
INSERT INTO `sgaf`.`caixas_tipo` (`caitip_codigo`, `caitip_nome`) VALUES ('2', 'Saida');


CREATE TABLE `sgaf`.`caixas_entradassaidas` (
  `caientsai_id` INT(11) NOT NULL AUTO_INCREMENT,
  `caientsai_tipo` TINYINT(2) NOT NULL,
  `caientsai_valor` FLOAT NOT NULL,
  `caientsai_datacadastro` DATETIME NOT NULL,
  `caientsai_descricao` VARCHAR(60) NULL,
  `caientsai_areceber` TINYINT(2) NOT NULL,
  `caientsai_venda` BIGINT(20) NULL,
  `caientsai_usuarioquecadastrou` INT(11) NOT NULL,
  PRIMARY KEY (`caientsai_id`));

ALTER TABLE `sgaf`.`caixas_entradassaidas` 
ADD COLUMN `caientsai_numerooperacao` INT(11) NOT NULL AFTER `caientsai_usuarioquecadastrou`;

ALTER TABLE `sgaf`.`caixas_entradassaidas` 
CHANGE COLUMN `caientsai_areceber` `caientsai_areceber` TINYINT(2) NULL ;

ALTER TABLE `sgaf`.`caixas_operacoes` 
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

ALTER TABLE `sgaf`.`caixas_operacoes` 
ADD COLUMN `caiopo_valoresperado` FLOAT NULL AFTER `caiopo_totaldescontovendas`;

ALTER TABLE `sgaf`.`caixas_operacoes` 
ADD COLUMN `caiopo_supervisor` INT(11) NULL AFTER `caiopo_valoresperado`;

CREATE TABLE `sgaf`.`produtos_porcoes` (
  `propor_codigo` INT NOT NULL AUTO_INCREMENT,
  `propor_produto` BIGINT(20) NOT NULL,
  `propor_quantidade` FLOAT NOT NULL,
  `propor_usuarioquecadastrou` INT(11) NOT NULL,
  `propor_quiosquequecadastrou` INT(11) NOT NULL,
  `propor_datacadastro` DATETIME NOT NULL,
  PRIMARY KEY (`propor_codigo`));

ALTER TABLE `sgaf`.`produtos_porcoes` 
ADD COLUMN `propor_nome` VARCHAR(45) NOT NULL AFTER `propor_produto`;

ALTER TABLE `sgaf`.`produtos_porcoes` 
DROP COLUMN `propor_quantidade_custo`,
ADD COLUMN `propor_valuniref` FLOAT NULL AFTER `propor_datacadastro`;


ALTER TABLE `sgaf`.`saidas_produtos` 
ADD COLUMN `saipro_porcao` INT NULL AFTER `saipro_fechado`,
ADD COLUMN `saipro_porcao_quantidade` FLOAT NULL AFTER `saipro_porcao`;
