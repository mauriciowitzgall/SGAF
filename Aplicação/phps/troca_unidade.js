function cooperativa_popula_quiosques (cooperativacodigo,pessoacodigo) {    
    $("select[name=quiosqueusuario]").html('<option>Carregando</option>');
    $.post("verifica_quiosqueusuario.php", {
        pessoa:pessoacodigo,
        cooperativa:cooperativacodigo
    },function(valor2){
        //alert(valor2);
        $("select[name=quiosqueusuario]").html(valor2);
    });    
}

function grupopermissoes_popula_quiosques (grupo,pessoacodigo) {    
    $("select[name=quiosqueusuario]").html('<option>Carregando</option>');
    $.post("pessoas_popula_quiosque.php",{
        pessoa:pessoacodigo,
        cooperativa:$("select[name=cooperativa]").val(),
        grupo_permissao:grupo
    },function(valor2){
        //alert(valor2);
        $("select[name=quiosqueusuario]").html(valor2);
    });    
}
