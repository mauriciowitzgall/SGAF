<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_cooperativa_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativa";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "COOPERATIVAS";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "cooperativas.png";
$tpl_titulo->show();


$codigo = $_GET["codigo"];
$operacao = $_GET["operacao"];
$passo = $_GET["passo"];


if ($usuario_grupo==1) {
    if ($passo==1) { //Pergunta se quer deletar mesmo
  
        $tpl6 = new Template("templates/notificacao.html");
        $tpl6->ICONES = $icones;
        $tpl6->block("BLOCK_ATENCAO");
        //$tpl6->block("BLOCK_CADASTRADO");    
        $tpl6->MOTIVO = "Ao apagar a cooperativa você <b>apará</b> também todos os registros efetuados por ele como entradas, saidas, fechamentos, acerto etc... de <b>TODOS QUIOSQUES</b>";
        $tpl6->LINK = "cooperativas_deletar.php?codigo=$codigo&passo=2";
        $tpl6->block("BLOCK_MOTIVO");
        $tpl6->PERGUNTA = "Tem certeza que deseja excluir a cooperativa e todos os quiosques relacionados?";
        $tpl6->block("BLOCK_PERGUNTA");
        $tpl6->NAO_LINK = "cooperativas.php";
        $tpl6->LINK_TARGET = "";
        $tpl6->block("BLOCK_BOTAO_NAO_LINK");
        $tpl6->block("BLOCK_BOTAO_SIMNAO");
        $tpl6->show();
    } else if ($passo==2) { //Pode deletar o quiosque e suas referencias
        
        // Pessoas
        echo $sql3 = "DELETE FROM fornecedores_tiponegociacao WHERE fortipneg_pessoa in (SELECT pes_codigo FROM pessoas WHERE pes_cooperativa=$codigo and (pes_grupopermissoes != 1 OR pes_grupopermissoes is null))";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL1: " . mysql_error());
        $sql3 = "DELETE FROM mestre_pessoas_tipo WHERE mespestip_pessoa in (SELECT pes_codigo FROM pessoas WHERE pes_cooperativa=$codigo and (pes_grupopermissoes != 1 OR pes_grupopermissoes is null))";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL2: " . mysql_error());
        
        $sql3 = "DELETE FROM pessoas WHERE pes_cooperativa=$codigo and (pes_grupopermissoes != 1 OR pes_grupopermissoes is null)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL3: " . mysql_error());
        
        // Produtos
        $sql3 = "DELETE FROM produtos WHERE pro_cooperativa=$codigo";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL4: " . mysql_error());
        $sql3 = "DELETE FROM produtos_categorias WHERE cat_cooperativa=$codigo";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL5: " . mysql_error());


        // Entradas
        $sql3 = "DELETE FROM entradas_produtos WHERE entpro_entrada in (SELECT DISTINCT ent_codigo FROM entradas JOIN quiosques on ent_quiosque=qui_codigo WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL6: " . mysql_error());
        $sql3 = "DELETE FROM entradas WHERE ent_quiosque in (SELECT qui_codigo FROM quiosques WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL7: " . mysql_error());

        // Saídas
        $sql3 = "DELETE FROM saidas_produtos WHERE saipro_saida in (SELECT DISTINCT sai_codigo FROM saidas JOIN quiosques on sai_quiosque=qui_codigo WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL8: " . mysql_error());
        $sql3 = "DELETE FROM saidas WHERE sai_quiosque in (SELECT qui_codigo FROM quiosques WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL9: " . mysql_error());
        
        // Estoque
        $sql3 = "DELETE FROM estoque WHERE etq_quiosque in (SELECT qui_codigo FROM quiosques WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL19: " . mysql_error());
        $sql3 = "DELETE FROM quantidade_ideal WHERE qtdide_quiosque in (SELECT qui_codigo FROM quiosques WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL11: " . mysql_error());
        
        // Acertos
        $sql3 = "DELETE FROM acertos_taxas WHERE acetax_acerto in (SELECT ace_codigo FROM acertos join quiosques on ace_quiosque=qui_codigo WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL12: " . mysql_error());
        $sql3 = "DELETE FROM acertos WHERE ace_quiosque in (SELECT qui_codigo FROM quiosques WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL13: " . mysql_error());
        // Fechamentos
        $sql3 = "DELETE FROM fechamentos_taxas WHERE fchtax_fechamento in (SELECT fch_codigo FROM fechamentos join quiosques on fch_quiosque=qui_codigo WHERE  qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL14: " . mysql_error());
        $sql3 = "DELETE FROM fechamentos WHERE fch_quiosque in (SELECT qui_codigo FROM quiosques WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL15: " . mysql_error());
        
        // Quiosque
        $sql3 = "DELETE FROM caixas_operadores WHERE caiope_caixa in (SELECT cai_quiosque FROM caixas WHERE cai_quiosque in (SELECT qui_codigo FROM quiosques WHERE qui_cooperativa=$codigo))";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL23: " . mysql_error());
        $sql3 = "DELETE FROM caixas WHERE cai_quiosque in (SELECT qui_codigo FROM quiosques WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL16: " . mysql_error());
        $sql3 = "DELETE FROM quiosques_supervisores WHERE quisup_quiosque in (SELECT qui_codigo FROM quiosques WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL17: " . mysql_error());
        $sql3 = "DELETE FROM quiosques_taxas WHERE quitax_quiosque in (SELECT qui_codigo FROM quiosques WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL18: " . mysql_error());
        $sql3 = "DELETE FROM quiosques_tiponegociacao WHERE quitipneg_quiosque in (SELECT qui_codigo FROM quiosques WHERE qui_cooperativa=$codigo)";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL19: " . mysql_error());
        $sql3 = "DELETE FROM quiosques WHERE qui_cooperativa=$codigo";

        // Taxas
        $sql3 = "DELETE FROM taxas WHERE tax_cooperativa=$codigo";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL20: " . mysql_error());
        // Quiosques
        $sql3 = "DELETE FROM quiosques WHERE qui_cooperativa=$codigo";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL21: " . mysql_error());
        // Cooperativa
        $sql3 = "DELETE FROM cooperativas WHERE coo_codigo=$codigo";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro SQL22: " . mysql_error());
        
         
        $tpl6 = new Template("templates/notificacao.html");
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->DESTINO = "cooperativas.php";
        $tpl_notificacao->ICONES = $icones;
        if ($usuario_cooperativa==$codigo) {
            $tpl_notificacao->MOTIVO_COMPLEMENTO = "<br>E necessário sair e entrar novamente do sistema, pois você está logado na cooperativa que acabou de excluir!<br>";
            $tpl_notificacao->DESTINO = "login_sair.php";
            //Altera quiosque dos usuários administradores para 0
            $sql3 = "UPDATE pessoas set pes_cooperativa=0, pes_quiosqueusuario=0 WHERE pes_grupopermissoes=1"; 
            $query3 = mysql_query($sql3);
            if (!$query3) {
                die("Erro SQL: " . mysql_error());
            } 
            
        } else {
            $tpl_notificacao->DESTINO = "cooperativas.php";
        }
        
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_APAGADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
                 
} 


include "rodape.php";
?>
