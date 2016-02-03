<script type="text/javascript">
    $(document).ready(function() {
        //Ao entrar pela primeira vez na pagina j� verificar se o usu�rio tem acesso ao sistema ou n�o
        verifica_usuario ();      

    });
    
</script>

<?php
//Verifica se o usuário tem permissão para acessar este conte�do
$tipopagina="trocaunidade";
require "login_verifica.php";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TROCA QUIOSQUE";
$tpl_titulo->SUBTITULO = "ALTERAÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques_trocar.png";
$tpl_titulo->show();


//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "troca_unidade2.php";


$tpl1->JS_CAMINHO = "troca_unidade.js";
$tpl1->block("BLOCK_JS");


//Cooperativa
$tpl1->TITULO = "Cooperativa";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "cooperativa";
$tpl1->SELECT_ID = "cooperativa";
$tpl1->SELECT_TAMANHO = "";
$tpl1->SELECT_ONCHANGE = "cooperativa_popula_quiosques(this.value,$usuario_codigo);";
$tpl1->block("BLOCK_SELECT_ONCHANGE");
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$sql8 = "SELECT * FROM mestre_pessoas_tipo WHERE mespestip_tipo=1 and mespestip_pessoa=$usuario_codigo";
$query8 = mysql_query($sql8);
if (!$query8)
    die("Erro 40:" . mysql_error());
$linhas8 = mysql_num_rows($query8);
if (($linhas8 > 0) || ($usuario_grupo == 7)||($usuario_grupo==1))
    $sql = "SELECT * FROM cooperativas ORDER BY coo_abreviacao";
else
    $sql = "SELECT * FROM cooperativas WHERE coo_codigo=$usuario_cooperativa";
$query = mysql_query($sql);
if (!$query)
    die("Erro Select Cooperativa: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl1->OPTION_VALOR = $dados["coo_codigo"];
    $tpl1->OPTION_NOME = $dados["coo_abreviacao"];
    $coo = $dados["coo_codigo"];
    if ($coo == $usuario_cooperativa)
        $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
    $tpl1->block("BLOCK_SELECT_OPTION");
}
//if ($usuario_grupo<>1)
    //$tpl1->block("BLOCK_SELECT_DESABILITADO");
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");



//Grupo de Permissões
$tpl1->TITULO = "Grupo de Permissões";
$tpl1->TITULO_ID = "span_grupopermissoes";
$tpl1->block("BLOCK_TITULO_ID");
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "grupopermissoes";
$tpl1->SELECT_ID = "grupopermissoes";
$tpl1->SELECT_ONCHANGE = "grupopermissoes_popula_quiosques(this.value,$usuario_codigo)";
$tpl1->block("BLOCK_SELECT_ONCHANGE");
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");

$sql = "SELECT gruper_codigo,gruper_nome FROM grupo_permissoes ORDER BY gruper_codigo";
$query = mysql_query($sql);
if (!$query)
    die("Erro Select Grupo Permissões: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl1->OPTION_VALOR = $dados["gruper_codigo"];
    $tpl1->OPTION_NOME = $dados["gruper_nome"];
    $grupo_codigo = $dados["gruper_codigo"];

    //Verifica se o esta pessoa é administrador
    if ($grupo_codigo == 1) {
        $sql9 = "SELECT * FROM mestre_pessoas_tipo WHERE mespestip_pessoa=$usuario_codigo and mespestip_tipo=1";
        $query9 = mysql_query($sql9);
        if (!$query9)
            die("Erro: 0" . mysql_error());
        $linhas9 = mysql_num_rows($query9);
        if ($linhas9 > 0) {
            if ($usuario_grupo == $grupo_codigo)
                $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
            $tpl1->block("BLOCK_SELECT_OPTION");
        }
    }

    //Verifica se o esta pessoa é gestor
    if ($grupo_codigo == 2) {
        $sql6 = "SELECT * FROM cooperativa_gestores WHERE cooges_gestor=$usuario_codigo";
        $query6 = mysql_query($sql6);
        if (!$query6)
            die("Erro: 0" . mysql_error());
        $linhas6 = mysql_num_rows($query6);
        if ($linhas6 > 0) {
            if ($usuario_grupo == $grupo_codigo)
                $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
            $tpl1->block("BLOCK_SELECT_OPTION");
        }
    }

    //Verifica se o esta pessoa é supervisor de algum quiosque
    if ($grupo_codigo == 3) {
        $sql3 = "SELECT DISTINCT qui_nome FROM quiosques join quiosques_supervisores on (qui_codigo=quisup_quiosque) WHERE quisup_supervisor=$usuario_codigo";
        $query3 = mysql_query($sql3);
        if (!$query3)
            die("Erro: 2" . mysql_error());
        $linhas3 = mysql_num_rows($query3);
        if ($linhas3 > 0) {
            if ($usuario_grupo == $grupo_codigo)
                $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
            $tpl1->block("BLOCK_SELECT_OPTION");
        }
    }

    //Verifica se o esta pessoa é caixa de algum quiosque
    if ($grupo_codigo == 4) {
        $sql2 = "SELECT * FROM caixas_operadores JOIN caixas on (caiope_caixa=cai_codigo) WHERE caiope_operador=$usuario_codigo";
        $query2 = mysql_query($sql2);
        if (!$query2)
            die("Erro: 1" . mysql_error());
        $linhas2 = mysql_num_rows($query2);
        if ($linhas2 > 0) {
            if ($usuario_grupo == $grupo_codigo)
                $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
            $tpl1->block("BLOCK_SELECT_OPTION");
        }
    }

    //Verifica se o esta pessoa é fornecedor de algum quiosque
    if ($grupo_codigo == 5) {
        $sql4 = "SELECT DISTINCT qui_nome FROM quiosques join entradas on (ent_quiosque=qui_codigo) WHERE ent_fornecedor=$usuario_codigo";
        $query4 = mysql_query($sql4);
        if (!$query4)
            die("Erro: 3" . mysql_error());
        $linhas4 = mysql_num_rows($query4);
        if ($linhas4 > 0) {
            if ($usuario_grupo == $grupo_codigo)
                $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
            $tpl1->block("BLOCK_SELECT_OPTION");
        }
    }
}
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");



//Quiosque do Usuário
$tpl1->TITULO = "Quiosque do Usuário";
$tpl1->TITULO_ID = "span_quiosqueusuario";
$tpl1->block("BLOCK_TITULO_ID");
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "quiosqueusuario";
$tpl1->SELECT_ID = "quiosqueusuario";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_NORMAL");


if (($usuario_grupo == 1) || ($usuario_grupo == 2)) {
    $tpl1->OPTION_VALOR = "";
    $tpl1->OPTION_NOME = "Todos";
    $tpl1->block("BLOCK_SELECT_OPTION");
    $sql = "SELECT qui_codigo,qui_nome FROM quiosques WHERE qui_cooperativa=$usuario_cooperativa";
    $query = mysql_query($sql); if (!$query) die("Erro: 8" . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        if ($quiosqueusuario == $dados['qui_codigo'])
            $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
        $tpl1->OPTION_VALOR = $dados["qui_codigo"];
        $tpl1->OPTION_NOME = $dados["qui_nome"];
        $tpl1->block("BLOCK_SELECT_OPTION");
    }
} else if ($usuario_grupo == 3) {
    $sql = "
    SELECT qui_codigo,qui_nome 
    FROM quiosques 
    join quiosques_supervisores on (quisup_quiosque=qui_codigo)
    WHERE qui_cooperativa=$usuario_cooperativa
    AND quisup_supervisor=$usuario_codigo
    ";
    $query = mysql_query($sql); if (!$query) die("Erro: 8" . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        if ($quiosqueusuario == $dados['qui_codigo'])
            $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
        $tpl1->OPTION_VALOR = $dados["qui_codigo"];
        $tpl1->OPTION_NOME = $dados["qui_nome"];
        $tpl1->block("BLOCK_SELECT_OPTION");
    }
} else IF ($usuario_grupo == 4) {
    $sql = "
    SELECT DISTINCT qui_codigo,qui_nome 
    FROM quiosques 
    join caixas on (cai_quiosque=qui_codigo)
    join caixas_operadores on (caiope_caixa=cai_codigo)
    WHERE qui_cooperativa=$usuario_cooperativa
    AND caiope_operador=$usuario_codigo
    ";
    $query = mysql_query($sql); if (!$query) die("Erro: 8" . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        if ($quiosqueusuario == $dados['qui_codigo'])
            $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
        $tpl1->OPTION_VALOR = $dados["qui_codigo"];
        $tpl1->OPTION_NOME = $dados["qui_nome"];
        $tpl1->block("BLOCK_SELECT_OPTION");
    }
} else IF ($usuario_grupo == 5) {
    $sql = "
    SELECT qui_codigo,qui_nome 
    FROM entradas 
    join quiosques on (ent_quiosque=qui_codigo)
    WHERE qui_cooperativa=$usuario_cooperativa
    AND ent_fornecedor=$usuario_codigo
    ";
    $query = mysql_query($sql); if (!$query) die("Erro: 8" . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        if ($quiosqueusuario == $dados['qui_codigo'])
            $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
        $tpl1->OPTION_VALOR = $dados["qui_codigo"];
        $tpl1->OPTION_NOME = $dados["qui_nome"];
        $tpl1->block("BLOCK_SELECT_OPTION");
    }
} else {
    $tpl1->OPTION_VALOR = $dados["qui_codigo"];
    $tpl1->OPTION_NOME = $dados["qui_nome"];
    $tpl1->block("BLOCK_SELECT_OPTION_PADRAO");    
}    
  


$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");
    

//Campos ocultos do formulario caso seja uma edi��o

//BOTOES
//Botão Salvar
$tpl1->block("BLOCK_BOTAO_SALVAR");

//Botão Voltar
$tpl1->block("BLOCK_BOTAO_VOLTAR");
$tpl1->block("BLOCK_BOTOES");
$tpl1->show();

include "rodape.php";
?>
