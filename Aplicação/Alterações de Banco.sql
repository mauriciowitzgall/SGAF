# Espaço reservado para incluir as alterações de banco de dados necessárias #

# Versão 3.1 

# Novo tipo de contagem. Cadastrar 'Sacola' e 'Outros'
INSERT INTO `sgaf_online`.`produtos_recipientes` (`prorec_nome`) VALUES ('Sacola');
INSERT INTO `sgaf_online`.`produtos_recipientes` (`prorec_nome`) VALUES ('Outro');


# Alteratina de contorno para: Edição de pessoas mostra campo 'Categoria' de pessoa jurídica
update pessoas set pes_id='1', pes_tipopessoa='1' where pes_id is null 


# Corrige problema de gravação de hora nos campos
ALTER TABLE `sgaf_online`.`fechamentos` 
CHANGE COLUMN `fch_dataini` `fch_dataini` DATETIME NOT NULL ,
CHANGE COLUMN `fch_datafim` `fch_datafim` DATETIME NOT NULL ;

# Altera permissãs para que supervisores possa ver dados de outros supervisores
UPDATE `sgaf_online`.`grupo_permissoes` SET `gruper_pessoas_ver_supervisores`='1' WHERE `gruper_codigo`='3';

# O nome 'vendedor' não é mais utilizado, foi trocado para 'caixa
UPDATE `sgaf_online`.`pessoas_tipo` SET `pestip_nome`='Caixa' WHERE `pestip_codigo`='4';

# Cria campo novo na tabela de produtos para armazenar se um produto é industrializado ou não
ALTER TABLE `sgaf_online`.`produtos` 
ADD COLUMN `pro_industrializado` INT NOT NULL AFTER `pro_idunico`;


# MÓDULO DE CAIXA

# Altera table de quiosque deixando-a apta para receber os relacionamentos de outras tabelas
ALTER TABLE `sgaf_online`.`quiosques` 
ENGINE = InnoDB ;
ALTER TABLE `sgaf_online`.`quiosques` 
CHANGE COLUMN `qui_codigo` `qui_codigo` INT(11) NOT NULL ;

# Tabela de situação de caixa
CREATE TABLE `sgaf_online`.`caixas_situacao` (
  `caisit_codigo` INT NOT NULL,
  `caisit_nome` VARCHAR(45) NULL,
  PRIMARY KEY (`caisit_codigo`));
INSERT INTO `sgaf_online`.`caixas_situacao` (`caisit_codigo`, `caisit_nome`) VALUES ('1', 'Aberto');
INSERT INTO `sgaf_online`.`caixas_situacao` (`caisit_codigo`, `caisit_nome`) VALUES ('2', 'Encerrado');


# Tabela de caixa
CREATE TABLE `caixas` (
  `cai_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `cai_nome` varchar(45) DEFAULT NULL,
  `cai_local` varchar(45) NOT NULL,
  `cai_quiosque` int(11) NOT NULL,
  `cai_situacao` int(11) NOT NULL,
  `cai_datahoracadastro` datetime NOT NULL,
  PRIMARY KEY (`cai_codigo`),
  KEY `situacao_idx` (`cai_situacao`),
  KEY `quiosque_idx` (`cai_quiosque`),
  CONSTRAINT `quiosque` FOREIGN KEY (`cai_quiosque`) REFERENCES `quiosques` (`qui_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `situacao` FOREIGN KEY (`cai_situacao`) REFERENCES `caixas_situacao` (`caisit_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `sgaf_online`.`pessoas` 
CHANGE COLUMN `pes_codigo` `pes_codigo` INT(11) NOT NULL ;

ALTER TABLE `sgaf_online`.`pessoas` 
ENGINE = InnoDB ;


CREATE TABLE `sgaf_online`.`caixas_operadores` (
  `caiope_caixa` INT NOT NULL,
  `caiope_operador` INT NOT NULL,
  `caiope_datafuncao` DATETIME NULL,
  PRIMARY KEY (`caiope_caixa`, `caiope_operador`),
  INDEX `operador_idx` (`caiope_operador` ASC),
  CONSTRAINT `operador`
    FOREIGN KEY (`caiope_operador`)
    REFERENCES `sgaf_online`.`pessoas` (`pes_codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `caixa`
    FOREIGN KEY (`caiope_caixa`)
    REFERENCES `sgaf_online`.`caixas` (`cai_codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


ALTER TABLE `sgaf_online`.`grupo_permissoes` 
ADD COLUMN `gruper_caixas_cadastrar` TINYINT(1) NOT NULL AFTER `gruper_pessoas_definir_grupo_caixas`,
ADD COLUMN `gruper_caixas_editar` TINYINT(1) NOT NULL AFTER `gruper_caixas_cadastrar`,
ADD COLUMN `gruper_caixas_excluir` TINYINT(1) NOT NULL AFTER `gruper_caixas_editar`,
ADD COLUMN `gruper_caixas_ver` TINYINT(1) NOT NULL AFTER `gruper_caixas_excluir`,
ADD COLUMN `gruper_caixas_operadores_gerir` TINYINT(1) NOT NULL AFTER `gruper_caixas_ver`;


UPDATE `sgaf_online`.`grupo_permissoes` SET `gruper_caixas_cadastrar`='1', `gruper_caixas_editar`='1', `gruper_caixas_excluir`='1', `gruper_caixas_ver`='1', `gruper_caixas_operadores_gerir`='1' WHERE `gruper_codigo`='1';
UPDATE `sgaf_online`.`grupo_permissoes` SET `gruper_caixas_cadastrar`='0', `gruper_caixas_editar`='0', `gruper_caixas_excluir`='0', `gruper_caixas_ver`='1', `gruper_caixas_operadores_gerir`='0' WHERE `gruper_codigo`='2';
UPDATE `sgaf_online`.`grupo_permissoes` SET `gruper_caixas_cadastrar`='1', `gruper_caixas_editar`='1', `gruper_caixas_excluir`='1', `gruper_caixas_ver`='1', `gruper_caixas_operadores_gerir`='1' WHERE `gruper_codigo`='3';
UPDATE `sgaf_online`.`grupo_permissoes` SET `gruper_caixas_ver`='1' WHERE `gruper_codigo`='4';


CREATE TABLE `caixas_operacoes` (
  `caiopo_numero` int(11) NOT NULL,
  `caiopo_caixa` int(11) NOT NULL,
  `caiopo_datahoraabertura` datetime NOT NULL,
  `caiopo_datahoraencerramento` datetime DEFAULT NULL,
  `caiopo_operador` int(11) NOT NULL,
  `caiopo_valorinicial` float NOT NULL,
  `caiopo_totalvendas` float DEFAULT NULL,
  `caiopo_totaltroco` float DEFAULT NULL,
  `caiopo_saldovendas` float DEFAULT NULL,
  `caiopo_diferenca` float DEFAULT NULL,
  PRIMARY KEY (`caiopo_numero`),
  KEY `caiopo_caixa_idx` (`caiopo_caixa`),
  KEY `caiopo_operador_idx` (`caiopo_operador`),
  CONSTRAINT `caiopo_caixa` FOREIGN KEY (`caiopo_caixa`) REFERENCES `caixas` (`cai_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `caiopo_operador` FOREIGN KEY (`caiopo_operador`) REFERENCES `pessoas` (`pes_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `sgaf_online`.`caixas_operacoes` 
CHANGE COLUMN `caiopo_numero` `caiopo_numero` INT(11) NOT NULL AUTO_INCREMENT ;



ALTER TABLE `sgaf_online`.`grupo_permissoes` 
ADD COLUMN `gruper_caixas_operacoes_ver` TINYINT(1) NOT NULL AFTER `gruper_caixas_operadores_gerir`,
ADD COLUMN `gruper_caixas_operacoes_abrir` TINYINT(1) NOT NULL AFTER `gruper_caixas_operacoes_ver`,
ADD COLUMN `gruper_caixas_operacoes_encerrar` TINYINT(1) NOT NULL AFTER `gruper_caixas_operacoes_abrir`;

UPDATE `sgaf_online`.`grupo_permissoes` SET `gruper_caixas_operacoes_ver`='1', `gruper_caixas_operacoes_abrir`='1', `gruper_caixas_operacoes_encerrar`='1' WHERE `gruper_codigo`='3';
UPDATE `sgaf_online`.`grupo_permissoes` SET `gruper_caixas_operacoes_ver`='1' WHERE `gruper_codigo`='2';
UPDATE `sgaf_online`.`grupo_permissoes` SET `gruper_caixas_operadores_gerir`='1', `gruper_caixas_operacoes_ver`='1', `gruper_caixas_operacoes_abrir`='1', `gruper_caixas_operacoes_encerrar`='1' WHERE `gruper_codigo`='4';
UPDATE `sgaf_online`.`grupo_permissoes` SET `gruper_caixas_operacoes_ver`='1', `gruper_caixas_operacoes_abrir`='1', `gruper_caixas_operacoes_encerrar`='1' WHERE `gruper_codigo`='1';


ALTER TABLE `sgaf_online`.`caixas_operacoes` 
ADD COLUMN `caiopo_valorfinal` FLOAT NULL AFTER `caiopo_diferenca`;


ALTER TABLE `sgaf_online`.`pessoas` 
ADD COLUMN `pes_caixausuario` INT(11) NULL AFTER `pes_pessoacontato`;

ALTER TABLE `sgaf_online`.`grupo_permissoes` 
ADD COLUMN `gruper_caixas_trocar` TINYINT(1) NOT NULL AFTER `gruper_caixas_operacoes_encerrar`;

UPDATE `sgaf_online`.`grupo_permissoes` SET `gruper_caixas_trocar`='1' WHERE `gruper_codigo`='1';
UPDATE `sgaf_online`.`grupo_permissoes` SET `gruper_caixas_trocar`='1' WHERE `gruper_codigo`='3';

ALTER TABLE `sgaf_online`.`saidas` 
ENGINE = InnoDB ;

ALTER TABLE `sgaf_online`.`saidas` 
CHANGE COLUMN `sai_caixa` `sai_caixa` INT(11) NOT NULL ;

ALTER TABLE `sgaf_online`.`saidas` 
CHANGE COLUMN `sai_caixa` `sai_caixaoperador` INT(11) NOT NULL ;

ALTER TABLE `sgaf_online`.`saidas` 
ADD COLUMN `sai_caixacodigo` INT(11) NULL AFTER `sai_metpag`;


ALTER TABLE `sgaf_online`.`saidas` 
DROP COLUMN `sai_caixacodigo`,
DROP COLUMN `sai_caixaoperador`,
ADD COLUMN `sai_caixaoperacaonumero` INT(11) NULL AFTER `sai_metpag`,
DROP INDEX `sai_vendedor` ;

ALTER TABLE `sgaf_online`.`pessoas` 
ADD COLUMN `pes_caixaoperacaonumero` INT(11) NULL AFTER `pes_caixausuario`;

ALTER TABLE `sgaf_online`.`pessoas` 
DROP COLUMN `pes_caixausuario`,
DROP INDEX `pes_caixausuario_idx` ;

ALTER TABLE `sgaf_online`.`saidas` 
ADD COLUMN `sai_caixaoperadorresponsavel` INT(11) NULL AFTER `sai_caixaoperacaonumero`;

ALTER TABLE `sgaf_online`.`caixas_operacoes` 
ADD COLUMN `caiopo_supervisor` INT(11) NULL AFTER `caiopo_valorfinal`;

ALTER TABLE `sgaf_online`.`caixas` 
ADD COLUMN `cai_status` INT(1) NOT NULL DEFAULT 1 AFTER `cai_datahoracadastro`;
