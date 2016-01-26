
# Versão 1.1 - Módulo entidades


/*  ENTIDADE CATEGORIA, USADO QUANDO CRIA-SE UMA PESSOA JURÍDICA*/
CREATE TABLE IF NOT EXISTS pessoas_categoria (
  pescat_codigo smallint(6) NOT NULL AUTO_INCREMENT,
  pescat_nome varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (pescat_codigo)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;
INSERT INTO pessoas_categoria (pescat_codigo, pescat_nome) VALUES
(1, 'Agro-indústria'),
(2, 'Empresa'),
(3, 'Distribuidora'),
(4, 'Orgão Público'),
(5, 'Outro');


/*  TIPO DE PESSOA, AGORA NO CADASTRO TEM 2 TIPOS, FÍSICA E JURÍDICA */
CREATE TABLE IF NOT EXISTS pessoas_tipopessoa (
  pestippes_codigo tinyint(4) NOT NULL,
  pestippes_nome varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (pestippes_codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT INTO pessoas_tipopessoa (pestippes_codigo, pestippes_nome) VALUES
(1, 'Pessoa Física'),
(2, 'Pessoa Jurídica');


/*  ATRIBUTOS NOVOS EM PESSOAS PARA CONTEMPLAR DADOS QUE FALTAM NO TIPO DE PESSOA 'PESSOA JURÍDICA'*/
ALTER TABLE  pessoas ADD  pes_cnpj VARCHAR( 14 ) NULL ,
ADD  pes_fone1ramal INT( 9 ) NULL ,
ADD  pes_fone2ramal VARCHAR( 9 ) NULL ,
ADD  pes_categoria MEDIUMINT NULL ,
ADD  pes_tipopessoa INT NULL ,
ADD  pes_pessoacontato VARCHAR( 70 ) NULL ,
ADD INDEX (  pes_categoria ,  pes_tipopessoa );

exportar grupo_permissoes


# -----------------------------------------------------------------------
# Versão 1.2 Modulo misto

# Atributo ent_tiponegociacao em ENTRADAS
ALTER TABLE  entradas ADD  ent_tiponegociacao TINYINT NOT NULL ,
ADD INDEX (  ent_tiponegociacao );

# Entidade tipo_negociacao
CREATE TABLE IF NOT EXISTS tipo_negociacao (
  tipneg_codigo tinyint(4) NOT NULL,
  tipneg_nome varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (tipneg_codigo)
);
INSERT INTO tipo_negociacao (tipneg_codigo, tipneg_nome) VALUES
(1, 'Consignação'),
(2, 'Revenda');


# Entidade mestre_produtos_tipo
CREATE TABLE IF NOT EXISTS mestre_produtos_tipo (
  mesprotip_produto bigint(20) NOT NULL,
  mesprotip_tipo tinyint(4) NOT NULL,
  PRIMARY KEY (mesprotip_produto,mesprotip_tipo),
  KEY mesprotip_produto (mesprotip_produto),
  KEY mesprotip_tipo (mesprotip_tipo)
);


# Entidade fornecedores_tiponegociacao
CREATE TABLE IF NOT EXISTS fornecedores_tiponegociacao (
  fortipneg_pessoa bigint(20) NOT NULL,
  fortipneg_tiponegociacao tinyint(4) NOT NULL,
  PRIMARY KEY (fortipneg_pessoa,fortipneg_tiponegociacao)
);


# Entidade quiosque_tiponegociacao
CREATE TABLE IF NOT EXISTS quiosques_tiponegociacao (
  quitipneg_quiosque int(11) NOT NULL,
  quitipneg_tipo tinyint(4) NOT NULL,
  PRIMARY KEY (quitipneg_quiosque,quitipneg_tipo),
  KEY quitipneg_tipo (quitipneg_tipo),
  KEY quitipneg_quiosque (quitipneg_quiosque)
);


# Entidade fechamentos
CREATE TABLE IF NOT EXISTS fechamentos (
  fch_codigo bigint(20) NOT NULL AUTO_INCREMENT,
  fch_datacadastro date NOT NULL,
  fch_horacadastro time NOT NULL,
  fch_dataini date NOT NULL,
  fch_horaini time NOT NULL,
  fch_datafim date NOT NULL,
  fch_horafim time NOT NULL,
  fch_supervisor bigint(20) NOT NULL,
  fch_totalvenda float NOT NULL,
  fch_totalcusto float NOT NULL,
  fch_totallucro float NOT NULL,
  fch_totaltaxas float NOT NULL,
  fch_totaltaxaquiosque float NOT NULL,
  fch_qtdvendas int(11) NOT NULL,
  fch_qtdprodutos int(11) NOT NULL,
  fch_qtdfornecedores int(11) NOT NULL,
  fch_qtdlotes int(11) NOT NULL,
  PRIMARY KEY (fch_codigo),
  KEY fch_supervisor (fch_supervisor)
) ;


# Entidade fechamentos taxas
CREATE TABLE IF NOT EXISTS fechamentos_taxas (
  fchtax_fechamento bigint(20) NOT NULL,
  fchtax_taxa int(11) NOT NULL,
  fchtax_referencia float NOT NULL,
  fchtax_valor float NOT NULL,
  PRIMARY KEY (fchtax_fechamento,fchtax_taxa),
  KEY fchtax_fechamento (fchtax_fechamento),
  KEY fchtax_taxa (fchtax_taxa)
);


# Alterações na entidade taxas
ALTER TABLE  taxas ADD  tax_tiponegociacao TINYINT NOT NULL ,
ADD INDEX (  tax_tiponegociacao );


# Alterações na entidade entradas_produtos
ALTER TABLE  entradas_produtos ADD  entpro_valunicusto FLOAT NULL;
ALTER TABLE  entradas_produtos ADD  entpro_valtotcusto FLOAT NULL;


# Alterações na entidade entradas
ALTER TABLE  entradas ADD  ent_valortotalcusto FLOAT NULL;


# Alterações na entidade saidas_produtos
ALTER TABLE  saidas_produtos ADD  saipro_fechado BIGINT NULL ,
ADD INDEX (  saipro_fechado );

# ------foi executado na base quente até aqui -----

# Alteração na entidade fechamentos
ALTER TABLE  fechamentos ADD  fch_quiosque INT NOT NULL ,
ADD INDEX (  fch_quiosque );

ALTER TABLE fechamentos
  DROP fch_horaini,
  DROP fch_horafim;

# alterado de date para datetime o atributo fch_dataini e fch_datafim
# ATENCAO


ALTER TABLE  saidas ADD  sai_datahoracadastro DATETIME NOT NULL;


ALTER TABLE  produtos ADD  pro_volume VARCHAR( 70 ) NULL ,
ADD  pro_recipiente VARCHAR( 70 ) NULL ,
ADD  pro_marca VARCHAR( 70 ) NULL ,
ADD  pro_composicao TEXT NULL



CREATE TABLE IF NOT EXISTS produtos_recipientes (
  prorec_codigo tinyint(4) NOT NULL AUTO_INCREMENT,
  prorec_nome varchar(70) NOT NULL,
  prorec_sigla varchar(10) DEFAULT NULL,
  PRIMARY KEY (prorec_codigo)
); 
INSERT INTO produtos_recipientes (prorec_codigo, prorec_nome, prorec_sigla) VALUES
(1, 'Pote', NULL),
(2, 'Caixa', NULL),
(3, 'Kit', NULL),
(4, 'Pacote', NULL),
(5, 'Saca', NULL),
(6, 'Peti', NULL),
(7, 'Fardo', NULL);


ALTER TABLE  produtos ADD  pro_codigounico BIGINT( 12 ) NULL
ALTER TABLE  produtos CHANGE  pro_codigounico  pro_codigounico BIGINT( 13 ) NULL DEFAULT NULL

ALTER TABLE  produtos CHANGE  pro_marca  pro_marca VARCHAR( 70 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL

Novo campo no banco 'qui_disponivelnobusca'

CREATE TABLE IF NOT EXISTS metodos_pagamento (
  metpag_codigo tinyint(4) NOT NULL AUTO_INCREMENT,
  metpag_nome varchar(45) NOT NULL,
  PRIMARY KEY (metpag_codigo)
);
INSERT INTO metodos_pagamento (metpag_codigo, metpag_nome) VALUES
(1, 'Dinheiro'),
(2, 'Cartão Crédito'),
(3, 'Cartão Débito'),
(4, 'Cheque'),
(5, 'Outro');

ALTER TABLE  saidas ADD  sai_areceber BOOLEAN NOT NULL ,
ADD  sai_metpag TINYINT NOT NULL ,
ADD INDEX (  sai_metpag );





# -----------------------------------------------------
# Versão 1.3