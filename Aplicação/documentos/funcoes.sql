/*Limpa o banco preparando-o para iniciar uma nova cooperativa */
TRUNCATE acertos;
TRUNCATE acertos_taxas;
TRUNCATE cooperativas;
TRUNCATE entradas;
TRUNCATE entradas_produtos;
TRUNCATE estoque;
TRUNCATE produtos;
TRUNCATE produtos_categorias;
TRUNCATE quantidade_ideal;
TRUNCATE quiosques;
TRUNCATE quiosques_supervisores;
TRUNCATE quiosques_taxas;
TRUNCATE quiosques_vendedores;
TRUNCATE saidas;
TRUNCATE saidas_produtos;
TRUNCATE taxas;
DELETE FROM pessoas WHERE pes_codigo not in (1);
DELETE FROM mestre_pessoas_tipo WHERE mespestip_pessoa not in (1);

/*  */

/*  */

/*  */

/*  */


