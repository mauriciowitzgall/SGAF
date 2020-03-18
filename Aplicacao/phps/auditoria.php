<?php
$tipopagina = "auditoria";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
include "includes.php";

if ($usaauditoria <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
//print_r($_REQUEST);

$filtro_numero=$_REQUEST["filtro_numero"];
$filtro_usuario_nome=$_REQUEST["filtro_usuario_nome"];
$filtro_tela=$_REQUEST["filtro_tela"];
$filtro_tabela=$_REQUEST["filtro_tabela"];
$filtro_operacao=$_REQUEST["filtro_operacao"];
$filtro_de=$_REQUEST["filtro_de"];
$filtro_ate=$_REQUEST["filtro_ate"];
$filtro_de_hora=$_REQUEST["filtro_de_hora"];
$filtro_ate_hora=$_REQUEST["filtro_ate_hora"];
if ($filtro_de_hora=="") $filtro_de_hora="00:00";
if ($filtro_ate_hora=="") $filtro_ate_hora="23:59";
$filtro_descricao=$_REQUEST["filtro_descricao"];



//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "AUDITORIA";
$tpl_titulo->SUBTITULO = "LOGS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "auditoria.png";
$tpl_titulo->show();


if ($usaauditoria!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO CAIXA</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}



$tpl = new Template("templates/listagem_2.html");


// Filtro Numero
$tpl->CAMPO_TITULO = "Número";
$tpl->CAMPO_VALOR = $filtro_numero;
$tpl->CAMPO_NOME = "filtro_numero";
$tpl->CAMPO_TAMANHO = "12";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

// Filtro usuario nome
$tpl->CAMPO_TITULO = "Usuário nome";
$tpl->CAMPO_NOME = "filtro_usuario_nome";
$tpl->CAMPO_VALOR = $filtro_usuario_nome;
$tpl->CAMPO_TAMANHO = "25";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

// Filtro tela
$tpl->CAMPO_TITULO = "Tela";
$tpl->CAMPO_NOME = "filtro_tela";
$tpl->CAMPO_VALOR = $filtro_tela;
$tpl->CAMPO_TAMANHO = "25";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

// Filtro tabela
$tpl->CAMPO_TITULO = "Tabela";
$tpl->CAMPO_NOME = "filtro_tabela";
$tpl->CAMPO_VALOR = $filtro_tabela;
$tpl->CAMPO_TAMANHO = "25";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

// Filtro operacao
$tpl->SELECT_TITULO="Operação";
$tpl->SELECT_NOME="filtro_operacao";
$tpl->SELECT_TAMANHO="";
$tpl->OPTION_VALOR="";
$tpl->OPTION_NOME="Qualquer";
if ($filtro_operacao=="") $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
$tpl->block("BLOCK_FILTRO_SELECT_OPTION");
$tpl->OPTION_VALOR="INSERT";
$tpl->OPTION_NOME="Inseriu";
if ($filtro_operacao=="INSERT") $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
$tpl->block("BLOCK_FILTRO_SELECT_OPTION");
$tpl->OPTION_VALOR="UPDATE";
$tpl->OPTION_NOME="Atualizou";
if ($filtro_operacao=="UPDATE") $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
$tpl->block("BLOCK_FILTRO_SELECT_OPTION");
$tpl->OPTION_VALOR="DELETE";
$tpl->OPTION_NOME="Removeu";
if ($filtro_operacao=="DELETE") $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
$tpl->block("BLOCK_FILTRO_SELECT_OPTION");
$tpl->block("BLOCK_FILTRO_SELECT");
$tpl->block("BLOCK_FILTRO_COLUNA");


$tpl->block("BLOCK_FILTRO");

// Filtro descrição
$tpl->CAMPO_TITULO = "Descrição";
$tpl->CAMPO_NOME = "filtro_descricao";
$tpl->CAMPO_VALOR = $filtro_descricao;
$tpl->CAMPO_TAMANHO = "30";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


// Filtro Datas
$tpl->CAMPO2_TITULO = "De";
$tpl->CAMPO2_TIPO = "date";
$tpl->CAMPO2_NOME = "filtro_de";
$tpl->CAMPO2_VALOR = "$filtro_de";
$tpl->CAMPO2_TAMANHO = "12";
$tpl->block("BLOCK_FILTRO_CAMPO2");
$tpl->CAMPO2_TITULO = "";
$tpl->CAMPO2_TIPO = "time";
$tpl->CAMPO2_NOME = "filtro_de_hora";
$tpl->CAMPO2_VALOR = "$filtro_de_hora";
$tpl->CAMPO2_TAMANHO = "12";
$tpl->block("BLOCK_FILTRO_CAMPO2");
$tpl->block("BLOCK_FILTRO_COLUNA");
$tpl->CAMPO2_TITULO = "Até";
$tpl->CAMPO2_TIPO = "date";
$tpl->CAMPO2_NOME = "filtro_ate";
$tpl->CAMPO2_VALOR = "$filtro_ate";
$tpl->CAMPO2_TAMANHO = "12";
$tpl->block("BLOCK_FILTRO_CAMPO2");
$tpl->CAMPO2_TITULO = "";
$tpl->CAMPO2_TIPO = "time";
$tpl->CAMPO2_NOME = "filtro_ate_hora";
$tpl->CAMPO2_VALOR = "$filtro_ate_hora";
$tpl->CAMPO2_TAMANHO = "12";
$tpl->block("BLOCK_FILTRO_CAMPO2");
$tpl->block("BLOCK_FILTRO_COLUNA");

$tpl->block("BLOCK_FILTRO_BOTOES");

$tpl->block("BLOCK_FILTRO");
$tpl->block("BLOCK_FILTRO");


//Código
$tpl->CABECALHO_COLUNA_TAMANHO="30px";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="ID";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Data 
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="2";
$tpl->CABECALHO_COLUNA_NOME="DATA";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Usuário
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="USUÁRIO";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Tela
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="TELA";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Tabela
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="TABELA";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Operação
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="OPERAÇÃO";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Descrição
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="DESCRIÇÃO";
$tpl->block("BLOCK_LISTA_CABECALHO");

if ($filtro_numero<>"") $sql_filtro.="AND aud_codigo = $filtro_numero";
if ($filtro_usuario_nome<>"") $sql_filtro.="AND aud_usuario_nome like '%$filtro_usuario_nome%'";
if ($filtro_tela<>"") $sql_filtro.="AND aud_tela like '%$filtro_tela%'";
if ($filtro_tabela<>"") $sql_filtro.="AND aud_tabela like '%$filtro_tabela%'";
if ($filtro_operacao<>"") $sql_filtro.="AND aud_operacao like '%$filtro_operacao%'";
if ($filtro_de<>"") $sql_filtro.="AND aud_data >=  '$filtro_de $filtro_de_hora'";
if ($filtro_ate<>"") $sql_filtro.="AND aud_data <= '$filtro_ate $filtro_ate_hora'";


$filtro_descricao=str_replace(' ','%',$filtro_descricao);
if ($filtro_descricao<>"") $sql_filtro.="AND aud_descricao like '%$filtro_descricao%'";

$sql="
    SELECT * FROM auditoria    
    WHERE 1
    $sql_filtro 
    ORDER BY aud_codigo
";


$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());


$cont=0;
while ($dados=  mysql_fetch_assoc($query)) {
    $codigo= $dados["aud_codigo"];
    $usuario_nome= $dados["aud_usuario_nome"];
    $data= $dados["aud_data"];
    $operacao= $dados["aud_operacao"];
    $tabela= $dados["aud_tabela"];
    $tela= $dados["aud_tela"];
    $descricao= $dados["aud_descricao"];

    //Código
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$codigo";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Data
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $data=converte_datahora($data);
    $data2 = explode (" ",$data);
    $data= $data2[0];
    $hora= converte_hora($data2[1]);
    $tpl->LISTA_COLUNA_VALOR="$data";
    $tpl->block("BLOCK_LISTA_COLUNA");
    $tpl->LISTA_COLUNA_ALINHAMENTO="left";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$hora" ;
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Usuario
    $tpl->LISTA_COLUNA_ALINHAMENTO="left";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR="$usuario_nome";
    $tpl->LINK="";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Tela
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$tela";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    
    //Tabela
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$tabela";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
   //Operação
   $tpl->LISTA_COLUNA_ALINHAMENTO="";
   $tpl->LISTA_COLUNA_CLASSE="";
   $tpl->LISTA_COLUNA_TAMANHO="";
   if ($operacao=="INSERT") $operacao_nome="Inseriu";
   else if ($operacao=="UPDATE") $operacao_nome="Atualizou";
   else if ($operacao=="DELETE") $operacao_nome="Removeu";
   $tpl->LISTA_COLUNA_VALOR= "$operacao_nome";
   $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Descrição
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$descricao";
    $tpl->block("BLOCK_LISTA_COLUNA");
    //Operações
    $tpl->ICONE_ARQUIVO="$icones";
    

    $tpl->block("BLOCK_LISTA"); 
    $cont++;
}

if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}


$tpl->show();

include "rodape.php";

?>



