<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

$config = new stdClass();
$config->host = 'smtp.gmail.com';
$config->user = 'guimadalozzo@gmail.com';
$config->password = 'madala1706618173';
$config->secure = 'tsl';
$config->port = 587;
$config->from = 'guimadalozzo@gmail.com';
$config->fantasy = 'NFe do Guigues';
$config->replyTo = 'guimadalozzo@gmail.com';
$config->replyName = 'Vendas';

use NFePHP\Mail\Mail;

try {
  //a configuração é uma stdClass com os campos acima indicados
  //esse parametro é OBRIGATÓRIO
  $mail = new Mail($config);

  //use isso para inserir seu próprio template HTML com os campos corretos
  //para serem substituidos em execução com os dados dos xml
  $htmlTemplate = '';
  $mail->loadTemplate($htmlTemplate);
  //aqui são passados os documentos, tanto pode ser um path como o conteudo
  //desses documentos
  $xml = 'teste65.xml';
  $pdf = '';//não é obrigatório passar o PDF, tendo em vista que é uma BOBAGEM
  $mail->loadDocuments($xml, $pdf);

  //se não for passado esse array serão enviados apenas os emails
  //que estão contidos no XML, isto se existirem
  $addresses = ['mauwitz@gmail.com'];
  //se esse array for passado serão enviados emails para os endereços indicados apenas
  //e os endereços contidos no xml serão ignorados

  //envia emails
//  $mail->send($addresses);

} catch (\InvalidArgumentException $e) {
    echo "Falha: " . $e->getMessage();
} catch (\RuntimeException $e) {
    echo "Falha: " . $e->getMessage();
} catch (\Exception $e) {
    echo "Falha: " . $e->getMessage();
}
