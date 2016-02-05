 <?php
require "login_verifica.php";


include "includes.php";

if ($usuario_grupo == 4) {
    if ($usuario_caixa=="") {
        header("Location: caixas.php");
    }
    else
        header("Location: saidas.php");
    exit;
}
if ($usuario_grupo == 5) {
    header("Location: estoque_porfornecedor_produto.php?fornecedor=$usuario_codigo");
}

if (($usuario_grupo == 1)&&($usuario_cooperativa==0)&&($usuario_quiosque==0)) {
    header("Location: troca_unidade.php");
}


if ($usuario_grupo==7) {
    header("Location: pessoas.php");
}


if (($usuario_grupo != 7) && ($usuario_grupo != 1) && ($usuario_grupo != 2)) {
    if (($usuario_quiosque == 0)||($usuario_grupo==0)) {
        $sql3="
        SELECT quisup_quiosque FROM quiosques_supervisores WHERE quisup_supervisor=$usuario_codigo
        UNION
        SELECT cai_quiosque FROM caixas_operadores JOIN caixas on cai_codigo=caiope_caixa WHERE caiope_operador=$usuario_codigo
        ";
        $query3 = mysql_query($sql3);
        if (!$query3) die("Erro SQL: " . mysql_error());
        $linhas3=  mysql_num_rows($query3);
        if ($linhas3>0) {
            $tpl = new Template("templates/notificacao.html");
            $tpl->ICONES = $icones;
            $tpl->MOTIVO_COMPLEMENTO = "Houveram alterações no perfil de seu usuario!<br>
            Isto normalmente acontece quando algum superior seu te adiciona ou remove dos seguinte cargos: gestor, supervisor, caixa ou fornecedor.<br>
            Ou então quam um quiosque que você está vinculado foi excluído<br>
            Clique em continuar para alterar seu grupo de permissões e quiosque!<br>
            Se mesmo assim não obter sucesso, favor contatar seu adminsitrador!";
            $tpl->block("BLOCK_ATENCAO");
            $tpl->DESTINO = "troca_unidade.php";
            $tpl->block("BLOCK_BOTAO");
        } else {
            $tpl = new Template("templates/notificacao.html");
            $tpl->ICONES = $icones;
            $tpl->MOTIVO_COMPLEMENTO = "Você não é mais vinculado como usuário a nenhum quiosque ativo!<br>
            Isso pode ter acontecido devido a exclusão de algum quiosque cujo você estava vinculado como operador/usuário (caixa, supervisor etc.)";
            $tpl->block("BLOCK_ATENCAO");
            $tpl->DESTINO = "login_sair.php";
            $tpl->block("BLOCK_BOTAO");
        }
        $tpl->show();
    } else {
        include "inicio.html";
        include "rodape.php";
    }
} else {
    include "inicio.html";
    include "rodape.php";
}
?>

