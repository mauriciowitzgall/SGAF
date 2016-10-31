<?php

require "login_verifica.php";

$msg = $_POST["descricao"];

include "includes.php";
//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "SUPORTE";
$tpl_titulo->SUBTITULO = "PRECISA DE AJUDA?";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "contato.png";
$tpl_titulo->show();


$sql="SELECT pes_fone1,pes_fone2,pes_fone1ramal, pes_fone2ramal, pes_email FROM pessoas WHERE pes_codigo=$usuario_codigo";
$query = mysql_query($sql); if (!$query) die("Erro: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$fone1 = $dados["pes_fone1"];
$fone2 = $dados["pes_fone2"];
$fone1ramal = $dados["pes_fone1ramal"];
$fone2ramal = $dados["pes_fone2ramal"];
$telefones= $fone1 . " / " . $fone2;
$email = $dados["pes_email"];


//Enviar e-mail ao usuário
include "email.php";
$de="mauwitz@hotmail.com";
$de_senha="m8w2t84";
$para = "mauwitz@icloud.com";
$de_nome="SGAF Suporte";
$assunto="Suporte SGAF - $usuario_nome / $usuario_quiosquenome";
$corpo = "
<h2>SGAF Suporte</h2>
<b>Usuário:</b> $usuario_nome<br>
<b>Telefone:</b> $telefones<br>
<b>E-mail:</b> $email<br>
<b>Quiosque:</b> $usuario_quiosquenome<br>
<b>Cooperativa:</b> $usuario_cooperativaabreviacao<br><br>

<b>Mensagem:</b> $msg <br><br>

<i>Mensagem enviada automaticamente através do sistema SGAF</i>
";
smtpmailer($para, $de, $de_senha, $de_nome, $assunto, $corpo);
if (!empty($error)) {
    echo $error;
} else {
    $tpl = new Template("templates/notificacao.html");
    $tpl->ICONES="$icones";
    $tpl->ICONE_ARQUIVO="comment.png";
    $tpl->TITULO="E-MAIL ENVIADO";
    $tpl->block("BLOCK_TITULO");  

    $tpl->MOTIVO="Foi enviado um e-mail ao pessoal do suporte do sistema SGAF<br>";
    $tpl->block("BLOCK_MOTIVO");
    $tpl->MOTIVO_COMPLEMENTO="A resposta para a sua solicitação será devolvida via e-mail. Portanto fique de olho em sua caixa postal.";

    $tpl->DESTINO="contato.php";
    $tpl->block("BLOCK_BOTAO");
    $tpl->show();
}

include "rodape.php";
    

?>

