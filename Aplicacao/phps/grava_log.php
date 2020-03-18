<?php

    $sql_logs="
            INSERT INTO auditoria (aud_usuario_cpf,aud_usuario_nome, aud_operacao, aud_tabela, aud_descricao, aud_sql,aud_quiosque,aud_tela) 
            VALUES ('$log_usuario_cpf','$log_usuario_nome','$log_operacao','$log_tabela','$log_descricao', '$log_sql','$log_usuario_quiosque','$log_tela')
    ";
    if (!$query_logs = mysql_query($sql_logs)) 
    die("ERRO AO GRAVAR LOG<br><b>Descrição: $log_descricao</b><br>SQL: $sql_logs<br>". mysql_error());
?>