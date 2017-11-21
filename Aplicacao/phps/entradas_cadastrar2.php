<script language="JavaScript" src="entradas_cadastrar2.js"></script>
<?php
$tipopagina = "entradas";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_entradas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$cancelar = $_GET["cancelar"];
$salvar = $_GET["salvar"];
$entrada = $_GET["entrada"];
if ($entrada=="") { echo "Não foi recebido parametro de entrada!"; exit;  }
$operacao = $_GET["operacao"];
$passo = $_REQUEST["passo"];

//Verifica se há algum produto composto dentro da entrada. 
//se houver deve-se realizar a retirada dos subprodutos do estoque, mostrar tela para escolher os lotes e quantidade
//se não tem então pode ir direto para o php que salva a entrada.
$sql="
    SELECT entpro_produto
    FROM entradas_produtos
    JOIN produtos_subproduto on entpro_produto=prosub_produto 
    WHERE entpro_entrada=$entrada and entpro_retiradodoestoquesubprodutos=0
";
if (!$query=mysql_query($sql)) die("Erro SQL 11: " . mysql_error());
$linhas=  mysql_num_rows($query);
if ($linhas==0) {
    echo "Os produtos da entrada não possuem sub-produtos a serem processados";
    header("Location: entradas_cadastrar3.php?salvar=1&entrada=$entrada&operacao=$operacao");
    exit;
}

include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ENTRADAS";
$tpl_titulo->SUBTITULO = "RETIRAR DO ESTOQUE";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "entradas.png";
$tpl_titulo->show();

//Veriifca se o usuário quer realizar retirar de estoque dos subprodutos

if (($passo == 1)&&($linhas!=0)) {
    echo "<br><br>";
    $tpl = new Template("templates/notificacao.html");
    $tpl->ICONES = $icones;
    //$tpl->MOTIVO_COMPLEMENTO = "";
    $tpl->block("BLOCK_ATENCAO");
    //$tpl->block("BLOCK_BOTAO");
    
    
    $tpl->LINK = "entradas_cadastrar2.php?salvar=$salvar&entrada=$entrada&operacao=$operacao&passo=2";
    $tpl->MOTIVO = "Nesta entrada há produtos compostos inseridos cujo seus sub-produtos ainda não foram retirados do estoque!";
    $tpl->block("BLOCK_MOTIVO");
    $tpl->PERGUNTA = "Deseja retirar os sub-produtos do estoque?";
    $tpl->block("BLOCK_PERGUNTA");
    $tpl->NAO_LINK = "entradas_cadastrar3.php?salvar=$salvar&entrada=$entrada&operacao=$operacao&retirar_subprodutos=2";
    $tpl->block("BLOCK_BOTAO_SIM_AUTOFOCO");
    $tpl->block("BLOCK_BOTAO_NAO_LINK");
    $tpl->block("BLOCK_BOTAO_SIMNAO");
    $tpl->show();
    exit;
}




//Mostra todos os produtos compostos que possuem sub-produtos a ser retirado do estoque
$sql="
    SELECT DISTINCT entpro_produto,entpro_quantidade,entpro_numero
    FROM entradas_produtos
    join produtos_subproduto on (entpro_produto=prosub_produto)
    WHERE entpro_entrada=$entrada and entpro_retiradodoestoquesubprodutos=0
";
if (!$query=mysql_query($sql)) die("Erro SQL 12: " . mysql_error());
while($dados=  mysql_fetch_assoc($query)) {
    $produto=$dados["entpro_produto"];
    $quantidade=$dados["entpro_quantidade"];
    $numero=$dados["entpro_numero"];
    
    //Verifica os tipos de contagem do produto
    $sql2 = "SELECT * FROM produtos JOIN produtos_tipo on (pro_tipocontagem=protip_codigo) WHERE pro_codigo=$produto";
    $query2 = mysql_query($sql2);
    if (!$query2)  die("Erro SQL 11: " . mysql_error());
    $dados2 = mysql_fetch_assoc($query2);
    $produto_tipocontagem=$dados2["protip_codigo"];
    $produto_tipocontagem=$dados2["protip_codigo"];
    $produto_tipocontagem_sigla=$dados2["protip_sigla"];
    
    
    $tpl = new Template("templates/listagem_2.html");
    $tpl->LINK_FILTRO = "entradas_cadastrar3.php?salvar=1&entrada=$entrada&operacao=$operacao&retirar_subprodutos=1";

    //OCULTO Entrada
    $tpl->CAMPOOCULTO_VALOR="$entrada";
    $tpl->CAMPOOCULTO_NOME="entrada2";
    $tpl->block("BLOCK_CAMPOSOCULTOS"); 
    
    
    //Item (cabeçalho)
    $tpl->CAMPO_TITULO = "Item";
    $tpl->CAMPO_NOME = "cabecalho_item";
    $tpl->CAMPO_VALOR = $numero;
    $tpl->CAMPO_TAMANHO = "9";
    $tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_FILTRO_CAMPO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
    
    //Produto (cabeçalho)
    $tpl->CAMPO_TITULO = "Produto";
    $produto_nome= $dados2['pro_nome'];
    $tpl->CAMPO_NOME = "cabecalho_produto";
    $tpl->CAMPO_VALOR = $produto_nome;
    $tpl->CAMPO_TAMANHO = "35";
    $tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_FILTRO_CAMPO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
    
    
    //Quantidade (cabeçalho)
    $tpl->CAMPO_TITULO = "Quantidade";
    if (($produto_tipocontagem==2)||($produto_tipocontagem==3)) {
        $tpl->CAMPO_VALOR=  number_format($quantidade,3,',','.');
    } else {
        $tpl->CAMPO_VALOR=  number_format($quantidade,0,'','.');
    }
    $tpl->CAMPO_NOME = "cabecalho_qtd";
    $tpl->CAMPO_TAMANHO = "10";
    $tpl->CAMPO_ESTILO = "text-align: right;";
    $tpl->block("BLOCK_FILTRO_CAMPO_ESTILO");
    $tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
    $tpl->CAMPO_TEXTO_TAMANHO = "";
    $tpl->CAMPO_TEXTO_TEXTO = "<br>$produto_tipocontagem_sigla";
    $tpl->block("BLOCK_FILTRO_CAMPO_TEXTO");
    
    $tpl->block("BLOCK_FILTRO_CAMPO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
    
    $tpl->block("BLOCK_FILTRO");

    //Numero
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="";
    $tpl->CABECALHO_COLUNA_NOME="Nº";
    $tpl->block("BLOCK_LISTA_CABECALHO");

    //Produto
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="";
    $tpl->CABECALHO_COLUNA_NOME="PRODUTO";
    $tpl->block("BLOCK_LISTA_CABECALHO");

    
    //Quantidade unitária do subproduto
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="2";
    $tpl->CABECALHO_COLUNA_NOME="QTD. SUB-PROD.";
    $tpl->block("BLOCK_LISTA_CABECALHO");
    

    //Quantidade utilizada
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="2";
    $tpl->CABECALHO_COLUNA_NOME="QTD. RETIRAR";
    $tpl->block("BLOCK_LISTA_CABECALHO");

    

    //Quantidade selecionada
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="2";
    $tpl->CABECALHO_COLUNA_NOME="QTD. SEL.";
    $tpl->block("BLOCK_LISTA_CABECALHO");

    //Situação
    $tpl->CABECALHO_COLUNA_TAMANHO="30px";
    $tpl->CABECALHO_COLUNA_COLSPAN="";
    $tpl->CABECALHO_COLUNA_NOME="SIT.";
    $tpl->block("BLOCK_LISTA_CABECALHO");    
    
    //Lotes
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="3";
    $tpl->CABECALHO_COLUNA_NOME="LOTES";
    $tpl->block("BLOCK_LISTA_CABECALHO");

    //Quantidade em Estoque
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="2";
    $tpl->CABECALHO_COLUNA_NOME="QTD. ESTOQUE";
    $tpl->block("BLOCK_LISTA_CABECALHO");
        
    
    //Verifica quais são os subprodutos do produto composto do item de entrada
    $sql3="
        SELECT 
                (SELECT pro_nome from produtos WHERE pro_codigo=ps.prosub_subproduto) as subproduto_nome,
                (SELECT protip_codigo from produtos_tipo JOIN produtos on pro_tipocontagem=protip_codigo WHERE pro_codigo=ps.prosub_subproduto) as subproduto_tipocontagem,
                (SELECT protip_sigla from produtos_tipo JOIN produtos on pro_tipocontagem=protip_codigo WHERE pro_codigo=ps.prosub_subproduto) as subproduto_tipocontagem_sigla,
                ps.prosub_quantidade as qtd,
                ps.prosub_numero as numero,
                ps.prosub_subproduto,
                e.etq_lote, 
                e.etq_validade, 
                e.etq_quantidade,
                (SELECT pes_nome FROM pessoas WHERE e.etq_fornecedor=pes_codigo) as fornecedor,
                (SELECT count(etq_lote) FROM estoque WHERE etq_produto=ps.prosub_subproduto) as lotes
                
        FROM produtos_subproduto ps
        LEFt JOIN estoque e on (ps.prosub_subproduto=e.etq_produto)
        WHERE ps.prosub_produto=$produto
    ";
    
    $query3 = mysql_query($sql3);
    if (!$query3) die("Erro3:" . mysql_error());
    $cont=0;
    while ($dados3=  mysql_fetch_assoc($query3)) {
        $cont++;
        $subproduto_nome=$dados3["subproduto_nome"];
        $subproduto_tipocontagem=$dados3["subproduto_tipocontagem"];
        $subproduto_tipocontagem_sigla=$dados3["subproduto_tipocontagem_sigla"];
        $subproduto_qtd=$dados3["qtd"];
        $subproduto_numero=$dados3["numero"];
        $subproduto_codigo=$dados3["prosub_subproduto"];
        $lote=$dados3["etq_lote"];
        $validade=$dados3["etq_validade"];
        $qtd_emestoque=$dados3["etq_quantidade"];
        $fornecedor=$dados3["fornecedor"];
        $lotes_qtd=$dados3["lotes"];
        if ($qtd_emestoque==0) {
            $lotes_qtd=1;
            $naotemestoque=1;
        } else {
            $naotemestoque=0;
        }
        
        if ($qtd_emestoque==0) {
            $tpl->TR_CLASSE="lin tabelalinhafundovermelho negrito";
        } else {
            $tpl->TR_CLASSE="";
        }
        
        
        //Numero
        if ($cont==1) {
            $tpl->LISTA_COLUNA_ALINHAMENTO="right";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="$lotes_qtd";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="20px";
            $tpl->LISTA_COLUNA_VALOR= "$subproduto_numero ";
            $tpl->block("BLOCK_LISTA_COLUNA");

            //Sub-produto Nome
            $tpl->LISTA_COLUNA_ALINHAMENTO="right";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="$lotes_qtd";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="";
            $tpl->LISTA_COLUNA_VALOR= "$subproduto_nome";
            $tpl->block("BLOCK_LISTA_COLUNA");

            
            //Sub-produto Quantidade (para fazer kg do produto composto)
            $tpl->LISTA_COLUNA_ALINHAMENTO="right";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="$lotes_qtd";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="70px";
            if (($subproduto_tipocontagem==2)||($subproduto_tipocontagem==3))
                $tpl->LISTA_COLUNA_VALOR= number_format($subproduto_qtd,3,",",".");
            else 
                $tpl->LISTA_COLUNA_VALOR= number_format($subproduto_qtd,0,"",".");
            $tpl->block("BLOCK_LISTA_COLUNA");
            $tpl->LISTA_COLUNA_ALINHAMENTO="left";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="$lotes_qtd";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="20px";
            $tpl->LISTA_COLUNA_VALOR= "$subproduto_tipocontagem_sigla";
            $tpl->block("BLOCK_LISTA_COLUNA");
            

            //Quantidade do subproduto a retirar do estoque
            $tpl->LISTA_COLUNA_ALINHAMENTO="right";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="$lotes_qtd";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="70px";
            $qtd_aretirar=$subproduto_qtd*$quantidade;
            if (($subproduto_tipocontagem==2)||($subproduto_tipocontagem==3))
                $tpl->LISTA_COLUNA_VALOR= number_format($qtd_aretirar,3,",",".");
            else 
                $tpl->LISTA_COLUNA_VALOR= number_format($qtd_aretirar,0,"",".");
            $tpl->block("BLOCK_LISTA_COLUNA");
            $tpl->LISTA_COLUNA_ALINHAMENTO="left";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="$lotes_qtd";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="20px";
            $tpl->LISTA_COLUNA_VALOR= "$subproduto_tipocontagem_sigla";
            $tpl->block("BLOCK_LISTA_COLUNA");
            
            //Quantidade Selecionada
            $tpl->LISTA_COLUNA_ALINHAMENTO="right";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="$lotes_qtd";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="70px";
            if (($subproduto_tipocontagem==2)||($subproduto_tipocontagem==3))
                $qtdselecionada=number_format(0,3,",",".");
            else 
                $qtdselecionada= number_format(0,0,"",".");
            $nome="span_qtdselecionada_"."$numero"."_"."$produto"."_"."$subproduto_codigo";
            $tpl->LISTA_COLUNA_VALOR="<span id='$nome'>$qtdselecionada</span>";
            $tpl->block("BLOCK_LISTA_COLUNA");
            $tpl->LISTA_COLUNA_ALINHAMENTO="left";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="$lotes_qtd";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="20px";
            $tpl->LISTA_COLUNA_VALOR= "$subproduto_tipocontagem_sigla";
            $tpl->block("BLOCK_LISTA_COLUNA");
            
            //Situação
            $tpl->LISTA_COLUNA_ALINHAMENTO="center";
            $tpl->LISTA_COLUNA_ROWSPAN="$lotes_qtd";
            $tpl->LISTA_COLUNA_VALOR="";
            $tpl->IMAGEM_TAMANHO="15px";
            $tpl->IMAGEM_PASTA="$icones";
            if ($naotemestoque==1)
                $tpl->IMAGEM_NOMEARQUIVO="atencao.png";
            else 
                $tpl->IMAGEM_NOMEARQUIVO="confirmar2.png";
            $tpl->IMAGEM_TITULO="";
            $nome="situacao_"."$numero"."_"."$produto"."_"."$subproduto_codigo";
            $tpl->IMAGEM_NOME="$nome";
            $tpl->block("BLOCK_LISTA_COLUNA_ICONE");
            $tpl->block("BLOCK_LISTA_COLUNA"); 

            //OCULTO Quantidade Selecionada
            $tpl->CAMPOOCULTO_VALOR="0";
            $nome="qtdselecionada_"."$numero"."_"."$produto"."_"."$subproduto_codigo";
            $tpl->CAMPOOCULTO_NOME="$nome";
            $tpl->block("BLOCK_CAMPOSOCULTOS"); 

            //OCULTO Quantidade Retirar
            $tpl->CAMPOOCULTO_VALOR="$qtd_aretirar";
            $nome="qtdaretirar_"."$numero"."_"."$produto"."_"."$subproduto_codigo";
            $tpl->CAMPOOCULTO_NOME="$nome";
            $tpl->block("BLOCK_CAMPOSOCULTOS"); 
        }
        
        
        //Lote
        if ($naotemestoque=="1") {
            $tpl->LISTA_COLUNA_ALINHAMENTO="center";
            $tpl->LISTA_COLUNA_ROWSPAN="";
            $tpl->LISTA_COLUNA_COLSPAN="5";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="";
            $tpl->LISTA_COLUNA_VALOR= "não tem estoque";
            $tpl->block("BLOCK_LISTA_COLUNA");           
        } else {
            //Checkbox
            $tpl->LISTA_COLUNA_ALINHAMENTO="center";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="20px";
            $nome_limpo_semlote="$numero"."_"."$produto"."_"."$subproduto_codigo ";
            $nome_limpo="$numero"."_"."$produto"."_"."$subproduto_codigo"."_"."$lote";
            $nome="box_"."$numero"."_"."$produto"."_"."$subproduto_codigo"."_"."$lote";
            $id=$nome;
            $tpl->LISTA_COLUNA_VALOR= "<input type='checkbox' onclick='habilitar_quantidade(this.checked,`$nome_limpo`,$subproduto_tipocontagem,`$nome_limpo_semlote`,$produto,$subproduto_codigo,$numero)' name='$nome' id='$id'>";
            $tpl->block("BLOCK_LISTA_COLUNA");
            //Lote Validade Lote
            $tpl->LISTA_COLUNA_ALINHAMENTO="left";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="120px";
            $validade2=  converte_data($validade);
            if ($validade2=="00/00/0000") $validade2=" sem validade";
            $tpl->LISTA_COLUNA_VALOR= "$lote - $validade2";
            $tpl->block("BLOCK_LISTA_COLUNA");

            //Quantidade Digitada
            $tpl->LISTA_COLUNA_ALINHAMENTO="left";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="95px";
            $nome="qtddigitada_"."$numero"."_"."$produto"."_"."$subproduto_codigo"."_"."$lote";
            $id=$nome;
            $tpl->LISTA_COLUNA_VALOR= "<input type='text' name='$nome'id='$id' class='campopadrao' style='width:70px' onblur='calcula_qtd_selecionada(this.value,`$nome_limpo`,$subproduto_codigo,$subproduto_tipocontagem,$produto,$numero)' disabled> $subproduto_tipocontagem_sigla";
            $tpl->block("BLOCK_LISTA_COLUNA");

            //Quantidade em Estoque
            $tpl->LISTA_COLUNA_ALINHAMENTO="right";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="70px";
            if (($subproduto_tipocontagem==2)||($subproduto_tipocontagem==3))
                $tpl->LISTA_COLUNA_VALOR= number_format($qtd_emestoque,3,",",".");
            else 
                $tpl->LISTA_COLUNA_VALOR= number_format($qtd_emestoque,0,"",".");
            $tpl->block("BLOCK_LISTA_COLUNA");
            $tpl->LISTA_COLUNA_ALINHAMENTO="left";
            $tpl->LISTA_COLUNA_COLSPAN="";
            $tpl->LISTA_COLUNA_ROWSPAN="";
            $tpl->LISTA_COLUNA_CLASSE="";
            $tpl->LISTA_COLUNA_TAMANHO="20px";
            $tpl->LISTA_COLUNA_VALOR= "$subproduto_tipocontagem_sigla";
            $tpl->block("BLOCK_LISTA_COLUNA");

            //OCULTO Quantidade Estoque
            $tpl->CAMPOOCULTO_VALOR="$qtd_emestoque";
            $nome="qtdemestoque_"."$numero"."_"."$produto"."_"."$subproduto_codigo"."_"."$lote";
            $tpl->CAMPOOCULTO_NOME="$nome";
            $tpl->block("BLOCK_CAMPOSOCULTOS"); 
            
            
        }

        if ($cont==$lotes_qtd) { $cont=0;  }
        
        $tpl->block("BLOCK_LISTA"); 
        
    }
    if (mysql_num_rows($query) == 0) $tpl->block("BLOCK_LISTA_NADA");
    
        $tpl->show();

    echo "<hr class='linha'>";

}




$tpl4 = new Template("templates/botoes1.html");
$tpl4->COLUNA_TAMANHO = "824px";
$tpl4->block("BLOCK_COLUNA");
$tpl4->block("BLOCK_BOTOES");


//Botão Cancelar
$tpl4->COLUNA_LINK_ARQUIVO = "entradas.php";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "100px";
$tpl4->COLUNA_ALINHAMENTO = "right";
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl4->block("BLOCK_BOTAOPADRAO_CANCELAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");
$tpl4->block("BLOCK_BOTOES");


//Botão Continuar
$tpl4->COLUNA_LINK_ARQUIVO = "";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "100px";
$tpl4->COLUNA_ALINHAMENTO = "right";
$tpl4->block("BLOCK_BOTAOPADRAO_DESABILITADO");
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SUBMIT");
$tpl4->block("BLOCK_BOTAOPADRAO_CONTINUAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");
$tpl4->block("BLOCK_LINHA");
$tpl4->block("BLOCK_BOTOES");


$tpl4->show();





include "rodape.php";

?>