<script type="text/javascript">
    $(document).ready(function() {
        //Ao entrar pela primeira vez na pagina j� verificar se o usu�rio tem acesso ao sistema ou n�o
        verifica_usuario ();      
    });
</script>

<?php
//Verifica se o usuário tem permissão para acessar este conte�do
$tipopagina="quiosque_configuracao";
require "login_verifica.php";
include "includes.php";

//Futuramente pegar por POST quando for criado a coluna na listagem de quiosques permitindo um adminsitrador alterar estas configuracoes no quiosque desejado (sem a necessidade de estar logado no quiosque)
$quiosque=$usuario_quiosque;


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "QUIOSQUE";
$tpl_titulo->SUBTITULO = "CONFIGURACOES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques_configuracoes.png";
$tpl_titulo->show();


//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "quiosques_configuracoes2.php";

$tpl1->JS_CAMINHO = "quiosques_configuracoes.js";
$tpl1->block("BLOCK_JS");


//Módulo Fiscal
$tpl1->TITULO = "Modulo Fiscal";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "usamodulofiscal";
$tpl1->SELECT_ID = "usamodulofiscal";
$tpl1->SELECT_TAMANHO = "";
$tpl1->SELECT_ONCHANGE = "usa_modulo_fiscal(this.value);";
$tpl1->block("BLOCK_SELECT_ONCHANGE");
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");

$sql="SELECT quicnf_usamodulofiscal FROM quiosques_configuracoes WHERE quicnf_quiosque=$quiosque";
 if (!$query= mysql_query($sql)) die("Erro: " . mysql_error());
 $dados=  mysql_fetch_assoc($query);
 $usamodulofiscal= $dados["quicnf_usamodulofiscal"];

if ($usamodulofiscal=="") {
    $usamodulofiscal= 3; 
    echo "<br>Primeira configuração do quiosque!<br>";
}
 
$tpl1->OPTION_VALOR = 1;
$tpl1->OPTION_NOME = "Sim";
if (($usamodulofiscal==1)||($usamodulofiscal==3)) $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->OPTION_VALOR = 0;
$tpl1->OPTION_NOME = "Não";
if ($usamodulofiscal==0) $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");


//Quiosque campo oculto
$tpl1->CAMPO_TIPO="hidden";
$tpl1->CAMPO_NOME="quiosque";
$tpl1->CAMPO_ID="quiosque";
$tpl1->CAMPO_VALOR="$quiosque";
$tpl1->block("BLOCK_CAMPO_NORMAL_OCULTO"); //Campo text que não aparece na tela
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");



//BOTOES
//Botão Salvar
$tpl1->block("BLOCK_BOTAO_SALVAR");

//Botão Voltar
$tpl1->block("BLOCK_BOTAO_VOLTAR");
$tpl1->block("BLOCK_BOTOES");
$tpl1->show();

include "rodape.php";
?>
