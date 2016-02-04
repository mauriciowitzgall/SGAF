
<?php


//Verifica se o usuário tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_pessoas_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "pessoas";
include "includes.php";


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PESSOAS  ";
$tpl_titulo->SUBTITULO = "PEQUISA/LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "pessoas.png";
$tpl_titulo->show();

$tpl = new Template("templates/listagem_2.html");
$tpl->FORM_ONLOAD = "pessoas_filtro_id()";

//FILTRO INICIO
$filtro_id = $_POST["filtro_id"];
$filtro_nome = $_POST["filtro_nome"];
$filtro_tipo = $_POST["filtro_tipo"];
$filtro_tipopessoa = $_POST["filtro_tipopessoa"];
$filtro_possuiacesso = $_POST["filtro_possuiacesso"];
$filtro_proprio = $_POST["filtro_proprio"];
if ($filtro_proprio=="") $filtro_proprio=1;

$tpl->LINK_FILTRO = "pessoas.php";

//Filtro ID
$tpl->CAMPO_TITULO = "ID";
$tpl->CAMPO_TAMANHO = "10";
$tpl->CAMPO_NOME = "filtro_id";
$tpl->CAMPO_VALOR = $filtro_id;
$tpl->CAMPO_QTD_CARACTERES = "20";
$tpl->CAMPO_ONKEYUP = "pessoas_filtro_id()";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Nome
$tpl->CAMPO_TITULO = "Nome";
$tpl->CAMPO_TAMANHO = "30";
$tpl->CAMPO_NOME = "filtro_nome";
$tpl->CAMPO_VALOR = $filtro_nome;
$tpl->CAMPO_ONKEYUP = "";
$tpl->CAMPO_QTD_CARACTERES = "70";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Tipo
if ($permissao_pessoas_criarusuarios == 1) {
    $tpl->SELECT_TITULO = "Tipo";
    $tpl->SELECT_NOME = "filtro_tipo";
    $tpl->SELECT_TAMANHO = "";
    $sql = "
SELECT DISTINCT
    *
FROM
    pessoas_tipo
ORDER BY
    pestip_codigo
";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        $tpl->OPTION_VALOR = $dados["pestip_codigo"];
        $tpl->OPTION_NOME = $dados["pestip_nome"];
        $tipo = $dados["pestip_codigo"];
        if ((($tipo == 1) && ($permissao_pessoas_ver_administradores == 1)) ||
                (($tipo == 2) && ($permissao_pessoas_ver_gestores == 1)) ||
                (($tipo == 3) && ($permissao_pessoas_ver_supervisores == 1)) ||
                (($tipo == 4) && ($permissao_pessoas_ver_caixas == 1)) ||
                (($tipo == 5) && ($permissao_pessoas_ver_fornecedores == 1)) ||
                (($tipo == 6) && ($permissao_pessoas_ver_supervisores == 1))
        ) {
            if ($dados["pestip_codigo"] == $filtro_tipo) {
                $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
            }
            $tpl->block("BLOCK_FILTRO_SELECT_OPTION");
        }
    }
    $tpl->block("BLOCK_FILTRO_SELECT");
    $tpl->block("BLOCK_FILTRO_COLUNA");
}

//Filtro Tipo Pessoa
$tpl->SELECT_TITULO = "Tipo de Pessoa";
$tpl->SELECT_NOME = "filtro_tipopessoa";
$tpl->SELECT_TAMANHO = "";
$sql = "
SELECT DISTINCT * FROM pessoas_tipopessoa ORDER BY pestippes_codigo";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl->OPTION_VALOR = $dados["pestippes_codigo"];
    $tpl->OPTION_NOME = $dados["pestippes_nome"];
    $tipo = $dados["pestippes_codigo"];
    if ($dados["pestippes_codigo"] == $filtro_tipopessoa) {
        $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
    }
    $tpl->block("BLOCK_FILTRO_SELECT_OPTION");
}
$tpl->block("BLOCK_FILTRO_SELECT");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Filtro Acesso
if ($permissao_pessoas_criarusuarios == 1) {

    $tpl->SELECT_TITULO = "Acesso";
    $tpl->SELECT_NOME = "filtro_possuiacesso";
    $tpl->SELECT_TAMANHO = "";
    $tpl->OPTION_VALOR = "1";
    $tpl->OPTION_NOME = "Somente Usuários";
    if ($filtro_possuiacesso == '1')
        $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
    $tpl->block("BLOCK_FILTRO_SELECT_OPTION");
    $tpl->OPTION_VALOR = "0";
    $tpl->OPTION_NOME = "Não Usuários";
    if ($filtro_possuiacesso == '0')
        $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
    $tpl->block("BLOCK_FILTRO_SELECT_OPTION");
    $tpl->block("BLOCK_FILTRO_SELECT");
    $tpl->block("BLOCK_FILTRO_COLUNA");
}
//Filtro Próprio
$tpl->SELECT_TITULO = "Próprio";
$tpl->SELECT_NOME = "filtro_proprio";
$tpl->SELECT_TAMANHO = "";
$tpl->OPTION_VALOR = "1";
$tpl->OPTION_NOME = "Sim";
if ($filtro_proprio == "1")
    $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
$tpl->block("BLOCK_FILTRO_SELECT_OPTION");
$tpl->OPTION_VALOR = "0";
$tpl->OPTION_NOME = "Todos";
if ($filtro_proprio == '0')
    $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
$tpl->block("BLOCK_FILTRO_SELECT_OPTION");
$tpl->block("BLOCK_FILTRO_SELECT");
$tpl->block("BLOCK_FILTRO_COLUNA");



if ($usuario_grupo != 5) {
    if ($permissao_pessoas_cadastrar == 0) {
        $tpl->block("BLOCK_RODAPE_BOTAO_CADASTRAR_DESABILITADO");
        $tpl->LINK_CADASTRO = "#";
    } else {
        $tpl->LINK_CADASTRO = "pessoas_cadastrar.php?operacao=cadastrar";
    }
    $tpl->BOTAO_CADASTRAR_NOME = "CADASTRAR PESSOA";
    $tpl->block("BLOCK_FILTRO_BOTAO_CAD");
}
$tpl->block("BLOCK_FILTRO_BOTOES");
$tpl->block("BLOCK_FILTRO");



//Filtro Fim
//LISTAGEM INICIO
//Cabe�alho
$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "ID";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "NOME";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "TIPO PESSOA";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "CIDADE";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "TELEFONE 01";
$tpl->block("BLOCK_LISTA_CABECALHO");

if ($permissao_pessoas_criarusuarios == 1) {
    $tpl->CABECALHO_COLUNA_TAMANHO = "80PX";
    $tpl->CABECALHO_COLUNA_COLSPAN = "";
    $tpl->CABECALHO_COLUNA_NOME = "POSSUI ACESSO";
    $tpl->block("BLOCK_LISTA_CABECALHO");
}

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "TIPO";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "TIPO NEG.";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_COLSPAN = "4";
$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_NOME = "OPERAÇÕES";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Lista linhas
//Verifica quais filtros devem ser considerados no sql principal
$sql_filtro = "";
if ($filtro_id <> "")
    $sql_filtro = $sql_filtro . " and pes_id = $filtro_id";
if ($filtro_nome <> "")
    $sql_filtro = $sql_filtro . " and pes_nome LIKE '%$filtro_nome%'";
if ($filtro_tipo <> "")
    $sql_filtro = $sql_filtro . " and mespestip_tipo = $filtro_tipo ";
if ($filtro_tipopessoa <> "")
    $sql_filtro = $sql_filtro . " and pes_tipopessoa = $filtro_tipopessoa ";
if ($filtro_possuiacesso <> "")
    $sql_filtro = $sql_filtro . " and pes_possuiacesso = $filtro_possuiacesso ";
if ($filtro_possuiacesso <> "")
    $sql_filtro = $sql_filtro . " and pes_possuiacesso = $filtro_possuiacesso ";
if ($usuario_grupo != 7)
    $sql_filtro = $sql_filtro . " and pes_cooperativa=$usuario_cooperativa ";
$cont = 0;
if ($permissao_pessoas_ver_administradores == 0) {
    $cont++;
    if ($cont == 1)
        $sql_filtro2 = $sql_filtro2 . " mespestip_tipo=1";
    else
        $sql_filtro2 = $sql_filtro2 . " or mespestip_tipo=1";
    $filtro2 = 1;
}
if ($permissao_pessoas_ver_gestores == 0) {
    $cont++;
    if ($cont == 1)
        $sql_filtro2 = $sql_filtro2 . " mespestip_tipo=2";
    else
        $sql_filtro2 = $sql_filtro2 . " or mespestip_tipo=2";
}
if ($permissao_pessoas_ver_supervisores == 0) {
    $cont++;
    if ($cont == 1)
        $sql_filtro2 = $sql_filtro2 . " mespestip_tipo=3";
    else
        $sql_filtro2 = $sql_filtro2 . " or mespestip_tipo=3";
}
if ($permissao_pessoas_ver_caixas == 0) {
    $cont++;
    if ($cont == 1)
        $sql_filtro2 = $sql_filtro2 . " mespestip_tipo=4";
    else
        $sql_filtro2 = $sql_filtro2 . " or mespestip_tipo=4";
}
if ($permissao_pessoas_ver_fornecedores == 0) {
    $cont++;
    if ($cont == 1)
        $sql_filtro2 = $sql_filtro2 . " mespestip_tipo=5";
    else
        $sql_filtro2 = $sql_filtro2 . " or mespestip_tipo=5";
}
if ($permissao_pessoas_ver_consumidores == 0) {
    $cont++;
    if ($cont == 1)
        $sql_filtro2 = $sql_filtro2 . " mespestip_tipo=6";
    else
        $sql_filtro2 = $sql_filtro2 . " or mespestip_tipo=6";
}
if ($filtro2 == 1) {
    $sql_filtro = $sql_filtro . " and pes_codigo not in (SELECT mespestip_pessoa FROM mestre_pessoas_tipo WHERE $sql_filtro2 )";
}

//Se o usuário for o Root então só mostrar os administradores
if ($usuario_grupo == 7) {
    $sql_filtro = " and mespestip_tipo=1";
}

if ($filtro_proprio==1) $sql_filtro.=" and pes_quiosquequecadastrou=$usuario_quiosque ";


//Inicio das tuplas
$sql = "
SELECT DISTINCT
    pes_codigo,pes_nome,cid_nome,pes_fone1,pes_fone2,pes_possuiacesso,pes_id,pestippes_nome,pestippes_codigo,pes_quiosquequecadastrou,pes_usuarioquecadastrou
FROM
    pessoas    
    JOIN cidades on (pes_cidade=cid_codigo)    
    JOIN mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)    
    left join pessoas_tipopessoa on (pes_tipopessoa=pestippes_codigo)
WHERE
    1 $sql_filtro 
ORDER BY
    pes_nome
";

//Paginação
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());
$linhas = mysql_num_rows($query);
$por_pagina = $usuario_paginacao;
$paginaatual = $_POST["paginaatual"];
$paginas = ceil($linhas / $por_pagina);
//Se � a primeira vez que acessa a pagina ent�o come�ar na pagina 1
if (($paginaatual == "") || ($paginas < $paginaatual) || ($paginaatual <= 0)) {
    $paginaatual = 1;
}
$comeco = ($paginaatual - 1) * $por_pagina;
$tpl->PAGINAS = "$paginas";
$tpl->PAGINAATUAL = "$paginaatual";
$tpl->PASTA_ICONES = "$icones";
$tpl->block("BLOCK_PAGINACAO");
$sql = $sql . " LIMIT $comeco,$por_pagina ";


$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$cont = 0;
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["pes_codigo"];
    $cont++;
    $tipopessoa = $dados["pestippes_codigo"];
    $quiosquequecadastrou=$dados["pes_quiosquequecadastrou"];
    $usuarioquecadastrou=$dados["pes_usuarioquecadastrou"];

    $sql3="SELECT qui_nome FROM quiosques WHERE qui_codigo=$quiosquequecadastrou";
    if (!$query3=mysql_query($sql3)) die("Erro SQL3:" . mysql_error());
    $dados3=  mysql_fetch_assoc($query3);
    $quiosquequecadastrou_nome=$dados3["qui_nome"];
    $motivo="Produto registrado pelo quiosque: $quiosquequecadastrou_nome";
    
    
    //Coluna ID
    $tpl->LISTA_COLUNA_VALOR = $dados["pes_id"];
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Nome
    $tpl->LISTA_COLUNA_VALOR = $dados["pes_nome"];
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Cidade
    $tpl->LISTA_COLUNA_VALOR = $dados["pestippes_nome"];
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Cidade
    $tpl->LISTA_COLUNA_VALOR = $dados["cid_nome"];
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Fone1 
    $tpl->LISTA_COLUNA_VALOR = $dados["pes_fone1"];
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Possui acesso
    if ($permissao_pessoas_criarusuarios == 1) {
        //Coluna Acesso
        if ($dados["pes_possuiacesso"] == 1) {
            $acesso = 'Sim';
        } else {
            $acesso = 'Não';
        }
        $tpl->LISTA_COLUNA_VALOR = $acesso;
        $tpl->block("BLOCK_LISTA_COLUNA");
    }



    //Coluna Tipo    
    $tpl->IMAGEM_ALINHAMENTO="right";
    $sql_tipo = "SELECT * FROM mestre_pessoas_tipo WHERE mespestip_pessoa='$codigo'";
    $query_tipo = mysql_query($sql_tipo);
    if (!$query)
        die("Erro de SQL Tipo de Pessoa: " . mysql_error());
    $tipo_administrador = 0;
    $tipo_gestor = 0;
    $tipo_supervisor = 0;
    $tipo_caixa = 0;
    $tipo_fornecedor = 0;
    $tipo_consumidor = 0;
    while ($dados_tipo = mysql_fetch_assoc($query_tipo)) {
        $tipo = $dados_tipo['mespestip_tipo'];
        //echo "$tipo ";
        if ($tipo == 1) {
            $tipo_administrador = 1;
        }
        if ($tipo == 2) {
            $tipo_gestor = 1;
        }
        if ($tipo == 3) {
            $tipo_supervisor = 1;
        }
        if ($tipo == 4) {
            $tipo_caixa = 1;
        }
        if ($tipo == 5) {
            $tipo_fornecedor = 1;
        }
        if ($tipo == 6) {
            $tipo_consumidor = 1;
        }
    }

    //Mostra os icones das pessoas na tela
    $icone_tamanho = "10px";
    $tpl->IMAGEM_TAMANHO = $icone_tamanho;

    if ($tipopessoa == 1) {
        //Administrador
        if ($tipo_administrador == 1) {
            $tpl->LINK = "#";
            $tpl->IMAGEM_PASTA = "$icones2";
            $tpl->IMAGEM_NOMEARQUIVO = "administrador.png";
            $tpl->IMAGEM_TITULO = "Administrador";
            $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
        } else {
            $tpl->LINK = "#";
            $tpl->IMAGEM_TAMANHO = $icone_tamanho;
            $tpl->IMAGEM_PASTA = "$icones2";
            $tpl->IMAGEM_NOMEARQUIVO = "administrador2.png";
            $tpl->IMAGEM_TITULO = "Administrador";
            $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
        }


        //Gestor
        $tpl->IMAGEM_TITULO = "Gestor";
        if ($tipo_gestor == 1) {
            $tpl->LINK = "#";
            $tpl->IMAGEM_PASTA = "$icones2";
            $tpl->IMAGEM_NOMEARQUIVO = "gestor.png";
            $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
        } else {
            $tpl->LINK = "#";
            $tpl->IMAGEM_TAMANHO = $icone_tamanho;
            $tpl->IMAGEM_PASTA = "$icones2";
            $tpl->IMAGEM_NOMEARQUIVO = "gestor2.png";

            $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
        }


        //Supervisor


        $tpl->IMAGEM_TITULO = "Supervisor";
        if ($tipo_supervisor == 1) {
            $tpl->LINK = "#";
            $tpl->IMAGEM_PASTA = "$icones2";
            $tpl->IMAGEM_NOMEARQUIVO = "supervisor.png";
            $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
        } else {
            $tpl->LINK = "#";
            $tpl->IMAGEM_TAMANHO = $icone_tamanho;
            $tpl->IMAGEM_PASTA = "$icones2";
            $tpl->IMAGEM_NOMEARQUIVO = "supervisor2.png";
            $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
        }



        //caixa
        $tpl->IMAGEM_TITULO = "Caixa";
        if ($tipo_caixa == 1) {
            $tpl->LINK = "#";
            $tpl->IMAGEM_PASTA = "$icones2";
            $tpl->IMAGEM_NOMEARQUIVO = "caixa.png";
            $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
        } else {
            $tpl->LINK = "#";
            $tpl->IMAGEM_TAMANHO = $icone_tamanho;
            $tpl->IMAGEM_PASTA = "$icones2";
            $tpl->IMAGEM_NOMEARQUIVO = "caixa2.png";
            $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
        }
    }

    //Fornecedor
    $tpl->IMAGEM_TITULO = "Fornecedor";
    if ($tipo_fornecedor == 1) {
        $tpl->LINK = "#";
        $tpl->IMAGEM_PASTA = "$icones2";
        $tpl->IMAGEM_NOMEARQUIVO = "fornecedor.png";
        $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    } else {
        $tpl->LINK = "#";
        $tpl->IMAGEM_TAMANHO = $icone_tamanho;
        $tpl->IMAGEM_PASTA = "$icones2";
        $tpl->IMAGEM_NOMEARQUIVO = "fornecedor2.png";
        $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    }

    //Consumidor
    $tpl->IMAGEM_TITULO = "Consumidor";
    if ($tipo_consumidor == 1) {
        $tpl->LINK = "#";
        $tpl->IMAGEM_PASTA = "$icones2";
        $tpl->IMAGEM_NOMEARQUIVO = "consumidor.png";
        $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    } else {
        $tpl->LINK = "#";
        $tpl->IMAGEM_TAMANHO = $icone_tamanho;
        $tpl->IMAGEM_PASTA = "$icones2";
        $tpl->IMAGEM_NOMEARQUIVO = "consumidor2.png";
        $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    }
    $tpl->block("BLOCK_LISTA_COLUNA_ICONES");




    //Tipo de negociação  
    $tpl->IMAGEM_ALINHAMENTO="center";
    if ($tipo_fornecedor == 1) {

        $icone_tamanho = "18px";
        $sql2 = "SELECT * FROM fornecedores_tiponegociacao WHERE fortipneg_pessoa=$codigo";
        $query2 = mysql_query($sql2);
        if (!$query2)
            die("Erro: 8" . mysql_error());
        $tpl->LINK = "#";
        $tpl->IMAGEM_TAMANHO = $icone_tamanho;
        $tpl->IMAGEM_PASTA = "$icones";
        $tipo_consignacao = 0;
        $tipo_revenda = 0;
        while ($dados2 = mysql_fetch_assoc($query2)) {
            $tipo2 = $dados2["fortipneg_tiponegociacao"];
            if ($tipo2 == 1)
                $tipo_consignacao = 1;
            if ($tipo2 == 2)
                $tipo_revenda = 1;
        }
        $tpl->IMAGEM_TITULO = "Consignação";
        $tpl->IMAGEM_NOMEARQUIVO = "consignacao_desabilitado.png";
        if ($tipo_consignacao == 1) {
            $tpl->IMAGEM_NOMEARQUIVO = "consignacao.png";
        }
        $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
        $tpl->IMAGEM_TITULO = "Revenda";
        $tpl->IMAGEM_NOMEARQUIVO = "revenda_desabilitado.png";
        if ($tipo_revenda == 1) {
            $tpl->IMAGEM_NOMEARQUIVO = "revenda.png";
        }
        $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    }
    $tpl->block("BLOCK_LISTA_COLUNA_ICONES");

    $tpl->CODIGO="$codigo";

    //Atenção
    $tpl->ICONE_ARQUIVO="$icones";
    $tpl->COLUNA_CLASSE="tab_operacao";
    if ($usuario_quiosque==$quiosquequecadastrou) {
        $tpl->ICONE_NOME="atencao2.png";
        $tpl->OPERACAO_TITULO="";
        $tpl->OPERACAO_NOME="Atenção";
    } else if ($quiosquequecadastrou==0){
         $tpl->ICONE_NOME="atencao.png";
        $tpl->OPERACAO_TITULO="Esta pessoa foi cadastrada pelos gestores da cooperativa";
        $tpl->OPERACAO_NOME="Atenção";       
    } else {
        $tpl->ICONE_NOME="atencao.png";
        $tpl->OPERACAO_TITULO="Esta pessoa foi cadastrada pelo quiosque: $quiosquequecadastrou_nome";
        $tpl->OPERACAO_NOME="Atenção";
    }
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");
    
    //detalhes
    if ($permissao_pessoas_ver == 1) {
        $tpl->LINK = "pessoas_cadastrar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=ver";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DETALHES");
    } else {
        $tpl->LINK = "#";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DETALHES_DESABILITADO");
    }
    //editar
    if ($permissao_pessoas_cadastrar == 1) {
        $tpl->LINK = "pessoas_cadastrar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=editar";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR");
    } else {
        $tpl->LINK = "#";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR_DESABILITADO");
    }
    //excluir
    if ($permissao_pessoas_excluir == 1) {
        $tpl->LINK = "pessoas_deletar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=excluir";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR");
    } else {
        $tpl->LINK = "#";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR_DESABILITADO");
    }



    $tpl->block("BLOCK_LISTA");
}
if ($cont == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}


$tpl->show();
include "rodape.php";
?>
