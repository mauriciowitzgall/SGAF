<?php


//Estrutua para enviar o e-mail
require_once("phpmailer/class.phpmailer.php");
$PHPMailer->Charset = 'UTF-8';
function smtpmailer($para, $de, $de_senha, $de_nome, $assunto, $corpo) {
    global $error;
  
    $mail = new PHPMailer();
    $mail->SMTPSecure = 'tls';
    $mail->Username = "$de";
    $mail->Password = "$de_senha";
    $mail->AddAddress("$para");
    $mail->FromName = "$de_nome";
    $mail->Subject = "$assunto";
    $mail->Body = utf8_encode($corpo);
    $mail->Host = "smtp.live.com";
    $mail->Port = 587;
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->From = $mail->Username;
    $mail->WordWrap = 50;               // set word wrap
    $mail->Priority = 1; 
    $mail->IsHTML(true);  
    $mail->Send();
    $mail->CharSet = "UTF-8";
    $mail->MsgHTML($corpo);
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


?>