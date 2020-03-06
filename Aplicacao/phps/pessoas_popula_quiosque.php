<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";
$grupo_permissao = $_POST["grupo_permissao"];
//print_r($_REQUEST);
$pessoa = $_POST["pessoa"];
$cooperativa = $_POST["cooperativa"];

if ($grupo_permissao == "") {
    echo "<option value=''>Todos</option>";
} else {
    if (($grupo_permissao == 1) || ($grupo_permissao == 2)) {
        echo "<option value=''>Todos</option>";
        $sql = "SELECT qui_codigo,qui_nome FROM quiosques WHERE qui_cooperativa=$cooperativa";
    } else if ($grupo_permissao==3) {
        $sql = "
            SELECT qui_codigo,qui_nome 
            FROM quiosques 
            join quiosques_supervisores on (quisup_quiosque=qui_codigo)
            WHERE qui_cooperativa=$cooperativa
            AND quisup_supervisor=$pessoa
        ";
    } else IF ($grupo_permissao==4){
        $sql = "
            SELECT qui_codigo,qui_nome 
            FROM quiosques 
            join caixas on (qui_codigo=cai_quiosque)
            join caixas_operadores on (caiope_caixa=cai_codigo)
            WHERE qui_cooperativa=$cooperativa
            AND caiope_operador=$pessoa
        ";        
     } else IF ($grupo_permissao==5){
        $sql = "
            SELECT DISTINCT qui_codigo,qui_nome 
            FROM entradas 
            join quiosques on (ent_quiosque=qui_codigo)
            WHERE qui_cooperativa=$cooperativa
            AND ent_fornecedor=$pessoa
        ";        
    } else {
        echo "<option value=''>Não há registros</option>";
        
    }
    $query = mysql_query($sql);
    if (!$query)
        die("Erro SQL:" . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        $qui_cod = $dados["qui_codigo"];
        $qui_nome = $dados["qui_nome"];
        echo "<option value='$qui_cod'>$qui_nome</option>";
    }
}

echo "($pessoa - $grupo_permissao - $cooperativa)";
?>
