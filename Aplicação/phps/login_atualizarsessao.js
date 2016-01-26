function sessao_atualizar()   {
    $.post("login_verifica.php", { 
        },function(valor) {
        //alert(valor);
        });
}            
setInterval("sessao_atualizar()", 300000);