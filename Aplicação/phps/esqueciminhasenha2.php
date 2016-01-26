<title>SGAF Esquecí minha senha</title>
<head>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br"></html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="classes.css" />
    <link rel="stylesheet" type="text/css" href="templates/geral.css">
        <script type="text/javascript" src="controle/google_analytics.js"></script>
        <script type="text/javascript" src="js/jquery-1.3.2.js"></script>
        <script src="js/jquery.maskedinput-1.1.4.pack.js" type="text/javascript"></script>
        <script src="mascaras.js" type="text/javascript"></script>    
        <script src="funcoes.js" type="text/javascript"></script>    
        <script type="text/javascript">
            window.onload = function(){
            };
        </script>
</head>
<body>        
    <div class="pagina" >
        <?php
        include ("templates/Template.class.php");
        include "controle/conexao.php";
        include "funcoes.php";
        include "js/mascaras.php";
        ?>
        <div class="corpo">
            <?php
            $tpl = new Template("templates/tituloemlinha_2.html");
            $tpl->block("BLOCK_TITULO");
            $tpl->LISTA_TITULO = "ESQUECÍ MINHA SENHA";
            $tpl->block("BLOCK_QUEBRA2");
            $tpl->show();

            $cpf = $_POST["cpf"];
            $cpf = str_replace('_', '', $cpf);
            $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            $resposta = $_POST["resposta"];
            $email = $_POST["email"];
            $metodo = $_POST["metodo"];
            //echo "($resposta)($email)($cpf)($metodo)";

            $tpl = new Template("templates/notificacao.html");
            $tpl->ICONES = "../imagens/icones/geral/";
            if ($metodo == 1) {//por pergunta
                $sql = "select pes_respostasecreta from pessoas where pes_cpf like '$cpf'";
                if (!$query = mysql_query($sql))
                    die("ERRO SQL" . mysql_error());
                $dados = mysql_fetch_array($query);
                $resposta_banco = $dados[0];
                if ($resposta == $resposta_banco) {
                    //Resposta certa, redefina uma nova senha!
                    $tpl->TITULO = "RESPOSTA  CORRETA!";
                    $tpl->ICONE_ARQUIVO = "comment.png";
                    $tpl->block("BLOCK_TITULO");
                    $tpl->MOTIVO = "<b>Clique no botão abaixo para definir uma nova senha!</b> <br>Desta vez certifique-se de ter em mãos um papel para anotar a nova senha! :)";
                    $tpl->MOTIVO_COMPLEMENTO = "";
                    $tpl->block("BLOCK_MOTIVO");
                    $tpl->FORM_DESTINO = "novasenha.php";
                    $tpl->CAMPOOCULTO_NOME = "cpf";
                    $tpl->CAMPOOCULTO_VALOR = "$cpf";
                    $tpl->block("BLOCK_BOTAO_FORM_CAMPOOCULTO");
                    $tpl->CAMPOOCULTO_NOME = "resposta";
                    $tpl->CAMPOOCULTO_VALOR = "$resposta";
                    $tpl->block("BLOCK_BOTAO_FORM_CAMPOOCULTO");
                    $tpl->block("BLOCK_BOTAO_FORM");
                    $tpl->show();
                } else {
                    //Resposta errada, não bate
                    $tpl->ICONE_ARQUIVO = "erro.png";
                    $tpl->TITULO = "RESPOSTA ERRADA!";
                    $tpl->block("BLOCK_TITULO");
                    $tpl->MOTIVO = "<b>A resposta digitada não confere com a resposta da pergunta registrada! </b><br>Tente novamente! Se não conseguir, tente o método de recuperação por e-mail, se não conseguir mesmo assim (ou não tiver esse método) por favor entre em contato com a equipe de suporte!";
                    $tpl->MOTIVO_COMPLEMENTO = "";
                    $tpl->block("BLOCK_MOTIVO");
                    $tpl->BOTAOGERAL_DESTINO = "esqueciminhasenha.php";
                    $tpl->BOTAOGERAL_TIPO = "button";
                    $tpl->BOTAOGERAL_NOME = "VOLTAR";
                    $tpl->block("BLOCK_BOTAOGERAL");
                    $tpl->show();
                }
            } else if ($metodo == 2) {//por email
                $sql = "select pes_email_senha from pessoas where pes_cpf like '$cpf'";
                if (!$query = mysql_query($sql))
                    die("ERRO SQL" . mysql_error());
                $dados = mysql_fetch_array($query);
                $email_banco = $dados[0];
                if ($email == $email_banco) {
                    //E-mail correto, será enviado por e-mail instruções!
                    //No e-mail será instruido para fazer nova senha, enviar por get criptografado
                    $tpl->TITULO = "E-MAIL CORRETO!";
                    $tpl->ICONE_ARQUIVO = "comment.png";
                    $tpl->block("BLOCK_TITULO");
                    $tpl->MOTIVO = "Está sendo enviado para o email digitado os procedimentos para recuperar a senha! Verifique sua caixa postal!";
                    $tpl->MOTIVO_COMPLEMENTO = "";
                    $tpl->block("BLOCK_MOTIVO");
                    $tpl->DESTINO = "../index.html";
                    $tpl->block("BLOCK_BOTAO");
                    $tpl->show();

                    //Enviar e-mail ao usuário
                    $destinatario = "$email";
                    $titulo="Recuperar senha da conta SGAF Online";
                    $emailmd5=md5($email_banco);
                    $link=$_SERVER["SERVER_NAME"]."/sgaf_online/phps/"."novasenha.php?cpf=$cpf&par=$emailmd5";
                    $textoemail = "
                        Ecosoli Suporte\n\n
                        Recuperação de senha\n
                        Para definir uma nova senha ao seu usuário no sistema SGAF Online, clique no link  a seguir:\n
                        $link \n
                        Este e-mail foi enviado para você porque este endereço de e-mail está cadastrado como referência para recuperação de senha no sistema SGAF Online (ecosoli.org).\n
                        Se você não sabe do que se trata isso, por favor ignore esta mensagem!\n\n
                        Atenciosamente...\nEquipe Ecosoli Suporte
                    ";
                    include "email.php";
                    
                    
                } else {
                    //E-mail não confere
                    $tpl->ICONE_ARQUIVO = "erro.png";
                    $tpl->TITULO = "E-MAIL INVÁLIDO!";
                    $tpl->block("BLOCK_TITULO");
                    $tpl->MOTIVO = "<b>O e-mail digitado não confere com o e-mail registrado para recuperação de senha! </b><br>Se você não lembra do e-mail cadastrado para recuperação de senha, tente o método de recuperação por pergunta e resposta secreta, se não conseguir mesmo assim (ou não tiver esse método) por favor entre em contato com a equipe de suporte!";
                    $tpl->MOTIVO_COMPLEMENTO = "";
                    $tpl->block("BLOCK_MOTIVO");
                    $tpl->BOTAOGERAL_DESTINO = "esqueciminhasenha.php";
                    $tpl->BOTAOGERAL_TIPO = "button";
                    $tpl->BOTAOGERAL_NOME = "VOLTAR";
                    $tpl->block("BLOCK_BOTAOGERAL");
                    $tpl->show();                    
                }
            } else {
                echo "ERRO GRAVE! TENTE NOVAMENTE!";
            }


            //$tpl = new Template("templates/notificacao.html");
            //$tpl->block("BLOCK_CONFIRMAR");
            //$tpl->block("BLOCK_ERRO");
            //$tpl->block("BLOCK_ATENCAO");
            //$tpl->block("BLOCK_APAGADO");
            //$tpl->block("BLOCK_NAOAPAGADO");
            //$tpl->block("BLOCK_NAOCADASTRADO");
            //$tpl->block("BLOCK_NAOEDITADO");
            //$tpl->block("BLOCK_MOTIVO_JAEXISTE");
            //$tpl->block("BLOCK_MOTIVO_EMUSO");
            //$tpl->FALTADADOS_MOTIVO="";
            //$tpl->block("BLOCK_MOTIVO_FALTADADOS");
            //$tpl->block("BLOCK_NAOTEMPERMISSAO");
            //$tpl->MOTIVO="";
            //$tpl->MOTIVO_COMPLEMENTO="";            
            //$tpl->block("BLOCK_MOTIVO");
            //$tpl->PERGUNTA="";
            //$tpl->block("BLOCK_PERGUNTA");
            //$tpl->BOTAOGERAL_DESTINO="";
            //$tpl->block("BLOCK_BOTAOGERAL_NOVAJANELA");
            //$tpl->BOTAOGERAL_TIPO="";
            //$tpl->BOTAOGERAL_NOME="";
            //$tpl->BOTAOGERAL_CLASSE="";
            //$tpl->block("BLOCK_BOTAOGERAL_AUTOFOCO");
            //$tpl->block("BLOCK_BOTAOGERAL");            
            //$tpl->DESTINO = "";
            //$tpl->block("BLOCK_BOTAO");
            //$tpl->block("BLOCK_BOTAO_VOLTAR");
            //$tpl->VOLTAR2_LINK = "";
            //$tpl->block("BLOCK_BOTAO_VOLTAR2");
            //$tpl->LINK = "";
            //$tpl->LINK_TARGET = "";
            //$tpl->NAO_LINK = "";
            //$tpl->block("BLOCK_BOTAO_NAO_LINK");
            //$tpl->block("BLOCK_BOTAO_NAO_VOLTAR");
            //$tpl->block("BLOCK_BOTAO_SIMNAO");
            ?>
        </div>        

    </div>
</body>
