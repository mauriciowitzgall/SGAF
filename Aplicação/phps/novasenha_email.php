<?php
/*
//Conteúdo do e-mail
$titulo="Recuperação de senha SGAF Online";
$textoemail = "
    Ecosoli Suporte\n\n
    Recuperação de senha\n
    Para definir uma nova senha ao seu usuário no sistema SGAF Online, clique no link  a seguir:\n
    $link \n\n 
    Este e-mail foi enviado para você porque este endereço de e-mail está cadastrado como referência para recuperação de senha no sistema SGAF Online (ecosoli.org).
    \nSe você não sabe do que se trata isso, por favor ignore esta mensagem!
";

$link="http://ecosoli.org/SGAF/teste.php";

//Destinatário
$destinatario = "mauwitz@gmail.com";
*/

//Configuração do Remetente
$remetente_nome="Ecosoli Suporte";
$remetente = "ecosolisistemas@gmail.com";
$remetente_senha = "m848484e";


//Estrutua para enviar o e-mail
$Vai = $textoemail;
require_once("phpmailer/class.phpmailer.php");
define('GUSER', $remetente); // <-- Insira aqui o seu GMail
define('GPWD', $remetente_senha);  // <-- Insira aqui a senha do seu GMail
function smtpmailer($para, $de, $de_nome, $assunto, $corpo) {
    global $error;
    $mail = new PHPMailer();
    $mail->IsSMTP();  // Ativar SMTP
    $mail->SMTPDebug = 0;  // Debugar: 1 = erros e mensagens, 2 = mensagens apenas
    $mail->SMTPAuth = true;  // Autenticação ativada
    $mail->SMTPSecure = 'ssl'; // SSL REQUERIDO pelo GMail
    $mail->Host = 'smtp.gmail.com'; // SMTP utilizado
    $mail->Port = 465;    // A porta 465 deverá estar aberta em seu servidor
    $mail->Username = GUSER;
    $mail->Password = GPWD;
    $mail->SetFrom($de, $de_nome);
    $mail->Subject = $assunto;
    $mail->Body = $corpo;
    $mail->AddAddress($para);
    if (!$mail->Send()) {
        $error = 'Mail error: ' . $mail->ErrorInfo;
        return false;
    } else {
        //$error = 'Mensagem enviada! Verifique seu e-mail!';
        return true;
    }
}

// Insira abaixo o email que irá receber a mensagem, o email que irá enviar (o mesmo da variável GUSER), 
//o nome do email que envia a mensagem, o Assunto da mensagem e por último a variável com o corpo do email.

if (smtpmailer($destinatario, $remetente, $remetente_nome, $titulo, $Vai)) {
    Header("../index.php"); // Redireciona para uma página de obrigado.
}
if (!empty($error))
    echo $error;
?>