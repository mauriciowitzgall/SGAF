<?php

include "funcoes.php";
include "controle/conexao.php";
include "controle/conexao_tipo.php";
$cpf = $_POST["cpf"];
$cpf = str_replace('_', '', $cpf);
$cpf = str_replace('.', '', $cpf);
$cpf = str_replace('-', '', $cpf);
$sql = "SELECT pes_usarperguntasecreta,pes_email_senha,pes_possuiacesso FROM pessoas WHERE pes_cpf like '$cpf'";
if (!$query = mysql_query($sql))
    die("ERRO SQL" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $usarpergunta = $dados["pes_usarperguntasecreta"];
    $email = $dados["pes_email_senha"];
    $possuiacesso = $dados["pes_possuiacesso"];
    if ($possuiacesso == 1) {

        if (($usarpergunta == 1) || ($email != "")) {
            echo "<option value=''>Selecione</option>";
        }
        $cont = 0;
        if ($usarpergunta == 1) {
            $cont++;
            echo "<option value='1'>Pergunta e Resposta Secreta</option>";
        }
        if ($email != "") {
            $cont++;
            echo "<option value='2'>E-mail</option>";
        }
        if ($cont == 0) {
            //echo "nenhum método";
            echo "nenhum";
        }
    } else {
        echo "naopossuiacesso";
    }
}
?>
