<?php

include "rel_topo.php";
include "cabecalho1.php";


////Pega os campo de filtro

$tiporel = $_REQUEST["tiporel"];
$classificacao = $_REQUEST["classificacao"];


//Listagem
$tpl_lista = new Template("../templates/lista2.html");
$tpl_lista->block("BLOCK_TABELA_CHEIA");


//Cabeçalho
$tpl_lista->TEXTO = "CONSUMIDOR";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "TIPO PESSOA";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "CPF / CNPJ";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "TEL 1";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "TEL 2";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "ENDERECO";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "CIDADE";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
if ($tiporel==2) {    
    $tpl_lista->TEXTO = "DATA CAD.";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");  
    $tpl_lista->TEXTO = "QTD. VENDAS";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");  
    $tpl_lista->TEXTO = "DATA ULT. VENDA";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");  
    $tpl_lista->TEXTO = "USUARIO QUE CAD.";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");  
    $tpl_lista->block("BLOCK_LINHA");  
}

//Fim do cabeçalho
$tpl_lista->LINHA_CLASSE = "tab_cabecalho";
$tpl_lista->block("BLOCK_LINHA_DINAMICA");
$tpl_lista->block("BLOCK_LINHA");
$tpl_lista->block("BLOCK_CORPO");

if ($tiporel==1) {
    $sql_select="1";
    $sql_orderby="pes_nome";
} else {
    $sql_select.="(SELECT count(sai_codigo) FROM saidas WHERE sai_consumidor=pes_codigo and pes_cooperativa=$usuario_cooperativa) as qtd_vendas, ";
    $sql_select.="(SELECT max(sai_datacadastro) FROM saidas WHERE sai_consumidor=pes_codigo and pes_cooperativa=$usuario_cooperativa) as ultima_venda, ";
    $sql_select.="(SELECT u.pes_nome FROM pessoas u WHERE u.pes_codigo=p.pes_usuarioquecadastrou) as usuario";
    if ($classificacao==1) {
        $sql_orderby="pes_datacadastro desc, pes_nome";
    } else if ($classificacao==2) { 
        $sql_orderby="(SELECT count(sai_codigo) FROM saidas WHERE sai_consumidor=pes_codigo and pes_cooperativa=$usuario_cooperativa) desc ,pes_nome";
    } else if ($classificacao==3) { 
        $sql_orderby="(SELECT max(sai_datacadastro) FROM saidas WHERE sai_consumidor=pes_codigo and pes_cooperativa=$usuario_cooperativa) desc,pes_nome";
    } else if ($classificacao==4) {
        $sql_orderby="(SELECT u. pes_nome FROM pessoas u WHERE u. pes_codigo=p. pes_usuarioquecadastrou) ,p. pes_nome";
    } else {
        $sql_orderby="pes_nome";
    }
}    
    
$sql = "    
    SELECT *, $sql_select
    FROM pessoas p
    LEFT JOIN cidades on (cid_codigo=pes_cidade)    
    WHERE pes_cooperativa=$usuario_cooperativa  
    
    order by $sql_orderby
";
$query = mysql_query($sql);
if (!$query)die("Erro 15:" . mysql_error());

while ($dados = mysql_fetch_assoc($query)) {
    
    //Consumidor
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $dados["pes_nome"];
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";   
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    
    //Tipo pessoas
    if ($dados["pes_tipopessoa"]==1) $tipopessoa="PF";
    else $tipopessoa="PJ";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $tipopessoa;
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";   
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    
    //CPF ou CNPJ
    if ($dados["pes_tipopessoa"]==1) $documento=$dados["pes_cpf"];  
    else $documento=$dados["pes_cnpj"]; 
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $documento;
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";   
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    
    
    //Telefone 1
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $dados["pes_fone1"];    
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";   
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    
    //Telefone 2
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $dados["pes_fone2"];    
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";   
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");  

    //Endereço
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $dados["pes_endereco"] . ", " . $dados["pes_endereco_numero"];  
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";   
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");  

    //Cidade
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $dados["cid_nome"]; 
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";   
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");  

    if ($tiporel==2) {
        //Data de cadastro
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = converte_data($dados["pes_datacadastro"]); 
        $tpl_lista->COLUNA_ALINHAMENTO = "left";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->COLUNA_COLSPAN = "";   
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");
        //Quantidade Vendas
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = $dados["qtd_vendas"];   
        $tpl_lista->COLUNA_ALINHAMENTO = "left";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->COLUNA_COLSPAN = "";   
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");
        //Data de ultima venda
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = converte_data($dados["ultima_venda"]); 
        $tpl_lista->COLUNA_ALINHAMENTO = "left";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->COLUNA_COLSPAN = "";   
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");
        //Usuário que cadastrou
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = $dados["usuario"];  
        $tpl_lista->COLUNA_ALINHAMENTO = "left";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->COLUNA_COLSPAN = "";   
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");          
        
    }
    $tpl_lista->block("BLOCK_LINHA");
  
}

$tpl_lista->block("BLOCK_CORPO");
$tpl_lista->block("BLOCK_LISTAGEM");
$tpl_lista->show();

include "rel_baixo.php";
?>