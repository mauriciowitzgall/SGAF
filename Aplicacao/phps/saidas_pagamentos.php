<?php
$tipopagina = "pagamentos";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";

$saida=$_GET["saida"];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PAGAMENTOS";
$tpl_titulo->SUBTITULO = "LISTA DE PAGAMENTOS DE UMA VENDA À RECEBER";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "saidas_pagamentos3.png";
$tpl_titulo->show();






$tpl = new Template("templates/listagem_2.html");

//Pega dados da venda para populas os campos de filtro desabilitados
$sql="SELECT * FROM saidas LEFT JOIN pessoas on sai_consumidor = pes_codigo WHERE sai_codigo=$saida";
if (!$query=mysql_query($sql)) die("Erro SQL Filtros que mostram dados da saída: " . mysql_error());
$dados=mysql_fetch_assoc($query);
$consumidor_nome=$dados["pes_nome"];
$datavenda=$dados["sai_datacadastro"];
$horavenda=$dados["sai_horacadastro"];
$qtd_parcelas=$dados["sai_qtdparcelas"];
$venda_totalcomdesconto=$dados["sai_totalcomdesconto"];
$venda_totalbruto=$dados["sai_totalbruto"];
$venda_descontovalor=$dados["sai_descontovalor"];
$venda_descontopercentual=$dados["sai_descontopercentual"];


//Verifica se tem pagamentos, e calcula o total de pagamentos recebidos
$sql2 = "
    SELECT *
    FROM saidas_pagamentos
    join saidas on (sai_codigo=saipag_saida)
    join metodos_pagamento on (saipag_metpagamento=metpag_codigo)
    WHERE sai_codigo=$saida
    ORDER BY saipag_data DESC
";
if (!$query2 = mysql_query($sql2)) die("Erro48" . mysql_error());
$linhas2=mysql_num_rows($query2);
$tempagamentos=0;
$pag_total=0;
while ($dados2=mysql_fetch_assoc($query2)) {
    $tempagamentos=1;
    $valor=$dados2["saipag_valor"];
    $pag_total+=$valor;
}


//Pega o valor total das devoluções
$sql18="
    SELECT * 
    FROM saidas_devolucoes_produtos
    JOIN saidas_devolucoes on saidevpro_numerodev=saidev_numero
    JOIN saidas on saidev_saida=sai_codigo
    JOIN produtos on saidevpro_produto=pro_codigo 
    LEFT JOIN pessoas on sai_consumidor = pes_codigo 
    WHERE saidev_saida=$saida
    ORDER BY saidevpro_itemdev DESC
    ";
if (!$query18 = mysql_query($sql18)) die("Erro CONSULTA DEVOLUCOES:" . mysql_error()."");
$linhas18=mysql_num_rows($query18);
if ($linhas18>0) $temdevolucoes=1; else $temdevolucoes=0;
$dev_total=0;
while ($dados18=mysql_fetch_assoc($query18)) {
    $val=$dados18["saidevpro_valtot"];
    $dev_total+=$val;
}



$tpl->COLUNA_TAMANHO = "";


//Campo Filtro Código da venda
$tpl->CAMPO_TITULO = "Venda";
$tpl->CAMPO_VALOR = $saida;
$tpl->CAMPO_TAMANHO = "10";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Campo Filtro Data da Venda
$tpl->CAMPO_TITULO = "Data da Venda";
$tpl->CAMPO_VALOR = converte_data($datavenda)." ".substr($horavenda,0,5);
$tpl->CAMPO_TAMANHO = "17";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Campo Filtro Consumidor Nome
$tpl->CAMPO_TITULO = "Consumidor";
if ($consumidor_nome=="") $consumidor_nome="Cliente Geral";
$tpl->CAMPO_VALOR = $consumidor_nome;
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Campo Filtro Quantidade Parcelas
$tpl->CAMPO_TITULO = "Parcelas";
$tpl->CAMPO_VALOR = $qtd_parcelas."x";
$tpl->CAMPO_TAMANHO = "10";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Campo Filtro Total Venda
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TITULO = "Total Venda";
$tpl->CAMPO_VALOR = "R$ " . number_format($venda_totalbruto, 2, ',', '.');
$tpl->CAMPO_TAMANHO = "17";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Campo Filtro Descondo da Venda
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TITULO = "Desconto";
$tpl->CAMPO_TAMANHO = "25";
$tpl->CAMPO_VALOR = "R$ " . number_format($venda_descontovalor, 2, ',', '.'). " (".str_replace(".", ",", $venda_descontopercentual)."%) ";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

$tpl->block("BLOCK_FILTRO");


//Campo Filtro Total Liquido da Venda
$tpl->CAMPO_TITULO = "Total Liq. Venda";
$totalliquido=$venda_totalbruto-$venda_descontovalor;
$tpl->CAMPO_VALOR = "R$ " . number_format($totalliquido, 2, ',', '.');
$tpl->CAMPO_TAMANHO = "17";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");



//Campo Filtro Total Devolvido
$tpl->COLUNA_TAMANHO = "";
if ($temdevolucoes==1) {
    $tpl->CAMPO_TITULO = "Total Devolvido";
    $dev_total_comdesconto=$dev_total*(100-$venda_descontopercentual)/100;
    $tpl->CAMPO_VALOR = "R$ " . number_format($dev_total_comdesconto, 2, ',', '.');
    $tpl->CAMPO_TAMANHO = "";
    $tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_FILTRO_CAMPO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
}


//Campo Filtro Total Pago
$tpl->CAMPO_TITULO = "Total Pago";
$tpl->CAMPO_VALOR = "R$ " . number_format($pag_total, 2, ',', '.');
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Campo Filtro Total a receber
$tpl->CAMPO_TITULO = "Saldo Pendente";
$saldofinal=$totalliquido-$pag_total;
$tpl->CAMPO_VALOR = "R$ " . number_format($saldofinal, 2, ',', '.');
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Botão Cadastrar novo pagamento
$usacaixa=usamodulocaixa($usuario_quiosque);
$caixaoperacao=pessoa_caixaoperacao($usuario_codigo);
if ($caixaoperacao=="") $temcaixaoperacao=0; else $temcaixaoperacao=1;
if ($saldofinal==0) {
    $tpl->LINK = "";
    $tpl->BOTAO_NOME = "NOVO PAGAMENTO";
    $tpl->BOTAO_TITULO="Não é possível realizar novos pagamentos porque a venda foi completamente paga!";
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO_DESABILITADO");  
} else if (($usacaixa==1)&&($temcaixaoperacao==0)) {
    $tpl->LINK = "";
    $tpl->BOTAO_NOME = "NOVO PAGAMENTO";
    $tpl->BOTAO_TITULO="Não é possível realizar novos pagamentos porque você precisa primeiro informar qual é o caixa que está atuando!";
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO_DESABILITADO");  
} else {
    $tpl->BOTAO_TITULO="";
    $tpl->LINK = "saidas_pagamentos_cadastrar.php?saida=$saida";
    $tpl->BOTAO_NOME = "NOVO PAGAMENTO";
}
$tpl->block("BLOCK_RODAPE_BOTAO_MODELO");
$tpl->block("BLOCK_FILTRO_COLUNA");
$tpl->block("BLOCK_FILTRO");


//INICIO DA LISTAGEM 

//Numero
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="Nº";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Data do pagamento
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="DATA";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Valor
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="VALOR";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Método de Pagamento
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="MET. PAGAMENTO";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Observação
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="OBSSERVAÇÃO";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Caixa Operação
$usacaixa=usamodulocaixa($usuario_quiosque);
if ($usacaixa==1) {
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="";
    $tpl->CABECALHO_COLUNA_NOME="CAIXA OPERAÇÃO";
    $tpl->block("BLOCK_LISTA_CABECALHO");
}

//Remover
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="OPERAÇÕES";
$tpl->block("BLOCK_LISTA_CABECALHO");

//SQL Principal
$sql="
    SELECT * 
    FROM saidas_pagamentos
    JOIN metodos_pagamento on (saipag_metpagamento=metpag_codigo)
    LEFT JOIN caixas_entradassaidas on (caientsai_saidapagamento=saipag_codigo)
    WHERE saipag_saida=$saida 
    ORDER BY saipag_data DESC
";


//PAGINAÇÃO
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

$cont=0;
while ($dados=  mysql_fetch_assoc($query)) {
    $numero= $dados["saipag_codigo"];
    $data= $dados["saipag_data"];
    $valor= $dados["saipag_valor"];
    $metpag= $dados["saipag_metpagamento"];
    $metpag_nome= $dados["metpag_nome"];
    $obs= $dados["saipag_obs"];
    $caixa_operacao= $dados["caientsai_saidapagamento"];


    //Nº
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$numero";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Data
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  converte_datahora($data);
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Valor
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=   "R$ " . number_format($valor, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");    
    
    //Met. Pagamento
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  "$metpag_nome";
    $tpl->block("BLOCK_LISTA_COLUNA");    
    
    //Observação
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  "$obs";
    $tpl->block("BLOCK_LISTA_COLUNA");    
    
    //Caixa Operação
    if ($usacaixa==1) {
        $tpl->LISTA_COLUNA_ALINHAMENTO="";
        $tpl->LISTA_COLUNA_CLASSE="";
        $tpl->LISTA_COLUNA_TAMANHO="";
        $tpl->LISTA_COLUNA_VALOR=  "$caixa_operacao";
        $tpl->block("BLOCK_LISTA_COLUNA");
    }

    //Remover 
    //Se este pagamento foi gerado automaticamente abatimento de alguma devolução então não pode excluir.
    $sql2="SELECT * FROM saidas_devolucoes WHERE saidev_pagamento=$numero";
    if (!$query2= mysql_query($sql2)) die("Erro SQL 2:" . mysql_error());
    $linhas2=mysql_num_rows($query2);
    if ($linhas2>0) { //nao pode excluir
        $tpl->IMAGEM_ALINHAMENTO="center";
        $tpl->IMAGEM_TAMANHO="12px";
        $tpl->IMAGEM_PASTA="$icones";
        $tpl->IMAGEM_TITULO="Você não pode excluir este pagamento porque há uma devolução vinculada!";
        $tpl->IMAGEM_NOMEARQUIVO="remover_desabilitado.png";
        $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM_SEMLINK");
        $tpl->block("BLOCK_LISTA_COLUNA_ICONES"); 


    } else { //permite excluir
        $tpl->IMAGEM_ALINHAMENTO="center";
        $tpl->LINK="saidas_pagamentos_deletar.php?numero=$numero&saida=$saida";
        $tpl->IMAGEM_TAMANHO="12px";
        $tpl->IMAGEM_PASTA="$icones";
        $tpl->IMAGEM_TITULO="Remover";
        $tpl->IMAGEM_NOMEARQUIVO="remover.png";
        $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
        $tpl->block("BLOCK_LISTA_COLUNA_ICONES"); 
    }

    $tpl->block("BLOCK_LISTA"); 
    $cont++;

}

if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}







//Botão Voltar
$tpl->LINK_VOLTAR="saidas_ver.php?codigo=$saida&ope=3&tiposaida=1&passo=1";
$tpl->block("BLOCK_RODAPE_BOTAO_VOLTAR");
$tpl->block("BLOCK_RODAPE_BOTAO");
$tpl->block("BLOCK_RODAPE_BOTOES");


$tpl->show();

include "rodape.php";

?>